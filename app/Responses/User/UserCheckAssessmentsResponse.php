<?php
/**
 * Created by PhpStorm.
 * User: BeeTimberlake
 * Date: 5/31/2019
 * Time: 10:33 PM
 */

namespace App\Responses\User;


use App\Http\Controllers\Helpers\Helpers;
use App\Models\Assessment;
use App\Models\CheckAssessment;
use App\Models\CheckAssessmentSection;
use App\Models\CheckAssessmentSectionQuestion;
use App\Models\SectionQuestion;
use App\Responses\Admin\Schema\AccessAnswerSchema;
use App\Responses\Admin\Schema\AnswerContentSchema;
use App\Responses\Admin\Schema\QuestionContentSchema;
use App\Traits\UserRoleTrait;
use App\User;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\DB;

class UserCheckAssessmentsResponse implements Responsable
{
    use UserRoleTrait;

    private $actions;
    private $options;
    private $req;

    /**
     * AssessmentActionResponse constructor.
     * @param $actions
     * @param array $options
     */
    public function __construct($actions, $options = [])
    {
        $this->actions = $actions;
        $this->options = $options;
    }

    public function get($request)
    {
        $fields = ['check_assessments.id', 'check_assessments.assessment_id', 'assessments.title', 'assessments.description', 'check_assessments.status', 'check_assessments.created_at', 'check_assessments.updated_at'];
        $request->request->add(['fields' => $fields]);
        $text = $this->options['text'];
        $paginateLimit = $this->options['limit'];

        //check if admin return back
        $user = $request->user();
        if ($user->hasActions('view_check_assessments')) {
            $data = CheckAssessment::select(array_merge($fields, ['type_users.name as type_user', 'users.id as user_id', 'users.name as user_name',
                DB::raw("CONCAT (users.name, ' ', users.last_name) as full_name")]))->join('assessments', 'assessments.id', 'check_assessments.assessment_id');
            $data->join('users', 'users.id', 'check_assessments.user_id')
                ->join('user_types', 'user_types.user_id', 'users.id')
                ->join('type_users', 'type_users.id', 'user_types.type_user_id');
        } else {
            $data = CheckAssessment::select($fields)->join('assessments', 'assessments.id', 'check_assessments.assessment_id')->where('user_id', $user->id);
        }
        $data->where(function ($query) use ($request, $text, $user) {
            foreach ($request->fields as $k => $f) {
                if ($f === 'check_assessments.created_at' || $f === 'check_assessments.updated_at') {
                    if (Helpers::isEngText($text)) {
                        $query->orWhere($f, 'LIKE', "%{$text}%");
                    } else {
                        continue;
                    }
                }
                $query->orWhere($f, 'LIKE', "%{$text}%");
            }

            if ($user->hasActions('view_check_assessments')) {
                $query->orWhere('type_users.name', 'LIKE', "%{$text}%");
                $query->orWhere(
                    DB::raw("CONCAT (users.name, ' ', users.last_name)"),
                    'LIKE',
                    "%{$text}%"
                );
            }

        });
        $data = $data->orderBy('check_assessments.updated_at', 'desc')->paginate($paginateLimit);
        $data->map(function ($d) use ($user) {
            if ($user->hasActions('view_check_assessments')) {
                $d->userColor = $d->type_user === 'field_inspector' ? '#00bfa5' : 'rgb(3, 155, 229)';
            }
            $d->statusColor = $d->status === 'success' ? '#00bfa5' : ($d->status === 'close' ? '#d50000' : '');
            return $d;
        });
        return $data;
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request)
    {
        if (Helpers::isAjax($request)) {
            $data = [];
            $this->req = $request;
            if ($this->actions === 'fetch') {
                $data = $this->fetch($request);
            }

            if ($data) {
                return response()->json(['data' => $data, 'success' => true]);
            }
        }
        return response()->json(['data' => null, 'success' => false]);
    }

    public function getAssessmentSchema(CheckAssessment $checkAssessmentModel, Assessment $assessmentModel): array
    {
        $assessmentModel = Assessment::find($assessmentModel->id);//re-get data
        $sections = $assessmentModel->sections;
        unset($assessmentModel->sections);
        //for first time add or changed section size we will remove all sections and questions please be check surely before send assessment.
        $isSameSectionsSize = true;
        if (count($checkAssessmentModel->sections()) !== count($sections)) {
            $checkAssessmentModel->sectionsDelete();
            $isSameSectionsSize = false;
        }
        $sections = $sections->map(function ($section, $sKey) use ($isSameSectionsSize, $checkAssessmentModel) {
            $section->desc = $section->description;
            $section->focusIndex = -1;
            $section->questionsJson();
            if (!$isSameSectionsSize) {
                $checkAssessmentSectionModel = new CheckAssessmentSection();
                $checkAssessmentModel->checkAssessmentSections()->save($checkAssessmentSectionModel);
                $this->addAnswers($section->questions, $sKey, $checkAssessmentSectionModel);
            }
            return $section;
        });
        //assign answers
        $checkAssessmentModel = CheckAssessment::find($checkAssessmentModel->id);//re-get data
        //check sections and questions answer
        foreach ($checkAssessmentModel->sections() as $sKey => $checkAssessmentSection) {
            $questions = $checkAssessmentSection->checkAssessmentSectionQuestions;
            if (count($questions) !== count($sections[$sKey]->questions)) {
                $checkAssessmentSection->checkAssessmentSectionQuestions()->delete();
                $this->addAnswers($sections[$sKey]->questions, $sKey, $checkAssessmentSection);
            } else {
                foreach ($questions as $qKey => $checkAssessmentSectionQuestion) {
                    $schema = json_decode($checkAssessmentSectionQuestion->schema);
                    if (isset($schema->access)) {
                        $access = $schema->access;
                        $question = SectionQuestion::find($access->questionId);
                        if (isset($question)) {
                            if ($question->updated_at->format('Y-m-d H:i:s') !== $access->updated_at) {
                                $this->addAnswer($access, $question, $checkAssessmentSectionQuestion);
                            }
                        } else {
                            $question = $sections[$access->sectionIndex]->questions[$access->questionIndex];
                            $access->questionId = $question->id;//change question id
                            $question = SectionQuestion::find($access->questionId);
                            $this->addAnswer($access, $question, $checkAssessmentSectionQuestion);
                        }
                    } else {//restore answer
                        $access = new AccessAnswerSchema();
                        $access->setSectionIndex($sKey);
                        $access->setQuestionIndex($qKey);
                        $question = SectionQuestion::find($sections[$sKey]->questions[$qKey]->id);
                        $this->addAnswer(json_decode(json_encode($access->toArray())), $question, $checkAssessmentSectionQuestion);
                    }
                }
            }
        }
        //assign answers
        $checkAssessmentModel = CheckAssessment::find($checkAssessmentModel->id);//re-get data
        $check_sections = $checkAssessmentModel->sections();
        $check_sections->map(function ($section) {
            $section->questionsJson();
        });
        $sorted = $sections->sortBy('section_order')->values()->all();
        $user = $this->req->user();
        if ($user->hasActions('view_check_assessments')) {
            $user = User::find($checkAssessmentModel->user_id);
        }
        return [
            'assessment' => $assessmentModel,
            'sections' => $sorted,
            'check_sections' => $check_sections,
            'user_name' => $user->name,
        ];
    }

    public function addAnswers($questions, $section_index, CheckAssessmentSection $checkAssessmentSectionModel)
    {
        foreach ($questions as $qKey => $question) {
            $checkAssessmentSectionQuestionModel = new CheckAssessmentSectionQuestion();
            #build answer
            $questionSchema = new QuestionContentSchema();
            $questionSchema->build($question);
            $accessAnswer = new AccessAnswerSchema();
            $accessAnswer->setSectionIndex($section_index);
            $accessAnswer->setQuestionIndex($qKey);
            $accessAnswer->setQuestionId($question->id);
            $accessAnswer->setUpdatedAt($question->updated_at->format('Y-m-d H:i:s'));
            $answerSchema = new  AnswerContentSchema($questionSchema, $accessAnswer);
            $answerSchema->build(['en' => null]);
            #build answer
            $checkAssessmentSectionQuestionModel->schema = $answerSchema->toJson();
            $checkAssessmentSectionModel->checkAssessmentSectionQuestions()->save($checkAssessmentSectionQuestionModel);
        }
    }

    public function addAnswer($access, $question, CheckAssessmentSectionQuestion $checkAssessmentSectionQuestion)
    {
        $questionDecode = $question->toJsonDecode();
        $checkQuestionDecode = $checkAssessmentSectionQuestion->toJsonDecode();
        #build answer
        $questionSchema = new QuestionContentSchema();
        $questionSchema->build($questionDecode);
        $accessAnswer = new AccessAnswerSchema();
        $accessAnswer->setSectionIndex($access->sectionIndex);
        $accessAnswer->setQuestionIndex($access->questionIndex);
        $accessAnswer->setQuestionId($question->id);
        $accessAnswer->setUpdatedAt($question->updated_at->format('Y-m-d H:i:s'));
        $answerSchema = new  AnswerContentSchema($questionSchema, $accessAnswer);

        if ($questionDecode->types === $checkQuestionDecode->question->types
            && ($questionDecode->types === 'short_answer' || $questionDecode->types === 'paragraph')) {
            $answerSchema->build(json_decode(json_encode($checkQuestionDecode->schema), true));
        } else {
            $answerSchema->build(['en' => null]);
        }
        #build answer
        $checkAssessmentSectionQuestion->schema = $answerSchema->toJson();
        $checkAssessmentSectionQuestion->save();
    }

    public function fetch($request)
    {
        $user = $request->user();
        if ($user->hasActions('view_check_assessments')) {
            $checkAssessmentModel = CheckAssessment::where('user_id', $request->user_id)->where('id', $request->id)->first();

        } else {
            $checkAssessmentModel = CheckAssessment::where('user_id', $user->id)->whereIn('status', ['checking', 'success'])->where('id', $request->id)->first();
        }
        if (isset($checkAssessmentModel)) {
            $assessmentModel = Assessment::find($checkAssessmentModel->assessment_id);
            if (isset($assessmentModel)) {
                return $this->getAssessmentSchema($checkAssessmentModel, $assessmentModel);
            }
        }
        return false;
    }

}

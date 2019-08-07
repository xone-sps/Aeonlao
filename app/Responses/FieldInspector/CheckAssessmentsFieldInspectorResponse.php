<?php
/**
 * Created by PhpStorm.
 * User: BeeTimberlake
 * Date: 5/31/2019
 * Time: 10:33 PM
 */

namespace App\Responses\FieldInspector;


use App\Http\Controllers\Helpers\Helpers;
use App\Models\Assessment;
use App\Models\CheckAssessment;
use App\Models\CheckAssessmentFieldInspector;
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

class CheckAssessmentsFieldInspectorResponse implements Responsable
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

    #this for admin and checker only
    public function get($request)
    {
        $fields = ['check_assessment_field_inspectors.id', 'check_assessment_field_inspectors.check_assessment_id', 'assessments.title', 'assessments.description', 'check_assessment_field_inspectors.status', 'check_assessment_field_inspectors.check_user_id', 'check_assessment_field_inspectors.field_inspector_id', 'check_assessment_field_inspectors.created_at', 'check_assessment_field_inspectors.updated_at'];
        $request->request->add(['fields' => $fields]);
        $text = $this->options['text'];
        $paginateLimit = $this->options['limit'];
        //check if admin return back
        $user = $request->user();
        $data = CheckAssessmentFieldInspector::select(array_merge($fields, ['type_users.name as type_user', 'users.id as user_id', 'users.name as user_name', DB::raw("CONCAT (users.name, ' ', users.last_name) as full_name")]))
            ->join('check_assessments', 'check_assessments.id', 'check_assessment_field_inspectors.check_assessment_id')
            ->join('assessments', 'assessments.id', 'check_assessments.assessment_id')
            ->join('users', 'users.id', 'check_assessments.user_id')
            ->join('user_types', 'user_types.user_id', 'users.id')
            ->join('type_users', 'type_users.id', 'user_types.type_user_id')
            ->where('check_assessment_id', $request->check_assessment_id)
            ->where('check_assessment_field_inspectors.field_inspector_id', $request->field_inspector_id);

        $data->where(function ($query) use ($request, $text, $user) {
            foreach ($request->fields as $k => $f) {
                if ($f === 'check_assessment_field_inspectors.created_at' || $f === 'check_assessment_field_inspectors.updated_at') {
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
        $data = $data->orderBy('check_assessment_field_inspectors.updated_at', 'desc')->paginate($paginateLimit);
        $data->map(function ($d) {
            $d->type = 'field_inspector';
            $d->check_institute_name = User::find($d->check_user_id)->name ?? 'Not known';
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

    public function getCheckAssessmentFieldInspector(CheckAssessment $checkAssessmentModel, User $institute)
    {
        $checkAssessmentFieldInspectorModel = CheckAssessmentFieldInspector::where('field_inspector_id', $checkAssessmentModel->user_id)->where('check_assessment_id', $checkAssessmentModel->id)->where('check_user_id', $institute->id)->first();
        return $checkAssessmentFieldInspectorModel;
    }

    public function getAssessmentSchema(CheckAssessment $checkAssessmentModel, Assessment $assessmentModel, User $institute): array
    {
        $assessmentModel = Assessment::find($assessmentModel->id);//re-get data
        $sections = $assessmentModel->sections;
        unset($assessmentModel->sections);
        $checkAssessmentFieldInspectorModel = $this->getCheckAssessmentFieldInspector($checkAssessmentModel, $institute);
        if (!isset($checkAssessmentFieldInspectorModel)) {
            $checkAssessmentFieldInspectorModel = new CheckAssessmentFieldInspector();
            $checkAssessmentFieldInspectorModel->field_inspector_id = $checkAssessmentModel->user_id;
            $checkAssessmentFieldInspectorModel->check_user_id = $institute->id;
            $checkAssessmentFieldInspectorModel->check_assessment_id = $checkAssessmentModel->id;//check assessment id not assessment id
            $checkAssessmentFieldInspectorModel->save();
        }
        //for first time add or changed section size we will remove all sections and questions please be check surely before send assessment.
        $isSameSectionsSize = true;
        if (count($checkAssessmentFieldInspectorModel->sections()) !== count($sections)) {
            $checkAssessmentFieldInspectorModel->sectionsDelete();
            $isSameSectionsSize = false;
        }
        $sections = $sections->map(function ($section, $sKey) use ($isSameSectionsSize, $checkAssessmentFieldInspectorModel) {
            $section->desc = $section->description;
            $section->focusIndex = -1;
            $section->questionsJson();
            if (!$isSameSectionsSize) {
                $checkAssessmentSectionModel = new CheckAssessmentSection();
                $checkAssessmentSectionModel->type = 'field_inspector';
                $checkAssessmentFieldInspectorModel->checkAssessmentSections()->save($checkAssessmentSectionModel);
                $this->addAnswers($section->questions, $sKey, $checkAssessmentSectionModel);
            }
            return $section;
        });
        $checkAssessmentFieldInspectorModel = $this->getCheckAssessmentFieldInspector($checkAssessmentModel, $institute);
        //check sections and questions answer
        foreach ($checkAssessmentFieldInspectorModel->sections() as $sKey => $checkAssessmentSection) {
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
        $checkAssessmentFieldInspectorModel = $this->getCheckAssessmentFieldInspector($checkAssessmentModel, $institute);
        $check_sections = $checkAssessmentFieldInspectorModel->sections();
        $check_sections->map(function ($section) {
            $section->questionsJson();
        });
        $sorted = $sections->sortBy('section_order')->values()->all();
        $user = $this->req->user();
        $related_user = User::find($checkAssessmentFieldInspectorModel->check_user_id);
        $related_institute_assessment = CheckAssessment::where('user_id', $checkAssessmentFieldInspectorModel->check_user_id)->where('assessment_id', $checkAssessmentModel->assessment_id)->first();
        if ($user->hasActions('view_check_assessments')) {
            $user = User::find($checkAssessmentModel->user_id);
        }
        return [
            'check_assessment_field_inspector' => $checkAssessmentFieldInspectorModel,
            'assessment' => $assessmentModel,
            'sections' => $sorted,
            'check_sections' => $check_sections,
            'user_name' => $user->name,
            'related_user_name' => $related_user->name,
            'related_institute_assessment' => $related_institute_assessment,
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
        $institute = User::find($request->institute_id);
        if ($user->hasActions('view_check_assessments')) {
            $checkAssessmentFieldInspectorModel = CheckAssessmentFieldInspector::where('field_inspector_id', $request->user_id)->where('id', $request->id)->where('check_user_id', $institute->id)->first();
            if (!isset($checkAssessmentFieldInspectorModel)) {
                return false;
            }
            $checkAssessmentModel = CheckAssessment::where('user_id', $request->user_id)->where('id', $checkAssessmentFieldInspectorModel->check_assessment_id)->first();
        } else {
            $checkAssessmentModel = CheckAssessment::where('user_id', $user->id)->whereIn('status', ['checking', 'success'])->where('id', $request->id)->first();
        }
        if (isset($checkAssessmentModel, $institute)) {
            $assessmentModel = Assessment::find($checkAssessmentModel->assessment_id);
            if (isset($assessmentModel)) {
                return $this->getAssessmentSchema($checkAssessmentModel, $assessmentModel, $institute);
            }
        }
        return false;
    }

}

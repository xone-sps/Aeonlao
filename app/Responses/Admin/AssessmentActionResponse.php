<?php
/**
 * Created by PhpStorm.
 * User: BeeTimberlake
 * Date: 3/2/2019
 * Time: 2:46 PM
 */

namespace App\Responses\Admin;


use App\Http\Controllers\Helpers\Helpers;
use App\Jobs\CheckAssessmentStatusJob;
use App\Models\Assessment;
use App\Models\AssessmentSection;
use App\Models\CheckAssessment;
use App\Models\CheckAssessmentFieldInspector;
use App\Models\CheckAssessmentSection;
use App\Models\SectionQuestion;
use App\Responses\Admin\Schema\QuestionContentSchema;
use Illuminate\Contracts\Support\Responsable;

class AssessmentActionResponse implements Responsable
{
    private $actions;
    private $options;

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
        $fields = ['id', 'title', 'description', 'status', 'created_at', 'updated_at'];
        $request->request->add(['fields' => $fields]);
        $text = $this->options['text'];
        $paginateLimit = $this->options['limit'];
        $data = Assessment::select($fields);
        $data->where(function ($query) use ($request, $text) {
            foreach ($request->fields as $k => $f) {
                if ($f === 'created_at' || $f === 'updated_at') {
                    if (Helpers::isEngText($text)) {
                        $query->orWhere($f, 'LIKE', "%{$text}%");
                    } else {
                        continue;
                    }
                }
                $query->orWhere($f, 'LIKE', "%{$text}%");
            }
        });
        $data = $data->orderBy('created_at', 'desc')->paginate($paginateLimit);
        $data->map(function ($d) {
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
            if ($this->actions === 'create') {
                $data = $this->create($request);
            }
            if ($this->actions === 'fetch') {
                $data = $this->fetch($request);
            }
            if ($this->actions === 'update') {
                $data = $this->update($request);
            }
            if ($this->actions === 'update-status') {
                $data = $this->updateStatus($request);
            }
            if ($this->actions === 'delete') {
                $data = $this->delete($request);
            }
            if ($data) {
                return response()->json(['data' => $data, 'success' => true]);
            }
        }
        return response()->json(['data' => null, 'success' => false]);
    }

    public function create($request)
    {
        $schemaString = json_encode($request->all());
        if (Helpers::isValidJson($schemaString)) {
            $schema = json_decode($schemaString);
            if (is_object($schema)) {
                //assessment
                $assessment = $schema->assessment ?? '';
                if (is_object($assessment)) {
                    $assessmentModel = new Assessment();
                    $assessmentModel->title = $assessment->title ?? '';
                    $assessmentModel->description = $assessment->description ?? '';
                    $assessmentModel->save();
                    //sections
                    $sections = $schema->sections ?? '';
                    if (is_array($sections)) {
                        foreach ($sections as $key => $section) {
                            if (is_object($section)) {
                                $sectionModel = new AssessmentSection();
                                $sectionModel->title = $section->title ?? '';
                                $sectionModel->description = $section->desc ?? '';
                                $sectionModel->section_order = $key;
                                $sectionModel->assessment_id = $assessmentModel->id;
                                $sectionModel->save();
                                $questions = $section->questions ?? '';
                                if (is_array($questions)) {
                                    foreach ($questions as $q_key => $question) {
                                        if (is_object($question)) {
                                            $questionModel = new SectionQuestion();
                                            $questionModel->question_order = $q_key;
                                            $questionSchema = new QuestionContentSchema();
                                            $questionSchema->build($question);
                                            $questionModel->schema = $questionSchema->toJson();
                                            $sectionModel->questions()->save($questionModel);
                                        }//question
                                    }
                                }//questions
                            }//section
                        }
                    } else { //if there is no section let insert default section
                        $sectionModel = new AssessmentSection();
                        $sectionModel->title = $section->title ?? '';
                        $sectionModel->description = $section->desc ?? '';
                        $sectionModel->section_order = 0;
                        $assessmentModel->sections()->save($sectionModel);
                    }
                    //sections
                    return $this->getAssessmentSchema($assessmentModel);
                }//assessment
            }//schema
        }
        return false;
    }

    public function getAssessmentSchema(Assessment $assessmentModel): array
    {
        $assessmentModel = Assessment::find($assessmentModel->id);//re-get data
        $sections = $assessmentModel->sections;
        unset($assessmentModel->sections);
        $sections = $sections->map(function ($section) {
            $section->desc = $section->description;
            $section->focusIndex = -1;
            $section->questionsJson();
            return $section;
        });
        $sorted = $sections->sortBy('section_order')->values()->all();
        return [
            'assessment' => $assessmentModel,
            'sections' => $sorted
        ];
    }

    public function fetch($request)
    {
        $assessmentModel = Assessment::find($request->id);
        if (isset($assessmentModel)) {
            return $this->getAssessmentSchema($assessmentModel);
        }
        return false;
    }

    private function update($request)
    {
        $assessmentModel = Assessment::find($request->id);
        if (isset($assessmentModel)) {
            $schemaString = json_encode($request->all());
            if (Helpers::isValidJson($schemaString)) {
                $schema = json_decode($schemaString);
                if (is_object($schema)) {
                    //assessment
                    $assessment = $schema->assessment ?? '';
                    if (is_object($assessment)) {
                        $assessmentModel->title = $assessment->title ?? '';
                        $assessmentModel->description = $assessment->description ?? '';
                        $assessmentModel->save();
                        //sections
                        $existSections = $assessmentModel->sections;
                        $sections = $schema->sections ?? '';
                        if (is_array($sections)) {
                            //check old sections and new sections
                            if (count($existSections) !== count($sections)) {
                                $exitSectionIds = $existSections->pluck('id')->all();
                                $sectionIds = array_filter(array_column($sections, 'id'));
                                $firstDiffSectionIds = array_diff($exitSectionIds, $sectionIds);
                                $secondDiffSectionIds = array_diff($sectionIds, $exitSectionIds);

                                foreach ($firstDiffSectionIds as $diffSectionId) {
                                    $diffExistSectionModel = AssessmentSection::with('questions')->get()->find($diffSectionId);
                                    if (isset($diffExistSectionModel)) {
                                        $diffExistSectionModel->questions()->delete();
                                    }
                                }
                                foreach ($secondDiffSectionIds as $diffSectionId) {
                                    $diffExistSectionModel = AssessmentSection::with('questions')->get()->find($diffSectionId);
                                    if (isset($diffExistSectionModel)) {
                                        $diffExistSectionModel->questions()->delete();
                                    }
                                }
                                AssessmentSection::destroy(array_merge($firstDiffSectionIds, $secondDiffSectionIds));
                            }
                            foreach ($sections as $key => $section) {
                                if (is_object($section)) {
                                    $existSectionModel = AssessmentSection::find($section->id ?? null);
                                    $sectionModel = $existSectionModel ?? new AssessmentSection();
                                    $sectionModel->title = $section->title ?? '';
                                    $sectionModel->description = $section->desc ?? '';
                                    $sectionModel->section_order = $key;
                                    $sectionModel->assessment_id = $assessmentModel->id;
                                    $sectionModel->save();
                                    $questions = $section->questions ?? '';
                                    $existQuestions = $existSectionModel ? $existSectionModel->questions : collect([]);
                                    if (is_array($questions)) {
                                        //check old questions and new questions
                                        if (count($existQuestions) !== count($questions)) {
                                            $exitQuestionIds = $existQuestions->pluck('id')->all();
                                            $questionIds = array_filter(array_column($questions, 'id'));
                                            $firstDiff = array_diff($exitQuestionIds, $questionIds);
                                            $secondDiff = array_diff($questionIds, $exitQuestionIds);
                                            SectionQuestion::destroy(array_merge($firstDiff, $secondDiff));
                                        }

                                        foreach ($questions as $q_key => $question) {
                                            if (is_object($question)) {
                                                $existQuestionModel = SectionQuestion::where('id', $question->id ?? null)
                                                    ->where('section_id', $sectionModel->id ?? null)->first();
                                                $questionModel = $existQuestionModel ?? new SectionQuestion();
                                                $questionModel->question_order = $q_key;
                                                $questionSchema = new QuestionContentSchema();
                                                $questionSchema->build($question);
                                                $questionModel->schema = $questionSchema->toJson();
                                                if (!isset($existQuestionModel)) {
                                                    $sectionModel->questions()->save($questionModel);
                                                } else {
                                                    $questionModel->save();
                                                }
                                            }//question
                                        }
                                    }//questions
                                }//section
                            }
                        } else { //if there is no section let insert default section
                            $sectionModel = new AssessmentSection();
                            $sectionModel->title = $section->title ?? '';
                            $sectionModel->description = $section->desc ?? '';
                            $sectionModel->section_order = 0;
                            $assessmentModel->sections()->save($sectionModel);
                        }
                        //sections
                        return $this->getAssessmentSchema($assessmentModel);
                    }//assessment
                }//schema
            }
        }
        return false;
    }

    public function delete($request)
    {
        $assessmentModel = Assessment::find($request->id);
        if (isset($assessmentModel)) {
            $sections = $assessmentModel->sections;
            foreach ($sections as $section) {
                $existSectionModel = AssessmentSection::with('questions')->get()->find($section->id);
                if (isset($existSectionModel)) {
                    $existSectionModel->questions()->delete();
                }
            }
            $this->deleteCheckAssessments($assessmentModel);
            $assessmentModel->sections()->delete();
            $assessmentModel->delete();
            return ['msg' => 'The assessment has been deleted!'];
        }
        return false;
    }

    public function updateStatus($request)
    {
        $assessmentModel = Assessment::find($request->id);
        if (isset($assessmentModel) && $this->allowStatuses($request->status)) {
            $isChecking = CheckAssessment::where('assessment_id', $assessmentModel->id)->exists();
            if ($request->status === 'open' && $isChecking) {
                $assessmentModel->status = 'opening';
            } else {
                $assessmentModel->status = $request->status;
            }
            $assessmentModel->save();
            dispatch(new CheckAssessmentStatusJob($assessmentModel))->delay(now()->addSeconds(5));
            return ['msg' => 'The assessment status has been changed!'];
        }
        return false;
    }

    public function allowStatuses($title)
    {
        $statuses = ['close', 'open', 'opening', 'success'];
        return in_array($title, $statuses, true);
    }

    public function deleteCheckAssessments($assessmentModel): void
    {
        $check_assessments = CheckAssessment::where('assessment_id', $assessmentModel->id)->get();
        foreach ($check_assessments as $check_assessment) {
            $check_assessment_field_inspectors = CheckAssessmentFieldInspector::where('check_assessment_id', $check_assessment->id)->get();
            foreach ($check_assessment_field_inspectors as $check_assessment_field_inspector) {
                CheckAssessmentSection::where('type', 'field_inspector')->where('check_assessment_id', $check_assessment_field_inspector->id)->delete();
            }
            CheckAssessmentSection::where('type', 'institute')->where('check_assessment_id', $check_assessment->id)->delete();
        }
    }
}


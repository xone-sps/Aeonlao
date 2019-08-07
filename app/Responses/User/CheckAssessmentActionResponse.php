<?php
/**
 * Created by PhpStorm.
 * User: BeeTimberlake
 * Date: 6/3/2019
 * Time: 5:06 PM
 */

namespace App\Responses\User;


use App\Http\Controllers\Helpers\Helpers;
use App\Jobs\CheckSuccessChangeStatusCheckAssessmentFieldInspectorJob;
use App\Jobs\CheckSuccessCheckAssessmentJob;
use App\Models\CheckAssessment;
use App\Models\CheckAssessmentFieldInspector;
use Illuminate\Contracts\Support\Responsable;

class CheckAssessmentActionResponse implements Responsable
{

    private $actions;
    private $options;


    /**
     * CheckAssessmentActionResponse constructor.
     * @param $actions
     * @param array $options
     */
    public function __construct($actions, $options = [])
    {
        $this->actions = $actions;
        $this->options = $options;
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
            $user = $request->user();
            if ($this->actions === 'change-status' && $user->hasActions('change_check_assessments_status')) {
                $data = $this->postChangeStatus($request);
            } else if ($this->actions === 'save-answer-status-score' && $user->hasActions('save_check_assessments_status_score')) {
                $data = $this->saveAnswerStatusScore($request);
            }
            if ($data) {
                return response()->json(['data' => $data, 'success' => true]);
            }
        }
        return response()->json(['data' => null, 'success' => false]);
    }

    public function postChangeStatus($request)
    {
        $type = $request->type ?? 'institute';
        $check_assessment = CheckAssessment::find($request->id);
        if ($type === 'field_inspector') {
            $check_assessment = CheckAssessmentFieldInspector::where('id', $request->id)->where('field_inspector_id', $request->user_id)->first();
        }
        if (isset($check_assessment) && $this->allowStatuses($request->status)) {
            $check_assessment->status = $request->status;
            $check_assessment->save();
            if ($type !== 'field_inspector') {
                dispatch(new CheckSuccessChangeStatusCheckAssessmentFieldInspectorJob($check_assessment))->delay(now()->addSeconds(5));
            }else{
                dispatch(new CheckSuccessCheckAssessmentJob($check_assessment, ['type' => $type, 'forceStatus' => true]))->delay(now()->addSeconds(5));
            }
            return $check_assessment;
        }
        return null;
    }

    public function allowStatuses($title)
    {
        $statuses = ['close', 'checking', 'success'];
        return in_array($title, $statuses, true);
    }


    public function saveAnswerStatusScore($request)
    {
        $type = $request->get('type') ?? 'institute';
        $isStatusChanged = false;
        $checkAssessmentModel = CheckAssessment::where('user_id', $request->user_id)->whereIn('status', ['checking', 'success'])->where('id', $request->id)->first();
        if ($type === 'field_inspector') {
            $checkAssessmentModel = CheckAssessmentFieldInspector::where('id', $request->id)
                ->whereIn('status', ['checking', 'success'])->where('field_inspector_id', $request->user_id)->first();
        }
        if (isset($checkAssessmentModel)) {
            $check_sections = $checkAssessmentModel->sections();
            $schemaString = json_encode($request->check_assessment_sections);//answers from client
            if (Helpers::isValidJson($schemaString)) {
                $schema = json_decode($schemaString);
                if (is_array($schema) && count($schema) === count($check_sections)) {
                    foreach ($check_sections as $sKey => $check_section) {
                        #set scores
                        $section = $schema[$sKey];
                        if (is_object($section) && isset($section->id) && $section->id === $check_section->id
                            && $section->check_assessment_id === $check_section->check_assessment_id
                            && $this->allowSectionScores($section->score ?? 0)) {
                            $check_section->score = $section->score ?? 0;
                            $check_section->save();
                        }
                        #set scores
                        #set question status
                        $check_sections_questions = $check_section->checkAssessmentSectionQuestions;
                        $sections_questions = $schema[$sKey]->answers;
                        if (is_array($sections_questions) && count($sections_questions) === count($check_sections_questions)) {
                            foreach ($check_sections_questions as $qKey => $check_sections_question) {
                                $question = $sections_questions[$qKey];
                                if (isset($question->id, $question->status_approved)
                                    && $check_sections_question->id === $question->id) {
                                    $check_sections_question->status = $question->status_approved ? 'success' : 'checking';
                                    $check_sections_question->save();
                                    $isStatusChanged = true;
                                }
                            }
                        }
                        #set question status
                    }
                    if ($isStatusChanged) {
                        dispatch(new CheckSuccessCheckAssessmentJob($checkAssessmentModel, ['type' => $type]))->delay(now()->addSeconds(5));
                    }
                    return ['msg' => 'The scores and statuses was successfully saved!.'];
                }
            }
        }
    }

    public function allowSectionScores($title)
    {
        $statuses = [0, 1, 2, 3, 4, 5];
        return in_array((int)$title, $statuses, true);
    }
}

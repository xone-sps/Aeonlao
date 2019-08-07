<?php

namespace App\Jobs;

use App\Models\Assessment;
use App\Models\CheckAssessment;
use App\Models\CheckAssessmentFieldInspector;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CheckSuccessCheckAssessmentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $check_assessment;
    private $check_assessment_sections;
    private $options;

    /**
     * Create a new job instance.
     *
     * @param  $check_assessment
     * @param $options
     */
    public function __construct($check_assessment, $options)
    {
        $this->options = $options;
        $this->check_assessment = CheckAssessment::find($check_assessment->id);
        if (isset($this->options['type']) && $this->options['type'] === 'field_inspector') {
            $this->check_assessment = CheckAssessmentFieldInspector::find($check_assessment->id);
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        #All changes from this behavior will make user changed status will not the same as user set it's will replace.
        #set check assessment status
        if ($this->check_assessment) {

            $check_sections = $this->check_assessment->sections();
            $check_sections_count = count($check_sections);
            $check_sections_count_success = 0;

            foreach ($check_sections as $check_section) {
                $check_sections_questions = $check_section->checkAssessmentSectionQuestions;
                $check_sections_questions_count = count($check_sections_questions);
                $check_sections_questions_count_success = 0;
                foreach ($check_sections_questions as $check_sections_question) {
                    if ($check_sections_question->status === 'success') {
                        $check_sections_questions_count_success++;
                    }
                }
                #set section status
                if ($check_sections_questions_count_success === $check_sections_questions_count) {
                    $check_section->status = 'success';
                    $check_sections_count_success++;
                    $check_section->save();
                } else {
                    $check_section->status = 'checking';
                    $check_section->save();
                }
                #set section status
            }
            if (!(isset($this->options['forceStatus']) && $this->options['forceStatus'] === true)) {
                #set check assessment status
                if ($check_sections_count === $check_sections_count_success) {
                    $this->check_assessment->status = 'success';
                    $this->check_assessment->save();
                } else {
                    $this->check_assessment->status = 'checking';
                    $this->check_assessment->save();
                }
                #set check assessment status
            }


            #set check assessment field inspector
            if (isset($this->options['type']) && $this->options['type'] === 'field_inspector') {
                $checkAssessmentModel = CheckAssessment::find($this->check_assessment->check_assessment_id);
                $all_check_assessments = CheckAssessmentFieldInspector::where('check_assessment_id', $this->check_assessment->check_assessment_id)
                    ->where('field_inspector_id', $this->check_assessment->field_inspector_id)->get();
                $all_success_check_assessments = CheckAssessmentFieldInspector::where('check_assessment_id', $this->check_assessment->check_assessment_id)
                    ->where('status', 'success')
                    ->where('field_inspector_id', $this->check_assessment->field_inspector_id)->get();
                $all_close_check_assessments = CheckAssessmentFieldInspector::where('check_assessment_id', $this->check_assessment->check_assessment_id)
                    ->where('status', 'close')
                    ->where('field_inspector_id', $this->check_assessment->field_inspector_id)->get();
                if (isset($checkAssessmentModel)) {
                    if (count($all_check_assessments) === (count($all_success_check_assessments) + count($all_close_check_assessments))) {
                        $checkAssessmentModel->status = 'success';
                    } else {
                        $checkAssessmentModel->status = 'checking';
                    }
                    $checkAssessmentModel->save();
                    $assessment = Assessment::find($checkAssessmentModel->assessment_id);
                }
            } else {
                $assessment = Assessment::find($this->check_assessment->assessment_id);
            }

            #set assessment status
            if (isset($assessment)) {
                $all_check_assessments = CheckAssessment::where('assessment_id', $assessment->id)->get();
                $all_success_check_assessments = CheckAssessment::where('status', 'success')->where('assessment_id', $assessment->id)->get();
                $all_close_check_assessments = CheckAssessment::where('status', 'close')->where('assessment_id', $assessment->id)->get();
                if (count($all_check_assessments) === (count($all_success_check_assessments) + count($all_close_check_assessments))) {
                    $assessment->status = 'success';
                    $assessment->save();
                } else {
                    $assessment->status = 'opening';
                    $assessment->save();
                }
            }
            #set assessment status

        }
        #set check assessment status

    }
}

<?php

namespace App\Jobs;

use App\Models\CheckAssessmentFieldInspector;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CheckSuccessChangeStatusCheckAssessmentFieldInspectorJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $check_assessment;

    /**
     * Create a new job instance.
     *
     * @param $check_assessment
     */
    public function __construct($check_assessment)
    {
        $this->check_assessment = $check_assessment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $status = $this->check_assessment->status;
        if ($this->check_assessment->user->userType->typeUser->name === 'field_inspector') {
            $check_assessment_field_inspectors = CheckAssessmentFieldInspector::where('check_assessment_id', $this->check_assessment->id)->get();
            foreach ($check_assessment_field_inspectors as $check_assessment_field_inspector) {
                $check_assessment_field_inspector->status = $status;
                $check_assessment_field_inspector->save();
                if ($status === 'checking') {
                    dispatch(new CheckSuccessCheckAssessmentJob($check_assessment_field_inspector, ['type' => 'field_inspector']))->delay(now()->addSeconds(5));
                    sleep(5);
                }
            }
        } else if ($status === 'checking') {
            dispatch(new CheckSuccessCheckAssessmentJob($this->check_assessment, ['type' => 'institute']))->delay(now()->addSeconds(5));
        }
    }
}

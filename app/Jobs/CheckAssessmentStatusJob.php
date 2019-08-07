<?php

namespace App\Jobs;

use App\Models\CheckAssessment;
use App\Models\CheckAssessmentFieldInspector;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CheckAssessmentStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $assessment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($assessment)
    {
        $this->assessment = $assessment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $check_assessments = CheckAssessment::where('assessment_id', $this->assessment->id)->get();
        $status = $this->getCheckAssessmentStatus($this->assessment->status);
        foreach ($check_assessments as $check_assessment) {
            $check_assessment->status = $status;
            $check_assessment->save();
            if ($status === 'checking') {
                dispatch(new CheckSuccessChangeStatusCheckAssessmentFieldInspectorJob($check_assessment))->delay(now()->addSeconds(5));
            }
        }
    }

    public function getCheckAssessmentStatus($title)
    {
        $statuses = ['opening' => 'checking', 'success' => 'success', 'close' => 'close'];
        return $statuses[$title] ?? 'checking';
    }
}

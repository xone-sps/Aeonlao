<?php

namespace App\Jobs;

use App\Mail\UserActionsEmailNotify;
use App\Models\Site;
use App\Traits\UserRoleTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendNewUpdatedCheckingAssessmentForUsers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, UserRoleTrait;

    protected $user;
    protected $settings;

    /**
     * SendNewUpdatedCheckingAssessmentForUsers constructor.
     * @param $user
     * @param $settings
     */
    public function __construct($user)
    {
        $this->user = $user;
        $this->settings = $this->getSettings();
    }

    public function getSettings(): array
    {
        $settings = Site::select('id', 'key', 'value')
            ->whereNotIn('key', [])->get();
        $s = [];
        foreach ($settings as $setting) {
            $s[$setting->key] = $setting->value;
        }
        return $s;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $assessments = $this->user->assessments;
        $assessmentsText = '';
        foreach ($assessments as $assessment) {
            $item = $assessment->assessment;
            $assessmentsText .= "Title: {$item->title}<br>";
        }
        $data['from'] = $this->settings['email'];
        $data['subject'] = 'New Checking Assessments | ' . $this->settings['site_name'];
        $data['user_name'] = 'Dear ' . $this->user->name;
        $data['content_text'] = "You are receiving this email because we received a new checking assessments for you.<br><br><b>Current Assessments:<b><br><br>{$assessmentsText}";
        $link = url('/');
        if ($this->user->userType->type_user_id === $this->getTypeUserId('institute')) {
            $link = route('institute.get.check-assessments');
        } else if ($this->user->userType->type_user_id === $this->getTypeUserId('field_inspector')) {
            $link = route('field-inspector.get.check-assessments');
        }
        $data['bottom_text'] = 'If you did not request to check it now, no further action is required.';
        $data['button_text'] = 'Check assessments now';
        $data['button_link'] = $link;
        $data['footer_text'] = 'If you’re having trouble clicking the button, copy and paste the URL below into your web browser: ' . $link;
        Mail::to($this->user->email)->send(new UserActionsEmailNotify($data));
    }
}

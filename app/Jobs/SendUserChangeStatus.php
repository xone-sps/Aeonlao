<?php

namespace App\Jobs;

use App\Mail\UserActionsEmailNotify;
use App\Models\Site;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendUserChangeStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $settings;

    /**
     * Create a new job instance.
     *
     * @param $user
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
        $data['from'] = $this->settings['email'];
        if ($this->user->status === 'approved') {
            $data['subject'] = 'Registration completed | ' . $this->settings['site_name'];
            $data['user_name'] = 'Dear ' . $this->user->name;
            $data['content_text'] = 'Thank you for your registration.<br>Your registration has been approved!';
            $link = route('get.home.login');
            $data['bottom_text'] = 'If you did not request to check it now, no further action is required.';
            $data['button_text'] = 'Sign in now';
            $data['button_link'] = $link;
            $data['footer_text'] = 'If you’re having trouble clicking the button, copy and paste the URL below into your web browser: ' . $link;
            Mail::to($this->user->email)->send(new UserActionsEmailNotify($data));
        } else if ($this->user->status === 'disabled') {
            $data['subject'] = 'Your account status changed | ' . $this->settings['site_name'];
            $data['user_name'] = 'Dear ' . $this->user->name;
            $data['content_text'] = 'You are receiving this email because your account status changed to disabled and you cannot using our website for this time.<br>Please contact us to get more information.';
            $link = route('get.home.index');
            $data['bottom_text'] = 'If you did not request to check it now, no further action is required.';
            $data['button_text'] = $this->settings['site_name'];
            $data['button_link'] = $link;
            $data['footer_text'] = 'If you’re having trouble clicking the button, copy and paste the URL below into your web browser: ' . $link;
            Mail::to($this->user->email)->send(new UserActionsEmailNotify($data));
        }
    }
}

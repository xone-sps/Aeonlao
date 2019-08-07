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

class SendNewUserRegistration implements ShouldQueue
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
        $data['subject'] = 'New User Registration | ' . $this->settings['site_name'];
        $data['user_name'] = 'Dear ' . $this->settings['site_name'];
        $data['content_text'] = "You are receiving this email because we received a new user registration on your site.<br>User name: {$this->user->name}<br>User email: {$this->user->email}.";
        $link = route('admin.get.index.members');
        $data['bottom_text'] = 'If you did not request to check it now, no further action is required.';
        $data['button_text'] = 'Check the user now';
        $data['button_link'] = $link;
        $data['footer_text'] = 'If you’re having trouble clicking the button, copy and paste the URL below into your web browser: ' . $link;
        Mail::to($this->settings['email'])->send(new UserActionsEmailNotify($data));
    }
}

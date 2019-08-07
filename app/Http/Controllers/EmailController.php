<?php

namespace App\Http\Controllers;

use App\Mail\UserActionsEmailNotify;
use App\Models\Posts;
use App\Models\Site;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    private static $owner;
    private static $owner_name;
    private static $owner_contact_info = '';
    private static $settings;

    public static function getSettings(): array
    {
        $settings = Site::select('id', 'key', 'value')
            ->whereNotIn('key', [])->get();
        $s = [];
        foreach ($settings as $setting) {
            $s[$setting->key] = $setting->value;
        }
        return $s;
    }

    public static function getOwnerContactInfo(): string
    {
        $settings = self::getSettings();
        if (isset($settings['tel'], $settings['email'])) {
            return "Tel: {$settings['tel']}<br> 
                    Email: {$settings['email']}";
        }
        return '';
    }

    public static function init(): void
    {
        self::$owner = config('mail.username');
        self::$owner_name = config('app.name');
        self::$owner_contact_info = self::getOwnerContactInfo();
        self::$settings = self::getSettings();
    }

    public static function UserNotify(User $user = null): void
    {
        self::init();
        $data['from'] = self::$settings['email'];
        $data['subject'] = 'New User Registration | ' . self::$settings['site_name'];
        $data['user_name'] = 'Dear ' . self::$owner_name;
        $data['content_text'] = "You are receiving this email because we received a new user registration on your site.\nUser name: {$user->name}, email: {$user->email}.";
        $link = route('admin.get.index.members');
        $data['bottom_text'] = 'If you did not request to check it now, no further action is required.';
        $data['button_text'] = 'Check the user now';
        $data['button_link'] = $link;
        $data['footer_text'] = 'If you’re having trouble clicking the button, copy and paste the URL below into your web browser: ' . $link;
        $when = Carbon::now()->addSeconds(5);
        Mail::to($data['from'])->later($when, new UserActionsEmailNotify($data));
    }

    public static function PostsCreatedNotify(Posts $post): void
    {
        self::init();
        $postType = ucfirst($post->type);
        $data['from'] = self::$owner;
        $data['subject'] = 'New ' . $postType . ' Posted | ' . self::$settings['site_name'];
        $data['user_name'] = 'Dear ' . self::$owner_name;
        $data['content_text'] = "You are receiving this email because we received a new {$postType} posted on your site.\n{$postType} title: {$post->title}.";
        $link = route('admin.get.' . $post->type);
        $data['bottom_text'] = 'If you did not request to check it now, no further action is required.';
        $data['button_text'] = 'Check the post now';
        $data['button_link'] = $link;
        $data['footer_text'] = 'If you’re having trouble clicking the button, copy and paste the URL below into your web browser: ' . $link;
        $when = Carbon::now()->addSeconds(5);
        Mail::later($when, new UserActionsEmailNotify($data));
    }
}

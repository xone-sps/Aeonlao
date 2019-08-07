<?php

namespace App\Notifications;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Helpers\Helpers;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class UserResetPasswordNotification extends Notification
{
    use Queueable;

    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * The callback that should be used to build the mail message.
     *
     * @var \Closure|null
     */
    public static $toMailCallback;

    /**
     * Create a notification instance.
     *
     * @param  string $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $settings = $this->getSettings();

        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        return (new MailMessage)->from($settings['email'])->view(
            'emails.email', $this->dataMessage($notifiable, $settings)
        );
    }

    public function dataMessage($notifiable, $settings)
    {
        $data['subject'] = 'Reset Password Notification | ' . $settings['site_name'];
        $data['user_name'] = 'Dear ' . $notifiable->name . ',';
        $data['content_text'] = 'You are receiving this email because we received a password reset request for your account.';
        $base6dEmail = Helpers::trimBase64('^') . Helpers::trimBase64($notifiable->email);
        $link = route('password.reset', $this->token . $base6dEmail);
        $data['bottom_text'] = 'If you did not request a password reset, no further action is required.';
        $data['button_text'] = 'Reset Password';
        $data['button_link'] = $link;
        $data['footer_text'] = 'If you’re having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser: ' . $link;
        return $data;
    }

    /**
     * Set a callback that should be used when building the notification mail message.
     *
     * @param  \Closure $callback
     * @return void
     */

    public static function toMailUsing($callback)
    {
        static::$toMailCallback = $callback;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    public function getSettings(): array
    {
        return (new AdminController())->getSettings();
    }
}

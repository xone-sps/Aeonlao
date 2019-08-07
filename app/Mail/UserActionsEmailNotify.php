<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserActionsEmailNotify extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new message instance.
     *
     * @param $data
     */

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self
    {
        if (isset($this->data['attach']) && $this->data['attach'] === true) {
            return $this->from($this->data['from'])
                //'->from('test@example.com', 'Example')
                ->subject($this->data['subject'])
                ->view('emails.email', $this->data)
                ->attachData($this->data['fileData'], $this->data['fileName'], [
                    'mime' => $this->data['fileMime'],
                ]);
        }
        return $this->from($this->data['from'])
            ->subject($this->data['subject'])
            ->view('emails.email', $this->data);
    }
}

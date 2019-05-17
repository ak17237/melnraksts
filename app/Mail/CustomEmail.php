<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($recievers,$title,$text,$button)
    {
        $this->recievers = $recievers;
        $this->title = $title;
        $this->text = $text;
        $this->button = $button;
        $this->preview = false;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('Emails.Customemail')->subject($this->title)->with([
            'title' => $this->title,
            'text' => $this->text,
            'button' => $this->button,
            'preview' => $this->preview
            ])->bcc($this->recievers);
    }
}

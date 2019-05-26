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
        $this->recievers = $recievers; // saņēmēju e-pasti
        $this->title = $title; // e-pasta virsraksts
        $this->text = $text; // e-pasta teksts
        $this->button = $button; // e-pasta pogas iestatījumi
        $this->preview = false; // skats nav preikš apskatīšanas bet pirekš sūtīšanas
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('Emails.Customemail')->subject($this->title)->with([ // sūta skatu lietotājiem padodot tam parametrus
            'title' => $this->title,
            'text' => $this->text,
            'button' => $this->button,
            'preview' => $this->preview
            ])->bcc($this->recievers);
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Ticket extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($reserv,$event,$path)
    {
        $this->reserv = $reserv;
        $this->event = $event;
        $this->path = $path;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('Emails.Ticket')->subject($this->event->Title)->with([
            'event' => $this->event,
            'reserv' => $this->reserv
            ])->attach($this->path)->to($this->reserv->email);
    }
}

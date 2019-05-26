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
    public function __construct($reserv,$email,$event,$path)
    {
        $this->reserv = $reserv; // rezervācijasdati
        $this->user = $email; // lietotāja dati
        $this->event = $event; // pasākuma dati
        $this->path = $path; // ceļš uz biļeti
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
            ])->attach($this->path)->to($this->user);
    }
}

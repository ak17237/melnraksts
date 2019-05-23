<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReservationChange extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($reservation,$email,$changes,$event)
    {
        $this->email = $email;
        $this->reservation = $reservation;
        $this->changes = $changes;
        $this->event = $event;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('Emails.Reservationchange')->subject("Rezervācijas izmaiņas")->with([
            'reservation' => $this->reservation,
            'changes' => $this->changes,
            'event' => $this->event
            ])->to($this->email);
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Auth;
use App\User;

class EventChange extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $reserv;
    public $event;
    public $changedate;
    public $changeaddress;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($reservuser,$user,$event,$change)
    {
        $this->user = $user;
        $this->reserv = $reservuser;
        $this->event = $event;
        $this->changedate = $change[0];
        $this->changeaddress = $change[1];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('Emails.Eventchange')->subject("Rezervētā pasākuma izmaiņas")->with([
            'event' => $this->event,
            'changedate' => $this->changedate,
            'changeaddress' => $this->changeaddress,
            'reserv' => $this->reserv
            ])->bcc($this->user);
    }
}

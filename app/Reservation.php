<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = ['email','EventID','Tickets','Seats','TableNr','TableSeats','Transport','QRcode'];
}

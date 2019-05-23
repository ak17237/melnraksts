<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = ['user_id','EventID','Tickets','Seats','TableNr','TableSeats','Transport','QRcode','Attendance'];
}

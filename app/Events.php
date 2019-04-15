<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    protected $fillable = ['Title','Datefrom','Dateto','Address','Tickets','Seatnumber','Tablenumber','Seatsontablenumber','Anotation','Description','Melnraksts','VIP','Editable','email','linkcode'];
    
    
}

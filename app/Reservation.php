<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $table = 'reservations';
    protected $fillable = [
        'name', 'phone', 'email', 'date_and_time', 'message', 'status',
    ];
}

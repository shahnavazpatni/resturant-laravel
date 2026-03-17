<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $table = 'sliders';
    protected $fillable = [
        'title', 'sub_title', 'image', 'status',
    ];
    protected $primaryKey = 'id';
}

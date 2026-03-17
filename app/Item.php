<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';
    protected $fillable = [
        'category_id', 'name', 'description', 'price', 'image',
    ];

    public function category(){
        return $this->belongsTo('App\Category');
    }
}

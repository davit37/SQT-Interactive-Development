<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    public function category_contents(){
        return $this->belongsToMany('App\CategoryContent');
     }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public function projectCategory(){
        return $this->belongsTo('App\ProjectCategory','project_category_id');
    }

    public function contents(){
        return $this->hasMany('\App\CategoryContent','project_category_id')->orderBy('sort_number');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public function projectCategory(){
        return $this->belongsTo('App\ProjectCategory');
    }

    public function contents(){
        return $this->hasMany('\App\CategoryContent','project_category_id','project_category_id')->orderBy('sort_number');
    }

    public function Menus(){
        return $this->hasMany('\App\Menu','project_category_id','project_category_id')->orderBy('order');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectCategory extends Model
{

    use SoftDeletes;
    
    public function contents(){
       return $this->hasMany('\App\CategoryContent','project_category_id')->orderBy('sort_number');
    }

    public function projects(){
        return $this->hasMany('\App\Project','project_category_id');
    }

    public function Menus(){
        return $this->hasMany('\App\Menu','project_category_id','project_category_id')->orderBy('order');
    }



}

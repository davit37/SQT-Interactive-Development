<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    //
    protected $table = 'menu';

    public function projectCategory(){
        return $this->belongsTo('App\ProjectCategory','project_category_id');
    }
}

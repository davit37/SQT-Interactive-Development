<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryContent extends Model
{
    protected $table = "catgory_contents";

    protected $fillable = [
        'name', 'project_category_id', 'custom_html','selected_content','sort_number',
    ];

    public function projectCategory(){
        return $this->belongsTo('App\ProjectCategry','project_category_id');
    }
    public function projects(){
        return $this->belongsTo('\App\Project','project_category_id');
    }

    
}

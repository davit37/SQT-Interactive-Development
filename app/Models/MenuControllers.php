<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuControllers extends Model
{
    use SoftDeletes;
    protected $table = "menu_controllers";
    
}

<?php

namespace App\Http\Controllers\Test;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SvgTest extends Controller
{
    public function index(){

        $svg = file_get_contents(asset('assets/img/menu icon/Guest Wifi.svg'));

        echo "<div class='icon-svg'>".$svg."</div>";

    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegexTest extends Controller
{
    public function index(){
    //     $string = '<div>April 15, 2003 <if_statement [slug="content"][abc="wew"] [wew="czxcxz"]>Welcome to room</if_statement> ini lanjutannya <if_statement [slug="category"][abc="wew"] [wew="czxcxz"]>End-Game</if_statement> ini akhirnya</div>';
    //    // $pattern = '/(\w+) (\d+), (\d+)/i';
    //    // $pattern = '/(\<if_statement.(\[slug="(\^[content\])"\])\>)(.*)(\<\/if_statement\>)/';
    //    $pattern = '/(<if_statement.\[slug="category"\].(.*)>)(.*)(\<\/if_statement\>)/';

    //    $replacement = '<span style="color:red;">$3</span>';
    //    echo preg_replace($pattern, $replacement, $string);


    //    exit;


        $regex = "/(\<if_statement.(.*?)\>)(.*)(\<\/if_statement\>)/";
        $text = '<if_statement slug="category" abc="wew" wew="czxcxz"><span class="">Meeting Room L[change="level_floor"] -[change="room_number"] is for :</span></if_statement>';

        
        $find_matches = preg_match($regex, $text, $output_array);

        $slug_value = "content";
        
        

        if(!empty($output_array[2])){

            $new_regex = '/(\w+)="(\w+)"/';
            $find_matches2 = preg_match_all($new_regex, $output_array[2], $new_output);

            if(!empty($new_output[1])){

                if(in_array('slug',$new_output[1])){
                    
                    $key = array_search('slug',$new_output[1]);
                    $slug_value = $new_output[2][$key];

                    echo $slug_value;


                }

            }


            if($slug_value )

            echo '<pre>';

            print_r($new_output);
            echo '</pre>';
        }else{
            echo "output gak ketemu";   
        }
        
  
        
    }
}

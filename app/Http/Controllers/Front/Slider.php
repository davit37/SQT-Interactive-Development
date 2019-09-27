<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Slider extends Controller
{
    public function slide($id){
        $projects= \App\Project::with('projectCategory')->with('contents')->where('id', $id)->firstOrFail();
        return view('front.slide', ['projects'=>$projects]);
    }
    

    public function custom_url($url){
        $projects= \App\Project::with('projectCategory')->with('contents')->with('menues')->where('custom_url', $url)->get();


        $selected_slug_type = "content";

       
        if($projects->isEmpty()){
            $projects= \App\ProjectCategory::where('slug',$url)->with('contents')->with('menues')->firstOrFail();

            $selected_slug_type="category";
          

            $new_contents= $projects->contents->map(function($item){
                $item['new_custom']=$item->custom_html;
                return $item;
            });
         
        }else{
          
            $projects= $projects->first();

            $new_contents= $projects->contents->map(function($item) use($projects){
          

                $tmp_change = array(
                        'name'=>$projects->name,
                        'level_floor' => $projects->level_floor,
                        'room_number' => $projects->room_number,
                );
                $item['new_custom']=$this->str_rep($item->custom_html,$tmp_change);
               
                return $item;
            });
         
        }


        

        // $new_contents= $projects->contents->map(function($item) use($projects){
          

        //     $pattern = '/(<if_statement.slug="((?!'.$item->selected_slug_type.').)*".(.*)>)(.*)(\<\/if_statement\>)/';
        //     $replacement = '<span style="color:red;">$3</span>';
        //     $item['new_custom']= preg_replace($pattern, $replacement, $item->new_custom);
           
        //     return $item;
        // });


        

        return view('front.slide', ['projects'=>$projects,'contents'=>$new_contents, 'selected_slug_type'=>$selected_slug_type]);
    }

    public function str_rep($text, $tmp_change){
        
        $regex = '/[[]change="([^"]*)"[]]/';
        $find_matches = preg_match_all($regex, $text, $output_array);
        
        if(!empty($output_array) && is_array($output_array)){

            foreach($output_array[1] as $i => $v){
                   
                // $tmp_change = array(
                //     'name' => 'Room 1',
                //     'level_floor' => 'Room 1',
                //     'room_number' => 'Room 1',
                // );
                $tmp_keys = array_keys($tmp_change);

                if(in_array($v, $tmp_keys)){
                    $text = str_replace($output_array[0][$i], $tmp_change[$v], $text);
                }else{
                    $text = str_replace($output_array[0][$i], '', $text);
                }


            
            }
        }
       
        return $text;

    }

}


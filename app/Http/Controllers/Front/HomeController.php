<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use App\Http\Controllers\Controller;
use App\Models\MenuControllers as MenuControllers_Model;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    private $agent;

    public function __construct()
    {
        $this->agent = New Agent();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $projects=\App\Project::with('projectCategory')->get();
        $projectCategories=\App\ProjectCategory::orderBy('id','desc')->get();
        

        $_temp=[];
        $url;
        $media='front.home.desktop';

        foreach ($projectCategories as $key => $value){
            array_push($_temp, 
            ['name'  => $value->name,
              'type' => $value->type_slide,
              'url'  => $value->slug,
              'menu_controller_id' => $value->menu_controller_id
              
              ]            
             );
        }

        if($this->agent->isMobile()){
            $media ='front.home.mobile';
        }
        
     
        return view($media, ['projects'=>$_temp]);
    }

    public function single_menu($slug=''){

        $menu = MenuControllers_Model::where("slug",$slug)->get();

        if($menu->isEmpty()){
            return $this->get_slide($slug);
        }
        
        $config = $menu->map(function($item){
            $data_config=[
                "icon"=>$item->icon_menu,
                "name"=>$item->name,
                'data'=>\App\ProjectCategory::where('menu_controller_id',$item->id)->orderBy('id','desc')->get(),
            ];

            return $data_config;
            
        });

    //   switch($slug){

    //     case 'meeting-rooms':
    //         $config=[
    //             'icon' => asset('assets/img/menu icon/Meeting Rooms.svg'),
    //             'name' => 'Meeting Rooms',
    //             'data' => \App\ProjectCategory::where('type_slide','meeting-rooms')->orderBy('id','desc')->get(),
    //         ];
    //         break;

    //     case 'telephone':
    //         $config=[
    //             'icon' => asset('assets/img/menu icon/Telephone.svg'),
    //             'name' => 'Telephone',
    //             'data' => \App\Project::where('type_slide',$slug)->get(),
    //         ];
    //         break;
    //         case 'printer':
    //         $config=[
    //             'icon' => asset('assets/img/menu icon/Printer.svg'),
    //             'name' => 'Printer',
    //             'data' => \App\Project::where('type_slide',$slug)->get(),
    //         ];
    //         break;
    //         case 'guest-wifi':
    //         $config=[
    //             'icon' => asset('assets/img/menu icon/Guest Wifi.svg'),
    //             'name' => 'Guest Wifi',
    //             'data' => \App\Project::where('type_slide',$slug)->get(),
    //         ];
    //         break;
    //         case 'panel-meeting-rooms':
    //         $config=[
    //             'icon' => asset('assets/img/menu icon/Panel Meeting Rooms.svg'),
    //             'name' => 'Panel Meeting Rooms',
    //             'data' => \App\Project::where('type_slide',$slug)->get(),
    //         ];
    //         break;
    //         case 'visitor-registration':
    //         $config=[
    //             'icon' => asset('assets/img/menu icon/Visitor Registration.svg'),
    //             'name' => 'Visitor Registration',
    //             'data' => \App\Project::where('type_slide',$slug)->get(),
    //         ];
    //         break;

    //     default: 
    

     $config['slug'] = $slug;


      return view('front.home.single_menu', ['config'=>$config]);

    }


    public function get_slide($slug){
        $projects= \App\Project::with('projectCategory')->with('contents')->with('Menus')->where('custom_url', $slug)->get();


        $selected_slug_type = "content";

    
        if($projects->isEmpty()){
            $projects= \App\ProjectCategory::where('slug',$slug)->with('contents')->with('Menus')->firstOrFail();

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



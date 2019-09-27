<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datatables;
use QrCode;
use QrMarge;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $menu_controller_id = $request->input(
            'menu_controller_id'
        );

        if($menu_controller_id === null){
            abort(404);
        }
        
        $projects=\App\Project::with("projectCategory")->get();

        return view('admin.project.index', ['projects'=>$projects, 'menu_controller_id'=>$menu_controller_id]);
    }


    //make shortlink
    private function bitly_short( $url ) {
        //$url = urlencode( $url );
        $req = "https://api-ssl.bitly.com/v3/shorten?access_token=99ec73ba38efec452a4075216eb6095f974d5571&longUrl=$url";
        $ch  = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $req );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch, CURLOPT_REFERER, "http://www.google.com" );
        $body = curl_exec( $ch );
        curl_close( $ch );
        $json = json_decode( $body, true );
        return $json['data']['url'];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $menu_controller_id = $request->input(
            'menu_controller_id'
        );
        $projectCategories =  \App\ProjectCategory::where('menu_controller_id',$menu_controller_id)->get();
        return view('admin.project.form', ['projectCategories'=>$projectCategories, 'menu_controller_id'=>$menu_controller_id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $project = new \App\Project;
        if($request->menu_controller_id === 4){
            $request->validate([
                'name'=> 'required|max:100',
                'category_id'   => 'required',
                'custom_url'    =>'required|unique:projects,custom_url',
                'room_number'   =>'required',
                'level_floor'   =>'required',
    
            ]);
            $project->level_floor =$request->level_floor ;
            $project->room_number = $request->room_number;
        }else{
            $request->validate([
                'name'=> 'required|max:100',
                'category_id'   => 'required',
                'custom_url'    =>'required|unique:projects,custom_url',    
            ]);
        }
       
        
        

      

        if(!empty($request->custom_url)){
            $custom_url =Str::slug($request->custom_url,'-');
            $url =  \URL::to('/').'/'.$custom_url;
            $short_url=$this->bitly_short($url);
            $project->short_url = $short_url;
            $project->custom_url=$custom_url;
        }

        
        $project->name=$request->name;
        $project->project_category_id = $request->category_id;

        
        $project->slug=str_slug($request->name,'-');

        $project->save();

        return redirect()->route("project.index",["menu_controller_id"=>$request->menu_controller_id])
        ->with('status', 'Project has been successfully saved');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {

        $menu_controller_id = $request->input(
            'menu_controller_id'
        );

        $projectCategories =  \App\ProjectCategory::where('menu_controller_id',$menu_controller_id)->get();
        $project = \App\Project::with('projectCategory')->findOrFail($id);
       

        return view('admin.project.form', ['projectCategories'=>$projectCategories, 'project'=>$project, 'menu_controller_id'=>$menu_controller_id ]);
    }

  

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $project = \App\Project::findOrFail($id);
        if($request->menu_controller_id === 4){
            $request->validate([
                'name'=> 'required|max:100',
                'category_id'   => 'required',
                'custom_url'    =>'required|unique:projects,custom_url,'.$id,
                'room_number'   =>'required',
                'level_floor'   =>'required',
    
            ]);
            $project->level_floor =$request->level_floor ;
            $project->room_number = $request->room_number;
        }else{
            $request->validate([
                'name'=> 'required|max:100',
                'category_id'   => 'required',
                'custom_url'    =>'required|unique:projects,custom_url,'.$id,  
            ]);
        }
            if(!empty($request->custom_url)){
            $custom_url =Str::slug($request->custom_url,'-');
            $url =  \URL::to('/').'/'.$custom_url;
            $short_url=$this->bitly_short($url);
            $project->short_url = $short_url;
            $project->custom_url= $custom_url;
        }

        
        $project->name=$request->name;
        $project->project_category_id = $request->category_id;

        
        $project->slug=str_slug($request->name,'-');

        $project->save();

        return redirect()->back()
        ->with('status', 'Project has been successfully update');

        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project_category = \App\Project::findOrFail($id);
        $project_category->delete();

        return back()->with('status', 'Project moved to trash');

    }

    public function _ajax_getQr(Request $req){

        $orientation = $req->orientation;

     
        if($orientation=="portrait"){

            $qr=QrCode::format('png')->color(27, 120, 171)->merge('/img/2019-03-21.jpg',
            .15)->margin(0)->size(398)->generate(\URL::to('/').'/'.$req->custom_url);
    
            $base64string=base64_encode(QrMarge::marge_image_portrait($qr,$req->short_url
            ));
        }else{

            $qr=QrCode::format('png')->color(27, 120, 171)->margin(0)->size(158)->generate(\URL::to('/').'/'.$req->custom_url);
    
            $base64string=base64_encode(QrMarge::marge_image_landscape($qr,$req->short_url
            ));

        }

        echo $base64string;
    }

    

}

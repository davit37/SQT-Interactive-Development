<?php

declare(strict_types=1);

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Request\ProjectCategoryReq as CategoryReq;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;


class ProjectCategoryController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
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
        $projectCategories=\App\ProjectCategory::where('menu_controller_id',$menu_controller_id)->orderBy('id','desc')->get();

        
     
        return view('admin.project_category.index', ['projectCategories'=>$projectCategories, 'menu_controller_id'=>$menu_controller_id]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $labels = \App\Label::get();
        $maxId=\DB::table('catgory_contents')->max('id');
        return view('admin.project_category.content',['labels'=>$labels,'maxId'=>$maxId]);
     
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      /*  $arrReq=[];
        $arrCategory= ['category_name'=>$request->get('category_name')];
        $jsonContent = ['json_content'=>json_decode($request->newJsonContent,true)];
        
        $arrReq=array_merge((array)$arrCategory,(array)$jsonContent);

        $content_type = 'static_image';
        


        $messages = [
            'json_content.*.name.required' => 'This field name is required.',
            'json_content.*.static_images.required' => 'This field need static image'  
        ];

        $validateCategory=\Validator::make(
            $arrReq, [
            'category_name'                         =>'required|max:191',
            // 'json_content.*.sort_number'           =>'required|unique',
            'json_content.*.name'                   =>'required',
            'json_content.*.static_images'          =>'required'
            ],
            $messages
        );

        if($validateCategory->fails()){
            
            return response()->json([
            
            'errors'=>$validateCategory->errors()],400);
        }
        */
        
        $project_category =new  \App\ProjectCategory;
        $project_category->name=$request->get('category_name');
        $project_category->slug=Str::slug($request->get('category_name'), '-');
        $project_category->menu_controller_id=$request->get('menu_controller_id');

       

        if($project_category->save()){
            
            $json= json_decode( $request->newJsonContent,true);

           
            
            try{
                
                $this->storeNewContent($request,$project_category->id);
                $this->storeNewMenu($request,$project_category->id);
                $request->session()->flash('status', 'New Category was successfully saved!');
                return  response()->json([
                    'status'      => 'saved',
                    
                ], 200);
            }catch(Exception $e){
                return  response()->json([
                    'status'      => 'failed',
                    'massage'     =>  $e,
                ], 400);
            }   
        }


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
    public function edit($id)
    {
        
        $category=\App\ProjectCategory::findOrFail($id);
        $category_contents=\App\CategoryContent::with('labels')->where('project_category_id',$id)->orderBy('sort_number')->get();
        $labels = \App\Label::get();
        $maxId=\DB::table('catgory_contents')->max('id');

        return view('admin.project_category.content', ['category'=>$category,'labels'=>$labels,'category_contents'=>$category_contents,'maxId'=>$maxId]);

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
        /*
        *For Validate
        *
        */
       

       

        $arrCategory    = ['category_name'      =>$request->get('category_name')];
        $oldJsonContent = ['old_json_content'   =>json_decode($request->oldJsonContent,true)];
        $newJsonContent = ['new_json_content'   =>json_decode($request->newJsonContent,true)];
        $deletedContent = ['deleted_content'    =>json_decode($request->deletedContent,true)];

        $arrReq=array_merge((array)$arrCategory,(array)$oldJsonContent,(array)$newJsonContent,(array)$deletedContent );
        
        $project_category =\App\ProjectCategory::FindOrFail($id);
        $project_category->name=$request->get('category_name');
       
       
        
        if($project_category->save()){


            $oldJson= json_decode($request->oldJsonContent,true);
            $label_categories_fil = $this->filterLabel($oldJson,'label_categories');
            $label_del_fil = $this->filterLabel($oldJson,'label_del');


            $this->labelDelete($label_del_fil);


            $this->storeLabel($label_categories_fil);
     
            foreach ($oldJson as $key => $value){
                unset($oldJson[$key]['label_categories']);
                unset($oldJson[$key]['label_del']);
            }

           $category_content=\Batch::update('catgory_contents', $oldJson, 'id');


            if($request->oldJsonMenu != 'null'){
                $this->updateOldMenu($request);
            }
         
            try{
                $this->storeNewMenu($request,$id);
                $this->storeNewContent($request,$id);
                $request->session()->flash('status', 'Category was successfully updated!');
                return  response()->json([
                    'status'      => 'updated', 
                ], 200);
            }catch(Exception $e){
                return  response()->json([
                    'status'      => 'failed',
                    'massage'     =>  $e,
                ], 400);
            }   
        }
    }

    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $req,$id)
    {
        //check child relationsship and prevent delete if true
        $check_relationships= \App\Project::where("project_category_id",$id)->get();

        if(!$check_relationships->isEmpty()){
            
            $req->session()->flash("status","alert-danger");
            return back()->with('message', 'Cannot delete category has child ');
        }

        $project_category = \App\ProjectCategory::findOrFail($id);
        $project_category->delete();

        $req->session()->flash("status","alert-success");
        return back()->with('message', 'Category moved to trash');
    }

    public function content($id){
        $category=\App\ProjectCategory::with('contents')->findOrFail($id);

        return view('admin.project_category.content', ['category'=>$category]);
    }

    public function duplicate($id){
        $category=\App\ProjectCategory::with('contents')->findOrFail($id);

        return view('admin.project_category.duplicate', ['category'=>$category]);
    }


    //detail for dinamic page

    public function detail(Request $request){

        $oldJsonMenu;
        $id= $request->input('id');
        $action=$request->input('action');

        $labels = \App\Label::get();
        $maxId=\DB::table('catgory_contents')->max('id');
        $maxIdMenu=\DB::table('menu')->max('id');
       

        if(!empty($id) && $action=='edit' || $action=='duplicate'){
            $category=\App\ProjectCategory::findOrFail($id);

            $category_contents=\App\CategoryContent::with('labels')->where('project_category_id',$id)->orderBy('sort_number')->get();


            $menues=\App\Menu::with('projectCategory')->where('project_category_id',$id)->orderBy('order')->get();

            $oldJsonMenuTmp = $menues->map(function($item, $key){
                unset($item->created_at);
                unset($item->updated_at);
                unset($item->project_category);
                return $item;
               });

            if($action=='edit'){

                $dataJson='oldJsonContent';

                

                $varMenu = 'oldJsonMenu';
                
            }elseif($action=='duplicate' ){

                if($request->input('menu_controller_id')===null){
                    abort(404);
                }

                $dataJson=' newJsonContent';
                $menu_controller_id = $request->input('menu_controller_id');

                foreach($category_contents as $key=>$content){

                    $maxId++;
                    unset($category_contents[$key]->id);
                    $category_contents[$key]->id=  $maxId;
                    
                }

               

               foreach($oldJsonMenuTmp as $key=>$menu){
                $maxIdMenu++;
                $oldJsonMenuTmp[$key]->id=  $maxIdMenu;
                 unset($category_contents[$key]->project_category_id);
                 unset($category_contents[$key]['project_category_id']);
                }

               $varMenu = 'newJsonMenu';
            }


            return view('admin.project_category.content', ['category'=>$category,'labels'=>$labels,'category_contents'=>$category_contents,'maxId'=>$maxId,'menues'=>$menues,'dataJson'=>$dataJson,'oldJsonMenu'=>$oldJsonMenuTmp , 'varMenu'=>$varMenu, 'menu_controller_id'=>$menu_controller_id ?? ''] );

        }else if(!empty($id) && ($action !='edit' || $action !='duplicate' || $request->input('menu_controller_id')===null )){
            abort(404);
        }else{
            $labels = \App\Label::get();
            $maxId=\DB::table('catgory_contents')->max('id');
            return view('admin.project_category.content',['labels'=>$labels,'maxId'=>$maxId, 'menu_controller_id'=>$request->input('menu_controller_id')]);
        }
     
    }


    /**
     * 
     * Start: Function Crud JSON 
     * 
     */

    private function storeNewContent(Request $request,$id){

        $newJson= json_decode($request->newJsonContent,true);
        $delID=$request->deletedContent ? json_decode($request->deletedContent,true):[];

        $label_categories_fil = $this->filterLabel($newJson,'label_categories');
        
     
        foreach ($newJson as $key => $value){
            if($newJson[$key]['name']==null){
                unset($newJson[$key]);
                continue; 
            }
            unset($newJson[$key]['label_categories']);
            unset($newJson[$key]['label_del']);
            unset($newJson[$key]['id']);
        }
        
        foreach($newJson as $key => $arr){
            $newJson[$key]['project_category_id']= $id;
        }

       

        $category_content=\App\CategoryContent::insert($newJson);
        $result=\App\CategoryContent::whereIn('id',$delID)->delete();

      
        $this->storeLabel($label_categories_fil);

        
        return $result;
    }


    private function storeLabel($data){
        $labelArr=[];

       

        foreach($data as $key=>$value){
           
            foreach($data[$key]['label_id'] as $val){

                $tmp=['category_content_id'=>$data[$key]['category_content_id'],'label_id'=>$val];

                $category_content_id=$tmp['category_content_id'];
                $label_id=$tmp['label_id'];

                $check_exist=\App\CategoryLabel::where('category_content_id', '=', $category_content_id)->where('label_id', '=', $label_id)->exists();


                if (!$check_exist) {
                    array_push ($labelArr,$tmp);
                }
            }
        }


        $label=\App\CategoryLabel::insert($labelArr);

        return $label;
    }

    


    private function labelDelete($data){
        $labelToDel=[];


        foreach($data as $key=>$value){
           
            foreach($data[$key]['label_id'] as $val){

                $tmp=['category_content_id'=>$data[$key]['category_content_id'],'label_id'=>$val];

                $category_content_id=$tmp['category_content_id'];
                $label_id=$tmp['label_id'];

                $check_exist=\App\CategoryLabel::where('category_content_id', '=', $category_content_id)->where('label_id', '=', $label_id)->exists();



                if ($check_exist) {
                    array_push ($labelToDel,$tmp);
                }
            }
        }

        $ids_conten_to_delete = array_map(function($item){ return $item['category_content_id']; }, $labelToDel); 
        $ids_label_to_delete  = array_map(function($item){ return $item['label_id']; }, $labelToDel); 

        $del= \DB::table('category_labels')
        ->whereIn('category_content_id', $ids_conten_to_delete )
        ->whereIn('label_id', $ids_label_to_delete )
        ->delete();
        

        return;

      
    }

    private function filterLabel($json,$coloumn){

        $label=array_column($json,$coloumn);

        $label_fil=array_filter($label, function($k) {
                     return $k['label_id'];
                 }, ARRAY_FILTER_USE_BOTH);

        return $label_fil;
    }


    private function storeNewMenu(Request $request,$id){
        $newMenu= json_decode($request->newJsonMenu,true);
        $delID=$request->deletedJsonMenu ? json_decode($request->deletedJsonMenu,true):[];

        
        foreach($newMenu as $key => $arr){
            if(empty($arr)) {
                unset($newMenu[$key]);
            }else{
                $newMenu[$key]['project_category_id']= $id;
                unset($newMenu[$key]['project_category']);
                unset($newMenu[$key]['id']);
            }
           
        }



        $save_menu=\App\Menu::insert($newMenu);
        $result=\App\Menu::whereIn('id',$delID)->delete();

        return $result;
    }

    private function updateOldMenu(Request $request){
        $oldMenu= json_decode($request->oldJsonMenu,true);
       
        foreach ($oldMenu as $key => $value){
            if(empty($value)) {
                unset($oldMenu[$key]);
                
            }
            unset($oldMenu[$key]['project_category']);            
        }
       
        $update_menu=\Batch::update('menu', $oldMenu, 'id');


        return $update_menu;
    }


    public function add_slug(){

        
    }


    /**
     * 
     * END: Function Crud JSON 
     * 
     */

}

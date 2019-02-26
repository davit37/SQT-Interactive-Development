<?php

declare(strict_types=1);

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Request\ProjectCategoryReq as CategoryReq;

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
    
    public function index()
    {
        $projectCategories=\App\ProjectCategory::with('contents')->get();

        return view('admin.project_category.index', ['projectCategories'=>$projectCategories]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.project_category.create');
     
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $arrReq=[];
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

        $project_category =new  \App\ProjectCategory;
        $project_category->name=$request->get('category_name');
       

        if($project_category->save()){
            $json= json_decode($request->newJsonContent,true);


            // if($validateContent->fails()){
            
            //     return response()->json([
                
            //     'errors'=>$validateCategory->errors()],400);
            // }
            
            foreach($json as $key => $arr){
                $json[$key]['project_category_id']= $project_category->id;
            }


                try{
                    
                    $category_content=\App\CategoryContent::insert($json);
                    $request->session()->flash('status', 'Category was successful saved!');
                    return  response()->json([
                        'status'      => 'suksess',
                        
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
        
        $category=\App\ProjectCategory::with('contents')->findOrFail($id);

        return view('admin.project_category.edit', ['category'=>$category]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    private function storeCategory(Request $request,$id){

        $newJson= json_decode($request->newJsonContent,true);
        $delID=json_decode($request->deletedContent,true);
        
        foreach($newJson as $key => $arr){
            $newJson[$key]['project_category_id']= $id;
        }

        $category_content=\App\CategoryContent::insert($newJson);
        $result=\App\CategoryContent::whereIn('id',$delID)->delete();

        $request->session()->flash('status', 'Category was successfully updated!');
        return $result;
    }

    public function update(Request $request, $id)
    {
      

        $arrReq=[];

        /*
        *For Validate
        *
        */
        $arrCategory    = ['category_name'      =>$request->get('category_name')];
        $oldJsonContent = ['old_json_content'   =>json_decode($request->oldJsonContent,true)];
        $newJsonContent = ['new_json_content'   =>json_decode($request->newJsonContent,true)];
        $deletedContent = ['deleted_content'   =>json_decode($request->deletedContent,true)];

        $arrReq=array_merge((array)$arrCategory,(array)$oldJsonContent,(array)$newJsonContent,(array)$deletedContent );

        
        
        // $content_type = 'static_image';
        
        // $messages = [
        //     'new_json_content.*.name.required'          => 'This field name is required.',
        //     'new_json_content.*.static_images.required' => 'This field need static image'  
        // ];

        // $test =['old_json_content.*.name'          =>'required'];

        // $validateCategory=\Validator::make(
        //     $arrReq, [
        //     'category_name'                         =>'required|max:191',
        //     // 'json_content.*.sort_number'           =>'required|unique',
        //     'new_json_content.*.name'                   =>'required',
        //     'new_json_content.*.static_images'          =>'required',
        //     $test,
        //     ],
        //     $messages
        // );

        // if($validateCategory->fails()){
            
        //     return response()->json([
            
        //     'errors'=>$validateCategory->errors()],400);
        // }
        
        
        $project_category =\App\ProjectCategory::FindOrFail($id);
        $project_category->name=$request->get('category_name');
       
        
        if($project_category->save()){
           $oldJson= json_decode($request->oldJsonContent,true);

           $category_content=\Batch::update('catgory_contents', $oldJson, 'id');

           //cek if oldcontent is exist
           if(!empty($oldJson)){
                $category_content=\Batch::update('catgory_contents', $oldJson, 'id');
           }

            try{
                $this->storeCategory($request,$id);
                return  response()->json([
                    'status'      => 'suksess',
                    
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
    public function destroy($id)
    {
        $project_category = \App\ProjectCategory::findOrFail($id);
        $project_category->delete();

        return redirect()->route('project-category.index')->with('status', 'Category moved to trash');
    }
}

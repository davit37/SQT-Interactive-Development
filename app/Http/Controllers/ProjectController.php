<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects=\App\Project::with('projectCategory')->get();

        return view('admin.project.index', ['projects'=>$projects]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $projectCategories =  \App\ProjectCategory::get();
        return view('admin.project.create', ['projectCategories'=>$projectCategories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=> 'required|max:100',
            'category_id' => 'required',
        ]);

        $project = new \App\Project;
        $project->name=$request->name;
        $project->project_category_id = $request->category_id;
        $project->slug=str_slug($request->name,'-');

        $project->save();

        return redirect()
                ->route('project.index')
                ->with('status', 'Poject has been successfully saved');
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function slide($id){
        $contents=\DB::table('projects')->join('catgory_contents', 'projects.project_category_id', '=', 'catgory_contents.project_category_id')->where('projects.id', $id)->orderBy('catgory_contents.sort_number')->get();
        return view('front.slide', ['contents'=>$contents]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArticleController extends Controller
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
        $articles=\App\Article::with('users')->get();

        return view('admin.article.index', ['articles'=>$articles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.article.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        \Validator::make($request->all(), [
            "title" => "required|min:5|max:200",
            "content" => "required|min:20",
            
        ])->validate();

        $new_article= new \App\Article;

        $new_article->title=$request->get('title');
        $new_article->content=$request->get('content');
        $new_article->created_by = \Auth::user()->id;
        $new_article->status=$request->get('save_action');

        $new_article->save();

        if($request->get('save_action') == 'PUBLISH'){
            return redirect()
                ->route('article.create')
                ->with('status', 'Article successfully saved and published');
        } else {
            return redirect()
                ->route('article.create')
                ->with('status', 'Article saved as draft');
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
        $article= \App\Article::findOrFail($id);

        return view('admin.article.edit', ['article'=>$article]);

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
        \Validator::make($request->all(), [
            "title" => "required|min:5|max:200",
            "content" => "required|min:20",
            
        ])->validate();

        $article= \App\Article::findOrFail($id);

        $article->title=$request->get('title');
        $article->content=$request->get('content');
        $article->updated_by = \Auth::user()->id;
        $article->status=$request->get('save_action');

        $article->save();

        if($request->get('save_action') == 'PUBLISH'){
            return redirect()
                ->route('article.edit',['id' => $id])
                ->with('status', 'Article successfully saved and published');
        } else {
            return redirect()
                ->route('article.edit',['id' => $id])
                ->with('status', 'Article saved as draft');
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
        $book = \App\Article::findOrFail($id);
        $book->delete();

        return redirect()->route('article.index')->with('status', 'Article moved to trash');
    }
}

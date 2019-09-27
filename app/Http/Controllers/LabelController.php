<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LabelController extends Controller
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
        $labels=\App\Label::get();

        return view('admin.label.index',['labels'=>$labels]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.label.create');
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
            'color' => 'required',
        ]);

        $label = new \App\Label;
        $label->name=$request->name;
        $label->color = $request->color;

        $label->save();

        return redirect()
                ->route('label.index')
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
        $label=\App\Label::findOrFail($id);
        $label->setAttribute('input',' <input type="hidden" name="_method" id="" value="PUT">');
        return view('admin.label.create',['label'=>$label]);

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
        $request->validate([
            'name'=> 'required|max:100',
            'color' => 'required',
        ]);

        $label =\App\Label::findOrFail($id);
        $label->name=$request->name;
        $label->color = $request->color;

        $label->save();

        return redirect()
                ->route('label.index')
                ->with('status', 'Poject has been successfully saved');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $label = \App\Label::findOrFail($id);
        $label->delete();

        return redirect()->route('label.index')->with('status', 'Label was deleted');

    }
}

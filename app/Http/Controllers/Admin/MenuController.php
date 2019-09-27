<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MenuControllers as MenuControllers_Model;
use Illuminate\Support\Str;
use App\ProjectCategory;

class MenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){

        $menu = MenuControllers_Model::orderBy('order')->get();

        return view("admin/menu_controller/index",["data_menus"=>$menu]);
    }
    
    public function form(Request $req){

        $action = $req->action;


        if($action === "edit"){
            $id = $req->id;
            $menu=MenuControllers_Model::findOrFail($id);

        }elseif($action != "create"){
            abort(404);
        }

        return view("admin/menu_controller/form", ['menu'=>$menu ?? '', "action"=>$action]);
    }

    public function store(Request $req){

        // dd($req->all());

        $req->validate([
            "name" => "required",
            "icon_menu" => "required"
        ]);

        $id = $req->id;

        $menu = $req->action=="create" ? new MenuControllers_Model : MenuControllers_Model::findOrFail($id) ;
      
        if($req->action=="create" ){

            $max_order = MenuControllers_Model::max("order");
            $menu->order = $max_order+1;
        }
    
        $menu->name = $req->name;
        $menu->icon_menu = $req->icon_menu;
        $menu->slug = Str::slug($req->name,"-");
        $menu->status =$menu->status ?? "enabled";
        
        $menu->save();
        $req->session()->flash("status","alert-success");
        $req->session()->flash('message', 'Status was successful saved!');
        return redirect()->route('menu-controller.index'); 

    }

    public function change_status(Request $req){

     

        $menu= MenuControllers_Model::findOrFail($req->id);

        $menu->status=$req->status;

        $menu->save();
        $req->session()->flash("status","alert-success");
        $req->session()->flash('message', 'Status was successful changed!');
    }

    public function update_order(Request $req){
        $order_json = json_decode($req->data_row,true);

        
        
        $update_oder=\Batch::update('menu_controllers',$order_json, 'id');

        if(!$update_oder){
            abort(500);
        }else{
            $req->session()->flash("status","alert-success");
            $req->session()->flash('message', 'Order was successful changed!');
            return "true";
        }
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $req)
    {
        //check child relationsship and prevent delete if true
        $check_relationships= ProjectCategory::where("menu_controller_id",$req->id)->get();

        if(!$check_relationships->isEmpty()){
            $req->session()->flash("status","alert-danger");
            return redirect()->route('menu-controller.index')->with('message', 'Cannot delete menu has child ');
        }

        //delete menu if false
        $menu =MenuControllers_Model::findOrFail($req->id);
        $menu->delete();

        $req->session()->flash("status","alert-success");
        return back()->with('message', 'Menu moved to trash');
    }
}

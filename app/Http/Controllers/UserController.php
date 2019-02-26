<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
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
        $users = \App\User::paginate(5);

        return view('admin.users.index', ['users'=>$users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        \Validator::make($request->all(),[
            "name" => "required|min:5|max:100",
            "username" => "required|min:5|max:20",
            
            "phone" => "required|digits_between:10,13",
            "address" => "required|min:10|max:200",
            
            "email" => "required|email",
            "password" => "required",
            "password_confirmation" => "required|same:password",
            
          ])->validate();

        $new_users= new \App\User;
        $new_users->name=$request->get('name');
        $new_users->username = $request->get('username');
        $new_users->roles = 'ADMIN';
        $new_users->address = $request->get('address');
        $new_users->phone = $request->get('phone');
        $new_users->email = $request->get('email');
        $new_users->password = \Hash::make($request->get('password'));
        if($request->file('avatar')){
            $file = $request->file('avatar')->store('avatars', 'public');
        
            $new_users->avatar = $file;
        }
        $new_users->save();
        return redirect()->route('users.create')->with('status','User Secessfully Created');
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
        $user= \App\User::findOrFail($id);

        return view('admin.users.edit', ['users'=>$user]);

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
            "name" => "required|min:5|max:100",
            
            "phone" => "required|digits_between:10,12",
            "address" => "required|min:10|max:200",
        ])->validate();
    
        $user = \App\User::findOrFail($id);

        $user->name = $request->get('name');
        
        $user->address = $request->get('address');
        $user->phone = $request->get('phone');
       
        if($request->file('avatar')){
            if($user->avatar && file_exists(storage_path('app/public/' . $user->avatar))){
                \Storage::delete('public/'.$user->avatar);
            }
            $file = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $file;
        }

        $user->save();

        return redirect()->route('users.edit', ['id' => $id])->with('status', 'User succesfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = \App\User::findOrFail($id);

        $user->delete();

        return redirect()->route('users.index')->with('status', 'User successfully delete');
    }
}

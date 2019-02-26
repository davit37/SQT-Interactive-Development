@extends('layouts.admin.global')

@section('title')index @endsection

@section('content')
<div class="row">
<div class="col-md-6">
                <div class="card">
                 
                  <div class="card-body">

                  @if(session('status'))
                  <div class="alert alert-primary dismissible fade show" role="alert">{{session('status')}}</div>
                  @endif 

                  @if ($errors->any())
                      <div class="alert alert-danger dismissible fade show">
                          <ul>
                              @foreach ($errors->all() as $error)
                                  <li>{{ $error }}</li>
                              @endforeach
                          </ul>
                      </div>
                  @endif
                    <form class="form-horizontal" action="{{route('users.update', ['id' => $users->id])}}" method="POST" enctype="multipart/form-data" autocomplete="off">
                    
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="PUT">
                      
                      <div class="form-group ">
                        <label class="" for="text-input">Name</label>
                       
                          <input class="form-control {{ $errors->has('name') ? ' has-error' : '' }}"  type="text" name="name" placeholder='Full Name' value="{{old('name') ? old('name') : $users->name}}">
                          
                        
                      </div>
                      <div class="form-group ">
                        <label class="" >Username</label>
                        
                          <input class="form-control" type="text" name="username" placeholder='username' autocomplete="off" value="{{old('username') ? old('username') : $users->username}}">
                          
                        
                      </div>

                      <div class="form-group ">
                        <label class="" for="email-input">Email Input</label>
                        
                          <input value="{{old('email') ? old('email') : $users->email}}" class="form-control"  type="email" name="email" placeholder='user@email.com' >
                        
                      </div>

                      
                      <div class="form-group ">
                        <label class="" for="disabled-input">Phone Number</label>
                       
                          <input class="form-control" id="disabled-input" type="text" name="phone" placeholder='phone number' value="{{old('phone') ? old('phone') : $users->phone}}">
                        
                      </div>

                      <div class="form-group ">
                        <label class="" for="textarea-input">Address</label>
                       
                          <textarea class="form-control" id="textarea-input" name="address" rows="2" placeholder="Address">"{{old('addess') ? old('address') : $users->address}}"</textarea>
                       
                      </div>
                      
                     
                      
                      <div class="form-group ">
                        <label class="" for="file-input">avatar</label>
                        
                        @if($users->avatar)
                        <small class="text-muted">Current Avatar</small><br>
                        <img src="{{asset('storage/' . $users->avatar)}}" width="96px"/>
                        @endif
                          <input id="file-input" type="file" class="form-control"  name="avatar">
                          <small class="text-muted">bisa di kosongkan</small>
                       
                       
                      </div>
                     
                   
                  </div>
                  <div class="card-footer">
                    <button class="btn btn-sm btn-primary" type="submit">
                      <i class="fa fa-dot-circle-o"></i> Submit</button>
                    <button class="btn btn-sm btn-danger" type="reset">
                      <i class="fa fa-ban"></i> Reset</button>
                  </div>
                  </form>
                </div>
                
              </div>
</div>
@endsection
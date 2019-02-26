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
                    <form class="form-horizontal" action="{{route('users.store')}}" method="POST" enctype="multipart/form-data" autocomplete="off">
                    
                    {{ csrf_field() }}
                      
                      <div class="form-group ">
                        <label class="" for="text-input">Name</label>
                       
                          <input class="form-control {{ $errors->has('name') ? ' has-error' : '' }}"  type="text" name="name" placeholder='Full Name' value="{{old('name')}}">
                          
                        
                      </div>
                      <div class="form-group ">
                        <label class="" >Username</label>
                        
                          <input class="form-control" type="text" name="username" placeholder='username' autocomplete="off" value="{{old('username')}}">
                          
                        
                      </div>

                      <div class="form-group ">
                        <label class="" for="email-input">Email Input</label>
                        
                          <input class="form-control"  type="email" name="email" placeholder='user@email.com'value="{{old('email')}}" >
                        
                      </div>

                      <div class="form-group ">
                        <label class="" for="password-input">Password</label>
                        
                          <input class="form-control" id="password-input" type="password" name="password" placeholder='password'>
                          
                        
                      </div>

                      <div class="form-group ">
                        <label class="" for="password-input">Password Confirmation</label>
                      
                          <input class="form-control" id="password-input" type="password" name="password_confirmation" placeholder='password confirmation'>
                          
                       
                      </div>
                      
                      <div class="form-group ">
                        <label class="" for="disabled-input">Phone Number</label>
                       
                          <input class="form-control" id="disabled-input" type="text" name="phone" placeholder='phone number' value="{{old('phone')}}">
                        
                      </div>

                      <div class="form-group ">
                        <label class="" for="textarea-input">Address</label>
                       
                          <textarea class="form-control" id="textarea-input" name="address" rows="2" placeholder="Address">{{old('address')}}</textarea>
                       
                      </div>
                      
                     
                      
                      <div class="form-group ">
                        <label class="" for="file-input">avatar</label>
                    
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
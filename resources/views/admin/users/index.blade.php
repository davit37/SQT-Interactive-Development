@extends('layouts.admin.global')

@section('title')User @endsection

@section('header-scripts')
<link rel="stylesheet" href="{{asset('coreui/node_modules/datatables/dataTables.bootstrap4.css')}}">
@endsection

@section('content')


<div class="card">
    <div class="card-header">
            <div class="col-md-12 ">
            <a class="btn btn-info text-white"  href="{{route('users.create')}}">Add New</a>
            </div>
    </div>
    <div class="card-body">
    @if(session('status'))
    <div class="alert alert-success">
      {{session('status')}}
    </div>
  @endif 
  <br>
    <table class="table table-striped table-bordered datatable">

      <thead>
        <tr>
          <th><b>Name</b></th>
          <th><b>Username</b></th>
          <th><b>Email</b></th>
          <th><b>Avatar</b></th>
          <th><b>Status</b></th>
          <th><b>Action</b></th>
        </tr>
      </thead>
      <tbody>


        @foreach($users as $user)
          <tr>
            <td>{{$user->name}}</td>
            <td>{{$user->username}}</td>
            <td>{{$user->email}}</td>
            <td>
              @if($user->avatar)
                <img src="{{asset('storage/'.$user->avatar)}}" width="70px"/> 
              @else 
                N/A
              @endif

            </td>
            <td>
              @if($user->status == "ACTIVE")
              <span class="badge badge-success">
                  {{$user->status}}
              </span>
              @else 
              <span class="badge badge-danger">
                  {{$user->status}}
              </span>
              @endif
          </td>
            <td><a   href="{{route('users.show', ['id' => $user->id])}}"   class="btn btn-primary btn-sm">Detail</a>

              <a class="btn btn-info text-white btn-sm" role='button' href="{{route('users.edit', ['id'=>$user->id])}}">Edit</a>
              <form 
                onsubmit="return confirm('Delete this user permanently?')" 
                class="d-inline" 
                action="{{route('users.destroy', ['id' => $user->id ])}}" 
                method="POST">

                {{ csrf_field() }}

                  <input 
                    type="hidden" 
                    name="_method" 
                    value="DELETE">

                  <input 
                    type="submit" 
                    value="Delete" 
                    class="btn btn-danger btn-sm">
              </form>
            </td>
          </tr>
        @endforeach 
      </tbody>
      <tfoot>
       
    </tfoot>
    </table>
    </div>

</div>




@endsection

@section('footer-scripts')
<script src="{{asset('coreui/node_modules/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('coreui/node_modules/datatables/dataTables.bootstrap4.js')}}"></script>
<script src="{{asset('coreui/node_modules/datatables/datatables.js')}}"></script>

@endsection
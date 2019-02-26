@extends('layouts.admin.global')

@section('title')User @endsection

@section('header-scripts')
<link rel="stylesheet" href="{{asset('coreui/node_modules/datatables/dataTables.bootstrap4.css')}}">
@endsection

@section('content')



<div class="card">
    <div class="card-header">
            <div class="col-md-12 ">
            <a class="btn btn-info text-white"  href="{{route('project-category.create')}}">Add New</a>
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
          <th><b>Title</b></th>

          <th><b>Created At</b></th>
          <th><b>Action</b></th>
        </tr>
      </thead>
      <tbody>




        @foreach($projectCategories as $category)
          <tr>
            <td>{{ $category->name}}</td>
           
            <td>{{date('d M Y',strtotime ($category->created_at))}}
           
         
            <td>

              <a class="btn btn-info text-white btn-sm" role='button' href="{{route('project-category.edit', ['id'=> $category->id])}}">Edit</a>
              <form
                    method="POST"
                    class="d-inline"
                    onsubmit="return confirm('Move article to trash?')"
                    action="{{route('project-category.destroy', ['id' => $category->id ])}}"
                  >

                  @csrf 
                  <input 
                    type="hidden" 
                    value="DELETE"
                    name="_method">

                  <input 
                    type="submit" 
                    value="Trash" 
                    class="btn btn-danger btn-sm">

                  </form>
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
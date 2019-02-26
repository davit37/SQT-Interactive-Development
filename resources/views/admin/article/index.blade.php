@extends('layouts.admin.global')

@section('title')User @endsection

@section('header-scripts')
<link rel="stylesheet" href="{{asset('coreui/node_modules/datatables/dataTables.bootstrap4.css')}}">
@endsection

@section('content')



<div class="card">
    <div class="card-header">
            <div class="col-md-12 ">
            <a class="btn btn-info text-white"  href="{{route('article.create')}}">Add New</a>
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
          <th><b>Author</b></th>
          <th><b>Date</b></th>
          <th><b>Status</b></th>
          <th><b>Action</b></th>
        </tr>
      </thead>
      <tbody>




        @foreach($articles as $article)
          <tr>
            <td>{{$article->title}}</td>
            <td>{{$article->users->name}}</td>
            <td>{{date('d M Y',strtotime($article->created_at))}}
           
            <td>
              @if($article->status == "PUBLISH")
              <span class="badge badge-success">
                  {{$article->status}}
              </span>
              @else 
              <span class="badge badge-danger">
                  {{$article->status}}
              </span>
              @endif
          </td>
            <td>

              <a class="btn btn-info text-white btn-sm" role='button' href="{{route('article.edit', ['id'=>$article->id])}}">Edit</a>
              <form
                    method="POST"
                    class="d-inline"
                    onsubmit="return confirm('Move article to trash?')"
                    action="{{route('article.destroy', ['id' => $article->id ])}}"
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
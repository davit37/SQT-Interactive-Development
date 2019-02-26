@extends('layouts.admin.global')

@section('title')index @endsection



@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <strong>Project</strong> Form</div>
            <div class="card-body">
                <form class="form-horizontal" action="{{route('project.store')}}" method="post">

                  @csrf

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="hf-email">Name</label>
                        <div class="col-md-9">
                            <input required class="form-control {{$errors->first('name') ? "is-invalid" : ""}}" id="hf-email" type="text" name="name" placeholder="Enter Project Name"
                                >
                                <div class="invalid-feedback">
                                 {{$errors->first('name')}}
                               </div>

                        </div>
                    </div>

                    <div class="form-group row">
                     <label class="col-md-3 col-form-label" for="hf-email">Category</label>
                     <div class="col-md-9">
                        <select name="category_id" class='form-control form-control-sm' id="" required>
                           @foreach ($projectCategories as $category)

                              <option value="{{ $category->id }}">{{$category->name}}</option>
                               
                           @endforeach
                        </select>

                     </div>
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

@section('footer-scripts')


@endsection

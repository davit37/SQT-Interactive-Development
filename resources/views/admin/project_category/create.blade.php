@extends('layouts.admin.global')

@section('title')index @endsection



@section('content')

{{-- <div class="d-flex justify-content-center">

    <div class="spinner" role="status"></div>
</div> --}}

<div class="card">
    <div class="card-header">
   
    </div>
    <div class="card-body">
        @if(session('status'))
        <div class="alert alert-primary dismissible fade show" role="alert">{{session('status')}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>

        </div>
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
        <form action="{{route('article.store')}}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group ">
                <label for="formGroupExampleInput">Name</label>
                <input class="form-control col-md-4" type="text" name="title" id='category-name' data-id='' placeholder='Enter Category Name'
                    autocomplete="off" value="{{old('title')}}">
                <em id="categoryname-error" class="error invalid-feedback"></em>
            </div>


            <div class="row">
                <div class="col-md-6">
                    <div class="form-group ">
                        <h3>Content</h3>
                        <button class="btn btn-sm btn-primary" type="button" id='add-new'>
                            <i class="fa fa-plus"></i> Add New</button>
                        <br>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-5">
                    <div id='template-content'>
                    </div>
                </div>

                <div class="col-md-7" id='slide-content' style='display:none'>

                    <div class="col-md-12">
                        <div class="card accordion" id="accordionExample">
                            <div class='card-body'>
                                <div class="form-group ">
                                    <label for="formGroupExampleInput">Name</label>
                                    <input class="form-control col-md-7" type="text" id='slider_name' slider-id=''
                                        placeholder='Enter Slider Name' autocomplete="off" value="{{old('title')}}">
                                </div>
                                <div class="form-group">
                                    
                                    <label for="formGroupExampleInput">Id</label>
                                    <input class="form-control col-md-7" type="text" id='slider_flag' slider-id=''
                                        placeholder='Enter Slider Name' autocomplete="off" value="{{old('')}}">

                                </div>
                                <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="slide-type">
                                            <label class="form-check-label" for="gridCheck">
                                                    Default navigation (prev and next button)
                                            </label>
                                        </div>
                                        </div>
                            </div>
                            <div id="headingOne" class='header-collapse'>
                                <h2 class="mb-0">
                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne"
                                        aria-expanded="false" aria-controls="collapseOne">
                                        Static Image
                                    </button>
                                    <input id="check_image" type="checkbox" checked>
                                </h2>
                            </div>

                            <div id="collapseOne" class="collapse " aria-labelledby="headingOne" data-parent="#accordionExample">
                                <div class="card-body">
                                    <div class="custom-file">
                                        <input class="custom-file-input" id="static_image" type="text" data-input="lbl"
                                            data-preview="holder">
                                        <label class="custom-file-label" for="customFile" id='lbl' name="filepath">Choose
                                            file</label>

                                    </div>
                                    <img id="holder" style="margin-top:15px;max-height:100px;" alt=''>
                                </div>
                            </div>

                            <div id="headingTwo">
                                <h2 class="mb-0">
                                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                        data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Custom Html
                                    </button>
                                    <input id="check_html" type="checkbox" name="agree" value="agree">
                                </h2>
                            </div>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                <div class="card-body">
                                    <div class="form-group ">

                                        <textarea class="mytextarea" name='content' slider-id='' id='custom_html'></textarea>

                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

    </div>

    <div class="card-footer">
        <button class="btn btn-sm btn-primary" id='btn-save' type="button" name="save_action" value="PUBLISH">
            <i class="fa fa-dot-circle-o"></i> Publihsed</button>
        <button class="btn btn-sm btn-danger" type="submit" name="save_action" value="DRAFT">
            <i class="fa fa-ban"></i> Draft</button>
    </div>

    </form>
</div>

@endsection

@section('footer-scripts')

<script src = 'https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=fv5ovfbkqfj78ddaux9dymkik5roh7f0qnvo5ggcv8svh53e' > </script>

<script src="{{asset('coreui/node_modules/jquery-ui/jquery-ui.min.js')}}"></script>
<script src="{{asset('vendor/laravel-filemanager/js/lfm.js')}}"></script>


<script>
    var laravel_csrf = "{{ csrf_token() }}";
    var projectCategoryForm = true;
    var jsonContent = {};
    var sortNumber = 0;

</script>
@endsection

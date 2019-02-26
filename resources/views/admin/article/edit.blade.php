@extends('layouts.admin.global')

@section('title') index @endsection



@section('content') <
    div class = "card" >
    <
    div class = "card-header" >
    <
    h3 > Add New Post < /h3> <
    /div> <
    div class = "card-body" >
    @if(session('status')) <
    div class = "alert alert-primary dismissible fade show"
role = "alert" > {
    {
        session('status')
    }
} < /div>
@endif

@if($errors - > any()) <
    div class = "alert alert-danger dismissible fade show" >
    <
    ul >
    @foreach($errors - > all() as $error) <
    li > {
        {
            $error
        }
    } < /li>
@endforeach
    <
    /ul> <
    /div>
@endif
    <
    form action = "{{route('article.update', ['id'=>$article->id])}}"
method = "POST"
enctype = "multipart/form-data" >
    @csrf <
    input type = "hidden"
name = "_method"
value = "PUT" >
    <
    div class = "form-group " >
    <
    input class = "form-control"
type = "text"
name = "title"
placeholder = 'Enter title here'
autocomplete = "off"
value = "{{old('title') ? old('title') : $article->title}}" >
    <
    /div> <
    div class = "form-group " >
    <
    label class = "" > < /label> <
    textarea id = "mytextarea"
name = 'content' > {
        {
            old('content') ? old('content') : $article - > content
        }
    } < /textarea> <
    /div>

    <
    /div>

    <
    div class = "card-footer" >
    <
    button class = "btn btn-sm btn-primary"
type = "submit"
name = "save_action"
value = "PUBLISH" >
    <
    i class = "fa fa-dot-circle-o" > < /i> publihsed</button >
    <
    button class = "btn btn-sm btn-danger"
type = "submit"
name = "save_action"
value = "DRAFT" >
    <
    i class = "fa fa-ban" > < /i> Draft</button >
    <
    /div>

    <
    /form> <
    /div>

@endsection

@section('footer-scripts') 
<script src = 'https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=fv5ovfbkqfj78ddaux9dymkik5roh7f0qnvo5ggcv8svh53e' > </script>

    <script >
    var editor_config = {
        path_absolute: "/e-docs/public/",
        selector: "#mytextarea",
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons template paste textcolor colorpicker textpattern"
        ],
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link  unlink | image media | currentdate",
        relative_urls: false,

        height: 300,
        ,
        content_css: [
            '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
            '/<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">'
        ],
        
        setup: function (editor) {

            function toTimeHtml(date) {
                return '<button class="btn btn-lg btn-primary" type="button">Meeting Reguler</button>';
            }

            function insertDate() {
                var html = toTimeHtml(new Date());
                editor.insertContent(html);
            }

            editor.ui.registry.addButton('currentdate', {
                icon: 'insertdatetime',
                tooltip: "Insert Current Date",
                onAction: insertDate
            });
        },
        file_browser_callback: function (field_name, url, type, win) {
            var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
            var y = window.innerHeight || document.documentElement.clientHeight || document.getElementsByTagName('body')[0].clientHeight;

            var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
            if (type == 'image') {
                cmsURL = cmsURL + "&type=Images";
            } else {
                cmsURL = cmsURL + "&type=Files";
            }

            tinyMCE.activeEditor.windowManager.open({
                file: cmsURL,
                title: 'Filemanager',
                width: x * 0.8,
                height: y * 0.8,
                resizable: "yes",
                close_previous: "no"
            });
        }
    });
};





tinymce.init(editor_config); <
/script>
@endsection

/* 
 * initProjectCategoryForm()
 * ! info project-category create / edit
 */
'use strict'


var jsonContent = {};
var formData = null;
var file = null;
var sortNumber = 0;
var sortId = 0;
var errorMsg=null;

 
    //DOM object
    var btnSlide = '.btn-slide';
    var categoryName = '#category-name';
    var sliderName = '#slider_name';
    var customHtml = '#custom_html';
    var staticImage = '#static_image';
    var checkHtml = "#check_html";
    var checkImage = "#check_image";
    var btnDeleteSlide = '.btn-del-slide';
    var btnSave = "#btn-save";
    var sliderIndex='slider-index';
    var slideContent ='#slide-content'

function initProjectCategoryForm() {  


    // console.log(typeof (projectCategoryForm))

    // console.log(typeof (projectCategoryForm) === 'undefined')

    if (typeof (projectCategoryForm) === 'undefined') {
        // console.log('stop initProjectCategoryForm()');
        return;
    }

    formData = new FormData();


    //TinyMce
    var editor_config = {
        path_absolute: "/e-docs/public/",
        selector: ".mytextarea",
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons template paste textcolor colorpicker textpattern"
        ],
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link  unlink | image media",
        relative_urls: false,

        height: 200,
        file_browser_callback: function (field_name, url, type, win) {
            var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName(
                'body')[0].clientWidth;
            var y = window.innerHeight || document.documentElement.clientHeight || document.getElementsByTagName(
                'body')[0].clientHeight;

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
        },

        init_instance_callback: function (editor) {
            editor.on('keyup', function (e) {

                jsonContent[$(slideContent).attr(sliderIndex)].custom_html = tinyMCE.activeEditor.getContent({
                    format: 'raw'
                });

            });
        },


    };


    //Save New Project
    function new_project() {
        console.log($(categoryName).attr('data-id') != '')
        if ($(categoryName).attr('data-id') != '') {
            console.log('not Empty')
            return
        }
        var category_name = $(categoryName).val();

        $.ajax({
            method: 'POST',
            url: "{{route('project-category.store')}}",
            data: {
                "_token": "{{ csrf_token() }}",
                name: category_name
            }

        }).done(function (res) {
            $(categoryName).attr('data-id', res)
            console.log(res)
        })
    }

    //upload foto 
    // function readURL(input) {


    //     if (input.files && input.files[0]) {
    //         let reader = new FileReader();

    //         console.log(input.files[0].name)
    //         $('#lbl').html(input.files[0].name)

    //         file = input.files[0];

    //         formData.append('file', file);

    //         reader.readAsDataURL(input.files[0]);

    //     } else {
    //         console.log('gagal')

    //     }
    // }

    


    $('#add-new').on('click', function () {
       
    
       jsonContent[sortNumber]={
            
            sort_number          : sortNumber+1,
            custom_html          : "",
            static_images        : '',
            name                 : "",
            selected_content     : 'static_image',
            project_category_id  : ''
        };

        $('#template-content').append(
            `
               <div class="form-group slide-temp" id='slide-${sortNumber}'>

                  <div class='form-inline'>
                    
                     <div class="input-group col-inline col-md-8">
                        
                           <div class="input-group-prepend">
                              <div class="input-group-text rm-radius"><i class="fa fa-file-image-o icon-slide" aria-hidden="true"></i> </div>
                           </div>
                           <input type="text" class="form-control slide-name rm-radius" placeholder='Slider' readonly slide-id='${sortNumber}'>

                     </div>
                     <button type="button" class="btn btn-primary my-1 rm-radius btn-slide" slide-id='${sortNumber}'><i class="fa fa-search" ></i> </button>
                     <button type="button" class="btn btn-danger my-1 rm-radius btn-del-slide" slide-id='${sortNumber}'><i class="fa fa-times" ></i> </button>

                  </div> 
               </div>

      `
        );

        sortNumber++;
    })

    //trigger form add content slide
    $(document).on('click', btnSlide, function () {

        // console.log('index ke '+$(this).attr('slider-id') )
        // console.log('click')

        $(slideContent).fadeIn("slow")

        // $(this).prop('disabled',true)

        $(slideContent).attr(sliderIndex, $(this).attr('slide-id'))
       


        $(sliderName).val(jsonContent[$(this).attr('slide-id')].name)

        // console.log(jsonContent[$(btnSlide).index(this)].static_images);
        
        $('#lbl').html(jsonContent[$(this).attr('slide-id')].static_images)
        $('#holder').attr('src', base_url+jsonContent[$(this).attr('slide-id')].static_images)

        
        if (jsonContent[$(this).attr('slide-id')].selected_content === 'static_image') {
            $(checkHtml).prop("checked", false);
            $(checkImage).prop("checked", true);
        } else if (jsonContent[$(this).attr('slide-id')].selected_content === 'custom_html') {
            $(checkHtml).prop("checked", true);
            $(checkImage).prop("checked", false);
        }


        tinymce.init(editor_config);
        tinymce.activeEditor.setContent(`${jsonContent[$(btnSlide).index(this)].custom_html}`);
    })


    //trigger delete slide item
    $(document).on('click', btnDeleteSlide, function () {

        let indexSlide = $(btnDeleteSlide).index(this);


        Swal.fire({
            title: 'Are you sure?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            width: '25rem',
        }).then((result) => {
            if (result.value) {

                delete jsonContent[$(this).attr('slide-id')]

                $('.slide-temp').eq($(btnDeleteSlide).index(this)).remove();
                Swal.fire(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                )

                for(var prop in jsonContent){
                    
                    jsonContent[prop].sort_number=$('#slide-'+prop).index()+1
                }
            }
        })
    })

    

    //   $(document).on('click','#save_action', function () {

    //       formData.append('name', $('#slider-name').val());
    //       formData.append('project_category_id', $(categoryName).attr('data-id'));
    //       formData.append('name', $('#slider-name').val());

    //   })

    //sorting slide
    $("#template-content").sortable({

        tolerance: 'pointer',
        revert: 'invalid',
        placeholder: 'form-inline col-md-10 place-temp',
        forceHelperSize: true,

        stop: function( event, ui ) {
            for(var prop in jsonContent){
                jsonContent[prop].sort_number=$('#slide-'+prop).index()+1
            }
        }


    });


    //store slider data to jsonContent

    
   

    //add reactiv form and store data to jsonContent

    $('#category-name').on('keyup',function(){
        if($(this).hasClass('is-invalid')){
            $(this).removeClass('is-invalid')
        }
    })

    //image
    $(staticImage).filemanager('image');

    $(document).on('change', '#lbl', function () {
        jsonContent[$(slideContent).attr(sliderIndex)].static_images = $('#lbl').html()
    });

    /* ! slider name */
    $(document).on('change', sliderName, function () {
        jsonContent[$(slideContent).attr(sliderIndex)].name = $(this).val()

    })
    $(document).on('keyup', sliderName, function () {
        $(".slide-name[slide-id='"+$(slideContent).attr(sliderIndex)+"']").val($(this).val());
    })


    //checkbox content type

    $(checkHtml).change(function () {
        if(!$(this).prop('checked')){
           
            $(checkHtml).prop("checked", true);
      
        }
        $('.icon-slide').eq($(slideContent).attr(sliderIndex)).removeClass('fa-file-image-o').addClass('fa-code')
        $(checkImage).prop("checked", false);
        
        jsonContent[$(slideContent).attr(sliderIndex)].selected_content = 'custom_html'
        
    })

    $(checkImage).change(function () {

        if(!$(this).prop('checked')){
           
            $(checkImage).prop("checked", true);
      
        }
        $('.icon-slide').eq($(slideContent).attr(sliderIndex)).removeClass(' fa-code ').addClass('fa-file-image-o')
        $(checkHtml).prop("checked", false);
        jsonContent[$(slideContent).attr(sliderIndex)].selected_content = 'static_image'
    })

    //save to databse
    $(btnSave).click(function () {
        formData.append('category_name', $('#category-name').val());
        formData.append('jsonContent', JSON.stringify(jsonContent));
        formData.append('_token', laravel_csrf)

        $.ajax({
            method: 'POST',
            contentType: "application/json",
            dataType: 'JSON',
            url: base_url + '/project-category/store',
            data: formData,
            async: false,
            processData: false,
            contentType: false,
            cache: false,
        }).done(function(res){
            console.log(res);

        }).fail(function(res){
            errorMsg=res.responseJSON.errors;
            console.log(res.responseJSON.errors);
            $('#category-name').addClass('is-invalid');
            $('#categoryname-error').html(res.responseJSON.errors)
        })

    })


}

//run function
$(document).ready(function () {

    initProjectCategoryForm();

})

/* 
 * initProjectCategoryForm()
 * ! info project-category create / edit
 */
'use strict'

var url = window.location;
var regExUrl = /create/; //cek url edit or create
var formData = null;
var file = null;
var sortId = 0;
var errorMsg = null;
var newJsonContent = {};
var jsonContent = {}; //sebagai template jika data oldJSon Undifined
var deletedContent = [];

/*untuk cek oldJson dan ubah reverensi jsonContent , 
 *jika oldjson undefined jsonContent akan berevenresi  ke newJson
 *
 */
function cekOldJson(id) {
    let oldJsonCek = typeof oldJsonContent === 'undefined' ? 'undefined' : typeof (oldJsonContent[id])
    console.log(oldJsonCek)
    console.log(oldJsonCek == 'undefined')

    if (oldJsonCek === 'undefined') {
        jsonContent = newJsonContent
        console.log('masuk new json')
        console.log(newJsonContent)

        return false;
    } else {
        console.log('masuk old json')
        console.log(oldJsonContent)
        jsonContent = oldJsonContent

        return true;
    }
}

function initProjectCategoryForm() {

    // console.log(typeof (projectCategoryForm))

    // console.log(typeof (projectCategoryForm) === 'undefined')

    if (typeof (projectCategoryForm) === 'undefined') {
        // console.log('stop initProjectCategoryForm()');
        return;
    }

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
    var sliderIndex = 'slider-index';
    var slideContent = '#slide-content';
    var checkType = '#slide-type'
    var sliderFlag = '#slider_flag';

    /*
     *memasukan data json ke dalam form kontent
     *
     */
    function setFormContent(id) {
        cekOldJson(id);

        $(slideContent).fadeIn("slow")

        // $(this).prop('disabled',true)

        $(slideContent).attr(sliderIndex, id)

        $(sliderName).val(jsonContent[id].name)
        $(sliderFlag).val(jsonContent[id].slide_flag)
        $(sliderName).focus();

        // console.log(jsonContent[$(btnSlide).index(this)].static_images);

        $('#lbl').html(jsonContent[id].static_images)
        $('#holder').attr('src', base_url + jsonContent[id].static_images)

        if (jsonContent[id].type === 'goto') {
            $(checkType).prop("checked", false);
        } else if (jsonContent[id].type !== 'goto') {

            $(checkType).prop("checked", true);
        }

        if (jsonContent[id].selected_content === 'static_image') {
            $(checkHtml).prop("checked", false);
            $(checkImage).prop("checked", true);
            $('#collapseOne').addClass('show')
            $('#collapseTwo').removeClass('show')

        } else if (jsonContent[id].selected_content === 'custom_html') {
            $(checkHtml).prop("checked", true);
            $(checkImage).prop("checked", false);
            $('#collapseTwo').addClass('show')
            $('#collapseOne').removeClass('show')
        }

        tinymce.init(editor_config);
        $('#custom_html').html(`${jsonContent[id].custom_html}`)
        tinymce.activeEditor.setContent(`${jsonContent[id].custom_html}`);
    }

    formData = new FormData();

    //TinyMce
    var editor_config = {
        path_absolute: "/e-docs/public/",
        selector: ".mytextarea",
        menubar: 'file edit insert format table tools help',
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons template paste textcolor colorpicker textpattern"
        ],
        toolbar: "template | undo redo | addbtn | cryaddbtn | tempalteOp | tempalteStatic | forecolor backcolor | styleselect | bold italic fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist | addlink  | image ",
        relative_urls: false,
        textcolor_rows: "4",
        fontsize_formats: "8pt 10pt 12pt 14pt 18pt 24pt 36pt",
        image_class_list: [
            {title: 'responsive', value: 'img-fluid'},
            
        ],

        height: 600,
      
        min_width: 320,

        content_css: [
            'https://fonts.googleapis.com/css?family=Muli',
            'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css',
            `${base_url}/css/tinymce.css`
        ],

        templates: [
            {title: 'Some title 1', description: 'Some desc 1', content: 'My content'},
            {title: 'Some title 2', description: 'Some desc 2', url: 'development.html'}
          ],

        
        setup: function (editor) {

           

            function getContent(){
                return tinymce.activeEditor.selection.getContent()
            }

            async function addButton() {
                const {value: text} = await Swal.fire({
                    input: 'text',
                    inputPlaceholder: 'Type Slide ID here...',
                    showCancelButton: true
                    
                  })
                 
                if(text){

                    console.log(text)
                    var html = `<button class="btn btn-lg btn-primary jump-to" data-target="#carouselSlide" data-id='${text}' type="button"> ${getContent()}</button>`;
                    editor.insertContent(html);
                }
            }
            editor.addButton('addbtn', {
                text: 'button',
                tooltip: "Add New Button",
                onclick: addButton
            });

            async function addLink() {
                const {value: text} = await Swal.fire({
                    input: 'text',
                    inputPlaceholder: 'Type Slide ID here...',
                    showCancelButton: true
                    
                  })
                 
                if(text){
                   
                    var html = `<a href='' class="jump-to" data-target="#carouselSlide" data-id='${text}' > ${getContent()}</a>`;
                    editor.insertContent(html);
                }
            }
            editor.addButton('addlink', {
                icon: 'link',
                tooltip: "Add Id Slide",
                onclick: addLink
            });
           
            async function addroundButton() {

                const {value: text} = await Swal.fire({
                    input: 'text',
                    inputPlaceholder: 'Type Slide ID here...',
                    
                  })
                 
                if(text){
                    var html = `<button class="btn btn-lg btn-primary jump-to rounded-circle" data-target="#carouselSlide" data-id='${text}' type="button"> ${getContent()}</button>`;
                    editor.insertContent(html);
                }
            }

            editor.addButton('cryaddbtn', {
                text: 'round btn',
                tooltip: "Add New Button",
                onclick: addroundButton
            });


            editor.addButton('tempalteOp', {
                type: 'listbox',
                text: 'template',
                icon: false,
                onselect: function (e) {
                    Swal.fire({
                     
                        text: "Your content will be replaced with new content!",
                        type: 'warning',
                        width:'20em',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes'
                      }).then((result) => {
                        if (result.value) {
                            editor.setContent(this.value());
                        }
                      })
                  
                },
                values: [
                  { text: 'Template A1', value: `<p>&nbsp;</p>
                            <h3 style="text-align: center;">&nbsp;</h3>
                            <h3 style="text-align: center;">&nbsp;</h3>
                            <p><img style="display: block; margin-left: auto; margin-right: auto;" src="/e-docs/public/photos/1/Slide--1.gif" alt="" width="330" height="168" /></p>
                            <h2 style="text-align: center;">Hello,</h2>
                            <p style="text-align: center;">Lorem ipsum dolor sit amet</p>
                            <p style="text-align: center;"><button class="btn btn-lg btn-primary jump-to" type="button" data-target="#carouselSlide" data-id="11">&nbsp; Lorem ipsum </button></p>` },

                  { text: 'Template A2', value: `
                            <h3 style="text-align: center;">&nbsp;</h3>
                            <h3 style="text-align: center;">&nbsp;</h3>
                            <h3 style="text-align: center;">Lorem ipsum dolor sit amet ?</h3>
                            <p>&nbsp;</p>
                            <p style="text-align: center;"><button class="btn btn-lg btn-primary jump-to" type="button" data-target="#carouselSlide" data-id="12"> Lorem ipsum</button></p>
                            <p style="text-align: center;"><button class="btn btn-lg btn-primary  jump-to" type="button" data-target="#carouselSlide" data-id="13">Lorem ipsum</button></p>
                            ` },

                    { text: 'Template A3', value: `
                            <h3 style="text-align: center;">&nbsp;</h3>
                            <h3 style="text-align: center;">&nbsp;</h3>
                            <h3 style="text-align: center;">Selamat!</h3>
                            <p>&nbsp;</p>
                            <h4 style="text-align: center;">Lorem ipsum dolor sit amet ? </h4>
                            <p>&nbsp;</p>
                            <p style="text-align: center;"><a class="jump-to" data-target="#carouselSlide" data-id="10"><img style="font-size: 1rem; margin-left: auto; margin-right: auto;" src="/e-docs/public/photos/1/go-home.png" alt="" width="107" height="36" /></a> <img style="font-size: 1rem; margin-left: auto; margin-right: auto;" src="/e-docs/public/photos/1/Asking.png" alt="" width="115" height="36" /></p>` },

                    
                    { text: 'Template B', value: `
                            <h3 style="text-align: center;">&nbsp;</h3>
                            <h3 style="text-align: center;">&nbsp;</h3>
                            <h3 style="text-align: center;">Lorem ipsum dolor sit amet ? </h3>
                            <p>&nbsp;</p>
                            <p style="text-align: center;"><button class="btn btn-lg btn-primary rounded-circle jump-to" type="button" data-target="#carouselSlide" data-id="13"> YA</button> Atau <button class="btn btn-lg btn-primary rounded-circle  jump-to" type="button" data-target="#carouselSlide" data-id="34"> TIDAK</button></p>` },

                    { text: 'Template img', value: `
                            <h3 style="text-align: center;">&nbsp;</h3>
                            <h3 style="text-align: center;">&nbsp;</h3>
                            <p style="text-align: center;">Lorem ipsum dolor sit amet, harum vidisse ut per ?</p>
                            <p style="text-align: center;">&nbsp;</p>
                            <p style="text-align: center;"> <img src="/e-docs/public/photos/1/Screenshot_11.png" alt="" width="120" height="120" />&nbsp;&nbsp;  <img src="/e-docs/public/photos/1/Screenshot_12.png" alt="" width="120" height="120" /></p>
                            <p style="text-align: center;">&nbsp;</p>
                            <p style="text-align: center;">&nbsp; &nbsp; &nbsp;&nbsp;</p>` },

              
                ],
               
            });
            
            editor.addButton('tempalteStatic', {
                type: 'listbox',
                text: 'static temp',
                icon: false,
                onselect: function (e) {
                    Swal.fire({
                     
                        text: "Your content will be replaced with new content!",
                        type: 'warning',
                        width:'20em',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes'
                      }).then((result) => {
                        if (result.value) {
                            editor.setContent(this.value());
                        }
                      })
                  
                },
                values: [
                  { text: 'Template A', value: `
                        <p>&nbsp;</p>
                        <table style="height: 214px; width: 98.1515%; border-collapse: collapse; border-style: none; margin-left: auto; margin-right: auto;"
                        border="0" cellpadding="5">
                            <tbody>
                                <tr style="height: 218px;">
                                    <td style="width: 50%; height: 218px;">
                                    <p style="text-align: left;"><span style="font-size: 10pt;">1. Lorem ipsum dolor sit amet, in expetendis</span></p>
                                    <p><img class="img-fluid" style="display: block; margin-left: auto; margin-right: auto;" src="/e-docs/public/photos/1/dummy.png"
                                            alt="" width="160" height="123" /></p>
                                    </td>
                                    <td style="width: 50%; height: 218px;">
                                    <p style="text-align: left;"><span style="font-size: 10pt;">2. Lorem ipsum dolor sit amet, in expetendis</span></p>
                                    <p style="text-align: justify;"><img class="img-fluid" src="/e-docs/public/photos/1/dummy.png" alt="" width="151"
                                            height="116" /></p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <p style="text-align: justify;">&nbsp;</p>
                        <p style="text-align: justify;">3. Lorem ipsum dolor sit amet, in expetendis scribentur per, ceteros honestatis pro id.
                            Sea novum ornatus denique id.</p>
                        <p style="text-align: left;"><img class="img-fluid" style="display: block; margin-left: auto; margin-right: auto;" src="/e-docs/public/photos/1/dummy.png"
                                alt="" width="296" height="159" /></p>`
                    },

                  { text: 'Template B', value: `
                            <p>&nbsp;</p>
                            <p><img class="img-fluid" style="display: block; margin-left: auto; margin-right: auto;" src="/e-docs/public/photos/1/fff.png" alt="" width="239" height="376" /></p>
                            <h5 style="text-align: center;">Lorem ipsum recteque no nec, purto complectitur eu duo, docendi corpora postulant his te</h5>
                            <p>&nbsp;</p>
                                        ` },

                    { text: 'Template A3', value: `
                            <h3 style="text-align: center;">&nbsp;</h3>
                            <h3 style="text-align: center;">&nbsp;</h3>
                            <h3 style="text-align: center;">Selamat!</h3>
                            <p>&nbsp;</p>
                            <h4 style="text-align: center;">Lorem ipsum dolor sit amet ? </h4>
                            <p>&nbsp;</p>
                            <p style="text-align: center;"><a class="jump-to" data-target="#carouselSlide" data-id="10"><img style="font-size: 1rem; margin-left: auto; margin-right: auto;" src="/e-docs/public/photos/1/go-home.png" alt="" width="107" height="36" /></a> <img style="font-size: 1rem; margin-left: auto; margin-right: auto;" src="/e-docs/public/photos/1/Asking.png" alt="" width="115" height="36" /></p>` },

                    { text: 'Template img', value: `
                            <h3 style="text-align: center;">&nbsp;</h3>
                            <h3 style="text-align: center;">&nbsp;</h3>
                            <p style="text-align: center;">Lorem ipsum dolor sit amet, harum vidisse ut per ?</p>
                            <p style="text-align: center;">&nbsp;</p>
                            <p style="text-align: center;"> <img src="/e-docs/public/photos/1/Screenshot_11.png" alt="" width="120" height="120" />&nbsp;&nbsp;  <img src="/e-docs/public/photos/1/Screenshot_12.png" alt="" width="120" height="120" /></p>
                            <p style="text-align: center;">&nbsp;</p>
                            <p style="text-align: center;">&nbsp; &nbsp; &nbsp;&nbsp;</p>` },


                    { text: 'Template B', value: `
                            <h3 style="text-align: center;">&nbsp;</h3>
                            <h3 style="text-align: center;">&nbsp;</h3>
                            <h3 style="text-align: center;">Lorem ipsum dolor sit amet ? </h3>
                            <p>&nbsp;</p>
                            <p style="text-align: center;"><button class="btn btn-lg btn-primary rounded-circle jump-to" type="button" data-target="#carouselSlide" data-id="13"> YA</button> Atau <button class="btn btn-lg btn-primary rounded-circle  jump-to" type="button" data-target="#carouselSlide" data-id="34"> TIDAK</button></p>` },

              
                ],
               
            });



        },

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
            editor.on('Change', function (e) {

                cekOldJson($(slideContent).attr(sliderIndex));

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

        
        newJsonContent[sortNumber] = {

            sort_number: sortNumber + 1,
            custom_html: "",
            static_images: '',
            name: "",
            selected_content: 'static_image',
            project_category_id: '',
            type:'normal',
            slide_flag:''
        };

        $('#template-content').append(
            `
               <div class="form-group slide-temp" id='slide-${sortNumber}'>

                  <div class='form-inline'>
                    
                     <div class="input-group col-inline col-md-10 ">
                        
                           <div class="input-group-prepend">
                              <div class="input-group-text rm-radius"><i class="fa fa-file-image-o icon-slide" slide-id='${sortNumber}' aria-hidden="true"></i> </div>
                              
                           </div>
                           <input type="text" class="form-control slide-name rm-radius" readonly id='content-${sortNumber}' slide-id='${sortNumber}'>
                           

                           <div class="input-group-append">
                           <button type="button" class="btn btn-primary  rm-radius btn-slide" slide-id='${sortNumber}'><i class="fa fa-search" ></i> </button>
                           <button type="button" class="btn btn-danger  rm-radius btn-del-slide" slide-id='${sortNumber}'><i class="fa fa-times" ></i> </button>
                            </div>

                            <em id="slidemsg-${sortNumber}" class="error invalid-feedback"></em>
                     </div>
                     

                  </div> 
               </div>

      `
        );
        sortNumber++;
        setFormContent(sortNumber - 1)
    })

    //trigger form add content slide
    $(document).on('click', btnSlide, function () {

        setFormContent($(this).attr('slide-id'));
    })

    //trigger delete slide item
    $(document).on('click', btnDeleteSlide, function () {

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


                if (cekOldJson($(this).attr('slide-id'))) {
                    console.log($(this).attr('slide-id'))
                    deletedContent.push(jsonContent[$(this).attr('slide-id')].id);
                }

                console.log(deletedContent)
                delete jsonContent[$(this).attr('slide-id')]

                $(`#slide-${$(this).attr('slide-id')}`).remove();
                Swal.fire(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                )

                if (typeof oldJsonContent !== 'undefined') {
                    for (var prop in oldJsonContent) {
                        console.log('#slide-' + prop)
                        console.log($('#slide-' + prop).index() + 1)
                        oldJsonContent[prop].sort_number = $('#slide-' + prop).index() + 1

                    }
                }
                for (let prop in newJsonContent) {
                    console.log('#slide-' + prop)
                    console.log($('#slide-' + prop).index() + 1)

                    newJsonContent[prop].sort_number = $('#slide-' + prop).index() + 1
                }

                sortNumber--;
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

        stop: function (event, ui) {

            if (typeof oldJsonContent !== 'undefined') {
                for (var prop in oldJsonContent) {
                    console.log('#slide-' + prop)
                    console.log($('#slide-' + prop).index() + 1)
                    oldJsonContent[prop].sort_number = $('#slide-' + prop).index() + 1

                }
            }
            for (let prop in newJsonContent) {
                console.log('#slide-' + prop)
                console.log($('#slide-' + prop).index() + 1)

                newJsonContent[prop].sort_number = $('#slide-' + prop).index() + 1
            }
        }


    });


    //store slider data to jsonContent

    //add reactiv form and store data to jsonContent


    $(checkType).change(function(){

        cekOldJson($(slideContent).attr(sliderIndex));
        if ($(this).prop('checked')) {

            jsonContent[$(slideContent).attr(sliderIndex)].type='normal';

        }else{
            
            jsonContent[$(slideContent).attr(sliderIndex)].type='goto';
        }

        
    })

    $('#category-name').on('keyup', function () {
        if ($(this).hasClass('is-invalid')) {
            $(this).removeClass('is-invalid')
        }
    })

    //image
    $(staticImage).filemanager('image');

    $(document).on('change', '#lbl', function () {

        cekOldJson($(slideContent).attr(sliderIndex));
        jsonContent[$(slideContent).attr(sliderIndex)].static_images = $('#lbl').html()
    });

    /* ! slider name */
    $(document).on('change', sliderName, function () {

        cekOldJson($(slideContent).attr(sliderIndex));

        jsonContent[$(slideContent).attr(sliderIndex)].name = $(this).val()

    })

    $(document).on('change', sliderFlag, function () {

        cekOldJson($(slideContent).attr(sliderIndex));

        jsonContent[$(slideContent).attr(sliderIndex)].slide_flag = $(this).val()

    })

    $(document).on('keyup', sliderName, function () {
        $(".slide-name[slide-id='" + $(slideContent).attr(sliderIndex) + "']").val($(this).val());

        if ($(".slide-name[slide-id='" + $(slideContent).attr(sliderIndex) + "']").hasClass('is-invalid')) {
            $(".slide-name[slide-id='" + $(slideContent).attr(sliderIndex) + "']").removeClass('is-invalid')
        }
        $(`#slidemsg-${sortNumber}`).html('')
    })

    $(document).on('keyup', sliderFlag, function () {
        $(".slide-name[slide-id='" + $(slideContent).attr(sliderIndex) + "']").val(`${$(sliderName).val()+' ('+$(this).val()+')'}`);

        if ($(".slide-name[slide-id='" + $(slideContent).attr(sliderIndex) + "']").hasClass('is-invalid')) {
            $(".slide-name[slide-id='" + $(slideContent).attr(sliderIndex) + "']").removeClass('is-invalid')
        }
        $(`#slidemsg-${sortNumber}`).html('')
    })


    //checkbox content type

    $(checkHtml).change(function () {
        if (!$(this).prop('checked')) {

            $(checkHtml).prop("checked", true);

        }

        cekOldJson($(slideContent).attr(sliderIndex));


        $(".icon-slide[slide-id='" + $(slideContent).attr(sliderIndex) + "']").removeClass('fa-file-image-o').addClass('fa-code')
        $(checkImage).prop("checked", false);

        jsonContent[$(slideContent).attr(sliderIndex)].selected_content = 'custom_html'

    })

    $(checkImage).change(function () {

        if (!$(this).prop('checked')) {

            $(checkImage).prop("checked", true);

        }

        cekOldJson($(slideContent).attr(sliderIndex));


        $(".icon-slide[slide-id='" + $(slideContent).attr(sliderIndex) + "']").removeClass(' fa-code ').addClass('fa-file-image-o')

        $(checkHtml).prop("checked", false);
        jsonContent[$(slideContent).attr(sliderIndex)].selected_content = 'static_image'
    })

    //save to databse
    $('#btn-spinner').click(function () {
        console.log(tinymce.activeEditor.selection.getContent());
    })

    $(document).ajaxStart(function () {
        $('#spinner').delay("fast").fadeIn();
    });

    $(btnSave).click(function () {

        $('.slide-name').removeClass('slide-name')
        $('.invalid-feedback').html('')

        formData.append('_token', laravel_csrf)
        formData.append('category_name', $('#category-name').val());

        if (typeof oldJsonContent !== 'undefined') {
            formData.append('_method', "PUT")
            formData.append('oldJsonContent', JSON.stringify(oldJsonContent));
            formData.append('deletedContent', JSON.stringify(deletedContent));
        }

        formData.append('newJsonContent', JSON.stringify(newJsonContent));

        let link = 'store';

        if (!regExUrl.test(url.pathname)) {
            link = `update/${categoryID}`;
        }

        $.ajax({
            method: 'POST',
            contentType: "application/json",
            dataType: 'JSON',
            url: base_url + `/project-category/${link}`,
            data: formData,
            async: true,
            processData: false,
            contentType: false,
            cache: false,
        }).done(function (res) {
            location.reload();

        }).fail(function (res) {
            $('#spinner').delay("fast").fadeOut();
            errorMsg = res.responseJSON.errors;

            let regFilter = /\d/; //untuk cari id slide yang gak lolos validasi
            let errIn = []; //untuk nampung data filter
            var err = null;
            var cekdupl = []


            for (var value in errorMsg) {
                if (value.match(regFilter) != null) {
                    errIn.push(value.match(regFilter))
                }
            }

            for (let [key, value] of errIn) {


                if (!cekdupl.includes(key)) {
                    cekdupl.push(key)
                    err = errIn.filter(errIn => errIn[0] == key)

                    $(`#content-${key}`).addClass('is-invalid');

                    for (let ind in err) {

                        $(`#slidemsg-${key}`).append(errorMsg[err[ind]['input']] + '<br>');
                    }
                }
            }
            $('#categoryname-error').html(res.responseJSON.errors)
        })

    })


}

//run function
$(document).ready(function () {

    initProjectCategoryForm();

})

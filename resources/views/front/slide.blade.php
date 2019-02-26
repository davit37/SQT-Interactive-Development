@extends('layouts.front.global')

@section('title')User @endsection

@section('content')



<div class="row justify-content-center slide-full">
    <div class="col-12 ">
        <div id="carouselSlide" class="carousel slide " data-ride="carousel"   data-type="multi"  data-interval="false">
            <div class="carousel-inner ">

               @foreach ($contents as $content)
                  <div class="carousel-item  @if ($loop->first) active @endif" id='slide-{{$content->slide_flag}}'>

                     @if($content->selected_content === 'static_image')

                              <div class='box-content'>
                                 <div>  
                                    <img src="{{asset($content->static_images)}}" class="img-fluid" alt="Responsive image">
                                 </div>
                              </div>

                              @if($content->type === 'normal')
                                 <div class="box-button position-fixed">
                                       <img src="{{asset('icons/back.svg')}}" class="btn-back float-left" alt="" srcset="" width="23%">

                                       <img src="{{asset('icons/next.svg')}}" class="btn-next float-right" alt="" srcset="" width="23%">
                                 </div>

                              @endif
                           
                     @else
                           <div class='box-content'>
                              <div>
                                    <p class="card-text">
                                          {!! htmlspecialchars_decode($content->custom_html) !!}
                                    </p>
                              </div>
                           </div>

                           @if($content->type === 'normal')                            
                              <div class="box-button position-fixed">
                                 <img src="{{asset('icons/back.svg')}}" class="btn-back float-left" alt="" srcset="" width="23%">

                                 <img src="{{asset('icons/next.svg')}}" class="btn-next float-right" alt="" srcset="" width="23%">
                              </div>
                           @endif

                     @endif
                  </div>
               @endforeach

               <button id='btn-next' style="display:none"></button>
               <button id='btn-prev' style="display:none"></button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer-scripts')
<script>

   function setSlideIndex(){
      let getClassJump = document.getElementsByClassName('jump-to');

      for (let i =0 ;i<=getClassJump.length;i++ ){
         getClassJump[i].setAttribute('data-slide-to', $(`#slide-${getClassJump[i].getAttribute('data-id')}`).index())
      }

      
   }

   $(document).ready(function(){
      setSlideIndex()
   })
   
   
   $('.btn-back').click(function () {
      $('.carousel').carousel('prev');
        
    })

    $('.btn-next').click(function () {
      $('.carousel').carousel('next');
    })

    

</script>
@endsection

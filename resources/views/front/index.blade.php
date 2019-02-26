@extends('layouts.front.global')

@section('title')User @endsection

@section('content')     

<div class="row justify-content-center">
   <div class="col-12">
      <div class='card'>
         <div class='card-body'>
               <div class="input-group">
                  <select class="custom-select" id="select-categoy" aria-label="Example select with button addon">
                     <option selected disabled>Choose Project</option>
                     @foreach($projects as $project)
                         <option value="{{$project->id}}">{{$project->name}}</option>
               
                     @endforeach
                  </select>
                  
                     
                
               </div>
         </div>

         <div class="card-footer">
               <a class="btn btn-primary" role="button" href="" id='link-project'>Button</a>
         </div>

         
      </div>
   </div>
</div>

@endsection

@section('footer-scripts')

   <script>
      var base_url='{{url('/')}}'
      $(document).ready(function(){
         $('#select-categoy').change(function(){
            $('#link-project').attr('href',  base_url+'/project-slide/'+$(this).val())
         })
      })
   
   
   </script>

@endsection
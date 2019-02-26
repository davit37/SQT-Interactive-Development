<!DOCTYPE html>
<!--
* CoreUI - Free Bootstrap Admin Template
* @version v2.1.11
* @link https://coreui.io
* Copyright (c) 2018 creativeLabs Łukasz Holeczek
* Licensed under MIT (https://coreui.io/license)
-->

<html lang="en">
  <head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">
    <meta name="author" content="Łukasz Holeczek">
    <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">
    <title>E @yield("title")</title>
    <!-- Icons-->
  
    <link href="{{asset('coreui/node_modules/flag-icon-css/css/flag-icon.min.css')}}" rel="stylesheet">
    <link href="{{asset('coreui/node_modules/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{asset('coreui/node_modules/simple-line-icons/css/simple-line-icons.css')}}" rel="stylesheet">
    <!-- Main styles for this application-->
    <link href="{{asset('coreui/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('coreui/css/custom.css?=vi')}}" rel="stylesheet">
    <link href="{{asset('coreui/vendors/pace-progress/css/pace.min.css')}}" rel="stylesheet">
    @yield('header-scripts')
    <!-- Global site tag (gtag.js) - Google Analytics-->
    <script async="" src="https://www.googletagmanager.com/gtag/js?id=UA-118965717-3"></script>
    <script>
      window.dataLayer = window.dataLayer || [];

      function gtag() {
        dataLayer.push(arguments);
      }
      gtag('js', new Date());
      // Shared ID
      gtag('config', 'UA-118965717-3');
      // Bootstrap ID
      gtag('config', 'UA-118965717-5');
    </script>
  </head>
 
  <body class="app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show">
        
       
  
  <header class="app-header navbar">
      <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
        <span class="navbar-toggler-icon"></span>
      </button>
      <a class="navbar-brand" href="#">
        <img class="navbar-brand-full" src="{{asset('coreui/img/brand/logo.svg')}}" width="89" height="25" alt="CoreUI Logo">
        <img class="navbar-brand-minimized" src="{{asset('coreui/img/brand/sygnet.svg')}}" width="30" height="30" alt="CoreUI Logo">
      </a>
      <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <ul class="nav navbar-nav ml-auto">
        
        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
            <img class="img-avatar" src="{{asset('coreui/img/avatars/6.jpg')}}" alt="admin@bootstrapmaster.com">
          </a>
          <div class="dropdown-menu dropdown-menu-right">
            <div class="dropdown-header text-center">
              <strong>Account</strong>
            </div>
            <a class="dropdown-item" href="#">
              <i class="fa fa-bell-o"></i> Updates
              <span class="badge badge-info">42</span>
            </a>
            <a class="dropdown-item" href="#">
              <i class="fa fa-envelope-o"></i> Messages
              <span class="badge badge-success">42</span>
            </a>
            <a class="dropdown-item" href="#">
              <i class="fa fa-tasks"></i> Tasks
              <span class="badge badge-danger">42</span>
            </a>
            <a class="dropdown-item" href="#">
              <i class="fa fa-comments"></i> Comments
              <span class="badge badge-warning">42</span>
            </a>
            <div class="dropdown-header text-center">
              <strong>Settings</strong>
            </div>
            <a class="dropdown-item" href="#">
              <i class="fa fa-user"></i> Profile</a>
            <a class="dropdown-item" href="#">
              <i class="fa fa-wrench"></i> Settings</a>
            <a class="dropdown-item" href="#">
              <i class="fa fa-usd"></i> Payments
              <span class="badge badge-secondary">42</span>
            </a>
            <a class="dropdown-item" href="#">
              <i class="fa fa-file"></i> Projects
              <span class="badge badge-primary">42</span>
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">
              <i class="fa fa-shield"></i> Lock Account</a>
            <a class="dropdown-item"  href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                     document.getElementById('logout-form').submit();">
            
              <i class="fa fa-lock"></i> Logout</a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  {{ csrf_field() }}
              </form>
          </div>
        </li>
      </ul>
     
      <button class="navbar-toggler aside-menu-toggler d-lg-none" type="button" data-toggle="aside-menu-show">
        <span class="navbar-toggler-icon"></span>
      </button>
    </header>
    <div class="app-body">
      <div class="sidebar sidebar-custom">
        <nav class="sidebar-nav">
          <ul class="nav">
            
            <li class="nav-title"></li>
            <li class="nav-item">
              <a class="nav-link" href="{{route("users.index")}}">
                <i class="nav-icon icon-people"></i> Management Users</a>
            </li>
            {{-- <li class="nav-item">
              <a class="nav-link" href="{{route("article.index")}}">
                <i class="nav-icon icon-book-open"></i> Management Article</a>
            </li> --}}
            <li class="nav-item">
              <a class="nav-link" href="{{route("project-category.index")}}">
                <i class="nav-icon icon-book-open"></i> Project Category </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{route("project.index")}}">
                <i class="nav-icon icon-book-open"></i> Project</a>
            </li>
            
            
          </ul>
        </nav>
        <button class="sidebar-minimizer brand-minimizer" type="button"></button>
      </div>
      <main class="main">
      <div class='container ' id='spinner' style='display: none;'>
          <div class="d-flex justify-content-center">
              <div class="loader"></div>
          </div>
        </div>
    
        
       
        <div class="container-fluid">
        
          <div class="animated fadeIn">
                @yield("content")
          </div>
        </div>
      </main>
     
    </div>
    
    <!-- CoreUI and necessary plugins-->
    <script src="{{asset('coreui/node_modules/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{asset('coreui/node_modules/popper.js/dist/umd/popper.min.js')}}"></script>
    <script src="{{asset('coreui/node_modules/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('coreui/node_modules/pace-progress/pace.min.js')}}"></script>
    <script src="{{asset('coreui/node_modules/perfect-scrollbar/dist/perfect-scrollbar.min.js')}}"></script>
    <script src="{{asset('coreui/node_modules/@coreui/coreui/js/coreui.min.js')}}"></script>
    <!-- Plugins and scripts required by this view-->
    @yield('footer-scripts')
    <script>
      var base_url='http://localhost/e-docs/public'
    </script>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@8.0.5/dist/sweetalert2.all.min.js'></script>
    <script src="{{asset('coreui/js/app.es6.js')}}"></script>
    <script src="{{asset('coreui/node_modules/@coreui/coreui/js/custom-tooltips.min.js')}}"></script>
   

  </body>
</html>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf_token" content="{{ csrf_token() }}">
  
  @yield('title')

  <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <!-- CSS Files -->
  <link href="{{asset('/')}}assets/admin/css/material-dashboard.css?v=2.1.2" rel="stylesheet" />
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="{{asset('/')}}assets/admin/demo/demo.css" rel="stylesheet" />
  <link href="{{asset('/')}}assets/admin/css/bootstrap.min.css" rel="stylesheet" />

  @stack('css')

</head>

<body class="">
  <div class="wrapper ">
    <!-- Sidebar -->
    @if (Request::is('admin*'))
        @include('admin.includes.sidebar')
    @endif
    
    @yield('secret')

    <!-- Sidebar -->
    <div class="main-panel">
        <!-- Navbar -->
        @if (Request::is('admin*'))
            @include('admin.includes.navbar')
        @endif
        <!-- End Navbar -->
        
        @yield('content')

        <!-- Footer -->
        @if (Request::is('admin*'))
            @include('admin.includes.footer')
        @endif
        <!-- Footer -->
    </div>
  </div>
      
    <!-- Fixed Plugin -->
  <!--   Core JS Files   -->
    @if (Request::is('admin*'))
       @include('admin.includes.js') 
    @endif
  


  @stack('js')
</body>

</html>
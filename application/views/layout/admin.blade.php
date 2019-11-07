@extends('layout.master')

@section('headers')
<link rel="stylesheet" href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('bower_components/font-awesome/css/font-awesome.min.css') }}">
<link rel="stylesheet" href="{{ asset('bower_components/Ionicons/css/ionicons.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist/css/AdminLTE.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist/css/skins/_all-skins.min.css') }}">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<link rel="stylesheet" href="{{ asset('bower_components/morris.js/morris.css') }}">
<link rel="stylesheet" href="{{ asset('bower_components/jvectormap/jquery-jvectormap.css') }}">
<link rel="stylesheet" href="{{ asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
<link rel="stylesheet" href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@yield('page-header')
@endsection

@section('class-body')hold-transition skin-blue sidebar-mini @endsection

@section('body')
<div class="wrapper">
    @component('component.main-header')
    @endcomponent

    @component('component.main-sidebar', ["aktif" => $aktif])
    @endcomponent
    
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          @yield('page-title')
        </h1>
        <ol class="breadcrumb">
          @yield('page-breadcrumb')
        </ol>
      </section>
  
      <!-- Main content -->
      <section class="content">
          @yield('page-content')
      </section>
    </div>
  </div>
@endsection

@section('footers')<footer class="main-footer">
    <div class="pull-right hidden-xs">
      Made with <i class="fa fa-heart text-danger"></i> by <a href="https://id.linkedin.com/in/rafli-firdausy-irawan-b0896a171" target="_blank">Rafli Firdausy Irawan</a>
    </div>
    <strong>Copyright &copy; {{ date('Y') }} <a href="{{ base_url() }}"></strong>{{ $app_name }}</a> | {{ $app_complete_name }}
  </footer>
  <div class="control-sidebar-bg"></div>

  
<script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('bower_components/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<script src="{{ asset('dist/js/pages/dashboard.js') }}"></script>
<script src="{{ asset('dist/js/demo.js') }}"></script>
<script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
@yield('page-footer')
@endsection
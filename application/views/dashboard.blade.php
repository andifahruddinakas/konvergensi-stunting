@extends('layout.admin')

@section('tab-title')
    Dashboard
@endsection

@section('page-title')
    Dashboard
@endsection

@section('page-header')
@endsection

@section('page-breadcrumb')
<li><a href="{{ base_url('dashboard') }}"><i class="fa fa-laptop"></i> {{ $app_name }}</a></li>
<li class="active">Dashboard</li>
@endsection

@section('page-content')
    <div class="row">
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-aqua">
            <div class="inner">
              @if ($CI->session->userdata("login")->level == "super_admin")
                <h3>{{ $totalPosyandu }}</h3>
                <p>Posyandu Terdaftar</p>
              @else
                <h3>{{ $ibuHamilBulanIni }}</h3>
                <p>Ibu Hamil Periksa Bulan ini</p>
              @endif              
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>            
          </div>
        </div>
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-green">
            <div class="inner">
            @if ($CI->session->userdata("login")->level == "super_admin")
              <h3>{{ $totalKpm }}</h3>
              <p>KPM Terdaftar</p>
            @else
              <h3>{{ $anakBulanIni }}</h3>
              <p>Anak Periksa Bulan ini</p>
            @endif                     
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>            
          </div>
        </div>
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>{{ $ibuHamilSemua }}</h3>
              <p>Total Periksa Ibu Hamil</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>            
          </div>
        </div>        
        <div class="col-lg-3 col-xs-6">          
          <div class="small-box bg-red">
            <div class="inner">
              <h3>{{ $anakSemua }}</h3>
              <p>Total Periksa Anak</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>            
          </div>
        </div>
      </div>
@endsection

@section('page-footer')


<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>

<script src="{{ asset('bower_components/raphael/raphael.min.js') }}"></script>
<script src="{{ asset('bower_components/morris.js/morris.min.js') }}"></script>
<script src="{{ asset('bower_components/jquery-sparkline/dist/jquery.sparkline.min.js') }}"></script>
<script src="{{ asset('plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
<script src="{{ asset('plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
<script src="{{ asset('bower_components/jquery-knob/dist/jquery.knob.min.js') }}"></script>
<script src="{{ asset('bower_components/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
<script src="{{ asset('bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('bower_components/fastclick/lib/fastclick.js') }}"></script>

@endsection
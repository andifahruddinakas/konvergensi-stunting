@extends('layout.admin')

@section('tab-title')
{{ $title }}
@endsection

@section('page-title')
{{ $title }}
@endsection

@section('page-header')

@endsection

@section('page-breadcrumb')
<li><a href="{{ base_url('dashboard') }}"><i class="fa fa-laptop"></i> {{ $app_name }}</a></li>
<li class="active">{{ $title }}</li>
@endsection

@section('page-content')
<div class="row">
    <div class="col-xs-12">
      
        @if ($CI->session->flashdata("sukses"))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-check"></i> Sukses</h4>
            {{ $CI->session->flashdata("sukses") }}
        </div>
        @endif      
        
        @if ($CI->session->flashdata("gagal"))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-check"></i> Gagal</h4>
            {{ $CI->session->flashdata("gagal") }}
        </div>
        @endif   

        <div class="box box-success">
            <div class="box-header">
                <div class="col-md-9 no-padding">
                    <div class="col-md-4">
                        <div class="form-group">                                
                            <select name="kuartal" id="kuartal" required class="form-control" title="Pilih salah satu">
                                @foreach (kuartal() as $item)
                                    <option value="{{ $item['ke'] }}" {{ $item['ke'] == $kuartal ? "selected" : "" }}>Kuartal ke {{ $item['ke'] }}  ({{ $item['bulan'] }})</option>
                                @endforeach                                
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">                                
                            <select name="tahun" id="tahun" required class="form-control" title="Pilih salah satu">
                                @foreach ($dataTahun as $item)
                                    <option value="{{ $item->tahun }}">{{ $item->tahun }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 no-padding">
                        <button type="button" class="btn col-md-12 btn-primary" id="cari">
                            <i class="fa fa-search"></i> Cari
                        </button>
                    </div>
                </div>
                <div class="col-md-3 no-padding pull-right">
                    <a href="{{ base_url('rekapitulasi/export-bulanan-anak/') . $kuartal .'/' . $_tahun }}" id="btnExport" type="button" class="btn pull-right col-md-6  btn-danger">
                        Export ke Excel
                    </a>                        
                </div>
            </div>

            <div class="box-body table-responsive">
                <table  id="table-datas" class="table table-bordered table-striped table-responsive">
                    <thead>
                        <tr>
                            <th colspan="4" class="text-center" style="vertical-align: middle;">
                                Tingkat Konvergensi Desa Periode Kuartal x Bulan Januari s/d Maret 2019
                            </th>                            
                        </tr>
                        <tr>                            
                            <th class="text-center" width="20%" style="vertical-align: middle;">Sasaran</th>
                            <th class="text-center" width="30%" style="vertical-align: middle;">Jumlah Sasaran Terdaftar dan Aktif</th>
                            <th class="text-center" width="30%" style="vertical-align: middle;">Jumlah Sasaran Total</th>
                            <th class="text-center" width="20%" style="vertical-align: middle;">%</th>
                        </tr>
                        <tr>                            
                            <th class="text-center" style="vertical-align: middle;">Jumlah</th>
                            <th class="text-center" style="vertical-align: middle;">123</th>
                            <th class="text-center" style="vertical-align: middle;">321</th>                            
                            <th class="text-center" style="vertical-align: middle;">100.00</th>  
                        </tr>
                    </thead>                  
                </table>
            </div>
            
        </div>
    </div>
</div>
@endsection

@section('page-footer')
<script src="{{ asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script src=" {{ asset('bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src=" {{ asset('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<!-- date-range-picker -->
<script src="{{ asset('bower_components/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script>

    $(function () {

        $('#cari').click(function(){
            let bulan = $('#bulan option:selected').val();
            let tahun = $('#tahun option:selected').val();
            window.location.href = "{{ base_url('pemantauan/ibu-hamil/') }}" + bulan + "/" + tahun;
        });

        $('#table-data').DataTable()

        $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          'Hari Ini'        : [moment(), moment()],
          'Kemarin'         : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          '7 Hari Terakhir' : [moment().subtract(6, 'days'), moment()],
          '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
          'Bulan Ini'       : [moment().startOf('month'), moment().endOf('month')],
          'Bulan Kemarin'   : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment()
      },
      function (start, end) {
        $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
        window.location.href = "https://google.com";
      }
    )
    })
</script>
@endsection
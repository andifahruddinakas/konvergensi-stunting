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
<li><a href="{{ base_url('dashboard') }}"><i class="fa fa-dashboard"></i> {{ $app_name }}</a></li>
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
                <table  id="table-datas" class="table  table-bordered table-striped table-responsive">
                    <thead>
                        <tr>
                            <th colspan="5" class="text-center" style="vertical-align: middle;">
                                Tingkat Konvergensi Desa <br>
                                Periode Kuartal x Bulan Januari s/d Maret
                            </th>                            
                        </tr>
                        <tr>
                            <th class="text-center" width="5%" style="vertical-align: middle;">No</th>
                            <th class="text-center" style="vertical-align: middle;">Sasaran</th>
                            <th class="text-center" width="20%" style="vertical-align: middle;">Jumlah Layanan Diterima</th>
                            <th class="text-center" width="20%" style="vertical-align: middle;">Jumlah yang Seharusnya Diterima</th>
                            <th class="text-center" width="20%" style="vertical-align: middle;">Konvergensi %</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">1</td>
                            <td>Ibu Hamil</td>
                            <td class="text-center">{{ $ibu_hamil["tingkatKonvergensiDesa"] == NULL ? "0" : $ibu_hamil["tingkatKonvergensiDesa"]["jumlah_diterima"] }}</td>
                            <td class="text-center">{{ $ibu_hamil["tingkatKonvergensiDesa"] == NULL ? "0" : $ibu_hamil["tingkatKonvergensiDesa"]["jumlah_seharusnya"] }}</td>
                            <td class="text-center">{{ $ibu_hamil["tingkatKonvergensiDesa"] == NULL ? "0" : $ibu_hamil["tingkatKonvergensiDesa"]["persen"] }}</td>
                        </tr>
                        <tr>
                            <td class="text-center">2</td>
                            <td>Anak 0-23 Bulan</td>
                            <td class="text-center">{{ $bulanan_anak["tingkatKonvergensiDesa"] == NULL ? "0" : $bulanan_anak["tingkatKonvergensiDesa"]["jumlah_diterima"] }}</td>
                            <td class="text-center">{{ $bulanan_anak["tingkatKonvergensiDesa"] == NULL ? "0" : $bulanan_anak["tingkatKonvergensiDesa"]["jumlah_seharusnya"] }}</td>
                            <td class="text-center">{{ $bulanan_anak["tingkatKonvergensiDesa"] == NULL ? "0" : $bulanan_anak["tingkatKonvergensiDesa"]["persen"] }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        @php
                            $JLD_IbuHamil   = $ibu_hamil["tingkatKonvergensiDesa"] == NULL ? "0" : $ibu_hamil["tingkatKonvergensiDesa"]["jumlah_diterima"];
                            $JLD_Anak       = $bulanan_anak["tingkatKonvergensiDesa"] == NULL ? "0" : $bulanan_anak["tingkatKonvergensiDesa"]["jumlah_diterima"];

                            $JYSD_IbuHamil  = $ibu_hamil["tingkatKonvergensiDesa"] == NULL ? "0" : $ibu_hamil["tingkatKonvergensiDesa"]["jumlah_seharusnya"];
                            $JYSD_Anak      = $bulanan_anak["tingkatKonvergensiDesa"] == NULL ? "0" : $bulanan_anak["tingkatKonvergensiDesa"]["jumlah_seharusnya"];

                            $JLD_TOTAL      = (int) $JLD_IbuHamil + (int) $JLD_Anak;
                            $JYSD_TOTAL     = (int) $JYSD_IbuHamil + (int) $JYSD_Anak;

                            $KONV_TOTAL     = $JLD_TOTAL / $JYSD_TOTAL * 100;
                        @endphp
                        <tr>
                            <th class="text-center" colspan="2">Total Tingkat Konvergensi Desa</th>
                            <td class="text-center">{{ $JLD_TOTAL }}</td>
                            <td class="text-center">{{ $JYSD_TOTAL }}</td>
                            <td class="text-center">{{ number_format($KONV_TOTAL, 2) }}</td>
                        </tr>
                    </tfoot>
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
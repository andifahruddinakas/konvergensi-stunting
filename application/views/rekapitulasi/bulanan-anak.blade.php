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
                        <a href="{{ base_url('rekapitulasi/export-ibu-hamil/') . $kuartal .'/' . $_tahun }}" id="btnExport" type="button" class="btn pull-right col-md-6  btn-danger">
                            Export ke Excel
                        </a>                        
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive">
                <table  id="table-datas" class="table  table-bordered table-striped table-responsive">
                    <thead>
                    <tr>
                        <th rowspan="3" class="text-center" style="vertical-align: middle;">No</th>
                        <th rowspan="3" class="text-center" style="vertical-align: middle;">NO KIA</th>
                        <th rowspan="3" class="text-center" style="vertical-align: middle;">Nama Anak</th>
                        <th rowspan="3" class="text-center" style="vertical-align: middle;">Jenis Kelamin </th>                    
                        <th colspan="10" style="vertical-align: middle;">Kuartal Ke {{ $kuartal }} Bulan {{ bulan($batasBulanBawah) }} s/d {{ bulan($batasBulanAtas) }} {{ $_tahun }}</th>
                        <th colspan="3" rowspan="2" class="text-center" style="vertical-align: middle;">Tingkat Konvergensi Indikator</th>                        
                    </tr>
                    <tr>
                        <th colspan="2" class="text-center" style="vertical-align: middle;">Umur dan Status Gizi</th>
                        <th colspan="10" class="text-center" style="vertical-align: middle;">Indikator Layanan</th>
                    </tr>
                    <tr>
                        <th class="text-center" style="vertical-align: middle;">Umur (Bulan)</th>
                        <th class="text-center" style="vertical-align: middle;">Normal / Buruk / Kurang / Stunting</th>
                        <th class="text-center" style="vertical-align: middle;">Pemberian Imunisasi Dasar</th>
                        <th class="text-center" style="vertical-align: middle;">Pengukuran Berat Badan</th>
                        <th class="text-center" style="vertical-align: middle;">Pengukuran Tinggi Badan</th>
                        <th class="text-center" style="vertical-align: middle;">Konseling Gizi Bagi Orang Tua</th>
                        <th class="text-center" style="vertical-align: middle;">Kunjungan Rumah</th>
                        <th class="text-center" style="vertical-align: middle;">Kepemilikan Akses Air Bersih</th>        
                        <th class="text-center" style="vertical-align: middle;">Kepemilikan jamban</th>
                        <th class="text-center" style="vertical-align: middle;">Akta Lahir</th>
                        <th class="text-center" style="vertical-align: middle;">Jaminan Kesehatan</th>
                        <th class="text-center" style="vertical-align: middle;">Pengasuhan (PAUD)</th>
                        <th class="text-center" style="vertical-align: middle;">Jumlah Diterima Lengkap</th>
                        <th class="text-center" style="vertical-align: middle;">Jumlah Seharusnya</th>
                        <th class="text-center" style="vertical-align: middle;">%</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if (!$dataFilter)
                            <tr>
                                <td class="text-center" style="vertical-align: middle;" colspan="20">Data Tidak Ditemukan!</td>
                            </tr>
                        @else
                           @foreach ($dataFilter as $item)
                           <tr>
                                <td class="text-center" style="vertical-align: middle;">{{ $loop->iteration }}</td>
                                <td class="text-center" style="vertical-align: middle;">{{ $item['user']['no_kia'] }}</td>
                                <td class="text-center" style="vertical-align: middle;">{{ $item['user']['nama_anak'] }}</td>
                                <td class="text-center" style="vertical-align: middle;">{{ $item['user']['jenis_kelamin'] }}</td>
                                <td class="text-center" style="vertical-align: middle;">{{ $item['umur_dan_gizi']['umur_bulan'] }}</td>
                                <td class="text-center" style="vertical-align: middle;">{{ $item['umur_dan_gizi']['status_gizi'] }}</td>
                                <td class="text-center" style="vertical-align: middle;">{{ $item['indikator']['imunisasi'] }}</td>
                                <td class="text-center" style="vertical-align: middle;">{{ $item['indikator']['pengukuran_berat_badan'] }}</td>
                                <td class="text-center" style="vertical-align: middle;">{{ $item['indikator']['pengukuran_tinggi_badan'] }}</td>
                                <td class="text-center" style="vertical-align: middle;">{{ $item['indikator']['konseling_gizi'] }}</td>
                                <td class="text-center" style="vertical-align: middle;">{{ $item['indikator']['kunjungan_rumah'] }}</td>
                                <td class="text-center" style="vertical-align: middle;">{{ $item['indikator']['air_bersih'] }}</td>
                                <td class="text-center" style="vertical-align: middle;">{{ $item['indikator']['jamban_sehat'] }}</td>
                                <td class="text-center" style="vertical-align: middle;">{{ $item['indikator']['akta_lahir'] }}</td>
                                <td class="text-center" style="vertical-align: middle;">{{ $item['indikator']['jaminan_kesehatan'] }}</td>
                                <td class="text-center" style="vertical-align: middle;">{{ $item['indikator']['pengasuhan_paud'] }}</td>
                                <td class="text-center" style="vertical-align: middle;">{{ $item['tingkat_konvergensi_indikator']['jumlah_diterima_lengkap'] }}</td>
                                <td class="text-center" style="vertical-align: middle;">{{ $item['tingkat_konvergensi_indikator']['jumlah_seharusnya'] }}</td>
                                <td class="text-center" style="vertical-align: middle;">{{ $item['tingkat_konvergensi_indikator']['persen'] }}</td>
                            </tr>
                           @endforeach
                        @endif
                        
                    </tbody>
                    @if ($dataFilter)
                    <tfoot>                                            
                        <tr>
                            <th colspan="3" rowspan="3" class="text-center" style="vertical-align: middle;">Tingkat Capaian Konvergensi</th>
                            <th colspan="3" class="text-center" style="vertical-align: middle;">Jumlah Diterima</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["imunisasi"]["jumlah_diterima"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["pengukuran_berat_badan"]["jumlah_diterima"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["pengukuran_tinggi_badan"]["jumlah_diterima"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["konseling_gizi"]["jumlah_diterima"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["kunjungan_rumah"]["jumlah_diterima"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["air_bersih"]["jumlah_diterima"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["jamban_sehat"]["jumlah_diterima"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["akta_lahir"]["jumlah_diterima"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["jaminan_kesehatan"]["jumlah_diterima"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["pengasuhan_paud"]["jumlah_diterima"] }}</th>
                            <th rowspan="3" class="text-center" style="vertical-align: middle;">{{ $tingkatKonvergensiDesa["jumlah_diterima"] }}</th>
                            <th rowspan="3" class="text-center" style="vertical-align: middle;">{{ $tingkatKonvergensiDesa["jumlah_seharusnya"] }}</th>
                            <th rowspan="3" class="text-center" style="vertical-align: middle;">{{ $tingkatKonvergensiDesa["persen"] }}</th>
                        </tr>
                        <tr>
                            <th colspan="3" class="text-center" style="vertical-align: middle;">Jumlah Seharusnya</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["imunisasi"]["jumlah_seharusnya"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["pengukuran_berat_badan"]["jumlah_seharusnya"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["pengukuran_tinggi_badan"]["jumlah_seharusnya"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["konseling_gizi"]["jumlah_seharusnya"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["kunjungan_rumah"]["jumlah_seharusnya"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["air_bersih"]["jumlah_seharusnya"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["jamban_sehat"]["jumlah_seharusnya"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["akta_lahir"]["jumlah_seharusnya"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["jaminan_kesehatan"]["jumlah_seharusnya"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["pengasuhan_paud"]["jumlah_seharusnya"] }}</th>
                        </tr>
                        <tr>
                            <th colspan="3" class="text-center" style="vertical-align: middle;">%</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["imunisasi"]["persen"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["pengukuran_berat_badan"]["persen"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["pengukuran_tinggi_badan"]["persen"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["konseling_gizi"]["persen"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["kunjungan_rumah"]["persen"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["air_bersih"]["persen"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["jamban_sehat"]["persen"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["akta_lahir"]["persen"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["jaminan_kesehatan"]["persen"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["pengasuhan_paud"]["persen"] }}</th>
                        </tr>                        
                    </tfoot>    
                    @endif                              
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

    $('#cari').click(function(){       
        let kuartal = $('#kuartal option:selected').val();
        let tahun   = $('#tahun option:selected').val();
        window.location.href = "{{ base_url('rekapitulasi/bulanan-anak/') }}" + kuartal + "/" + tahun;
    });

    $(function () {

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
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
                            <th colspan="2" width="65%" class="text-center" style="vertical-align: middle;">Tingkatan Capaian Indikator</th>
                            <th colspan="3" width="35%" class="text-center" style="vertical-align: middle;">Kuartal Ke {{ $kuartal }}</th>
                        </tr>
                        <tr>
                            <th class="text-center" width="5%" style="vertical-align: middle;">No</th>
                            <th class="text-center" style="vertical-align: middle;">Indikator</th>
                            <th class="text-center" style="vertical-align: middle;">Jumlah Diterima</th>
                            <th class="text-center" style="vertical-align: middle;">Jumlah Seharusnya</th>
                            <th class="text-center" width="5%" style="vertical-align: middle;">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th colspan="5">Sasaran Ibu Hamil</th>
                        </tr>
                        <tr>
                            <td class="text-center">1</td>
                            <td>Ibu hamil periksa kehamilan paling sedikit 4 kali selama kehamilan kehamilan</td>
                            <td class="text-center">{{ $ibu_hamil["capaianKonvergensi"] == NULL ? "0" : $ibu_hamil["capaianKonvergensi"]["periksa_kehamilan"]["Y"] }}</td>
                            <td class="text-center">{{ $ibu_hamil["capaianKonvergensi"] == NULL ? "0" : $ibu_hamil["capaianKonvergensi"]["periksa_kehamilan"]["jumlah_seharusnya"] }}</td>
                            <td class="text-center">{{ $ibu_hamil["capaianKonvergensi"] == NULL ? "0" : $ibu_hamil["capaianKonvergensi"]["periksa_kehamilan"]["persen"] }}</td>
                        </tr>        
                        <tr>
                            <td class="text-center">2</td>
                            <td>Ibu hamil mendapatkan dan minum 1 tablet tambah darah (pil FE) setiap hari minimal selama 90 hari</td>
                            <td class="text-center">{{ $ibu_hamil["capaianKonvergensi"] == NULL ? "0" : $ibu_hamil["capaianKonvergensi"]["pil_fe"]["Y"] }}</td>
                            <td class="text-center">{{ $ibu_hamil["capaianKonvergensi"] == NULL ? "0" : $ibu_hamil["capaianKonvergensi"]["pil_fe"]["jumlah_seharusnya"] }}</td>
                            <td class="text-center">{{ $ibu_hamil["capaianKonvergensi"] == NULL ? "0" : $ibu_hamil["capaianKonvergensi"]["pil_fe"]["persen"] }}</td>
                        </tr>      
                        <tr>
                            <td class="text-center">3</td>
                            <td>Ibu bersalin mendapatkan layanan nifas oleh nakes dilaksanakan minimal 3 kali</td>
                            <td class="text-center">{{ $ibu_hamil["capaianKonvergensi"] == NULL ? "0" : $ibu_hamil["capaianKonvergensi"]["pemeriksaan_nifas"]["Y"] }}</td>
                            <td class="text-center">{{ $ibu_hamil["capaianKonvergensi"] == NULL ? "0" : $ibu_hamil["capaianKonvergensi"]["pemeriksaan_nifas"]["jumlah_seharusnya"] }}</td>
                            <td class="text-center">{{ $ibu_hamil["capaianKonvergensi"] == NULL ? "0" : $ibu_hamil["capaianKonvergensi"]["pemeriksaan_nifas"]["persen"] }}</td>
                        </tr>  
                        <tr>
                            <td class="text-center">4</td>
                            <td>Ibu hamil mengikuti kegiatan konseling gizi atau kelas ibu hamil minimal 4 kali selama kehamilan </td>
                            <td class="text-center">{{ $ibu_hamil["capaianKonvergensi"] == NULL ? "0" : $ibu_hamil["capaianKonvergensi"]["konseling_gizi"]["Y"] }}</td>
                            <td class="text-center">{{ $ibu_hamil["capaianKonvergensi"] == NULL ? "0" : $ibu_hamil["capaianKonvergensi"]["konseling_gizi"]["jumlah_seharusnya"] }}</td>
                            <td class="text-center">{{ $ibu_hamil["capaianKonvergensi"] == NULL ? "0" : $ibu_hamil["capaianKonvergensi"]["konseling_gizi"]["persen"] }}</td>
                        </tr>  
                        <tr>
                            <td class="text-center">5</td>
                            <td>Ibu hamil dengan kondisi resiko tinggi dan/atau Kekurangan Energi Kronis (KEK) mendapat kunjungan ke rumah oleh bidan Desa secara terpadu minimal 1 bulan sekali </td>
                            <td class="text-center">{{ $ibu_hamil["capaianKonvergensi"] == NULL ? "0" : $ibu_hamil["capaianKonvergensi"]["kunjungan_rumah"]["Y"] }}</td>
                            <td class="text-center">{{ $ibu_hamil["capaianKonvergensi"] == NULL ? "0" : $ibu_hamil["capaianKonvergensi"]["kunjungan_rumah"]["jumlah_seharusnya"] }}</td>
                            <td class="text-center">{{ $ibu_hamil["capaianKonvergensi"] == NULL ? "0" : $ibu_hamil["capaianKonvergensi"]["kunjungan_rumah"]["persen"] }}</td>
                        </tr>    
                        <tr>
                            <td class="text-center">6</td>
                            <td>Rumah Tangga Ibu hamil memiliki sarana akses air minum yang aman</td>
                            <td class="text-center">{{ $ibu_hamil["capaianKonvergensi"] == NULL ? "0" : $ibu_hamil["capaianKonvergensi"]["akses_air_bersih"]["Y"] }}</td>
                            <td class="text-center">{{ $ibu_hamil["capaianKonvergensi"] == NULL ? "0" : $ibu_hamil["capaianKonvergensi"]["akses_air_bersih"]["jumlah_seharusnya"] }}</td>
                            <td class="text-center">{{ $ibu_hamil["capaianKonvergensi"] == NULL ? "0" : $ibu_hamil["capaianKonvergensi"]["akses_air_bersih"]["persen"] }}</td>
                        </tr>    
                        <tr>
                            <td class="text-center">7</td>
                            <td>Rumah Tangga Ibu hamil memiliki sarana jamban keluarga yang layak.</td>
                            <td class="text-center">{{ $ibu_hamil["capaianKonvergensi"] == NULL ? "0" : $ibu_hamil["capaianKonvergensi"]["kepemilikan_jamban"]["Y"] }}</td>
                            <td class="text-center">{{ $ibu_hamil["capaianKonvergensi"] == NULL ? "0" : $ibu_hamil["capaianKonvergensi"]["kepemilikan_jamban"]["jumlah_seharusnya"] }}</td>
                            <td class="text-center">{{ $ibu_hamil["capaianKonvergensi"] == NULL ? "0" : $ibu_hamil["capaianKonvergensi"]["kepemilikan_jamban"]["persen"] }}</td>
                        </tr>  
                        <tr>
                            <td class="text-center">8</td>
                            <td>Ibu hamil memiliki jaminan layanan kesehatan</td>
                            <td class="text-center">{{ $ibu_hamil["capaianKonvergensi"] == NULL ? "0" : $ibu_hamil["capaianKonvergensi"]["jaminan_kesehatan"]["Y"] }}</td>
                            <td class="text-center">{{ $ibu_hamil["capaianKonvergensi"] == NULL ? "0" : $ibu_hamil["capaianKonvergensi"]["jaminan_kesehatan"]["jumlah_seharusnya"] }}</td>
                            <td class="text-center">{{ $ibu_hamil["capaianKonvergensi"] == NULL ? "0" : $ibu_hamil["capaianKonvergensi"]["jaminan_kesehatan"]["persen"] }}</td>
                        </tr>  
                        <tr>
                            <th colspan="5">Sasaran Anak 0 sd 23 Bulan</th>
                        </tr>  
                        <tr>
                            <td class="text-center">1</td>
                            <td>Bayi usia 12 bulan ke bawah mendapatkan imunisasi dasar  lengkap</td>
                            <td class="text-center">{{ $bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["imunisasi"]["Y"] }}</td>
                            <td class="text-center">{{ $bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["imunisasi"]["jumlah_seharusnya"] }}</td>
                            <td class="text-center">{{ $bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["imunisasi"]["persen"] }}</td>
                        </tr>                            
                        <tr>
                            <td class="text-center">2</td>
                            <td>Anak  usia  0-23 bulan  diukur  berat  badannya di posyandu secara rutin setiap bulan </td>
                            <td class="text-center">{{ $bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["pengukuran_berat_badan"]["Y"] }}</td>
                            <td class="text-center">{{ $bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["pengukuran_berat_badan"]["jumlah_seharusnya"] }}</td>
                            <td class="text-center">{{ $bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["pengukuran_berat_badan"]["persen"] }}</td>
                        </tr>  
                        <tr>
                            <td class="text-center">3</td>
                            <td>Anak usia 0-23 bulan diukur panjang/tinggi badannya oleh tenaga kesehatan terlatih minimal 2 kali dalam setahun </td>
                            <td class="text-center">{{ $bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["pengukuran_tinggi_badan"]["Y"] }}</td>
                            <td class="text-center">{{ $bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["pengukuran_tinggi_badan"]["jumlah_seharusnya"] }}</td>
                            <td class="text-center">{{ $bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["pengukuran_tinggi_badan"]["persen"] }}</td>
                        </tr>  
                        <tr>
                            <td class="text-center">4</td>
                            <td>Orang tua/pengasuh yang memiliki anak usia 0-23 bulan  mengikuti kegiatan konseling gizi secara rutin minimal sebulan sekali.</td>
                            <td class="text-center">{{ $bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["konseling_gizi"]["Y"] }}</td>
                            <td class="text-center">{{ $bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["konseling_gizi"]["jumlah_seharusnya"] }}</td>
                            <td class="text-center">{{ $bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["konseling_gizi"]["persen"] }}</td>
                        </tr>  
                        <tr>
                            <td class="text-center">5</td>
                            <td>Anak usia 0-23 bulan dengan status gizi buruk, gizi kurang, dan stunting mendapat kunjungan ke rumah secara terpadu minimal 1 bulan sekali </td>
                            <td class="text-center">{{ $bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["kunjungan_rumah"]["Y"] }}</td>
                            <td class="text-center">{{ $bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["kunjungan_rumah"]["jumlah_seharusnya"] }}</td>
                            <td class="text-center">{{ $bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["kunjungan_rumah"]["persen"] }}</td>
                        </tr>  
                        <tr>
                            <td class="text-center">6</td>
                            <td>Rumah Tangga anak usia 0-23 bulan memiliki sarana akses air minum yang aman</td>
                            <td class="text-center">{{ $bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["air_bersih"]["Y"] }}</td>
                            <td class="text-center">{{ $bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["air_bersih"]["jumlah_seharusnya"] }}</td>
                            <td class="text-center">{{ $bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["air_bersih"]["persen"] }}</td>
                        </tr>  
                        <tr>
                            <td class="text-center">7</td>
                            <td>Rumah Tangga anak usia 0-23 bulan memiliki sarana jamban yang layak</td>
                            <td class="text-center">{{ $bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["jamban_sehat"]["Y"] }}</td>
                            <td class="text-center">{{ $bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["jamban_sehat"]["jumlah_seharusnya"] }}</td>
                            <td class="text-center">{{ $bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["jamban_sehat"]["persen"] }}</td>
                        </tr>  
                        <tr>
                            <td class="text-center">8</td>
                            <td>Anak usia 0-23 bulan memiliki akte kelahiran</td>
                            <td class="text-center">{{ $bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["akta_lahir"]["Y"] }}</td>
                            <td class="text-center">{{ $bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["akta_lahir"]["jumlah_seharusnya"] }}</td>
                            <td class="text-center">{{ $bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["akta_lahir"]["persen"] }}</td>
                        </tr>  
                        <tr>
                            <td class="text-center">9</td>
                            <td>Anak usia 0-23 bulan memiliki jaminan layanan kesehatan</td>
                            <td class="text-center">{{ $bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["jaminan_kesehatan"]["Y"] }}</td>
                            <td class="text-center">{{ $bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["jaminan_kesehatan"]["jumlah_seharusnya"] }}</td>
                            <td class="text-center">{{ $bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["jaminan_kesehatan"]["persen"] }}</td>
                        </tr>  
                        <tr>
                            <td class="text-center">10</td>
                            <td>Orang tua/pengasuh yang memiliki anaksia 0-23 bulan mengikuti Kelas Pengasuhan minimal sebulan sekali</td>
                            <td class="text-center">{{ $bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["pengasuhan_paud"]["Y"] }}</td>
                            <td class="text-center">{{ $bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["pengasuhan_paud"]["jumlah_seharusnya"] }}</td>
                            <td class="text-center">{{ $bulanan_anak["capaianKonvergensi"] == NULL ? "0" : $bulanan_anak["capaianKonvergensi"]["pengasuhan_paud"]["persen"] }}</td>
                        </tr>  
                        <tr>
                            <th colspan="5">Sasaran Anak > 2 sd 6 Tahun</th>
                        </tr>
                        <tr>
                            <td class="text-center">1</td>
                            <td>Anak usia > 2-6 tahun terdaftar dan aktif mengikuti kegiatan layanan PAUD</td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                        </tr> 
                    </tbody>
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
            let kuartal = $('#kuartal option:selected').val();
            let tahun = $('#tahun option:selected').val();
            // alert(bulan + " " + tahun);
            window.location.href = "{{ base_url('formulir-bantu/capaian-penerimaan-layanan/') }}" + kuartal + "/" + tahun;
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
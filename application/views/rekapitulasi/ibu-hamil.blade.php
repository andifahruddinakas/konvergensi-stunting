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
                        @if ($CI->session->userdata("login")->level == "super_admin")
                        <div class="col-md-3">
                            <div class="form-group">                                
                                <select name="id_posyandu" id="id_posyandu" required class="form-control" title="Pilih salah satu">
                                    <option value="">Semua</option>
                                    @foreach ($posyandu as $item)
                                        <option value="{{ $item->id_posyandu }}" {{ $item->id_posyandu  == $id_posyandu ? "selected" : "" }}>{{ $item->nama_posyandu }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-2 no-padding">
                            <button type="button" class="btn col-md-12 btn-primary" id="cari">
                                <i class="fa fa-search"></i> Cari
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3 no-padding pull-right">
                        <a href="{{ base_url('rekapitulasi/export-ibu-hamil/') . $kuartal .'/' . $_tahun . '/' . $id_posyandu }}" id="btnExport" 
                        type="button" class="btn pull-right col-md-6  btn-danger">
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
                        <th rowspan="3" class="text-center" style="vertical-align: middle;">Nama Ibu</th>
                        <th rowspan="3" class="text-center" style="vertical-align: middle;">Status Kehamilan</th>                    
                        <th colspan="10" style="vertical-align: middle;">Kuartal Ke {{ $kuartal }} Bulan {{ bulan($batasBulanBawah) }} s/d {{ bulan($batasBulanAtas) }} {{ $_tahun }}</th>
                        <th colspan="3" rowspan="2" class="text-center" style="vertical-align: middle;">Tingkat Konvergensi Indikator</th>                        
                    </tr>
                    <tr>
                        <th colspan="2" class="text-center" style="vertical-align: middle;">Usia Kehamilan dan Persalinan</th>
                        <th colspan="8" class="text-center" style="vertical-align: middle;">Status Penerimaan Indikator</th>
                    </tr>
                    <tr>
                        <th class="text-center" style="vertical-align: middle;">Usia Kehamilan (Bulan)</th>
                        <th class="text-center" style="vertical-align: middle;">Tanggal Melahirkan</th>
                        <th class="text-center" style="vertical-align: middle;">Pemeriksaan Kehamilan (bulan)</th>
                        <th class="text-center" style="vertical-align: middle;">Dapat & Konsumsi Pil Fe</th>
                        <th class="text-center" style="vertical-align: middle;">Pemeriksaan Nifas</th>
                        <th class="text-center" style="vertical-align: middle;">Konseling Gizi (Kelas IH)</th>
                        <th class="text-center" style="vertical-align: middle;">Kunjungan Rumah</th>
                        <th class="text-center" style="vertical-align: middle;">Kepemilikan Akses Air Bersih</th>
                        <th class="text-center" style="vertical-align: middle;">Kepemilikan jamban</th>
                        <th class="text-center" style="vertical-align: middle;">Jaminan Kesehatan</th>
                        <th class="text-center" style="vertical-align: middle;">Jumlah Diterima Lengkap</th>
                        <th class="text-center" style="vertical-align: middle;">Jumlah Seharusnya</th>
                        <th class="text-center" style="vertical-align: middle;">%</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if (!$dataFilter)
                            <tr>
                                <td class="text-center" style="vertical-align: middle;" colspan="17">Data Tidak Ditemukan!</td>
                            </tr>
                        @else
                            @foreach ($dataFilter as $item)
                            {{-- {{ die(json_encode($item[1]['no_kia'])) }} --}}                            
                                <tr>
                                    <td class="text-center" style="vertical-align: middle;">{{ $loop->iteration }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item['user']['no_kia'] }}</td>
                                    <td style="vertical-align: middle;">{{ $item['user']['nama_ibu'] }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item['user']['status_kehamilan'] }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item['user']['usia_kehamilan'] }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item['user']['tanggal_melahirkan'] == '-' ? $item['user']['tanggal_melahirkan'] : shortdate_indo($item['user']['tanggal_melahirkan']) }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item['indikator']['periksa_kehamilan'] }}</td>                                                                        
                                    <td class="text-center" style="vertical-align: middle;">{{ $item['indikator']['pil_fe'] }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item['indikator']['pemeriksaan_nifas'] }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item['indikator']['konseling_gizi'] }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item['indikator']['kunjungan_rumah'] }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item['indikator']['akses_air_bersih'] }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item['indikator']['kepemilikan_jamban'] }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item['indikator']['jaminan_kesehatan'] }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item['konvergensi_indikator']['jumlah_diterima_lengkap'] }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item['konvergensi_indikator']['jumlah_seharusnya'] }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item['konvergensi_indikator']['persen'] }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                    @if ($dataFilter)
                    <tfoot>                        
                        <tr>
                            <th colspan="3" rowspan="3" class="text-center" style="vertical-align: middle;">Tingkat Capaian Konvergensi</th>
                            <th colspan="3" class="text-center" style="vertical-align: middle;">Jumlah Diterima</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["periksa_kehamilan"]["Y"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["pil_fe"]["Y"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["pemeriksaan_nifas"]["Y"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["konseling_gizi"]["Y"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["kunjungan_rumah"]["Y"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["akses_air_bersih"]["Y"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["kepemilikan_jamban"]["Y"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["jaminan_kesehatan"]["Y"] }}</th>
                            <th rowspan="3" class="text-center" style="vertical-align: middle;">{{ $tingkatKonvergensiDesa["jumlah_diterima"] }}</th>
                            <th rowspan="3" class="text-center" style="vertical-align: middle;">{{ $tingkatKonvergensiDesa["jumlah_seharusnya"] }}</th>
                            <th rowspan="3" class="text-center" style="vertical-align: middle;">{{ $tingkatKonvergensiDesa["persen"] }}</th>
                        </tr>
                        <tr>
                            <th colspan="3" class="text-center" style="vertical-align: middle;">Jumlah Seharusnya</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["periksa_kehamilan"]["jumlah_seharusnya"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["pil_fe"]["jumlah_seharusnya"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["pemeriksaan_nifas"]["jumlah_seharusnya"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["konseling_gizi"]["jumlah_seharusnya"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["kunjungan_rumah"]["jumlah_seharusnya"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["akses_air_bersih"]["jumlah_seharusnya"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["kepemilikan_jamban"]["jumlah_seharusnya"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["jaminan_kesehatan"]["jumlah_seharusnya"] }}</th>
                        </tr>
                        <tr>
                            <th colspan="3" class="text-center" style="vertical-align: middle;">%</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["periksa_kehamilan"]["persen"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["pil_fe"]["persen"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["pemeriksaan_nifas"]["persen"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["konseling_gizi"]["persen"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["kunjungan_rumah"]["persen"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["akses_air_bersih"]["persen"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["kepemilikan_jamban"]["persen"] }}</th>
                            <th class="text-center" style="vertical-align: middle;">{{ $capaianKonvergensi["jaminan_kesehatan"]["persen"] }}</th>
                        </tr>
                    </tfoot> 
                    @endif                    
                </table>
                </div>
            </div>
        </div>
      </div>

      <div class="modal fade" id="modal-hapus">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title"><b>Peringatan Hapus Data</b></h4>
                </div>
                <form enctype="multipart/form-data" role="form" method="POST" action="{{ base_url('pemantauan/hapus-data') }}">
                    <div class="modal-body">  
                        <b>Peringatan!</b> 
                        <span id="info_hapus">Kamu akan menghapus data Rafli Firdausy</span> <br>
                        <span>Data yang di hapus tidak dapat di kembalikan. Tetap hapus ?</span>
                        <input type="hidden" name="id_ibu_hamil" id="idIbuHamil">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <input type="submit" name="submit" value="Hapus" class="btn btn-danger">
                    </div>
                </form>
              </div>
            </div>
        </div>

        <div class="modal fade" id="modal-input-edit-data">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 id="modalTitle" class="modal-title"></h4>
                    </div>
                    <form id="form_tambah_edit" enctype="multipart/form-data" role="form" method="POST">
                        <div class="modal-body">    
                            <div class="form-group">
                                <label class="form-label">No Register KIA</label>
                                <input required type="text" id="no_kia" name="no_kia" class="form-control">  
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nama Ibu</label>
                                <input required type="text" id="nama_ibu" name="nama_ibu" class="form-control">  
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Status Kehamilan</label>
                                        <select id="status_kehamilan" name="status_kehamilan" required class="form-control" title="Pilih salah satu">
                                            <option value="NORMAL">Normal</option>
                                            <option value="RISTI">Risiko Tinggi (Risti)</option>
                                            <option value="KEK">Kekurangan Energi Kronis (KEK)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Hari Perkiraan Lahir</label>
                                        <input required type="date" id="perkiraan_lahir" name="perkiraan_lahir" class="form-control">  
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Usia Kehamilan (Bulan)</label>
                                        <input required type="number" min="0" id="usia_kehamilan" name="usia_kehamilan" class="form-control">  
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Tanggal Melahirkan (Boleh Tidak Diisi)</label>
                                        <input type="date" id="tanggal_melahirkan" name="tanggal_melahirkan" class="form-control">  
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Pemeriksaan kehamilan</label>
                                        <select id="pemeriksaan_kehamilan" name="pemeriksaan_kehamilan" required class="form-control" title="Pilih salah satu">
                                            <option value="v">Ya</option>
                                            <option value="x">Tidak</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Dapat & Konsumsi Pil Fe</label>
                                        <select id="pil_fe" name="pil_fe" required class="form-control" title="Pilih salah satu">
                                            <option value="v">Ya</option>
                                            <option value="x">Tidak</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Pemeriksaan Nifas</label>
                                        <select id="pemeriksaan_nifas" name="pemeriksaan_nifas" required class="form-control" title="Pilih salah satu">
                                            <option value="v">Ya</option>
                                            <option value="x">Tidak</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Konseling Gizi (Kelas IH)</label>
                                        <select id="konseling_gizi" name="konseling_gizi" required class="form-control" title="Pilih salah satu">
                                            <option value="v">Ya</option>
                                            <option value="x">Tidak</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Kunjungan Rumah</label>
                                        <select id="kunjungan_rumah" name="kunjungan_rumah" required class="form-control" title="Pilih salah satu">
                                            <option value="v">Ya</option>
                                            <option value="x">Tidak</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Kepemilikan Akses Air Bersih</label>
                                        <select id="air_bersih" name="air_bersih" required class="form-control" title="Pilih salah satu">
                                            <option value="v">Ya</option>
                                            <option value="x">Tidak</option>
                                        </select>                                
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Kepemilikan Jamban</label>
                                        <select id="kepemilikan_jamban" name="kepemilikan_jamban" required class="form-control" title="Pilih salah satu">
                                            <option value="v">Ya</option>
                                            <option value="x">Tidak</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Jaminan Kesehatan</label>
                                        <select id="jaminan_kesehatan" name="jaminan_kesehatan" required class="form-control" title="Pilih salah satu">
                                            <option value="v">Ya</option>
                                            <option value="x">Tidak</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="id_ibu_hamil" id="id_ibu_hamil">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                            <input type="submit" name="submit" value="Simpan" class="btn btn-primary">
                        </div>
                    </form>
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
        let posyandu    = $('#id_posyandu option:selected').val();
        window.location.href = "{{ base_url('rekapitulasi/ibu-hamil/') }}" + kuartal + "/" + tahun + "/" + posyandu;
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
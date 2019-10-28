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
                        <div class="col-md-3">
                            <div class="form-group">                                
                                <select name="bulan" id="bulan" required class="form-control" title="Pilih salah satu">
                                    @foreach (bulan_array() as $item)
                                        <option value="{{ $item['urut'] }}" {{ $item['nama_panjang'] == $bulan ? "selected" : "" }} >{{ $item['nama_panjang'] }}</option>
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
                        <button id="btn_input" type="button" class="btn col-md-6 btn-primary" data-toggle="modal" data-target="#modal-input-edit-data">
                            Input Data
                        </button>
                        <a href="{{ base_url('pemantauan/export-bulanan-anak/') . $_bulan .'/' . $_tahun }}" id="btnExport" type="button" class="btn col-md-6  btn-danger">
                            Export ke Excel
                        </a>                        
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive">
                <table  id="table-datas" class="table  table-bordered table-striped table-responsive">
                    <thead>
                    <tr>
                        <th rowspan="4" class="text-center" style="vertical-align: middle;">No</th>
                        <th rowspan="4" class="text-center" style="vertical-align: middle;">NO KIA</th>
                        <th rowspan="4" class="text-center" style="vertical-align: middle;">Nama Anak</th>
                        <th rowspan="4" class="text-center" style="vertical-align: middle;">Jenis Kelamin</th>
                        <th rowspan="4" class="text-center" style="vertical-align: middle;">Tanggal Lahir Anak</th>
                        <th rowspan="4" class="text-center" style="vertical-align: middle;">Status Gizi Anak</th>
                        <th colspan="13" style="vertical-align: middle;">Bulan : {{ $bulan }} {{ $_tahun }}</th>
                        <th rowspan="4" class="text-center" style="vertical-align: middle;">Pilihan Aksi</th>
                    </tr>
                    <tr>
                        <th colspan="2" class="text-center" style="vertical-align: middle;">Umur dan Status Tikar</th>
                        <th colspan="11" class="text-center" style="vertical-align: middle;">Indikator Layanan</th>
                    </tr>
                    <tr>
                        <th rowspan="2" class="text-center" style="vertical-align: middle;">Umur (Bulan)</th>
                        <th rowspan="2" class="text-center" style="vertical-align: middle;">Hasil (M/K/H)</th>
                        <th rowspan="2" class="text-center" style="vertical-align: middle;">Pemberian Imunisasi Dasar</th>
                        <th rowspan="2" class="text-center" style="vertical-align: middle;">Pengukuran Berat Badan</th>
                        <th rowspan="2" class="text-center" style="vertical-align: middle;">Pengukuran Tinggi Badan</th>
                        <th colspan="2" class="text-center" style="vertical-align: middle;">Konseling Gizi Bagi Orang Tua</th>
                        <th rowspan="2" class="text-center" style="vertical-align: middle;">Kunjungan Rumah</th>
                        <th rowspan="2" class="text-center" style="vertical-align: middle;">Kepemilikan Akses Air Bersih</th>
                        <th rowspan="2" class="text-center" style="vertical-align: middle;">Kepemilikan jamban Sehat</th>
                        <th rowspan="2" class="text-center" style="vertical-align: middle;">Akta Lahir</th>
                        <th rowspan="2" class="text-center" style="vertical-align: middle;">Jaminan Kesehatan</th>
                        <th rowspan="2" class="text-center" style="vertical-align: middle;">Pengasuhan (PAUD)</th>
                    </tr>
                    <tr>
                        <th class="text-center" style="vertical-align: middle;">Ayah</th>
                        <th class="text-center" style="vertical-align: middle;">Ibu</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if (sizeof($bulananAnak) < 1)
                            <tr>
                                <td class="text-center" style="vertical-align: middle;" colspan="20">Data Tidak Ditemukan!</td>
                            </tr>
                        @else
                            @foreach ($bulananAnak as $item)
                                <tr>
                                    <td class="text-center" style="vertical-align: middle;">{{ $loop->iteration }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->no_kia }}</td>
                                    <td style="vertical-align: middle;">{{ $item->nama_anak }}</td>                                        
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->jenis_kelamin_anak }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ shortdate_indo($item->tanggal_lahir_anak) }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->status_gizi }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->umur_bulan }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->status_tikar == "TD" ? "-" : $item->status_tikar }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->pemberian_imunisasi_dasar }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->pengukuran_berat_badan }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->pengukuran_tinggi_badan }}</td>                                        
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->konseling_gizi_ayah }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->konseling_gizi_ibu }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->kunjungan_rumah }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->air_bersih }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->kepemilikan_jamban }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->akta_lahir }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->jaminan_kesehatan }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->pengasuhan_paud }}</td>
                                    <td>
                                        <button 
                                                data-id="{{ $item->id_bulanan_anak }}" 
                                                data-no_kia="{{ $item->no_kia }}" 
                                                data-nama_anak="{{ $item->nama_anak }}" 
                                                data-jenis_kelamin_anak = "{{ $item->jenis_kelamin_anak }}"
                                                data-tanggal_lahir_anak="{{ $item->tanggal_lahir_anak }}" 
                                                data-status_gizi="{{ $item->status_gizi }}" 
                                                data-umur_bulan="{{ $item->umur_bulan }}" 
                                                data-status_tikar = "{{ $item->status_tikar }}"
                                                data-pemberian_imunisasi_dasar = "{{ $item->pemberian_imunisasi_dasar }}"
                                                data-pengukuran_berat_badan = "{{ $item->pengukuran_berat_badan }}"
                                                data-pengukuran_tinggi_badan = "{{ $item->pengukuran_tinggi_badan }}"
                                                data-konseling_gizi_ayah = "{{ $item->konseling_gizi_ayah }}"
                                                data-konseling_gizi_ibu = "{{ $item->konseling_gizi_ibu }}"
                                                data-kunjungan_rumah = "{{ $item->kunjungan_rumah }}"
                                                data-air_bersih = "{{ $item->air_bersih }}"
                                                data-kepemilikan_jamban = "{{ $item->kepemilikan_jamban }}"
                                                data-akta_lahir = "{{ $item->akta_lahir }}"
                                                data-jaminan_kesehatan = "{{ $item->jaminan_kesehatan }}"
                                                data-pengasuhan_paud = "{{ $item->pengasuhan_paud }}"
                                                data-toggle="modal" 
                                                data-target="#modal-input-edit-data" 
                                                title="Edit" 
                                                type="button" 
                                                class="editData btn btn-primary col-xs-12">Edit</button>
                                            <button 
                                                data-id="{{ $item->id_bulanan_anak }}" 
                                                data-nama="{{ $item->nama_anak }}" 
                                                data-toggle="modal" 
                                                data-target="#modal-hapus" 
                                                type="button" 
                                                class="hapusData btn btn-danger col-xs-12">Hapus</button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
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
                <form enctype="multipart/form-data" role="form" method="POST" action="{{ base_url('pemantauan/hapus-data-bulanan-anak') }}">
                    <div class="modal-body">  
                        <b>Peringatan!</b> 
                        <span id="info_hapus">Kamu akan menghapus data Rafli Firdausy</span> <br>
                        <span>Data yang di hapus tidak dapat di kembalikan. Tetap hapus ?</span>
                        <input type="hidden" name="id_bulanan_anak" id="idBulananAnak">
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
                                <label class="form-label">Nama Anak</label>
                                <input required type="text" id="nama_anak" name="nama_anak" class="form-control">  
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Jenis Kelamin Anak</label>
                                        <select id="jenis_kelamin_anak" name="jenis_kelamin_anak" required class="form-control" title="Pilih salah satu">
                                            <option value="L">Laki - Laki (L)</option>
                                            <option value="P">Perempuan (P)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Tanggal Lahir Anak</label>
                                        <input required type="date" id="tanggal_lahir_anak" name="tanggal_lahir_anak" class="form-control">  
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Status Gizi Anak</label>
                                        <select id="status_gizi" name="status_gizi" required class="form-control" title="Pilih salah satu">
                                            <option value="N">Sehat / Normal (N)</option>
                                            <option value="GK">Gizi Kurang (GK)</option>
                                            <option value="GB">Gizi Buruk (GB)</option>
                                            <option value="S">Stunting (S)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Umur (Bulan)</label>
                                        <input required type="number" min="0" max="24" id="umur_bulan" name="umur_bulan" class="form-control">  
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Hasil Status Tikar</label>
                                        <select id="status_tikar" name="status_tikar" required class="form-control" title="Pilih salah satu">
                                            <option value="TD">Tidak Diukur</option>
                                            <option value="M">Merah (M)</option>
                                            <option value="K">Kuning (K)</option>
                                            <option value="H">Hijau (H)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Pemberian Imunisasi Dasar</label>
                                        <select id="pemberian_imunisasi_dasar" name="pemberian_imunisasi_dasar" required class="form-control" title="Pilih salah satu">
                                            <option value="v">Ya</option>
                                            <option value="x">Tidak</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Pengukuran Berat Badan</label>
                                        <select id="pengukuran_berat_badan" name="pengukuran_berat_badan" required class="form-control" title="Pilih salah satu">
                                            <option value="v">Ya</option>
                                            <option value="x">Tidak</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Pengukuran Tinggi Badan</label>
                                        <select id="pengukuran_tinggi_badan" name="pengukuran_tinggi_badan" required class="form-control" title="Pilih salah satu">
                                            <option value="v">Ya</option>
                                            <option value="x">Tidak</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Konseling Gizi Orang Tua (L)</label>
                                        <select id="konseling_gizi_ayah" name="konseling_gizi_ayah" required class="form-control" title="Pilih salah satu">
                                            <option value="v">Ya</option>
                                            <option value="x">Tidak</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Konseling Gizi Orang Tua (P)</label>
                                        <select id="konseling_gizi_ibu" name="konseling_gizi_ibu" required class="form-control" title="Pilih salah satu">
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
                                        <label class="form-label">Kepemilikan Jamban Sehat</label>
                                        <select id="kepemilikan_jamban" name="kepemilikan_jamban" required class="form-control" title="Pilih salah satu">
                                            <option value="v">Ya</option>
                                            <option value="x">Tidak</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Akta Lahir</label>
                                        <select id="akta_lahir" name="akta_lahir" required class="form-control" title="Pilih salah satu">
                                            <option value="v">Ya</option>
                                            <option value="x">Tidak</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Jaminan Kesehatan</label>
                                        <select id="jaminan_kesehatan" name="jaminan_kesehatan" required class="form-control" title="Pilih salah satu">
                                            <option value="v">Ya</option>
                                            <option value="x">Tidak</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Pengasuhan (PAUD)</label>
                                        <select id="pengasuhan_paud" name="pengasuhan_paud" required class="form-control" title="Pilih salah satu">
                                            <option value="v">Ya</option>
                                            <option value="x">Tidak</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="id_bulanan_anak" id="id_bulanan_anak">
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

    // EDIIIIIITT
    $("#btn_input").click(function(){
        $("#form_tambah_edit").attr('action', "{{ base_url('pemantauan/bulanan-anak') }}");        
        $("#no_kia").attr('readonly', false);
        $("#nama_anak").attr('readonly', false);

        $("#modalTitle").text("Input Pemantauan Bulanan Anak 0-2 Tahun");

        $("#id_bulanan_anak").val(""); 
        $("#no_kia").val(""); 
        $("#nama_anak").val("");
        $("#jenis_kelamin_anak").val("L");
        $("#tanggal_lahir_anak").val("");
        $("#status_gizi").val("N");
        $("#umur_bulan").val("");
        $("#status_tikar").val("TD");
        $("#pemberian_imunisasi_dasar").val("v");
        $("#pengukuran_berat_badan").val("v");
        $("#pengukuran_tinggi_badan").val("v");
        $("#konseling_gizi_ayah").val("");
        $("#konseling_gizi_ibu").val("");
        $("#kunjungan_rumah").val("v");
        $("#air_bersih").val("v");
        $("#kepemilikan_jamban").val("v");
        $("#akta_lahir").val("v");
        $("#jaminan_kesehatan").val("v");
        $("#pengasuhan_paud").val("v");
    });

    $(".editData").click(function(){
        $("#form_tambah_edit").attr('action', "{{ base_url('pemantauan/edit-bulanan-anak') }}");

        $("#no_kia").attr('readonly', true);
        $("#nama_anak").attr('readonly', true);
        
        let id                          = $(this).data('id');
        let no_kia                      = $(this).data('no_kia');
        let nama_anak                   = $(this).data('nama_anak');
        let jenis_kelamin_anak          = $(this).data('jenis_kelamin_anak');
        let tanggal_lahir_anak          = $(this).data('tanggal_lahir_anak');
        let status_gizi                 = $(this).data('status_gizi');
        let umur_bulan                  = $(this).data('umur_bulan');
        let status_tikar                = $(this).data('status_tikar');
        let pemberian_imunisasi_dasar   = $(this).data('pemberian_imunisasi_dasar');
        let pengukuran_berat_badan      = $(this).data('pengukuran_berat_badan');
        let pengukuran_tinggi_badan     = $(this).data('pengukuran_tinggi_badan');
        let konseling_gizi_ayah         = $(this).data('konseling_gizi_ayah');
        let konseling_gizi_ibu          = $(this).data('konseling_gizi_ibu');
        let kunjungan_rumah             = $(this).data('kunjungan_rumah');
        let air_bersih                  = $(this).data('air_bersih');
        let kepemilikan_jamban          = $(this).data('kepemilikan_jamban');
        let akta_lahir                  = $(this).data('akta_lahir');
        let jaminan_kesehatan           = $(this).data('jaminan_kesehatan');
        let pengasuhan_paud             = $(this).data('pengasuhan_paud');
        
        $("#no_kia").attr('readonly', true);
        $("#nama_anak").attr('readonly', true);
        $("#modalTitle").text("Edit Pemantauan Bulanan Anak 0-2 Tahun");

        $("#id_bulanan_anak").val(id); 
        $("#no_kia").val(no_kia); 
        $("#nama_anak").val(nama_anak);
        $("#jenis_kelamin_anak").val(jenis_kelamin_anak);
        $("#tanggal_lahir_anak").val(tanggal_lahir_anak);
        $("#status_gizi").val(status_gizi);
        $("#umur_bulan").val(umur_bulan);
        $("#status_tikar").val(status_tikar);
        $("#pemberian_imunisasi_dasar").val(pemberian_imunisasi_dasar);
        $("#pengukuran_berat_badan").val(pengukuran_berat_badan);
        $("#pengukuran_tinggi_badan").val(pengukuran_tinggi_badan);
        $("#konseling_gizi_ayah").val(konseling_gizi_ayah);
        $("#konseling_gizi_ibu").val(konseling_gizi_ibu);
        $("#kunjungan_rumah").val(kunjungan_rumah);
        $("#air_bersih").val(air_bersih);
        $("#kepemilikan_jamban").val(kepemilikan_jamban);
        $("#akta_lahir").val(akta_lahir);
        $("#jaminan_kesehatan").val(jaminan_kesehatan);
        $("#pengasuhan_paud").val(pengasuhan_paud);

    });


    $(".hapusData").click(function(){
        let id      = $(this).data('id');
        let nama    = $(this).data('nama');
    
        $("#info_hapus").text("Kamu akan menghapus data " + nama);
        $("#idBulananAnak").val(id);
    });

    function delay(callback, ms) {
        var timer = 0;
        return function() {
            var context = this, args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function () {
            callback.apply(context, args);
            }, ms || 0);
        };
    }

    $('#no_kia').keyup(delay(function (e) {
        $.ajax({
            type: 'GET',
            url: '{{ base_url("pemantauan/getDataByNoKia/") }}' + this.value,
            dataType: 'json',
            success: function(x){
                if(x.status == 1){
                    $('#nama_anak').val(x.data.nama_anak);
                    $('#jenis_kelamin').val(x.data.jenis_kelamin_anak);
                    $('#tanggal_lahir_anak').val(x.data.tanggal_lahir_anak);
                } else {
                    $('#nama_anak').val("");                    
                    $('#jenis_kelamin').val("");
                    $('#tanggal_lahir_anak').val("");
                }
            }
        });
    }, 500));


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
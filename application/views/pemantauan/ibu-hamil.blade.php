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
            @php
                $CI = &get_instance();
            @endphp

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
                                        <option value="{{ $item->tahun }}" {{ $item->tahun == $_tahun ? "selected" : "" }}>{{ $item->tahun }}</option>
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
                        @if ($CI->session->userdata("login")->level !== "super_admin")
                        <button id="btn_input" type="button" class="btn col-md-6 btn-primary" data-toggle="modal" data-target="#modal-input-edit-data">
                            Input Data
                        </button>
                        @endif
                        <a href="{{ base_url('pemantauan/export-ibu-hamil/') . $_bulan .'/' . $_tahun . '/' . $id_posyandu }}" id="btnExport" 
                        type="button" class="btn col-md-{{ $CI->session->userdata("login")->level !== "super_admin" ? "6" : "12"}}  btn-danger">
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
                        <th rowspan="3" class="text-center" style="vertical-align: middle;">Hari Perkiraan Lahir</th>
                        <th colspan="10" style="vertical-align: middle;">Bulan : {{ $bulan }} {{ $_tahun }}</th>
                        @if ($CI->session->userdata("login")->level !== "super_admin")
                        <th rowspan="3" class="text-center" style="vertical-align: middle;">Pilihan Aksi</th>
                        @endif                        
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
                    </tr>
                    </thead>
                    <tbody>
                        @if (sizeof($ibuHamil) < 1)
                            <tr>
                                <td class="text-center" style="vertical-align: middle;" colspan="16">Data Tidak Ditemukan!</td>
                            </tr>
                        @else
                            @foreach ($ibuHamil as $item)
                                <tr>
                                    <td class="text-center" style="vertical-align: middle;">{{ $loop->iteration }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->no_kia }}</td>
                                    <td style="vertical-align: middle;">{{ $item->nama_ibu }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->status_kehamilan }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->hari_perkiraan_lahir == NULL ?  "-" : shortdate_indo($item->hari_perkiraan_lahir) }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->usia_kehamilan }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->tanggal_melahirkan == NULL ? "-" : shortdate_indo($item->tanggal_melahirkan) }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->pemeriksaan_kehamilan }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->konsumsi_pil_fe }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->pemeriksaan_nifas }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->konseling_gizi }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->kunjungan_rumah }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->akses_air_bersih }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->kepemilikan_jamban }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->jaminan_kesehatan }}</td>
                                    @if ($CI->session->userdata("login")->level !== "super_admin")
                                    <td>
                                        <button data-id="{{ $item->id_ibu_hamil }}" 
                                            data-no_kia="{{ $item->no_kia }}" 
                                            data-nama="{{ $item->nama_ibu }}" 
                                            data-status_kehamilan = "{{ $item->status_kehamilan }}"
                                            data-perkiraan="{{ $item->hari_perkiraan_lahir }}" 
                                            data-usia_kehamilan="{{ $item->usia_kehamilan }}" 
                                            data-melahirkan="{{ $item->tanggal_melahirkan }}" 
                                            data-pemeriksaan_kehamilan = "{{ $item->pemeriksaan_kehamilan }}"
                                            data-konsumsi_pil_fe = "{{ $item->konsumsi_pil_fe }}"
                                            data-butir_pil_fe = "{{ $item->butir_pil_fe }}"
                                            data-pemeriksaan_nifas = "{{ $item->pemeriksaan_nifas }}"
                                            data-konseling_gizi = "{{ $item->konseling_gizi }}"
                                            data-kunjungan_rumah = "{{ $item->kunjungan_rumah }}"
                                            data-akses_air_bersih = "{{ $item->akses_air_bersih }}"
                                            data-kepemilikan_jamban = "{{ $item->kepemilikan_jamban }}"
                                            data-jaminan_kesehatan = "{{ $item->jaminan_kesehatan }}"
                                            data-toggle="modal" data-target="#modal-input-edit-data"
                                            title="Edit" 
                                            type="button" 
                                            class="editData btn btn-primary col-xs-12">Edit</button>
                                        <button data-id="{{ $item->id_ibu_hamil }}" data-nama="{{ $item->nama_ibu }}" data-toggle="modal" data-target="#modal-hapus" type="button" class="hapusData btn btn-danger col-xs-12">Hapus</button>
                                    </td>
                                    @endif
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
                <form enctype="multipart/form-data" role="form" method="POST" action="{{ base_url('pemantauan/hapus-data-ibu-hamil') }}">
                    <div class="modal-body">  
                        <b>Peringatan!</b> 
                        <span id="info_hapus">Kamu akan menghapus data KPM Stunting</span> <br>
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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Pemeriksaan kehamilan</label>
                                        <select id="pemeriksaan_kehamilan" name="pemeriksaan_kehamilan" required class="form-control" title="Pilih salah satu">
                                            <option value="v">Ya</option>
                                            <option value="x">Tidak</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Dapat & Konsumsi Pil Fe</label>
                                        <select id="pil_fe" name="pil_fe" required class="form-control" title="Pilih salah satu">
                                            <option value="v">Ya</option>
                                            <option value="x">Tidak</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Berapa butir pil Fe</label>
                                        <input required type="number" min="1" id="butir_pil_fe" disabled name="butir_pil_fe" class="form-control">  
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

    $("#pil_fe").change(function(){
        // alert()
        $('#butir_pil_fe').val(null);
        if($(this).val() == 'v'){
            $('#butir_pil_fe').prop("disabled", false);
        } else {
            $('#butir_pil_fe').prop("disabled", true);
        }
    });

    // EDIIIIIITT
    $("#btn_input").click(function(){
        $("#form_tambah_edit").attr('action', "{{ base_url('pemantauan/ibu-hamil') }}");        
        $("#no_kia").attr('readonly', false);
        $("#nama_ibu").attr('readonly', false);

        $("#modalTitle").text("Input Pemantauan Bulanan Ibu Hamil");

        $("#id_ibu_hamil").val(null); 
        $("#no_kia").val(null); 
        $("#nama_ibu").val(null);
        $("#status_kehamilan").val(null);
        $("#perkiraan_lahir").val(null);
        $('#perkiraan_lahir').prop("disabled", false);
        $("#usia_kehamilan").val(null);
        $("#tanggal_melahirkan").val(null);
        $("#pemeriksaan_kehamilan").val(null);
        $("#pil_fe").val(null);
        $("#pemeriksaan_nifas").val(null);
        $("#konseling_gizi").val(null);
        $("#kunjungan_rumah").val(null);
        $("#air_bersih").val(null);
        $("#kepemilikan_jamban").val(null);
        $("#jaminan_kesehatan").val(null);
    });

    $(".editData").click(function(){
        $("#form_tambah_edit").attr('action', "{{ base_url('pemantauan/edit-ibu-hamil') }}");

        let id                      = $(this).data('id');
        let no_kia                  = $(this).data('no_kia');
        let nama_ibu                = $(this).data('nama');
        let status_kehamilan        = $(this).data('status_kehamilan');
        let hari_perkiraan_lahir    = $(this).data('perkiraan');
        let usia_kehamilan          = $(this).data('usia_kehamilan');
        let tanggal_melahirkan      = $(this).data('melahirkan');
        let pemeriksaan_kehamilan   = $(this).data('pemeriksaan_kehamilan');
        let konsumsi_pil_fe         = $(this).data('konsumsi_pil_fe');
        let butir_pil_fe            = $(this).data('butir_pil_fe');
        let pemeriksaan_nifas       = $(this).data('pemeriksaan_nifas');
        let konseling_gizi          = $(this).data('konseling_gizi');
        let kunjungan_rumah         = $(this).data('kunjungan_rumah');
        let akses_air_bersih        = $(this).data('akses_air_bersih');
        let kepemilikan_jamban      = $(this).data('kepemilikan_jamban');
        let jaminan_kesehatan       = $(this).data('jaminan_kesehatan');

        if(konsumsi_pil_fe == 'v'){
            $('#butir_pil_fe').prop("disabled", false);
        } else {
            $('#butir_pil_fe').prop("disabled", true);
        }


        $("#no_kia").attr('readonly', true);
        $("#nama_ibu").attr('readonly', true);

        $("#modalTitle").text("Edit Pemantauan Bulanan Ibu Hamil");

        $("#id_ibu_hamil").val(id); 
        $("#no_kia").val(no_kia); 
        $("#nama_ibu").val(nama_ibu);
        $("#status_kehamilan").val(status_kehamilan);
        $("#perkiraan_lahir").val(hari_perkiraan_lahir);
        $('#perkiraan_lahir').prop("disabled", true);

        $("#usia_kehamilan").val(usia_kehamilan);
        $("#tanggal_melahirkan").val(tanggal_melahirkan);
        $("#pemeriksaan_kehamilan").val(pemeriksaan_kehamilan);
        $("#pil_fe").val(konsumsi_pil_fe);
        $("#butir_pil_fe").val(butir_pil_fe);
        $("#pemeriksaan_nifas").val(pemeriksaan_nifas);
        $("#konseling_gizi").val(konseling_gizi);
        $("#kunjungan_rumah").val(kunjungan_rumah);
        $("#air_bersih").val(akses_air_bersih);
        $("#kepemilikan_jamban").val(kepemilikan_jamban);
        $("#jaminan_kesehatan").val(jaminan_kesehatan);

    });


    $(".hapusData").click(function(){
        let id      = $(this).data('id');
        let nama    = $(this).data('nama');
    
        $("#info_hapus").text("Kamu akan menghapus data " + nama);
        $("#idIbuHamil").val(id);
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
                    $('#nama_ibu').val(x.data.nama_ibu);           
                    $('#perkiraan_lahir').val(x.data.hari_perkiraan_lahir)                    
                } else {
                    $('#nama_ibu').val(null);
                    $("#perkiraan_lahir").val(null);
                }
            }
        });
    }, 500));


    $(function () {

        $('#cari').click(function(){
            let bulan       = $('#bulan option:selected').val();
            let tahun       = $('#tahun option:selected').val();
            let posyandu    = $('#id_posyandu option:selected').val();
            window.location.href = "{{ base_url('pemantauan/ibu-hamil/') }}" + bulan + "/" + tahun + "/" + posyandu;
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
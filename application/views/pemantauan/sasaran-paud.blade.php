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
                        @if ($CI->session->userdata("login")->level !== "super_admin")
                        <button id="btn_input" type="button" class="btn col-md-6 btn-primary" data-toggle="modal" data-target="#modal-input-edit-data">
                            Input Data
                        </button>
                        @endif
                        <a href="{{ base_url('pemantauan/export-sasaran-paud/') . $_tahun . '/' . $id_posyandu }}" id="btnExport" 
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
                        <th rowspan="4" colspan="1" class="text-center" style="vertical-align: middle;">No</th>
                        <th rowspan="4" colspan="1" class="text-center" style="vertical-align: middle;">Nomor Rumah Tangga</th>
                        <th rowspan="4" colspan="1" class="text-center" style="vertical-align: middle;">Nama Anak</th>
                        <th rowspan="4" colspan="1" class="text-center" style="vertical-align: middle;">Jenis Kelamin (L/P)</th>
                        <th rowspan="2" colspan="2" class="text-center" style="vertical-align: middle;">Usia Menurut Kategori</th>
                        <th rowspan="1" colspan="12" class="text-center" style="vertical-align: middle;">Pada Bulan Ini Apakah Anak Mendapatkan Pelayanan PAUD</th>
                        @if ($CI->session->userdata("login")->level !== "super_admin")
                        <th rowspan="4" colspan="1" class="text-center" style="vertical-align: middle;">Aksi</th>
                        @endif
                    </tr>
                    <tr>
                        <th rowspan="1" colspan="12" class="text-center" style="vertical-align: middle;">Mengikuti Layanan PAUD (Parenting Bagi Orang Tua Anak Usia 2 - < 3 Tahun) Atau Kelas PAUD Bagi Anak 3 - 6 Tahun</th>
                    </tr>
                    <tr>
                        <th rowspan="2" colspan="1" class="text-center" style="vertical-align: middle;">Anak Usia 2 - < 3 Tahun</th>
                        <th rowspan="2" colspan="1" class="text-center" style="vertical-align: middle;">Anak Usia 3 - 6 Tahun</th>
                        <th rowspan="1" colspan="12" class="text-center" style="vertical-align: middle;">Tahun : {{ $_tahun }}</th>
                    </tr>
                    <tr>
                        @foreach (bulan_array() as $item)
                            <th rowspan="1" colspan="1" class="text-center" style="vertical-align: middle;">{{ $item['nama_panjang'] }}</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                        @if (sizeof($dataSasaranPaud) > 0)
                            @foreach ($dataSasaranPaud as $item)
                                <tr>
                                    <td class="text-center" style="vertical-align: middle;">{{ $loop->iteration }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->no_rt }}</td>
                                    <td style="vertical-align: middle;">{{ $item->nama_anak }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->jenis_kelamin }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->usia_menurut_kategori == 'a' ? 'v' : '-' }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->usia_menurut_kategori == 'b' ? 'v' : '-' }}</td>

                                    <td class="text-center" style="vertical-align: middle;">{{ $item->januari == "belum" ? "-" : $item->januari }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->februari == "belum" ? "-" : $item->februari }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->maret == "belum" ? "-" : $item->maret }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->april == "belum" ? "-" : $item->april }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->mei == "belum" ? "-" : $item->mei }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->juni == "belum" ? "-" : $item->juni }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->juli == "belum" ? "-" : $item->juli }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->agustus == "belum" ? "-" : $item->agustus }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->september == "belum" ? "-" : $item->september }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->oktober == "belum" ? "-" : $item->oktober }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->november == "belum" ? "-" : $item->november }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ $item->desember == "belum" ? "-" : $item->desember }}</td>
                                    @if ($CI->session->userdata("login")->level !== "super_admin")
                                    <td class="text-center" style="vertical-align: middle;">
                                        <button                                             
                                            data-toggle="modal" 
                                            data-target="#modal-input-edit-data" 
                                            data-id_sasaran_paud = "{{ $item->id_sasaran_paud }}"
                                            data-no_rt = "{{ $item->no_rt }}"
                                            data-nama_anak = "{{ $item->nama_anak }}"
                                            data-jenis_kelamin_anak = "{{ $item->jenis_kelamin }}"
                                            data-usia_menurut_kategori = "{{ $item->usia_menurut_kategori }}"
                                            data-januari = "{{ $item->januari }}"
                                            data-februari = "{{ $item->februari }}"
                                            data-maret = "{{ $item->maret }}"
                                            data-april = "{{ $item->april }}"
                                            data-mei = "{{ $item->mei }}"
                                            data-juni = "{{ $item->juni }}"
                                            data-juli = "{{ $item->juli }}"
                                            data-agustus = "{{ $item->agustus }}"
                                            data-september = "{{ $item->september }}"
                                            data-oktober = "{{ $item->oktober }}"
                                            data-november = "{{ $item->november }}"
                                            data-desember = "{{ $item->desember }}"                                            
                                            title="Edit" 
                                            type="button" 
                                            class="editData btn btn-primary col-xs-12">Edit</button>
                                        <button 
                                            data-id="{{ $item->id_sasaran_paud }}" 
                                            data-nama="{{ $item->nama_anak }}" 
                                            data-toggle="modal" 
                                            data-target="#modal-hapus" 
                                            type="button" 
                                            class="hapusData btn btn-danger col-xs-12">Hapus</button>
                                    </td>
                                    @endif
                                </tr>
                            @endforeach                            
                        @else
                            <tr>
                                <td class="text-center" style="vertical-align: middle;" colspan="19">Data Tidak Ditemukan!</td>
                            </tr>
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
                <form enctype="multipart/form-data" role="form" method="POST" action="{{ base_url('pemantauan/hapus-sasaran-paud') }}">
                    <div class="modal-body">  
                        <b>Peringatan!</b> 
                        <span id="info_hapus">Kamu akan menghapus data Stunting</span> <br>
                        <span>Data yang di hapus tidak dapat di kembalikan. Tetap hapus ?</span>
                        <input type="hidden" name="id_sasaran_paud" id="id_sasaran_paud">
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
                            <div class="row">
                                <div class="col-md-4"> 
                                    <div class="form-group">
                                        <label class="form-label">Nomor Rumah Tangga</label>
                                        <input required type="number" id="no_rt" name="no_rt" class="form-control">  
                                    </div> 
                                </div>
                                <div class="col-md-8"> 
                                    <div class="form-group">
                                        <label class="form-label">Nama Anak</label>
                                        <input required type="text" id="nama_anak" name="nama_anak" class="form-control">  
                                    </div>
                                </div>
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
                                        <label class="form-label">Kategori Usia</label>
                                        <select id="usia_menurut_kategori" name="usia_menurut_kategori" required class="form-control" title="Pilih salah satu">
                                            <option value="a">Anak Usia 2 - < 3 Tahun</option>
                                            <option value="b">Anak Usia 3 - 6 Tahun</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Januari</label>
                                        <select id="januari" name="januari" required class="form-control" title="Pilih salah satu">
                                            <option value="belum">Belum</option>
                                            <option value="v">Mengikuti</option>
                                            <option value="x">Tidak Mengikuti</option> 
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Februari</label>
                                        <select id="februari" name="februari" required class="form-control" title="Pilih salah satu">
                                            <option value="belum">Belum</option>
                                            <option value="v">Mengikuti</option>
                                            <option value="x">Tidak Mengikuti</option> 
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Maret</label>
                                        <select id="maret" name="maret" required class="form-control" title="Pilih salah satu">
                                            <option value="belum">Belum</option>
                                            <option value="v">Mengikuti</option>
                                            <option value="x">Tidak Mengikuti</option> 
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">April</label>
                                        <select id="april" name="april" required class="form-control" title="Pilih salah satu">
                                            <option value="belum">Belum</option>
                                            <option value="v">Mengikuti</option>
                                            <option value="x">Tidak Mengikuti</option> 
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Mei</label>
                                        <select id="mei" name="mei" required class="form-control" title="Pilih salah satu">
                                            <option value="belum">Belum</option>
                                            <option value="v">Mengikuti</option>
                                            <option value="x">Tidak Mengikuti</option> 
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Juni</label>
                                        <select id="juni" name="juni" required class="form-control" title="Pilih salah satu">
                                            <option value="belum">Belum</option>
                                            <option value="v">Mengikuti</option>
                                            <option value="x">Tidak Mengikuti</option> 
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Juli</label>
                                        <select id="juli" name="juli" required class="form-control" title="Pilih salah satu">
                                            <option value="belum">Belum</option>
                                            <option value="v">Mengikuti</option>
                                            <option value="x">Tidak Mengikuti</option> 
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Agustus</label>
                                        <select id="agustus" name="agustus" required class="form-control" title="Pilih salah satu">
                                            <option value="belum">Belum</option>
                                            <option value="v">Mengikuti</option>
                                            <option value="x">Tidak Mengikuti</option> 
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">September</label>
                                        <select id="september" name="september" required class="form-control" title="Pilih salah satu">
                                            <option value="belum">Belum</option>
                                            <option value="v">Mengikuti</option>
                                            <option value="x">Tidak Mengikuti</option> 
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Oktober</label>
                                        <select id="oktober" name="oktober" required class="form-control" title="Pilih salah satu">
                                            <option value="belum">Belum</option>
                                            <option value="v">Mengikuti</option>
                                            <option value="x">Tidak Mengikuti</option> 
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">November</label>
                                        <select id="november" name="november" required class="form-control" title="Pilih salah satu">
                                            <option value="belum">Belum</option>
                                            <option value="v">Mengikuti</option>
                                            <option value="x">Tidak Mengikuti</option> 
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Desember</label>
                                        <select id="desember" name="desember" required class="form-control" title="Pilih salah satu">
                                            <option value="belum">Belum</option>
                                            <option value="v">Mengikuti</option>
                                            <option value="x">Tidak Mengikuti</option>                                            
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="id_sasaran_paud" id="idSasaran">
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

    $("#umur_bulan").change(function(){
        let umur_bulan = $(this).val();
        $('#pemberian_imunisasi_campak').val("");
        if(umur_bulan >= 6){            
            $('#pemberian_imunisasi_campak').prop("disabled", false);
        } else {
            $('#pemberian_imunisasi_campak').prop("disabled", true);
        }
    });

    // EDIIIIIITT
    $("#btn_input").click(function(){
        $("#form_tambah_edit").attr('action', "{{ base_url('pemantauan/sasaran-paud') }}");
        $("#modalTitle").text("Input Layanan dan Sasaran PAUD Anak > 2 - 6 Tahun");

        $("#no_rt").val(null); 
        $("#nama_anak").val(null);         
        $("#jenis_kelamin_anak").val(null);
        $("#usia_menurut_kategori").val(null);
        $("#januari").val("belum");
        $("#februari").val("belum");
        $("#maret").val("belum");
        $("#april").val("belum");
        $("#mei").val("belum");
        $("#juni").val("belum");
        $("#juli").val("belum");
        $("#agustus").val("belum");
        $("#september").val("belum");
        $("#oktober").val("belum");
        $("#november").val("belum");
        $("#desember").val("belum");
    });

    $(".editData").click(function(){
        $("#form_tambah_edit").attr('action', "{{ base_url('pemantauan/edit-sasaran-paud') }}");    
        $("#modalTitle").text("Edit Layanan dan Sasaran PAUD Anak > 2 - 6 Tahun");    

        $("#idSasaran").val($(this).data('id_sasaran_paud')); 
        $("#no_rt").val($(this).data('no_rt')); 
        $("#nama_anak").val($(this).data('nama_anak'));          
        $("#jenis_kelamin_anak").val($(this).data('jenis_kelamin_anak')); 
        $("#usia_menurut_kategori").val($(this).data('usia_menurut_kategori')); 
        $("#januari").val($(this).data('januari')); 
        $("#februari").val($(this).data('februari')); 
        $("#maret").val($(this).data('maret')); 
        $("#april").val($(this).data('april')); 
        $("#mei").val($(this).data('mei')); 
        $("#juni").val($(this).data('juni')); 
        $("#juli").val($(this).data('juli')); 
        $("#agustus").val($(this).data('agustus')); 
        $("#september").val($(this).data('september')); 
        $("#oktober").val($(this).data('oktober')); 
        $("#november").val($(this).data('november')); 
        $("#desember").val($(this).data('desember'));                    

    });


    $(".hapusData").click(function(){
        let id      = $(this).data('id');
        let nama    = $(this).data('nama');
    
        $("#info_hapus").text("Kamu akan menghapus data " + nama);
        $("#id_sasaran_paud").val(id);
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
                    $('#jenis_kelamin_anak').val(x.data.jenis_kelamin_anak);
                    $('#tanggal_lahir_anak').val(x.data.tanggal_lahir_anak);
                } else {
                    $('#nama_anak').val("");                    
                    $('#jenis_kelamin_anak').val("");
                    $('#tanggal_lahir_anak').val("");
                }
            }
        });
    }, 500));


    $(function () {

        $('#cari').click(function(){            
            let tahun = $('#tahun option:selected').val();
            let posyandu    = $('#id_posyandu option:selected').val();
            window.location.href = "{{ base_url('pemantauan/sasaran-paud/') }}" + tahun + "/" + posyandu;
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
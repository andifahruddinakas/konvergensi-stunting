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
                <div class="col-md-2 no-padding pull-right">
                    <button id="btn_input" type="button" class="btn col-md-12 btn-primary" data-toggle="modal" data-target="#modal-input-edit-data">
                        Tambah Posyandu
                    </button>                     
                </div>
            </div>

            <div class="box-body table-responsive">
                <table  id="table-data" class="table  table-bordered table-striped table-responsive">
                    <thead>                        
                        <tr>
                            <th class="text-center" width="5%" style="vertical-align: middle;">No</th>
                            <th class="text-center" style="vertical-align: middle;">Nama KPM</th>
                            <th class="text-center" style="vertical-align: middle;">Username</th>
                            <th class="text-center" style="vertical-align: middle;">Posyandu</th>
                            <th class="text-center" width="20%" style="vertical-align: middle;">Pilihan Aksi</th>                            
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kpm as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $item->nama_lengkap}}</td>
                                <td>{{ $item->username }}</td>
                                <td>{{ $item->nama_posyandu }}</td>
                                <td>
                                    <button 
                                        data-id="{{ $item->id_user }}" 
                                        data-nama_lengkap="{{ $item->nama_lengkap }}" 
                                        data-username="{{ $item->username }}"                                        
                                        data-posyandu="{{ $item->id_posyandu }}"   
                                        data-toggle="modal" 
                                        data-target="#modal-input-edit-data" 
                                        title="Edit" 
                                        type="button" 
                                        class="editData btn btn-primary col-xs-6">Edit</button>
                                    <button 
                                        data-id="{{ $item->id_user }}" 
                                        data-nama_lengkap="{{ $item->nama_lengkap }}" 
                                        data-toggle="modal" 
                                        data-target="#modal-hapus" 
                                        type="button" 
                                        class="hapusData btn btn-danger col-xs-6">Hapus</button>
                                </td>
                            </tr>
                        @endforeach                        
                    </tbody>
                    <tfoot>
                       
                    </tfoot>
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
        <form enctype="multipart/form-data" role="form" method="POST" action="{{ base_url('pengaturan/hapus-kpm') }}">
            <div class="modal-body">  
                <b>Peringatan!</b> 
                <span id="info_hapus">Kamu akan menghapus data Rafli Firdausy</span> <br>
                <span>Data yang di hapus tidak dapat di kembalikan. Tetap hapus ?</span>
                <input type="hidden" name="id_user" id="idUser">
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
                        <label class="form-label">Nama Kader Pembangunan Manusia</label>
                        <input required type="text" id="nama_kpm" name="nama_kpm" class="form-control">  
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pilih Posyandu</label>
                        <select required name="posyandu"  class="form-control" id="posyandu">
                            @foreach ($posyandu as $item)
                                <option value="{{ $item->id_posyandu }}">{{ $item->nama_posyandu }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Username</label>
                        <input required type="text" id="username_kpm" name="username_kpm" class="form-control">  
                        <small id="error_username" style="color:red"></small>
                    </div>
                    <div class="form-group">
                        <label id="label_pass" class="form-label">Password</label>
                        <input required type="password" id="pass_kpm" name="pass_kpm" class="form-control">  
                        <small id="error_pass" style="color:red"></small>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password</label>
                        <input required type="password" id="konfirmpass_kpm" name="konfirmpass_kpm" class="form-control">  
                        <small id="error_konfirm" style="color:red"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id_user" id="id_user">
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

    $("#btn_input").click(function(){
        $("#form_tambah_edit").attr('action', "{{ base_url('pengaturan/kpm') }}");  
        $("#modalTitle").text("Tambah Data Kader Pembangunan Manusia");

        $("#id_user").val('');        
        $("#nama_kpm").val('');
        $("#posyandu").val('');
        $("#username_kpm").val('');
        $("#pass_kpm").val('');
        $("#konfirmpass_kpm").val('');

        $("#pass_kpm").prop('required',true);
        $("#konfirmpass_kpm").prop('required',true);
    });

    $(".editData").click(function(){
        $("#form_tambah_edit").attr('action', "{{ base_url('pengaturan/edit-kpm') }}");  
        $("#modalTitle").text("Edit Data Kader Pembangunan Manusia");

        let id              = $(this).data('id');
        let nama_lengkap    = $(this).data('nama_lengkap');
        let username        = $(this).data('username');
        let posyandu        = $(this).data('posyandu');
        
        $("#nama_kpm").val(nama_lengkap);
        $("#posyandu").val(posyandu);
        $("#username_kpm").val(username);
        $("#id_user").val(id);

        $("#label_pass").text("Password (opsional - isi jika ingin merubah password user KPM ini)");
        
        $("#pass_kpm").prop('required',false);
        $("#konfirmpass_kpm").prop('required',false);

        $("#error_username").text("");
        $("#error_username").text("");
        $("#error_konfirm").text("");
       
    });

    $(".hapusData").click(function(){
        let id      = $(this).data('id');
        let nama    = $(this).data('nama_lengkap');
    
        $("#info_hapus").text("Kamu akan menghapus data " + nama);
        $("#idUser").val(id);
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

    $('#username_kpm').keyup(delay(function (e) {
       if($("#username_kpm").val().length < 5){
           $("#error_username").text("Username Minimal 5 Karakter")
       } else {
        $("#error_username").text("");
       }
    }, 500));

    $('#pass_kpm').keyup(delay(function (e) {
        if($("#pass_kpm").val().length < 8){
           $("#error_pass").text("Password Minimal 8 Karakter")
       } else {
        $("#error_pass").text("");
       }
    }, 500));

    $('#konfirmpass_kpm').keyup(delay(function (e) {
        if($("#pass_kpm").val() != $("#konfirmpass_kpm").val()){
           $("#error_konfirm").text("Konfirmasi Password Salah")
       } else {
        $("#error_konfirm").text("");
       }
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
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
                            <th class="text-center" style="vertical-align: middle;">Nama Posyandu</th>
                            <th class="text-center" style="vertical-align: middle;">Alamat</th>
                            <th class="text-center" width="20%" style="vertical-align: middle;">Pilihan Aksi</th>                            
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($posyandu as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $item->nama_posyandu}}</td>
                                <td>{{ $item->alamat_posyandu}}</td>
                                <td>
                                    <button 
                                        data-id="{{ $item->id_posyandu }}" 
                                        data-nama_posyandu="{{ $item->nama_posyandu }}" 
                                        data-alamat_posyandu="{{ $item->alamat_posyandu }}"                                        
                                        data-toggle="modal" 
                                        data-target="#modal-input-edit-data" 
                                        title="Edit" 
                                        type="button" 
                                        class="editData btn btn-primary col-xs-6">Edit</button>
                                    <button 
                                        data-id="{{ $item->id_posyandu }}" 
                                        data-nama_posyandu="{{ $item->nama_posyandu }}" 
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
        <form enctype="multipart/form-data" role="form" method="POST" action="{{ base_url('pengaturan/hapus-posyandu') }}">
            <div class="modal-body">  
                <b>Peringatan!</b> 
                <span id="info_hapus">Kamu akan menghapus data Rafli Firdausy</span> <br>
                <span>Data yang di hapus tidak dapat di kembalikan. Tetap hapus ?</span>
                <input type="hidden" name="id_posyandu" id="idPosyandu">
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
                        <label class="form-label">Nama Posyandu</label>
                        <input required type="text" id="nama_posyandu" name="nama_posyandu" class="form-control">  
                    </div>
                    <div class="form-group">
                        <label class="form-label">Alamat Posyandu</label>
                        <textarea required type="text" id="alamat_posyandu" name="alamat_posyandu" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id_posyandu" id="id_posyandu">
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
        $("#form_tambah_edit").attr('action', "{{ base_url('pengaturan/posyandu') }}");  
        $("#modalTitle").text("Tambah Data Posyandu");

        $("#id_posyandu").val('');
        $("#nama_posyandu").val('');
        $("#alamat_posyandu").val('');
    });

    $(".editData").click(function(){
        $("#form_tambah_edit").attr('action', "{{ base_url('pengaturan/edit-posyandu') }}");  
        $("#modalTitle").text("Edit Data Posyandu");

        let id              = $(this).data('id');
        let nama_posyandu   = $(this).data('nama_posyandu');
        let alamat_posyandu = $(this).data('alamat_posyandu');

        $("#id_posyandu").val(id);
        $("#nama_posyandu").val(nama_posyandu);
        $("#alamat_posyandu").val(alamat_posyandu);
    });

    $(".hapusData").click(function(){
        let id      = $(this).data('id');
        let nama    = $(this).data('nama_posyandu');
    
        $("#info_hapus").text("Kamu akan menghapus data " + nama);
        $("#idPosyandu").val(id);
    });

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
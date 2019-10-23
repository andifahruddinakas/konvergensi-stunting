@extends('layout.admin')

@section('tab-title')
    Data Tilang
@endsection

@section('page-title')
Data Tilang
@endsection

@section('page-header')


@endsection

@section('page-breadcrumb')
<li><a href="{{ base_url('dashboard') }}"><i class="fa fa-dashboard"></i> GO BANG</a></li>
<li class="active">Data Tilang</li>
@endsection


@section('page-content')
    <div class="row">
        <div class="col-xs-12">
          <div class="box box-success">
            <div class="box-header">
                <div class="col-md-4">
                  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-import">
                    Import Data Tilang
                  </button>
                </div>
                <div class="col-md-8">
                    <div class="form-group pull-right">
                        <div class="input-group">
                            <button type="button" class="btn btn-primary pull-right" id="daterange-btn">
                            <span>
                                <i class="fa fa-calendar"></i> Filter Tanggal
                            </span>
                                <i class="fa fa-caret-down"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="table-petugas" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>No</th>
                  <th>No Reg Tilang</th>
                  <th>Nama Terpidana</th>
                  <th>Alamat</th>
                  <th>Barang Bukti</th>
                  <th>Total Denda</th>
                  <th>Posisi Barang Bukti</th>
                  <th>Request Antar</th>
                  <th>Tanggal</th>
                  <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>DD12345</td>
                        <td>Rafli Firdausy Irawan</td>
                        <td>Klahang</td>
                        <td>STNK</td>
                        <td>Rp 51.000</td>
                        <td>Kejaksaan</td>
                        <td>Tidak</td>
                        <td>13 Maret 2019</td>
                        <td>
                            <a href="#" type="button" class="btn btn-flat btn-info btn-sm">LIHAT</a>
                            <a href="#" type="button" class="btn btn-flat btn-warning btn-sm">UBAH</a>
                            <a href="#" type="button" class="btn btn-flat btn-danger btn-sm">HAPUS</a>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>DD12342</td>
                        <td>Sani Hasani</td>
                        <td>Sokaraja</td>
                        <td>SIM</td>
                        <td>Rp 91.000</td>
                        <td>Kantor Pos</td>
                        <td>Ya</td>
                        <td>14 Maret 2019</td>
                        <td>
                            <a href="#" type="button" class="btn btn-flat btn-info btn-sm">LIHAT</a>
                            <a href="#" type="button" class="btn btn-flat btn-warning btn-sm">UBAH</a>
                            <a href="#" type="button" class="btn btn-flat btn-danger btn-sm">HAPUS</a>
                        </td>
                    </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>


      <div class="modal fade" id="modal-import">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Import Data Tilang</h4>
            </div>
            <form enctype="multipart/form-data" role="form" method="POST" action="{{ base_url('DataTilang/importTilang') }}">
              <div class="modal-body">
                  Sebelum upload file excel data tilang, pastikan formatnya sudah sesuai. Silahkan download contoh format excel data tilang
                  <b><a href="#"> DI SINI </a></b>
                  <br><br>
                
                  <div class="form-group">
                    <label for="">Pilih file Excel Data tilang</label>
                    <label for="exampleInputFile"></label>
                    <input class="form-control pull-right" name="file_excel" type="file" accept=".xls,.xlsx" id="inputExcel">
                    <p class="help-block">Tipe File : .xls / .xlsx</p>
                  </div>
              </div>
              <div class="modal-footer">
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
    $(function () {
        $('#table-petugas').DataTable()

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
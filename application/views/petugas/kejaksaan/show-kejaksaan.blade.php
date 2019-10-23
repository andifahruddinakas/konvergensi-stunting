@extends('layout.admin')

@section('tab-title')
    Admin Kejaksaan
@endsection

@section('page-title')
Admin Kejaksaan
@endsection

@section('page-header')
<link rel="stylesheet" href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('page-breadcrumb')
<li><a href="{{ base_url('dashboard') }}"><i class="fa fa-dashboard"></i> GO BANG</a></li>
<li class="active">Petugas Kejaksaan</li>
@endsection

@section('page-content')
    <div class="row">
        <div class="col-xs-12">
          <div class="box box-success">
            <div class="box-header">
                <a href="{{base_url('kejaksaan/tambah')}}" type="button" class="btn btn-primary btn-flat pull-left">Tambah Admin Kejaksaan</a>
            </div>
            <div class="box-body">
              <table id="table-petugas" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>No</th>
                  <th>NIP</th>
                  <th>Nama</th>
                  <th>Nomer Hp</th>
                  <th>Jenis Kelamin</th>
                  <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($admin as $data)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $data->nip }}</td>
                        <td>{{ $data->nama }}</td>
                        <td>{{ $data->no_hp }}</td>
                        <td>{{ $data->jenis_kelamin }}</td>
                        <td>
                            <a href="#" type="button" class="btn btn-flat btn-info btn-sm">LIHAT</a>
                            <a href="#" type="button" class="btn btn-flat btn-warning btn-sm">UBAH</a>
                            <a href="#" type="button" class="btn btn-flat btn-danger btn-sm">HAPUS</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
@endsection

@section('page-footer')
<script src=" {{ asset('bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src=" {{ asset('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script>
    $(function () {
        $('#table-petugas').DataTable()
    })
</script>
@endsection
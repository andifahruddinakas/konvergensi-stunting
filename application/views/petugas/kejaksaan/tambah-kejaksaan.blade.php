@extends('layout.admin')

@section('tab-title')
    Tambah Admin Kejaksaan
@endsection

@section('page-title')
Tambah Admin Kejaksaan
@endsection

@section('page-header')
<link rel="stylesheet" href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('page-breadcrumb')
<li><a href="{{ base_url('dashboard') }}"><i class="fa fa-dashboard"></i> GO BANG</a></li>
<li><a href="{{ base_url('kejaksaan') }}">Petugas Kejaksaan</a></li>
<li class="active">Tambah</li>
@endsection

@section('page-content')
    <div class="row">
        <div class="col-xs-12">
          <div class="box box-success">
            <div class="box-header">
                <h3 class="box-title">Silahkan isi data admink kejaksaan yang baru disini</h3>
            </div>
            <div class="box-body">
                <form role="form" method="POST">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="nip">Nomor Induk Pegawai (NIP)</label>
                            <input type="text" class="form-control" id="nip" name="nip" placeholder="Masukan NIP">
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukan Nama Lengkap">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Masukan Email">
                        </div>
                        <div class="form-group">
                            <label for="no_hp">Nomor Handphone</label>
                            <input type="tel" class="form-control" id="no_hp" name="no_hp" placeholder="Masukan Nomor Handphone">
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea class="form-control" rows="3" name="alamat" placeholder="Masukan Alamat Lengkap"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="">Jenis Kelamin</label>
                            <div class="radio">
                                <label style="margin-right:50px;">
                                    <input type="radio" name="jenis_kelamin" value="1" checked>Pria
                                </label>
                                <label>
                                    <input type="radio" name="jenis_kelamin" value="0">Wanita
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Foto</label>
                            <label for="exampleInputFile"></label>
                            <input class="form-control pull-right" type="file" id="exampleInputFile">
                        <p class="help-block">Tipe File */image , Max size : 5 Mb</p>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
          </div>
        </div>
      </div>
@endsection
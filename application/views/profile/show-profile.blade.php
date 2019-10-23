@extends('layout.admin')

@section('tab-title')
    Profile
@endsection

@section('page-title')
User Profile
@endsection

@section('page-header')

@endsection

@section('page-breadcrumb')
<li><a href="{{ base_url('dashboard') }}"><i class="fa fa-dashboard"></i> GO BANG</a></li>
<li class="active">Profile</li>
@endsection

@section('page-content')
    <div class="row">
        <div class="col-md-4">
            <div class="box box-success">
                <div class="box-body box-profile">
                    <img class="profile-user-img img-responsive img-circle" src="{{ asset('dist/img/user4-128x128.jpg') }}" alt="User profile picture">
                    <h3 class="profile-username text-center">Rafli Firdausy Irawan</h3>
                    <p class="text-muted text-center">Admin Kejaksaan</p>
                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item">
                            <b>Terakhir Login</b> <a class="pull-right">23 Maret 2019 12:10:22</a>
                        </li>
                    <li class="list-group-item">
                        <b>Terakhir Diupdate</b> <a class="pull-right">23 Maret 2019 12:10:22</a>
                    </li>
                    <li class="list-group-item">
                        <b>Total Aksi</b> <a class="pull-right">1234</a>
                    </li>
                </ul>
                </div>        
            </div>
        </div>
        <div class="col-md-8">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#biodata-tab" data-toggle="tab">Biodata</a></li>
                    <li><a href="#password-tab" data-toggle="tab">Password</a></li>
                </ul>
                <div class="tab-content">

                    <div class="tab-pane active" id="biodata-tab">
                        <form action="#" class="form-horizontal">
                            <div class="form-group">
                                <label for="nama" class="col-sm-2 control-label">Nama</label>
                                <div class="col-sm-10">
                                    <input type="text" name="nama" class="form-control" id="nama" placeholder="Masukan nama lengkap">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-sm-2 control-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" name="email" class="form-control" id="email" placeholder="Masukan Email">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="no_hp" class="col-sm-2 control-label">Nomor Hp</label>
                                <div class="col-sm-10">
                                    <input type="tel" class="form-control" id="no_hp" placeholder="Masukan nomor handphone">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="alamat" class="col-sm-2 control-label">Alamat</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="alamat" id="alamat" placeholder="Masukan Alamat Lengkap"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputSkills" class="col-sm-2 control-label">Jenis Kelamin</label>            
                                <div class="col-sm-10">
                                    <div class="radio">
                                        <label style="margin-right:50px;">
                                            <input type="radio" name="jenis_kelamin" value="1" checked>Pria
                                        </label>
                                        <label>
                                            <input type="radio" name="jenis_kelamin" value="0">Wanita
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="foto" class="col-sm-2 control-label">Foto</label>
                                <div class="col-sm-10">
                                    <input class="form-control pull-right" type="file" name="foto" id="foto">
                                    <p class="help-block">Tipe File */image , Max size : 5 Mb</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane" id="password-tab">
                        <form action="#" class="form-horizontal">
                            <div class="form-group">
                                <label for="password_sekarang" class="col-sm-3 control-label">Password Lama</label>
                                <div class="col-sm-9">
                                    <input type="password" name="password_sekarang" class="form-control" id="nama" placeholder="Masukan password sekarang">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password_baru" class="col-sm-3 control-label">Password Baru</label>
                                <div class="col-sm-9">
                                    <input type="password" name="password_baru" class="form-control" id="email" placeholder="Masukan password baru">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="konfirmasi_password_baru" class="col-sm-3 control-label">Konfirmasi Password Baru</label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control" id="konfirmasi_password_baru" placeholder="Konfirmasi password baru">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-10">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    
                </div>
            </div>
        </div>
    </div>
@endsection
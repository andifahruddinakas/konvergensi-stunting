  <aside class="main-sidebar">
      <section class="sidebar">
        <div class="user-panel">
          <div class="pull-left image">
            <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
          </div>
          <div class="pull-left info">
            <p>Rafli Firdausy</p>
            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
          </div>
        </div>
        <ul class="sidebar-menu" data-widget="tree">
          <li class="header">Kepala E-PPKPS</li>
          <li class="active">
            <a href="{{ base_url('dashboard') }}"><i class="fa fa-home"></i> <span>Dashboard</span></a>
          </li>
          <li class="">
            <a href="#"><i class="fa fa-user"></i> <span>Tambah Admin</span></a> 
          </li>      
          <li class="">
              <a href="#"><i class="fa fa-heartbeat"></i> <span>Data KIA</span></a> 
            </li>  
          <li class="">
            <a href="#"><i class="fa fa-medkit"></i> <span>Pendataan kondisi Layanan</span></a>
          </li>  
          <li class="treeview">
            <a href="#">
              <i class="fa fa-stethoscope"></i> <span>Pemantauan</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="{{ base_url('pemantauan/ibu-hamil/') }}"><i class="fa fa-circle-o"></i> Bulanan Ibu Hamil</a></li>
              <li><a href="{{ base_url('pemantauan/bulanan-anak/') }}"><i class="fa fa-circle-o"></i> Bulanan Anak 0-2 Tahun</a></li>
              <li><a href="#"><i class="fa fa-circle-o"></i> Sasaran Paud Anak 2 - 6 Tahun</a></li>
            </ul>
          </li>      
          <li class="treeview">
            <a href="#">
              <i class="fa fa-hospital-o"></i> <span>Rekapitulasi</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class=""><a href="{{ base_url('rekapitulasi/ibu-hamil/') }}"><i class="fa fa-circle-o"></i> 3 Bulanan Ibu Hamil</a></li>
              <li><a href="#"><i class="fa fa-circle-o"></i> 3 Bulanan Anak 0-2 Tahun</a></li>
            </ul>
          </li> 
          <li class="treeview">
              <a href="#">
                <i class="fa fa-wheelchair"></i> <span>Formulir Bantu</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="#"><i class="fa fa-circle-o"></i> Layanan Paud Anak 2-6 tahun</a></li>
                <li><a href="#"><i class="fa fa-circle-o"></i> Capaian Penerimaan Layanan</a></li>
                <li><a href="#"><i class="fa fa-circle-o"></i> Konvergensi Desa</a></li>
              </ul>
            </li>    
            <li class="">
                <a href="#"><i class="fa fa-ambulance"></i> <span>Scorcard Konvergensi Desa</span></a>
              </li>        
        </ul>
      </section>
    </aside>
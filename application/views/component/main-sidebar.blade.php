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
          <li class="header">Kepala Kejaksaan</li>
          <li class="active">
            <a href="{{ base_url('dashboard') }}"><i class="fa fa-home"></i> <span>Dashboard</span></a>
          </li>
          <li class="treeview">
            <a href="#">
              <i class="fa fa-user-circle"></i> <span>Petugas</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class=""><a href="{{ base_url('kejaksaan') }}"><i class="fa fa-circle-o"></i> Admin Kejaksaan</a></li>
              <li><a href="{{ base_url('pos') }}"><i class="fa fa-circle-o"></i> Admin POS</a></li>
              <li><a href="{{ base_url('kurir') }}"><i class="fa fa-circle-o"></i> Petugas Kurir</a></li>
            </ul>
          </li>  
          <li class="">
            <a href="{{ base_url('datatilang') }}"><i class="fa fa-laptop"></i> <span>[K] Data Tilang</span></a>
          </li>      
          <li class="">
            <a href="#">
              <i class="fa fa-bell-o"></i> <span>[K] Permintaan Antar</span>
              <span class="pull-right-container">
                <small class="label pull-right bg-blue">8</small>
              </span>
            </a>
          </li>    
          <li class="">
              <a href="#">
                  <i class="fa fa-bell-o"></i> <span>[K] Data Siap Antar</span>
                  <span class="pull-right-container">
                    <small class="label pull-right bg-green">8</small>
                  </span>
                </a>
          </li>   
          <li class="">
              <a href="#"><i class="fa fa-laptop"></i> <span>[P] Cari Data</span></a>
          </li>  
          <li class="">
              <a href="#"><i class="fa fa-laptop"></i> <span>[P] Penugasan Kurir</span></a>
          </li>  
        </ul>
      </section>
    </aside>
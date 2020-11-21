<!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?php echo base_url();?>" class="brand-link">
      Welcome <span style="font-weight: bolder;color:white;"><?php echo ucfirst($profile['name']);?></span></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
                with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="<?php echo base_url();?>" class="nav-link">
                    <i class="nav-icon fas fa-file"></i>
                    <p>Home</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url();?>admin/group" class="nav-link">
                    <i class="nav-icon fas fa-file"></i>
                    <p>Group Akses</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url();?>profile" class="nav-link">
                    <i class="nav-icon fas fa-file"></i>
                    <p>Profile</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url();?>admin/doctor" class="nav-link">
                    <i class="nav-icon fas fa-file"></i>
                    <p>Dokter</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url();?>admin/tnc" class="nav-link">
                    <i class="nav-icon fas fa-file"></i>
                    <p>TnC</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url();?>admin/about" class="nav-link">
                    <i class="nav-icon fas fa-file"></i>
                    <p>About</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url();?>/login/logout" class="nav-link">
                    <i class="nav-icon fas fa-file"></i>
                    <p>Logout</p>
                    </a>
                </li>
            </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

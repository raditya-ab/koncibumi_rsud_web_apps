<!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
            <a href="#" class="d-block"><?php echo ucfirst($profile['username']);?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          
          <li class="nav-item">
            <a href="<?php echo base_url();?>" class="nav-link">
              <i class="nav-icon fas fa-file"></i>
              <p>Daftar Resep</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo base_url().'farmasi/waiting';?>" class="nav-link">
              <i class="nav-icon fas fa-file"></i>
              <p>Daftar Tunggu Resep</p>
            </a>
          </li>
          
          <li class="nav-item">
            <a href="<?php echo base_url().'profile';?>" class="nav-link">
              <i class="nav-icon fas fa-file"></i>
              <p>Profile</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo base_url().'access/logout';?>" class="nav-link">
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

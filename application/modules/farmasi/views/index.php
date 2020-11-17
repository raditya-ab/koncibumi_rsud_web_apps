<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Koncibumi | Farmasi</title>
  <?php $this->load->view("header");?>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
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

  <?php $this->load->view("sidebar");?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Daftar Resep</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
               
                <table id="example2" class="table table-bordered table-hover">
                  <thead class="head_background">
                  <tr>
                    <th>No.</th>
                    <th>No Pesanan</th>
                    <th>Pasien</th>
                    <th>Dokter</th>
                    <th>Tanggal</th>
                    <th>Detail</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr>
                    <td>Trident</td>
                    <td>Trident</td>
                    <td>Trident</td>
                    <td>Win 95+</td>
                    <td> 4</td>
                    <td>X</td>
                  </tr>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php $this->load->view("copyright");?>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<?php $this->load->view("footer");?>
</body>
</html>

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
                <h3 class="card-title">Daftar Resep yang menunggu diproses</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                  <thead class="head_background">
                  <tr>
                    <th>No.</th>
                    <th>No Order</th>
                    <th>No Resep</th>
                    <th>Pasien</th>
                    <th>Dokter</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Detail</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                    if ( count($pending) > 0 ){
                      foreach ($pending as $key => $value) {
                  ?>
                  <tr>
                    <td><?php echo $key + 1; ?></td>
                    <td><?php echo $value['order_no']?></td>
                    <td><?php echo $value['receipt_no'];?></td>
                    <td><?php echo $value['first_name'].' '.$value['last_name'];?></td>
                    <td><?php echo $value['doctor_name']?></td>
                    <td><?php echo date("d M Y",strtotime($value['created_at']));?></td>
                    <td>Menunggu Farmasi</td>
                    <td>
                      <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-default" onClick="showDetailReceipt(<?php echo $value['id'];?>)">
                        Lihat Resep
                      </button>
                </td>
                  </tr>
                  <?php 
                      }
                    }
                  ?>
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
  <div class="modal fade" id="modal-default">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Detail Resep</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="container-fluid">
            <div class="row">
              <!-- left column -->
              <div class="col-md-6">
                <div class="form-group">
                  <label>No Order.</label>
                  <input type="text" class="form-control" name="order_no" id="order_no" disabled>
                  <input type="hidden" class="form-control" name="order_id" id="order_id">
                  <input type="hidden" class="form-control" name="status" id="status">
                </div>
                <div class="form-group">
                  <label>No Receipt.</label>
                  <input type="text" class="form-control" name="receipt_no" id="receipt_no" disabled>
                </div>
                <div class="form-group">
                  <label>Nama Pasien</label>
                  <input type="text" class="form-control" name="patient_name" id="patient_name" disabled>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Dokter</label>
                  <input type="text" class="form-control" name="doctor_name" id="doctor_name" disabled>
                </div>
                <div class="form-group">
                  <label>Poli</label>
                  <input type="text" class="form-control" name="poli" id="poli" disabled>
                </div>
                <div class="form-group">
                  <label>Tanggal Pengambilan</label>
                  <input type="text" class="form-control float-right" id="reservation" required>
                </div>
                <div class="form-group">
                  <label>Status Restricted</label>
                  <span id="label_resep"></span>
                </div>
              </div>
            </div>
          </div>
          
          <div class="form-group">
            <label>Keterangan</label><br/>
            <textarea name="desc" id="desc" rows="8" cols="60">
              
            </textarea>
          </div>
          <div class="form-group">
            <table class="table table-bordered table-hover">
              <thead class="head_background">
                <tr>
                  <td>No.</td>
                  <td>Nama Obat</td>
                  <td>Jumlah</td>
                  <td>Satuan</td>
                  <td>Dosis</td>
                  <td>Frekuensi(x)</td>
                </tr>
              </thead>
              <tbody id="list_obat">
                
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onClick="submitProcess();">Approve</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

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

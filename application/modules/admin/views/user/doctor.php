<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>KonciBumi | Admin</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php $this->load->view("header");?>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <?php $this->load->view("sidebar");?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    <p>&nbsp;</p>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Daftar Dokter</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                    <thead class="head_background">
                    <tr>
                        <th>No.</th>
                        <th>NIK</th>
                        <th>Name</th>
                        <th>Poli</th>
                        <th>Created At</th>
                        <th>Terdaftar di Koncibumi</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        if ( count($doctor) > 0 ){
                        foreach ( $doctor as $key => $value ){
                    ?>
                    <tr>
                        <td><?php echo $key+1; ?></td>
                        <td><?php echo $value['nik'];?></td>
                        <td><?php echo ucfirst($value['first_name']);?></td>
                        <td><?php echo $value['poli'];?></td>
                        <td><?php echo date("d/M/Y",strtotime($value['created_at']));?></td>
                        <td><?php echo ( $value['user_id'] == NULL ) ? "<span class='right badge badge-danger'>Not Register</span>" : "<span class='right badge badge-success'>Registered</span>";?></td>
                    </tr>
                    <?php 
                            } 
                        } 
                    ?>
                    </tbody>
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
  <?php $this->load->view("copyright");?>
</div>
<!-- ./wrapper -->

<?php $this->load->view("footer");?>
</body>
</html>

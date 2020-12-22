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
                <h3 class="card-title">Daftar Pasien</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <a class="btn btn-success" href="<?php echo base_url();?>admin/add_patient">Tambah Pasien</a>
                <table id="example2" class="table table-bordered table-hover">
                    <thead class="head_background">
                    <tr>
                        <th>No.</th>
                        <th>Full Name</th>
                        <th>No. BPJS</th>
                        <th>No. Medrek</th>
                        <th>Tanggal Lahir</th>
                        <th>Gender</th>
                        <th>Type Darah</th>
                        <th>Alamat</th>
                        <th>Nomor Handphone</th>
                        <th>Status Pernikahan</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                      <?php
                        foreach ( $patient as $key => $value ){
                      ?>
                      <tr>
                        <td><?php echo $key + 1 ;?></td>
                        <td><?php echo $value['first_name'].' '.$value['last_name']; ?></td>
                        <td><?php echo $value['no_bpjs'];?></td>
                        <td><?php echo $value['no_medrec'];?></td>
                        <td><?php echo date("d-M-Y",strtotime($value['dob']));?></td>
                        <td><?php echo $arrayGender[$value['gender']];?></td>
                        <td><?php echo $value['blood_type']?></td>
                        <td><?php echo $value['address'];?></td>
                        <td><?php echo $value['mobile_number'];?></td>
                        <td><?php echo $value['marrital_status'];?></td>
                        <td><?php echo $arrayStatus[$value['status']];?></td>
                      </tr>
                      <?php } ?>
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

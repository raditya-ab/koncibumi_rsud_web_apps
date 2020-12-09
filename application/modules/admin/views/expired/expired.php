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
                <h3 class="card-title">Daftar Order yang Expired</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                    <thead class="head_background">
                    <tr>
                        <th>No.</th>
                        <th>Order No</th>
                        <th>Patient Name</th>
                        <th>Status</th>
                        <th>Poli</th>
                        <th>Dokter</th>
                        <th>Created At</th>
                    </tr>
                    </thead>
                    <tbody>
                      <?php
                        if ( count($expired) > 0 ){
                          foreach ($expired as $key => $value) {
                      ?>
                      <tr>
                        <td><?php echo $key + 1; ?></td>
                        <td>
                          <?php echo $value['order_no']; ?><br/>
                          <span style="font-size: 12px;">Dokter : <strong><?php echo $value['doctor_name'];?></strong></span>
                        </td>
                        <td><?php echo $value['first_name']. $value['last_name']; ?></td>
                        <td><?php echo $status[$value['status']]; ?></td>
                        <td><?php echo $value['poli'];?></td>
                        <td>
                          <select id="doctor_<?php echo $value['id'];?>" class="form-control">
                            <?php
                              foreach ( $doctor as $key_doctor => $val_doctor){
                                if ( $val_doctor['poli'] == $value['poli']){
                            ?>
                            <option value="<?php echo $val_doctor['id'];?>"><?php echo $val_doctor['first_name'];?></option>
                            <?php } } ?>
                          </select><br/>
                          <button class="btn btn-success" id="btn_<?php echo $value['id'];?>" onClick="updateDoctor('<?php echo $value['id'];?>')">Update Dokter</button>
                          <span id="loading_<?php echo $value['id'];?>" style="display: none;">Loading ....</span>
                        </td>
                        <td><?php echo date("d-M-Y",strtotime($value['created_at']));?></td>
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

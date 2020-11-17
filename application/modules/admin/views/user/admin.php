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
                <h3 class="card-title">Daftar User</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <a class="btn btn-success" href="<?php echo base_url();?>admin/add">Tambah User</a>
                <table id="example2" class="table table-bordered table-hover">
                    <thead class="head_background">
                    <tr>
                        <th>No.</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Status Login</th>
                        <th>Detail</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        if ( count($get_user) > 0 ){
                        foreach ( $get_user as $key => $value ){
                    ?>
                    <tr>
                        <td><?php echo $key+1; ?></td>
                        <td><?php echo ucfirst($value['username']);?></td>
                        <td><?php echo $value['email'];?></td>
                        <td><?php echo ( $value['login_status'] != NULL ) ? "<span class='right badge badge-success'>Online</span>" : "<span class='right badge badge-danger'>Offline</span>" ?></td>
                        <td><a href="<?php echo base_url().'admin/user_detail/'.$value['id'];?>" class="btn btn-block btn-primary">Detail</a></td>
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

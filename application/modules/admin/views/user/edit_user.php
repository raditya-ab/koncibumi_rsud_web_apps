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
          <!-- left column -->
          <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Edit User</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" action="<?php echo base_url();?>admin/register" method="post">
                <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_data[0]['id'];?>"/>
                <div class="card-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $user_data[0]['email'];?>" placeholder="Enter email" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo $user_data[0]['username'];?>" placeholder="Enter Name" autocomplete="off" required>  
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Group Akses</label>
                        <select name="group" class="form-control">
                        <?php
                            foreach ( $group as $key => $value ){
                        ?>
                            <option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option>
                        <?php } ?>
                        </select>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-success" id="btn_edit_password" onClick="resetPassword('<?php echo $user_data[0]['id'];?>')">Reset Password</button>
                    <a class="btn btn-warning" href="<?php echo base_url().'admin';?>">Back</a>
                </div>
              </form>
            </div>
            <!-- /.card -->
          </div>
          <!--/.col (left) -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
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

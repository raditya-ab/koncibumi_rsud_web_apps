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
                <h3 class="card-title">Daftar Group Akses</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form role="form" action="<?php echo base_url();?>admin/group/add" method="post">
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Nama Grup Akses</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" autocomplete="off" required>
                  </div>
                  <div class="form-group">
                    <button class="btn btn-info" type="submit">Simpan</button>
                  </div>
                </form>
                <table id="example2" class="table table-bordered table-hover">
                    <thead class="head_background">
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Edit/Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                      <?php
                          foreach ( $group as $key => $value ){
                      ?>
                      <tr>
                        <td><?php echo $key + 1 ;?></td>
                        <td><?php echo $value['name'];?></td>
                        <td>
                          <button class="btn btn-info" onClick="editGroup('<?php echo $value['id'];?>');">Edit</button>
                          <button class="btn btn-danger" onClick="removeGroup('<?php echo $value['id'];?>');">Remove</button>
                        </td>
                      </tr>
                      <?php }  ?>
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

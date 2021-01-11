<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>KonciBumi | Admin</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
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
                <h3 class="card-title">Tambah Pasien</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" action="<?php echo base_url();?>admin/register_pasien" method="post">
                <input type="hidden" name="user_id" value="<?php echo $user_id?>">
                <div class="card-body">
                  <div class="form-group">
                      <label for="exampleInputPassword1">No. BPJS</label>
                      <input type="text" class="form-control" id="detail_bpjs" name="detail_bpjs" value="<?php echo $patient[0]['no_bpjs']?>" autocomplete="off" required>  
                  </div>
                  <div class="form-group">
                      <label for="exampleInputPassword1">No. Medrek</label>
                      <input type="text" class="form-control" id="detail_medrek" name="detail_medrek" value="<?php echo $patient[0]['no_medrec']?>" autocomplete="off" required>  
                  </div>
                  <div class="form-group">
                      <label for="exampleInputPassword1">Nama</label>
                      <input type="text" class="form-control" id="detail_name" name="detail_name" value="<?php echo $patient[0]['first_name']?>" autocomplete="off" required>  
                  </div>
                  <div class="form-group">
                      <label for="exampleInputPassword1">Handphone</label>
                      <input type="text" class="form-control" id="detail_handphone" name="detail_handphone" value="<?php echo $patient[0]['mobile_number']?>" autocomplete="off" required>  
                  </div>
                  <div class="form-group">
                      <label for="exampleInputPassword1">Alamat</label>
                      <textarea name="detail_adress" cols="30" rows="5" class="form-control"><?php echo $patient[0]['address'];?></textarea>
                  </div>
                  <div class="form-group">
                      <label for="exampleInputPassword1">Status Aktif</label>
                      <?php if ( $patient[0]['status'] == "" || $patient[0]['status'] == "1" ) { ?>
                        <input type="checkbox" name="active" checked > 
                      <?php } else { ?>
                        <input type="checkbox" name="active">
                      <?php } ?>
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a class="btn btn-warning" href="<?php echo base_url().'admin/patient';?>">Back</a>
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

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
  $(function () {
    $("#dob").datepicker();
  });
</script>
</body>
</html>

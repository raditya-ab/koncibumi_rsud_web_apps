<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>KonciBumi | Admin</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url();?>dashboard/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url();?>dashboard/dist/css/adminlte.min.css">
  <!-- summernote -->
  <link rel="stylesheet" href="<?php echo base_url();?>dashboard/plugins/summernote/summernote-bs4.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
 <?php $this->load->view("sidebar");?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <p>&nbsp;</p>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <form action="<?php echo base_url().'admin/tnc/submit';?>" method="post">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Data TNC
              </h3>
              <!-- tools box -->
              <div class="card-tools">
                <button type="button" class="btn btn-tool btn-sm" data-card-widget="collapse" data-toggle="tooltip"
                        title="Collapse">
                  <i class="fas fa-minus"></i></button>
                <button type="button" class="btn btn-tool btn-sm" data-card-widget="remove" data-toggle="tooltip"
                        title="Remove">
                  <i class="fas fa-times"></i></button>
              </div>
              <!-- /. tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body pad">
              <div class="mb-3">
                <textarea class="textarea" placeholder="Place some text here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" name="content">
                  <?php 
                    if ( count($about) > 0 ) { 
                      echo $about[0]['content'];
                    }
                  ?>
                </textarea>
              </div> 
            </div>
            <div class="card-body pad">
              <div class="mb-3">
                <button type="submit" class="btn btn-info">Simpan</button>
              </div>
            </div>
        </div>
        </form>
        <!-- /.col-->
      </div>
      <!-- ./row -->
    </section>
    <!-- /.content -->
  </div>
  <?php $this->load->view("copyright");?>
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="<?php echo base_url();?>dashboard/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url();?>dashboard/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url();?>dashboard/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url();?>dashboard/dist/js/demo.js"></script>
<!-- Summernote -->
<script src="<?php echo base_url();?>dashboard/plugins/summernote/summernote-bs4.min.js"></script>
<script>
  $(function () {
    // Summernote
    $('.textarea').summernote()
  })
</script>
</body>
</html>

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
                        <th>Dokter</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                      <?php
                        foreach ( $blocking as $key => $value ){
                          $order_id = $value['id'];
                      ?>
                      <tr>
                        <td><?php echo $key + 1; ?></td>
                        <td><?php echo $value['first_name'];?></td>
                        <td><?php echo $value['bpjs_number'];?></td>
                        <td><?php echo $value['medical_number'];?></td>
                        <td><?php echo $value['doctor_name'];?></td>
                        <td><?php echo date("d-M-Y",strtotime($value['created_at']));?></td>
                        <td>Ada Keluhan</td>
                        <td><button class="btn btn-success" type="button" onClick="openBlocking('<?php echo $order_id?>')">Buka</button></td>
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
<script type="text/javascript">
  function openBlocking(order_id){
    if ( confirm("Apakah anda yakin ingin menyelesaikan pesanan pasien ini ? ")){
        var request = $.ajax({
          url : "admin/open_order",
          data : {
            order_id :: order_id
          },
          type : "post",
          dataType : "json"
        });

        request.done(function(){
          alert("Pasien sudah bisa mengorder kembali");
          window.location.reload();
        });
    }else{
      return false;
    }
  }
</script>
</body>
</html>

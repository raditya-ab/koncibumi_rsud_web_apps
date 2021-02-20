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
                
                <a href="<?php echo base_url()?>admin/group" class="btn btn-info">Kembali</a>
                <table id="example2" class="table table-bordered table-hover">
                    <thead class="head_background">
                    <tr>
                        <th>No.</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                      <?php
                          foreach ( $detail_group as $key => $value ){
                      ?>
                      <tr>
                        <td><?php echo $key + 1 ;?></td>
                        <td><?php echo $value['username'];?></td>
                        <td><?php echo $value['email'];?></td>
                        <td><button class="btn btn-danger" onClick="removeMember('<?php echo $value['id']?>')">Delete</button></td>
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
<script type="text/javascript">
  function editGroup(id,name){
    $("#name").val(name);
    $("#akses_id").val(id);
  }

  function removeMember(id){
    if ( confirm("Apakah anda yakin ingin menghapus member dari group ini ? ")){
      var request = $.ajax({
        url : "<?php echo base_url();?>/admin/group/deleteMember",
        dataType : "json",
        data : {
          id : id
        },
        type : "post"
      });

      request.done(function(data){
        if ( data.status == 0 ){
          alert("Data telah dihapus");
        }
        window.location.reload();
      })
    }else{
      return false;
    }
  }
</script>
</body>
</html>

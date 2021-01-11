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
                <div class="card-body">
                  <input type="hidden" name="detail_name" id="detail_name">
                  <input type="hidden" name="detail_bpjs" id="detail_bpjs">
                  <input type="hidden" name="detail_medrek" id="detail_medrek">
                  <input type="hidden" name="detail_dob" id="detail_dob">
                  <input type="hidden" name="detail_gender" id="detail_gender">
                  <input type="hidden" name="detail_blood" id="detail_blood">
                  <input type="hidden" name="detail_adress" id="detail_adress">
                  <input type="hidden" name="detail_hadnphone" id="detail_handphone">
                  <input type="hidden" name="detail_marital" id="detail_marital">

                  <div class="form-group">
                      <label for="exampleInputPassword1">No. BPJS</label>
                      <input type="text" class="form-control" id="bpjs" name="bpjs" autocomplete="off" required>  
                  </div>
                  <div class="form-group">
                      <label for="exampleInputPassword1">No. Medrek</label>
                      <input type="text" class="form-control" id="medrek" name="medrek" autocomplete="off" required>  
                  </div>
      
                  <div class="form-group">
                      <label for="exampleInputPassword1">Status Aktif</label>
                      <input type="checkbox" name="active" > 
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <button class="btn btn-info" type="button" onClick="getKunjungan()" id="btn_cek">Cek Data</button>
                    <span id="loading_bar" style="display: none;">Loading...</span>
                    <button type="submit" class="btn btn-primary" style="display: none;" id="btn_submit">Submit</button>
                    <a class="btn btn-warning" href="<?php echo base_url().'admin/patient';?>">Back</a>
                </div>
              </form>
            </div>
            <!-- /.card -->
          </div>
          <!--/.col (left) -->

          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Data Kunjungan</h3>
              </div>
              <div class="card-body">
                <h4>Nama : <span id="label_name"></span></h4>
                <h4>No. BPJS : <span id="label_bpjs"></span></h4>
                <h4>No. Medik : <span id="label_medrec"></span></h4>
                <br/>
                <table id="example2" class="table table-bordered table-hover">
                  <thead class="head_background">
                    <tr>
                        <th>No.</th>
                        <th>ID Kunjungan</th>
                        <th>Tanggal Kunjungan</th>
                        <th>Dokter</th>
                        <th>Poli</th>
                        <th>ICD Code</th>
                        <th>IC Description</th>
                        <th>Tindak Lanjut</th>
                    </tr>
                  </thead>
                  <tbody id="body_kunjungan">
                    
                  </tbody>
                </table>
              </div>
            </div>
            <!-- /.card -->
          </div>
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

  function getKunjungan(){
    $("#btn_cek").hide();
    $("#btn_submit").hide();
    $("#loading_bar").show();
    $("#body_kunjungan").html("");

    if ( $("#bpjs").val() == "" || $("#medrek").val() == ""){
      alert("Silahkan isi data BPJS atau Nomor Medical");
      return false;
    }else{
      var request = $.ajax({
        url : "<?php echo base_url();?>/admin/check_pasien",
        data : {
          bpjs : $("#bpjs").val(),
          medrek : $("#medrek").val()
        },
        type : "post",
        dataType : "json"
      });

      request.done(function(data){
        $("#loading_bar").hide();
        $("#btn_cek").show();
        if ( data.total_kunjungan <= 0 ){
          alert(data.message);
          return false;
        }else{

          $("#body_kunjungan").append(data.html);
          $("#btn_submit").show();
          $("#label_name").text(data.detail_patient.patient_name);
          $("#label_bpjs").text(data.detail_patient.no_bpjs);
          $("#label_medrec").text(data.detail_patient.no_medical_record);

          $("#detail_name").val(data.detail_patient.patient_name);
          $("#detail_bpjs").val(data.detail_patient.no_bpjs);
          $("#detail_medrek").val(data.detail_patient.no_medical_record);
          $("#detail_dob").val(data.detail_patient.date_of_birth);
          $("#detail_gender").val(data.detail_patient.sex);
          $("#detail_blood").val(data.detail_patient.blood_type);
          $("#detail_adress").val(data.detail_patient.address);
          $("#detail_handphone").val(data.detail_patient.mobile_no);
          $("#detail_marital").val(data.detail_patient.marital_status);

        }
      });
    }
  }
</script>
</body>
</html>

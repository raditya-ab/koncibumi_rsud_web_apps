<!-- jQuery -->
<script src="<?php echo base_url();?>dashboard/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url();?>dashboard/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="<?php echo base_url();?>dashboard/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>dashboard/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url();?>dashboard/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url();?>dashboard/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url();?>dashboard/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url();?>dashboard/dist/js/demo.js"></script>
<!-- page script -->

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true,
      "autoWidth": false,
    });
    
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
    $('#reservation').datepicker();
  });

  function showDetailReceipt(id){
    var request = $.ajax({
      url : "<?php echo base_url();?>farmasi/getdetail",
      dataType : "json",
      data : {
        id : id
      },
      type : "post"
    });

    request.done(function(data){
      $("#order_id").val(data.resep_id);
      $("#order_no").val(data.order_no);
      $("#receipt_no").val(data.receipt_no);
      $("#patient_name").val(data.patient_name);
      $("#doctor_name").val(data.doctor_name);
      $("#poli").val(data.poli);
      $("#desc").val(data.desc);
      $("#list_obat").html(data.list_obat);
      $("#status").val(data.next_status);
      if ( data.restricted == 1 ){
        $("#label_resep").text("Obat harus diambil ke RS");
        $("#label_resep").attr("style","font-weight:bolder;color:red;");
      }else{
        $("#label_resep").text("Obat bisa dikirim");
        $("#label_resep").attr("style","font-weight:bolder;color:blue;");
      }

      if ( data.delivery_date != ""){
        $("#reservation").val(data.delivery_date);
      }
    })
  }

  function submitProcess(){
    if ( confirm("Anda yakin ingin memproses data ini ? ")){
      if ( $("#reservation").val() == "" ){
        alert("Silahkan isi tanggal pengiriman");
        return false;
      }
      
      var request = $.ajax({
        url : "<?php echo base_url();?>farmasi/proses",
        data : {
          id : $("#order_id").val(),
          status : $("#status").val(),
          delivery_date:$("#reservation").val()
        },
        dataType : "json",
        type : "post"
      });

      request.done(function(data){
        if ( data.status == 0 ){
          alert("Pesanan telah diproses");
        }

        window.location.reload();
      })
    }else{
      return false;
    }
  }
</script>
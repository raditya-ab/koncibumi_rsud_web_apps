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
  });
</script>
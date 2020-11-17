<!-- jQuery -->
<script src="<?php echo base_url();?>dashboard/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url();?>dashboard/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url();?>dashboard/dist/js/adminlte.min.js"></script>
<script type="text/javascript">
    function submitLogin(){
        $("#label_invalid_login").hide();
        $("#label_loading").show();
        var request = $.ajax({
            url : "<?php echo base_url();?>/login/submit",
            dataType : "json",
            data : {
                email : $("#email").val(),
                password : $("#password").val()
            },
            type : "post"
        });

        request.done(function(data){
            $("#label_loading").hide();
            if ( data.status == "1"){
                $("#label_invalid_login").show();
                return false;
            }else{
                window.location.href = data.profile.menu.url;
            }
        });
    }
</script>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin Koncibumi | Log in</title>
  <?php $this->load->view("header")?>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="../../index2.html"><b>Admin</b>KONCIBUMI</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
    <p class="login-box-msg">Sign in to start your session</p>
    <form action="<?php echo site_url('login/submit');?>" id="form_login">
      <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Email" name="email" id="email" autocomplete="off" required>
          <div class="input-group-append">
          <div class="input-group-text">
              <span class="fas fa-envelope"></span>
          </div>
          </div>
      </div>
      <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password" name="password" id="password" autocomplete="off" required>
          <div class="input-group-append">
          <div class="input-group-text">
              <span class="fas fa-lock"></span>
          </div>
          </div>
      </div>
      <div class="input-group mb-3">
          <button class="btn btn-info" type="submit" onClick="submitLogin();" id="btn_login">Login</button><br/>
      </div>
    </form>
    <div class="input-group mb-3">
      <h5 style="font-weight:bolder;color:red;display:none;" id="label_invalid_login">Invalid Login</h5><br/>
      <h5 style="font-weight:bolder;display:none;" id="label_loading">Loading...</h5><br/>
    </div>
  </div>
</div>
<!-- /.login-box -->
<?php $this->load->view("footer")?>


</body>
</html>

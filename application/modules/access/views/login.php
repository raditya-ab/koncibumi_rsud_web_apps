<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
</head>
<body>
<form action="<?php echo base_url().'/access/submit';?>" method="post">
	<input type="text" name="username">
	<input type="password" name="password">
	<button type="submit">Login</button>
</form>
</body>
</html>
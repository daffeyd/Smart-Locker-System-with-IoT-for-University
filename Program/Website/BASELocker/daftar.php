<!DOCTYPE html>
<html>
<head>
	<title>Daftar Akun</title>
	<link rel="stylesheet" type="text/css" href="style_log.css">
	<style>



	</style>
</head>

<body>
    
     <form class="ind" action="cekDaftar.php" method="post">
     	<h2>Daftar Akun</h2>
     	<?php if (isset($_GET['error'])) { ?>
     		<p class="error"><?php echo $_GET['error']; ?></p>
     	<?php } ?>
     	<label>Username</label>
     	<input type="text" name="username" placeholder="Username"><br>

     	<label>Password</label>
     	<input type="password" name="password" placeholder="Password"><br>

     	<button class="log" type="submit">Daftar</button>
     </form>
	 
     
</body>

</html>
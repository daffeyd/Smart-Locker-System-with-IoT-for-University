<?php 
session_start(); 
include "koneksi.php";

if (isset($_POST['username']) && isset($_POST['password'])) {

	function validate($data){
       $data = trim($data);
	   $data = stripslashes($data);
	   $data = htmlspecialchars($data);
	   return $data;
	}

	$username = validate($_POST['username']);
	$pass = validate($_POST['password']);

	// $username = $_POST['username'];
	// $pass = $_POST['password'];

	if (empty($username)) {
		header("Location: login.php?error=Email / Username is required");
	    exit();
	}else if(empty($pass)){
        header("Location: login.php?error=Password is required");
	    exit();
	}else{
		$sql = "SELECT * FROM user WHERE username='$username' AND password='$pass'";

		$result = mysqli_query($db, $sql);

		if (mysqli_num_rows($result) === 1) {
			$row = mysqli_fetch_assoc($result);
            // if ($row['username'] === $username && $row['password'] === $pass) {
            	$_SESSION['iduser'] = $row['id'];
            	$_SESSION['pass'] = $row['password'];
            	$_SESSION['username'] = $row['username'];
				$_SESSION['status'] = $row['status'];
				$_SESSION['locker'] = $row['locker'];
            	header("Location: index.php");
		        exit();
            // }else{
			// 	header("Location: login.php?error=Incorect Email / Username or password");
		    //     exit();
			// }
		}else{
			header("Location: login.php?error=Incorect Email / Username or password");
	        exit();
		}
	}
	
}else{
	header("Location: login.php");
	exit();
}
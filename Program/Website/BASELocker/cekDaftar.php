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

	if (empty($username)) {
		header("Location: login.php?error=Email / Username is required");
	    exit();
	}else if(empty($pass)){
        header("Location: login.php?error=Password is required");
	    exit();
	}else{
		$sql = "INSERT INTO `user`(`username`, `password`, `status`, `locker`) VALUES ('$username','$pass','user','-')";
		$result = mysqli_query($db, $sql);
        header("Location: login.php?success=Akun Sudah Didaftarkan!!!");
	}
	
}else{
	header("Location: login.php");
	exit();
}
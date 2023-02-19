<?php
session_start();
include "../koneksi.php";
$kampus    =    $_GET['kampus'];
$lokasi    =    $_GET['lokasi'];
$nama    =    $_GET['name'];
$no     =    $_GET['position'];

function statusSelector($db, $kampus, $ruangan, $nama)
{
	$sql = "SELECT status FROM locker Where lokasi = '$kampus' and ruangan = '$ruangan'and nama = '$nama'";
	$lockerLocation = "";

	$result = mysqli_query($db, $sql);
	if (mysqli_num_rows($result) === 1) {
		while ($row = mysqli_fetch_array($result)) {
			$lockerLocation  = $row['status'];
		}
		$lockerLocation = str_split($lockerLocation);
		return $lockerLocation;
	} else {
		header("Location: index.php");
		exit();
	}
}

function update($db, $kampus, $ruangan, $nama, $no, $status)
{


    $statusLocker   =  [];
    if (isset($_SESSION['statusLocker'])){
        $statusLocker   =  $_SESSION['statusLocker']; 
    }
    else{
        $statusLocker = statusSelector($db, $kampus, $ruangan, $nama);
    }

    
    $statusLocker[$no - 1] = $status;
    $status = implode("", $statusLocker);
    echo $status;
    echo $kampus;
    echo $ruangan;
    echo $nama;
    echo $no;
    print_r($statusLocker);
    $sql = "UPDATE locker set `status`= '$status' Where lokasi = '$kampus'and ruangan = '$ruangan'and nama = '$nama'";
    $query = mysqli_query($db, $sql);
}
function updateUser($db, $user, $pass, $locker)
{

    $sql = "UPDATE user set `locker`= '$locker' Where username = '$user'and password = '$pass'";
    $query = mysqli_query($db, $sql);
}
function LockerUpdateUser($db, $locker,$kampus, $ruangan, $nama,$no)
{
    $UserLocker = $kampus.";".$ruangan.";".$nama.";".$no;
    $sql = "UPDATE user set `locker`= '$locker' Where locker = '$UserLocker'";
    $query = mysqli_query($db, $sql);
}

if (isset($kampus) && isset($lokasi) && isset($nama) && isset($_SESSION['username']) && $_SESSION['pass']) {

    update($db, $kampus, $lokasi, $nama, $no, 0);
    updateUser($db, $_SESSION['username'], $_SESSION['pass'], "-");
    header("location:../index.php");
    exit();
} 
elseif (isset($kampus) && isset($lokasi) && isset($nama) && isset($_GET['auth'])) {
    update($db, $kampus, $lokasi, $nama, $no, 0);
    LockerUpdateUser($db, "-", $kampus, $lokasi, $nama, $no);
    echo "Data Berhasil Berubah";
    exit();
  }  
  else {
    header("Location: ../../login.php");
    exit();
}
  
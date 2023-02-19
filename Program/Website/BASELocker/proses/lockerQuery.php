<?php
session_start();
include "../koneksi.php";
$kampus    =    $_GET['kampus'];
$ruangan    =    $_GET['ruangan'];
$nama    =    $_GET['nama'];
$no     =    $_GET['no'];

function statusSelector($db, $kampus, $ruangan, $nama)
{
	$sql = "SELECT status FROM locker Where lokasi = '$kampus'and ruangan = '$ruangan'and nama = '$nama'";
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
    $statusLocker   =  $_SESSION['statusLocker'];
    $statusLocker[$no - 1] = $status;
    $status = implode("", $statusLocker);
    echo $status;
    print_r($statusLocker);
    $sql = "UPDATE locker set `status`= '$status' Where lokasi = '$kampus'and ruangan = '$ruangan'and nama = '$nama'";
    $query = mysqli_query($db, $sql);
}
function updateUser($db, $user, $pass, $locker)
{

    $sql = "UPDATE user set `locker`= '$locker' Where username = '$user'and password = '$pass'";
    $query = mysqli_query($db, $sql);
}

if (isset($_SESSION['kampus']) && isset($_SESSION['ruangan']) && isset($_SESSION['nama']) && isset($_SESSION['username']) && $_SESSION['pass']) {

    $statusLocker = statusSelector($db, $_SESSION['kampus'], $_SESSION['ruangan'], $_SESSION['nama']);
    if ($statusLocker[$no-1] == 0)
    {
    update($db, $_SESSION['kampus'], $_SESSION['ruangan'], $_SESSION['nama'], $no, 1);

    $userLocker = array($_SESSION['kampus'], $_SESSION['ruangan'], $_SESSION['nama'], $no);
    $userLocker = join(";", $userLocker);

    updateUser($db, $_SESSION['username'], $_SESSION['pass'], $userLocker);

    header("location:../index.php");
    exit();
    }
    else
    {
        header("location:../pages/lockerStatus.php");
    }
} else {
    header("Location: ../login.php");
    exit();
}

	<?php
session_start();
include "../koneksi.php";

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

if (isset($_POST['kampus']) && isset($_POST['ruangan']) && isset($_POST['nama'])) {

    $iduser = $_SESSION['iduser'];
    $nama = $_SESSION['username'];
	$pass = $_SESSION['pass'];
	if (empty($nama) || empty($pass)) {
		header("Location: index.php");
		exit();
	} else {

		$statusLocker = statusSelector($db, $_POST['kampus'], $_POST['ruangan'], $_POST['nama']);
		$_SESSION['statusLocker'] = $statusLocker;
		$_SESSION['nama'] = $_POST['nama'];
		$_SESSION['kampus'] = $_POST['kampus'];
		$_SESSION['ruangan'] = $_POST['ruangan'];
		header("Location: ../pages/lockerStatus.php");
		exit();
	}
} else {
	header("Location: login.php");
	exit();
}

<?php
include "koneksi.php";

$kampus    =    $_GET['kampus'];
$lokasi    =    $_GET['lokasi'];
$nama    =    $_GET['nama'];

function statusSelector($db, $kampus, $ruangan, $nama)
{
	$sql = "SELECT status FROM locker Where lokasi = '$kampus'and ruangan = '$ruangan'and nama = '$nama'";
	$lockerLocation = "";

	$result = mysqli_query($db, $sql);
	if (mysqli_num_rows($result) === 1) {
		while ($row = mysqli_fetch_array($result)) {
			$lockerLocation  = $row['status'];
		}
		return $lockerLocation;
	} else {
		echo $kampus.$ruangan.$nama;
	}
}

$status = statusSelector($db, $kampus, $lokasi, $nama);
echo "=". $status;
?>

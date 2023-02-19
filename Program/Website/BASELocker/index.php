<?php

session_start();
include 'koneksi.php';
$tgl = date('Y-m-d');

function locker($db, $location)
{
	$sql = "SELECT $location FROM locker";
	$lockerLocation = [];

	$result = mysqli_query($db, $sql);

	while ($row = mysqli_fetch_array($result)) {
		array_push($lockerLocation, $row[$location]);
	}
	$lockerLocation = array_unique($lockerLocation);

	return $lockerLocation;
}
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
	} 
}

function lockerSelector($db, $user, $pass)
{
	$sql = "SELECT locker FROM user	 Where username = '$user' and password = '$pass'";
	$lockerLocation = "";

	$result = mysqli_query($db, $sql);
	if (mysqli_num_rows($result) === 1) {
		while ($row = mysqli_fetch_array($result)) {
			$lockerLocation  = $row['locker'];
		}
		return $lockerLocation;
	} 
}

if ((isset($_SESSION['iduser']) && (isset($_SESSION['username']) && (isset($_SESSION['pass']))))) {
	$iduser = $_SESSION['iduser'];
	$nama = $_SESSION['username'];
?>
	<!doctype html>
	<html>

	<head>
		<title>Sekolah Maju Bersama</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Bootstrap CSS -->

		<link rel="stylesheet" type="text/css" href="style.css">
	</head>

	<body>
		<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
		<script type="text/javascript" src="js/propeller.min.js"></script>

		<div id="container">
			<div id="header">
				<div id="logo-container">
					<image id="logo" src="images/BASE.png" class="center">
				</div>
				<div id="opening-container">
					<div class="salam">
						<h1>
							<div id="nama-user">Hai <?php echo $_SESSION['username']; ?>!</div>
						</h1>
					</div>
					<br>
					<div>
						<h4 id="keterangan">Silahkan memilih lokasi loker yang ingin kamu pinjam :</h4>
					</div>
				</div>
			</div>

			<?php
			$statusLocker = lockerSelector($db, $_SESSION['username'], $_SESSION['pass']);
			if ($statusLocker != "-") { 
				?>

				<div id="content-locker" class="center">
					
				<?php
						$titik = "'";
						$statusLocker = explode(";", $statusLocker);
						$locker = statusSelector($db, $statusLocker[0], $statusLocker[1], $statusLocker[2]);
						$lockerLenght = count($locker);
						for ($i = 1; $i <= $lockerLenght; $i += 1) {
							if ($i == $statusLocker[3]){
								echo '<a onclick="returnLocker(' . $i . ',' . $titik . strval($statusLocker[0]) . $titik .  ',' . $titik . strval($statusLocker[1]). $titik .',' . $titik . strval($statusLocker[2]) . $titik . ')" style="height:80px;width:80px;background-color: #EBEBEB;border: 10px solid green;" class="center">' . $i . '</a>';
								continue;
							}
							echo '<a style="height:80px;width:80px;background-color: #EBEBEB;padding: 10px" class="center">' . $i . '</a>';
						}
						?>
					
				</div>
				<?php 
            echo '
            <script type="text/javascript">
                function returnLocker(lockerPosition, kampus,lokasi, name) {
                    if (confirm("Do you want to return " + lockerPosition + " in " + name)) {
                        window.location.href = "http://192.168.43.7/BASELocker/proses/returnLocker.php?kampus=" + kampus + "&lokasi=" + lokasi + "&name=" + name + "&position=" + lockerPosition ;
                    } 
                    else 
                    {
                       
                    }
                }
            </script>';
            ?>
			<?php 
			} else {
			?>
				<div id="content-container">
					<div class="container">
						<div class="row">
							<form action="proses/LockerSelection.php" method="post" class="">
								<label for="kampus">Lokasi Kampus :</label><br>
								<select name="kampus" id="kampus" required>
									<option selected="true" disabled="disabled">Pilih Lokasi Kampus</option>
									<?php
									$locateArray = locker($db, 'lokasi');

									foreach ($locateArray as $kampus) {
										print '<option value="' . $kampus . '">' . $kampus . '</option>';
									} ?>
								</select>
								<br>
								<label for="ruangan">Ruangan :</label><br>
								<select name="ruangan" id="ruangan" required>
									<option selected="true" disabled="disabled">Pilih Ruangan Loker</option>
									<?php
									$locateArray = locker($db, 'ruangan');
									foreach ($locateArray as $ruangan) {
										print '<option value="' . $ruangan . '">' . $ruangan . '</option>';
									} ?>
								</select>
								<br>
								<label for="nama">Nama Locker :</label><br>
								<select name="nama" id="nama" required>
									<option selected="true" disabled="disabled">Pilih Nama Loker</option>
									<?php
									$locateArray = locker($db, 'nama');
									foreach ($locateArray as $nama) {
										print '<option value="' . $nama . '">' . $nama . '</option>';
									} ?>
								</select>

								<br>
								<input type="submit" name="submit" value="Choose options">
							</form>
						</div>
					</div>

				</div>
			<?php
			} ?>

			<!-- <div id="footer">
				<h3>Sekolah Maju Bersama</h3>
			</div> -->
		</div>
	</body>

	</html>
<?php
} else {

	echo '<a href="login.php">Login Page</a>';

}
?>
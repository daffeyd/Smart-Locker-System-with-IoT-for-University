<?php
session_start();
include '../koneksi.php';
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
	} else {
		header("Location: index.php");
		exit();
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

        <link rel="stylesheet" type="text/css" href="../style.css">
    </head>


    <body>
        <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script type="text/javascript" src="js/propeller.min.js"></script>

        <div id="container">
            <div id="header">
                <div id="logo-container">
                    <image id="logo" src="../images/BASE.png" class="center">
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

            <div id="content-locker" class="center">

                <?php
                $statusLocker = statusSelector($db, $_SESSION['kampus'], $_SESSION['ruangan'], $_SESSION['nama']);
                $_SESSION['statusLocker'] = $statusLocker;

                $lockerLenght = count($_SESSION['statusLocker']);
                // $lockerStatus = explode("",$_SESSION['statusLocker']);
                $lockerStatus = $_SESSION['statusLocker'];
                $titik = "'";
                
                for ($i = 1; $i <= $lockerLenght; $i += 1) {
                    if ($lockerStatus[$i-1] == 1) {
                        echo '<a  onclick="selectOther()" style="height:100px;width:100px;background-color:pink" class="center">' . $i . '</a   >';
                        continue;
                    }
                    echo '<a  onclick="selectLocker(' . $i . ',' . $titik . strval($_SESSION['nama']) . $titik . ')" style="height:100px;width:100px;background-color: #90EE90" class="center">' . $i . '</a   >';
                }
                ?>
            </div>


            <?php 
            echo '
            <script type="text/javascript">
                function selectLocker(lockerPosition, name) {
                    if (confirm("Do you want to choose " + lockerPosition + " in " + name)) {
                        window.location.href = "http://192.168.43.7/BASELocker/proses/lockerQuery.php?kampus='.$titik .$_SESSION["kampus"] .$titik. '&ruangan=' .$titik. $_SESSION["ruangan"].$titik . '&nama='.$titik . $_SESSION["nama"] .$titik.'&no=" + lockerPosition ;
                    } 
                    else 
                    {
                       
                    }
                }
                function selectOther() {
                    alert("Please Select Other Locker");
                }
            </script>';
            ?>
            <!-- <div id="footer">
				<h3>Sekolah Maju Bersama</h3>
			</div> -->
        </div>
    </body>

    </html>
<?php
} else {
?>
    <a href="login.php">Login Page</a>
<?php
}
?>
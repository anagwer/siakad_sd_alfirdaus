<?php
include '../../../../assets/koneksi.php';

$idj=$_GET['id'];


$sql2="DELETE from jadwal where id_jadwal='$idj' ";
$result2=mysqli_query($conn, $sql2) or die(mysqli_error()) ;


?>

<script type="text/javascript">
	alert('jadwal dihapus');
	window.location.href="../../?a=jadwal"
</script>

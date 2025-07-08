
<?php
include "../../../../assets/koneksi.php";


$nama		= $_POST['nama'];
$jk		    = $_POST['jk'];
$tmpl		= $_POST['tmpl'];
$tgll		= $_POST['tgll'];
$nip		= $_POST['nip'];
$gol		= $_POST['gol'];
$jabatan	= $_POST['jabatan'];
$alamat		= $_POST['alamat'];
$notelp		= $_POST['notelp'];
$ijazah		= $_POST['ijazah'];


	$sql = "INSERT into guru set 	
	nama_guru = '$nama',
	jk = '$jk',
	tmpl = '$tmpl',
	tgll = '$tgll',
	nip = '$nip',
	gol = '$gol',
	jabatan = '$jabatan',
	alamat = '$alamat',
	notelp = '$notelp',
	ijazah_tahun = '$ijazah'
	
";

mysqli_query($conn, $sql)or die (mysqli_error());
?>
<script type='text/javascript'>
	alert('Data guru berhasil disimpan');
	window.location.href="../../index.php?a=guru"
</script>
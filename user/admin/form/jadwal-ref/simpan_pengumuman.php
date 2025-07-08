<?php 
session_start();
include "../../../../assets/koneksi.php" ;

$tgls = date('Y-m-d');
$judul= $_POST['judul'];
$ket= $_POST['ket'];
$ekstensi_diperbolehkan	= array('jpg','JPEG','JPG','PNG','png', 'jpeg','pdf','PDF');
$lokasifile=$_FILES['file']['tmp_name'];
$file=$_FILES['file']['name'];
$x = explode('.', $file);
$ekstensi = strtolower(end($x));
$ukuran=$_FILES['file']['size'];

$namafile=date('his').$file;
$folder="file/".$namafile;


if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){


		$upload=move_uploaded_file($lokasifile, $folder);
	$perintah= "INSERT into jadwal
	set nama_jadwal='$judul', keterangan='$ket', file='$namafile'";
			
			$sql=mysqli_query($conn, $perintah)or die(mysqli_error());
			$_SESSION['pesan']='<div class="callout callout-info">
        <h4>Jadwal dengan file berhasil ditambahkan</h4>
       
       
      </div>';
		
		}
else {
	
				$perintah= "INSERT into jadwal
	set nama_jadwal='$judul', keterangan='$ket'";
			$sql=mysqli_query($conn, $perintah)or die(mysqli_error());
			
	$_SESSION['pesan']='<div class="callout callout-success">
        <h4>Jadwal berhasil ditabahkan</h4>

       
      </div>';
}




header("Location:../../?a=jadwal");



 ?>
 
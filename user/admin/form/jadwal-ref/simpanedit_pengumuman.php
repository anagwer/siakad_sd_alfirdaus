<?php 
session_start();
include "../../../../assets/koneksi.php" ;

$tgls = date('Y-m-d');
$id= $_POST['id'];
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
	$perintah= "UPDATE jadwal
	set nama_jadwal='$judul', keterangan='$ket', tgl_input='$tgls', file='$namafile' where id_jadwal='$id'";
			
			$sql=mysqli_query($conn, $perintah)or die(mysqli_error());
			$_SESSION['pesan']='<div class="callout callout-info">
        <h4>Jadwal dengan file berhasil ditambahkan</h4>
       
       
      </div>';
		
		}
else {
	
				$perintah= "UPDATE jadwal 
	set nama_jadwal='$judul', keterangan='$ket', tgl_input='$tgls' where id_jadwal='$id'";
			$sql=mysqli_query($conn, $perintah)or die(mysqli_error());
			
	$_SESSION['pesan']='<div class="callout callout-success">
        <h4> Jadwal berhasil ditabahkan</h4>

       
      </div>';
}




header("Location:../../?a=jadwal");



 ?>
 
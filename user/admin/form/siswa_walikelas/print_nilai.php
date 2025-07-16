<?php
session_start();
include "../../../../assets/koneksi.php";
require_once("../../../../assets/dompdf/src/Autoloader.php");
Dompdf\Autoloader::register();
use Dompdf\Dompdf;

// Ambil parameter dari URL
$id_ta_aktif = isset($_GET['id_ta']) ? $_GET['id_ta'] : '';
$id_kelas = isset($_GET['id_kelas']) ? $_GET['id_kelas'] : '';
$semester_ke = isset($_GET['semester']) ? $_GET['semester'] : '';
$id_wk = $_SESSION['id_user'];

// Array untuk nama semester
$semester = [1 => 'Ganjil', 'Genap'];

// Query untuk tahun ajaran aktif
$qta = mysqli_query($conn, "SELECT * FROM tahun_ajaran WHERE id_ta='$id_ta_aktif' AND semester='$semester_ke'");
$jta = mysqli_num_rows($qta);
$dta = mysqli_fetch_array($qta);

// Jika tidak ada tahun ajaran yang sesuai, gunakan tahun ajaran tanpa filter semester
if ($jta == 0) {
    $qta_selesai = mysqli_query($conn, "SELECT * FROM tahun_ajaran WHERE id_ta='$id_ta_aktif'");
    $dta_selesai = mysqli_fetch_array($qta_selesai);
    $id_ta = $dta_selesai['id_ta'];
    $status_ta = $dta_selesai['status_ta'];
    $semester_aktif = $semester[$semester_ke];
} else {
    $id_ta = $dta['id_ta'];
    $status_ta = $dta['status_ta'];
    $semester_aktif = $semester[$dta['semester']];
}

// Query untuk kelas
$qkelas = mysqli_query($conn, "SELECT * FROM kelas WHERE id_kelas='$id_kelas'");
$dkelas = mysqli_fetch_array($qkelas);

// Query untuk wali kelas
$qcekwk = mysqli_query($conn, "SELECT b.nama_guru, a.status_wali_kelas, a.id_guru, b.nip, a.username, a.id_walikelas 
                                FROM wali_kelas a 
                                LEFT JOIN guru b ON a.id_guru = b.id_guru 
                                WHERE a.id_kelas='$id_kelas' 
                                ORDER BY id_walikelas DESC LIMIT 1");
$jcekwk = mysqli_num_rows($qcekwk);
$dcekwk = mysqli_fetch_array($qcekwk);
$id_wk_aktif = $dcekwk['id_guru'];
$id_wali_kelas = $dcekwk['id_walikelas'];

// Mulai membuat HTML untuk PDF
$html = '
<center>
  <h3>LAPORAN NILAI AKHIR HASIL BELAJAR <br>PENILAIAN AKHIR SEMESTER</h3>
</center>
<hr>  
<table style="font-size:14px;border-collapse: collapse;">
   <tr>
        <td width="200px">Kelas</td>
        <td>' . $dkelas['tingkat'] . ' - ' . $dkelas['nama_kelas'] . '</td>
      </tr>
';

if ($jta == 0) {
    $html .= '
   <tr>
        <td width="200px">Tahun Ajaran</td>
        <td>' . $dta_selesai['nama_ta'] . '</td>
      </tr>
   <tr>
        <td width="200px">Semester</td>
        <td>' . $semester[$semester_ke] . '</td>
      </tr>
      </table>
    ';
} else {
    $html .= '
   <tr>
        <td width="200px">Tahun Ajaran</td>
        <td>' . $dta['nama_ta'] . '</td>
      </tr>
   <tr>
        <td width="200px">Semester</td>
        <td>' . $semester[$dta['semester']] . '</td>
      </tr>
      </table>
    ';
}

$html .= '<br>';

// Ambil data mata pelajaran berdasarkan tingkat kelas
$tingkat = $dkelas['tingkat'];
$qmapel = mysqli_query($conn, "SELECT * FROM mapel WHERE tingkat='$tingkat'");
$jmapel = mysqli_num_rows($qmapel);
$kumpulkan_mapel = [];
while ($dmapel = mysqli_fetch_array($qmapel)) {
    $data = [
        'id_mapel' => $dmapel['id_mapel'],
        'nama_mapel' => $dmapel['nama_mapel'],
        'kkm' => $dmapel['kkm']
    ];
    array_push($kumpulkan_mapel, $data);
}

// Tabel untuk daftar siswa dan nilai
$html .= '
 <table style="font-size:12px;border-collapse: collapse; width:100%" border="1">
    <thead>
      <tr>
      <th rowspan="2">No</th>
        <th rowspan="2">NIS</th>
        <th rowspan="2">NISN</th>
        <th rowspan="2">Nama Siswa</th>
        <th rowspan="2">Jenis Kelamin</th>
        <th colspan="' . $jmapel . '"><center>Mata Pelajaran</center></th>
        <th rowspan="2">Total Nilai</th>
        <th rowspan="2">Rata Rata</th>
';

if ($dta['status_ta'] == 'Selesai' && $semester_ke == '2') {
    $html .= '
 <th rowspan="2">Keputusan</th>
</tr>
';
}

$html .= '
<tr>
';
foreach ($kumpulkan_mapel as $value) {
    $html .= '
   <th style="font-size:12px; margin:5px">' . $value['nama_mapel'] . '</th>     
  ';
}
$html .= '
   </tr>     
  ';

// Ambil data siswa dan nilai
$no = 1;
$mapel = mysqli_query($conn, "SELECT a.status_ks, a.id_siswa, b.nama_siswa, b.nis, b.nisn, b.jk 
                               FROM kelas_siswa a
                               LEFT JOIN siswa b ON a.id_siswa = b.id_siswa
                               WHERE a.id_kelas='$id_kelas' AND a.id_ta='$id_ta_aktif'");
while ($data = mysqli_fetch_array($mapel)) {
    $html .= '
   <tr>     
  ';
    $html .= '
  <td>' . $no++ . '</td>
  <td>' . $data['nis'] . '</td>
  <td>' . $data['nisn'] . '</td>
  <td>' . $data['nama_siswa'] . '</td>
  <td>' . $data['jk'] . '</td>';

    $total_nilai = 0;
    $tuntas = 0;
    $tidak_tuntas = 0;

    foreach ($kumpulkan_mapel as $dmapel) {
        $id = $data['id_siswa'];
        $id_mapel = $dmapel['id_mapel'];
        $qnilai = mysqli_query($conn, "SELECT * FROM nilai WHERE id_kelas='$id_kelas' AND id_ta='$id_ta_aktif' AND id_wali_kelas='$id_wk' AND id_mapel='$id_mapel' AND id_siswa='$id' AND semester='$semester_ke'");
        $dnilai = mysqli_fetch_array($qnilai);
        $nilai = $dnilai['nilai'] ?? 0;
        $total_nilai += $nilai;

        if ($nilai < $dmapel['kkm']) {
            $warna = "red";
            $tidak_tuntas++;
        } else {
            $warna = "green";
            $tuntas++;
        }

        $show_nilai = $nilai == '' ? '-' : $nilai;
        $html .= '
  <td><p style="color: ' . $warna . '">' . $show_nilai . '</p></td>
  ';
    }

    $show_total_nilai = $total_nilai == 0 ? '-' : $total_nilai;
    $ratarata = round($total_nilai / $jmapel, 2);
    $show_ratarata = $ratarata == 0 ? '-' : $ratarata;

    $html .= '
  <td>' . $show_total_nilai . '</td>     
  <td>' . $show_ratarata . '</td>     
  ';

    if ($dta['status_ta'] == 'Selesai' && $semester_ke == '2') {
        if ($data['status_ks'] == 'Aktif') {
            $keputusan = "Belum ada keputusan";
        } elseif ($data['status_ks'] == 'Lanjut') {
            $next_kelas = $tingkat + 1;
            $keputusan = "Naik ke Kelas " . $next_kelas;
        } else {
            $keputusan = "Tinggal di Kelas " . $tingkat;
        }
        $html .= '
  <td>' . $keputusan . '</td>     
  ';
    }

    $html .= '
   </tr>     
  ';
}

// Render PDF menggunakan Dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('Data Nilai Siswa.pdf', ['Attachment' => 0]);
?>
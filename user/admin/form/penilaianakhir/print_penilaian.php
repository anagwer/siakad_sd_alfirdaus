<?php
session_start();
include '../../../../assets/koneksi.php';

// Cek login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

$level = $_SESSION['level'];
$id_user = $_SESSION['id_user'];

// Mendapatkan tahun ajaran aktif
$tahun_ajaran_aktif = date('Y');
$semester_aktif = (date('n') <= 6) ? 1 : 2;
$query_ta = "SELECT * FROM tahun_ajaran WHERE semester = $semester_aktif AND status_ta = 'Aktif'";
$result_ta = mysqli_query($conn, $query_ta);
$data_ta = mysqli_fetch_assoc($result_ta);
$id_ta_aktif = $data_ta['id_ta'];

// Proses Simpan Nilai
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['simpan_nilai'])) {
    $id_siswa = $_POST['id_siswa'];
    $id_mapel = $_POST['id_mapel'];
    $id_kelas = $_POST['id_kelas'];
    $id_ta_aktif = $_POST['id_ta_aktif'];
    $ulangan1 = $_POST['ulangan1'];
    $ulangan2 = $_POST['ulangan2'];
    $ulangan3 = $_POST['ulangan3'];
    $ulangan4 = $_POST['ulangan4'];
    $uts = $_POST['uts'];
    $uas = $_POST['uas'];
    $total = $ulangan1 + $ulangan2 + $ulangan3 + $ulangan4 + $uts + $uas;
    $rata_rata = round($total / 6, 2);

    $query = "INSERT INTO penilaian (id_siswa, id_mapel, id_kelas, id_ta, ulangan1, ulangan2, ulangan3, ulangan4, uts, uas, rata_rata)
              VALUES ('$id_siswa', '$id_mapel', '$id_kelas', '$id_ta_aktif', '$ulangan1', '$ulangan2', '$ulangan3', '$ulangan4', '$uts', '$uas', '$rata_rata')";
    mysqli_query($conn, $query);
}

// Proses Update Nilai
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['simpan_edit'])) {
    $id_nilai = $_POST['id_nilai'];
    $id_siswa = $_POST['id_siswa'];
    $id_mapel = $_POST['id_mapel'];
    $id_kelas = $_POST['id_kelas'];
    $id_ta_aktif = $_POST['id_ta_aktif'];
    $ulangan1 = $_POST['ulangan1'];
    $ulangan2 = $_POST['ulangan2'];
    $ulangan3 = $_POST['ulangan3'];
    $ulangan4 = $_POST['ulangan4'];
    $uts = $_POST['uts'];
    $uas = $_POST['uas'];
    $total = $ulangan1 + $ulangan2 + $ulangan3 + $ulangan4 + $uts + $uas;
    $rata_rata = round($total / 6, 2);

    $query = "UPDATE penilaian SET
        ulangan1 = '$ulangan1',
        ulangan2 = '$ulangan2',
        ulangan3 = '$ulangan3',
        ulangan4 = '$ulangan4',
        uts = '$uts',
        uas = '$uas',
        rata_rata = '$rata_rata'
        WHERE id_nilai = '$id_nilai'";

    mysqli_query($conn, $query);
    
}

// Proses Hapus Nilai
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['hapus'])) {
    $id_nilai = $_POST['id_nilai'];
    mysqli_query($conn, "DELETE FROM penilaian WHERE id_nilai='$id_nilai'");
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cetak Jadwal Pelajaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h3 {
            margin: 0;
        }
        .kode-guru {
            margin-top: 20px;
        }
        .kode-guru table {
            width: 50%;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Halaman Penilaian</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        <?php if ($level === 'Wali Kelas'): ?>
            <?php
            $query_kelas = "SELECT id_kelas FROM wali_kelas WHERE id_guru = '$id_user' AND status_wali_kelas = '1'";
            $result_kelas = mysqli_query($conn, $query_kelas);
            $data_kelas = mysqli_fetch_assoc($result_kelas);
            if (!$data_kelas) {
                echo "<p>Anda bukan Wali Kelas aktif.</p>";
            } else {
                $id_kelas = $data_kelas['id_kelas'];
                $query_nama_kelas = "SELECT nama_kelas FROM kelas WHERE id_kelas='$id_kelas'";
                $nama_kelas = mysqli_fetch_assoc(mysqli_query($conn, $query_nama_kelas))['nama_kelas'];
            ?>
                <!-- Card Info -->
                <div class="card card-primary">
                    <div class="card-body">
                        <h5>Kelas: <?= $nama_kelas ?></h5>
                        <h5>Tahun Ajaran: <?= $tahun_ajaran_aktif ?></h5>
                        <h5>Semester <?= (date('n') <= 6) ? 'Ganjil' : 'Genap'; ?></h5>
                    </div>
                </div>
            <?php } ?>

            <div class="table-responsive">
                <table class="table table-bordered" id="example1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Mapel</th>
                            <th>Ulangan 1</th>
                            <th>Ulangan 2</th>
                            <th>Ulangan 3</th>
                            <th>Ulangan 4</th>
                            <th>UTS</th>
                            <th>UAS</th>
                            <th>Rata-Rata</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Query Data Siswa
                        if ($level === 'Wali Kelas') {
                            $query_siswa = "SELECT s.id_siswa, s.nama_siswa 
                                            FROM kelas_siswa ks 
                                            JOIN siswa s ON ks.id_siswa = s.id_siswa 
                                            WHERE ks.id_kelas = '$id_kelas'";
                        }if ($level === 'Siswa') {
                            $query_siswa = "SELECT s.id_siswa, s.nama_siswa 
                                            FROM kelas_siswa ks 
                                            JOIN siswa s ON ks.id_siswa = s.id_siswa 
                                            WHERE ks.id_siswa = '$id_user'";
                        } else {
                            $query_siswa = "SELECT * FROM siswa";
                        }

                        $result_siswa = mysqli_query($conn, $query_siswa);
                        $no = 1;

                        while ($siswa = mysqli_fetch_assoc($result_siswa)) {
                            $id_siswa = $siswa['id_siswa'];
                            $query_nilai = "SELECT n.*, m.nama_mapel, m.kkm 
                                            FROM penilaian n
                                            LEFT JOIN mapel m ON n.id_mapel = m.id_mapel
                                            WHERE n.id_siswa = '$id_siswa'";

                            $result_nilai = mysqli_query($conn, $query_nilai);

                            while ($nilai = mysqli_fetch_assoc($result_nilai)): 
                                // Ambil id_kelas dari tabel kelas_siswa
                                $query_kelas_siswa = "SELECT id_kelas FROM kelas_siswa WHERE id_siswa = '{$siswa['id_siswa']}' LIMIT 1";
                                $data_kelas_siswa = mysqli_fetch_assoc(mysqli_query($conn, $query_kelas_siswa));
                                $id_kelas_loop = $data_kelas_siswa['id_kelas'];
                                ?>

                                <tr>
                                    <td><?= $no ?></td>
                                    <td><?= $siswa['nama_siswa'] ?></td>
                                    <td><?= $nilai['nama_mapel'] ?></td>
                                    <td><?= $nilai['ulangan1'] ?></td>
                                    <td><?= $nilai['ulangan2'] ?></td>
                                    <td><?= $nilai['ulangan3'] ?></td>
                                    <td><?= $nilai['ulangan4'] ?></td>
                                    <td><?= $nilai['uts'] ?></td>
                                    <td><?= $nilai['uas'] ?></td>
                                    <td class="<?= ($nilai['rata_rata'] < $nilai['kkm']) ? 'bg-red text-white' : '' ?>">
                                        <?= number_format($nilai['rata_rata'], 2) ?>
                                    </td>
                                </tr>

                                <?php $no++; endwhile; }
                        ?>
                    </tbody>
                </table>
            </div>
        <?php endif;?>
    </div>
</section>

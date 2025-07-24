<?php
session_start();
include '../../../../assets/koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

$level = $_SESSION['level'];
$id_user = $_SESSION['id_user'];

// Mendapatkan tahun ajaran aktif
$tahun_ajaran_aktif = date('Y');
$semester_aktif = (date('n') <= 6) ? 1 : 2;
$query_tahun_ajaran = "SELECT * FROM tahun_ajaran WHERE semester = $semester_aktif AND status_ta = 'Aktif'";
$result_tahun_ajaran = mysqli_query($conn, $query_tahun_ajaran);
$data_tahun_ajaran = mysqli_fetch_assoc($result_tahun_ajaran);
$id_ta_aktif = $data_tahun_ajaran['id_ta'];

// Proses edit status pembayaran
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_pembayaran'])) {
    $id_pembayaran = $_POST['id_pembayaran'];
    $status_pembayaran = $_POST['status_pembayaran'];
    $tanggal_pembayaran = ($status_pembayaran == 'Lunas') ? date('Y-m-d') : NULL;

    // Update data
    $query_update = "UPDATE spp SET 
                     status_pembayaran = '$status_pembayaran',
                     tanggal_pembayaran = '$tanggal_pembayaran'
                     WHERE id_spp = '$id_pembayaran'";
    mysqli_query($conn, $query_update);

    echo "<script>alert('Status pembayaran berhasil diubah.'); window.location.href='?a=SPP';</script>";
    exit();
}

// Proses tambah tagihan 12 bulan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buat_tagihan'])) {
    $id_kelas = $_POST['id_kelas'];
    $jumlah_spp = $_POST['jumlah_spp'];
    $year = date('Y');

    // Ambil semua siswa di kelas ini
    $query_siswa = "SELECT s.id_siswa FROM kelas_siswa ks JOIN siswa s ON ks.id_siswa = s.id_siswa WHERE ks.id_kelas = '$id_kelas'";
    $result_siswa = mysqli_query($conn, $query_siswa);

    // Hapus jika sudah ada tagihan untuk kelas ini
    $delete_query = "DELETE FROM spp WHERE id_kelas = '$id_kelas' and tahun = '$year'";
    mysqli_query($conn, $delete_query);

    // Masukkan tagihan baru
    $bulan_list = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    while ($row_siswa = mysqli_fetch_assoc($result_siswa)) {
        foreach ($bulan_list as $bulan) {
            $tahun = date('Y');
            $insert_query = "INSERT INTO spp (id_siswa, id_kelas, bulan, tahun, jumlah, status_pembayaran)
                             VALUES ('{$row_siswa['id_siswa']}', '$id_kelas', '$bulan', '$tahun', '$jumlah_spp', 'Belum Lunas')";
            mysqli_query($conn, $insert_query);
        }
    }

    echo "<script>alert('Tagihan SPP berhasil dibuat.'); window.location.href='?a=SPP';</script>";
    exit();
}
?>
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
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0" style="text-align:center;">Halaman Pembayaran SPP</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        <?php if ($level !== 'Siswa'): ?>

            <?php
            $id_siswa_login = $_SESSION['id_user'];

            if ($level === 'Wali Kelas'):
            // Data Wali Kelas
            $query_kelas = "SELECT id_kelas FROM wali_kelas WHERE id_guru = '$id_user' AND status_wali_kelas = '1'";
            $result_kelas = mysqli_query($conn, $query_kelas);
            $data_kelas = mysqli_fetch_assoc($result_kelas);

            if (!$data_kelas) {
                echo "<p>Anda bukan Wali Kelas aktif.</p>";
            } else {
                $id_kelas = $data_kelas['id_kelas'];
            }
                $query_spp = "SELECT s.id_spp, s.bulan, s.tahun, s.jumlah, s.status_pembayaran, s.tanggal_pembayaran,
                        siswa.nama_siswa, siswa.nis
                FROM spp s
                JOIN siswa ON s.id_siswa = siswa.id_siswa
                WHERE s.id_kelas = '$id_kelas'
                ORDER BY FIELD(s.bulan, 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember')";
            else:
                $query_spp = "SELECT s.id_spp, s.bulan, s.tahun, s.jumlah, s.status_pembayaran, s.tanggal_pembayaran,
                     siswa.nama_siswa, siswa.nis
                    FROM spp s
                    JOIN siswa ON s.id_siswa = siswa.id_siswa
                    ORDER BY FIELD(s.bulan, 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember')";

            endif;
            $result_spp = mysqli_query($conn, $query_spp);
            ?>

            <div class="table-responsive">
                <table class="table" >
                    <thead>
                        <tr>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Bulan</th>
                            <th>Tahun</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Tanggal Bayar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($data = mysqli_fetch_assoc($result_spp)): ?>
                        <tr>
                            <td><?= $data['nis'] ?></td>
                            <td><?= $data['nama_siswa'] ?></td>
                            <td><?= $data['bulan'] ?></td>
                            <td><?= $data['tahun'] ?></td>
                            <td>Rp <?= number_format($data['jumlah'], 2, ',', '.') ?></td>
                            <td><?= $data['status_pembayaran'] ?></td>
                            <td><?= $data['tanggal_pembayaran'] ?: '-' ?></td>
                            

                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                

            </div>
        <?php else: ?>
            <?php
            $id_siswa_login = $_SESSION['id_user'];
            $query_spp = "SELECT s.bulan, s.tahun, s.jumlah, s.status_pembayaran, s.tanggal_pembayaran 
                          FROM spp s 
                          WHERE s.id_siswa = '$id_siswa_login'
                          ORDER BY FIELD(s.bulan, 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember')";
            $result_spp = mysqli_query($conn, $query_spp);
            ?>
            <div class="table-responsive">
                <table class="table table-bordered" id="example1">
                    <thead>
                        <tr>
                            <th>Bulan</th>
                            <th>Tahun</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Tanggal Bayar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($data = mysqli_fetch_assoc($result_spp)): ?>
                        <tr>
                            <td><?= $data['bulan'] ?></td>
                            <td><?= $data['tahun'] ?></td>
                            <td>Rp <?= number_format($data['jumlah'], 2, ',', '.') ?></td>
                            <td><?= $data['status_pembayaran'] ?></td>
                            <td><?= $data['tanggal_pembayaran'] ?: '-' ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</section>
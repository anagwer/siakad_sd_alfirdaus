<?php
session_start();
include '../../../../assets/koneksi.php';
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}
$level = $_SESSION['level'];
$id_user = $_SESSION['id_user'];

// Proses simpan transaksi tabungan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan_transaksi'])) {
    $id_siswa = $_POST['id_siswa'];
    $jenis_transaksi = $_POST['jenis_transaksi'];
    $jumlah = floatval($_POST['jumlah']);
    $tanggal_transaksi = date('Y-m-d');

    // Ambil id_kelas berdasarkan id_siswa dari tabel kelas_siswa
    $q_kelas = "SELECT id_kelas FROM kelas_siswa WHERE id_siswa = '$id_siswa' ORDER BY id_kelas DESC LIMIT 1";
    $r_kelas = mysqli_query($conn, $q_kelas);
    $id_kelas = ($r_kelas && mysqli_num_rows($r_kelas) > 0) ? mysqli_fetch_assoc($r_kelas)['id_kelas'] : null;

    if (!$id_kelas) {
        echo "<script>alert('ID Kelas tidak ditemukan untuk siswa ini.'); window.history.back();</script>";
        exit();
    }

    // Ambil saldo terakhir dari tabungan siswa
    $query_saldo = "SELECT saldo FROM tabungan WHERE id_siswa = '$id_siswa' ORDER BY tanggal_transaksi DESC, id_tabungan DESC LIMIT 1";
    $result_saldo = mysqli_query($conn, $query_saldo);
    $saldo_sebelumnya = ($result_saldo && mysqli_num_rows($result_saldo) > 0) ? mysqli_fetch_assoc($result_saldo)['saldo'] : 0;

    // Validasi penarikan
    if ($jenis_transaksi === 'Penarikan' && $jumlah > $saldo_sebelumnya) {
        echo "<script>alert('Saldo tidak mencukupi untuk penarikan ini.'); window.history.back();</script>";
        exit();
    }

    // Hitung saldo baru
    $saldo_baru = ($jenis_transaksi === 'Setoran') ? $saldo_sebelumnya + $jumlah : $saldo_sebelumnya - $jumlah;

    // Simpan transaksi tabungan
    $query_insert = "INSERT INTO tabungan (id_siswa, id_kelas, jenis_transaksi, jumlah, tanggal_transaksi, saldo)
                     VALUES ('$id_siswa', '$id_kelas', '$jenis_transaksi', '$jumlah', '$tanggal_transaksi', '$saldo_baru')";
    mysqli_query($conn, $query_insert);

    echo "<script>alert('Transaksi berhasil dicatat.'); window.location.href='?a=Tabungan';</script>";
    exit();
}

// Proses Edit Transaksi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_transaksi'])) {
    $id_tabungan = $_POST['id_tabungan'];
    $jenis_transaksi_baru = $_POST['jenis_transaksi_edit'];
    $jumlah_baru = floatval($_POST['jumlah_edit']);
    $tanggal_baru = $_POST['tanggal_transaksi_edit'];

    // Ambil data lama
    $query_old = "SELECT * FROM tabungan WHERE id_tabungan = '$id_tabungan'";
    $result_old = mysqli_query($conn, $query_old);
    $data_old = mysqli_fetch_assoc($result_old);
    $id_siswa = $data_old['id_siswa'];

    // Hitung ulang semua saldo setelah transaksi ini
    // Update transaksi saat ini
    $query_update = "UPDATE tabungan SET jenis_transaksi = '$jenis_transaksi_baru', jumlah = '$jumlah_baru', tanggal_transaksi = '$tanggal_baru' WHERE id_tabungan = '$id_tabungan'";
    mysqli_query($conn, $query_update);

    // Rebuild saldo untuk seluruh transaksi siswa ini
    $query_all = "SELECT * FROM tabungan WHERE id_siswa = '$id_siswa' ORDER BY tanggal_transaksi ASC";
    $result_all = mysqli_query($conn, $query_all);
    $saldo = 0;
    while ($row = mysqli_fetch_assoc($result_all)) {
        if ($row['jenis_transaksi'] == 'Setoran') {
            $saldo += $row['jumlah'];
        } else {
            $saldo -= $row['jumlah'];
        }
        mysqli_query($conn, "UPDATE tabungan SET saldo = '$saldo' WHERE id_tabungan = '{$row['id_tabungan']}'");
    }

    echo "<script>alert('Transaksi berhasil diedit.'); window.location.href='?a=Tabungan';</script>";
    exit();
}

// Proses Hapus Transaksi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus_transaksi'])) {
    $id_tabungan = $_POST['id_tabungan'];

    // Ambil data transaksi yang akan dihapus
    $query_delete = "SELECT * FROM tabungan WHERE id_tabungan = '$id_tabungan'";
    $result_delete = mysqli_query($conn, $query_delete);
    $data_delete = mysqli_fetch_assoc($result_delete);
    $id_siswa = $data_delete['id_siswa'];

    // Hapus transaksi
    mysqli_query($conn, "DELETE FROM tabungan WHERE id_tabungan = '$id_tabungan'");

    // Rebuild saldo untuk seluruh transaksi siswa ini
    $query_all = "SELECT * FROM tabungan WHERE id_siswa = '$id_siswa' ORDER BY tanggal_transaksi ASC";
    $result_all = mysqli_query($conn, $query_all);
    $saldo = 0;
    while ($row = mysqli_fetch_assoc($result_all)) {
        if ($row['jenis_transaksi'] == 'Setoran') {
            $saldo += $row['jumlah'];
        } else {
            $saldo -= $row['jumlah'];
        }
        mysqli_query($conn, "UPDATE tabungan SET saldo = '$saldo' WHERE id_tabungan = '{$row['id_tabungan']}'");
    }

    echo "<script>alert('Transaksi berhasil dihapus.'); window.location.href='?a=Tabungan';</script>";
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
                <h1 class="m-0" style="text-align:center;">Halaman Tabungan Siswa</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        <?php if ($level !== 'Siswa'): ?>

            <!-- Tabel Daftar Tabungan -->
            <div class="table-responsive">
                <table class="table table-bordered" id="example1">
                    <thead>
                        <tr>
                            <th>Nama Siswa</th>
                            <th>Jenis Transaksi</th>
                            <th>Jumlah</th>
                            <th>Tanggal</th>
                            <th>Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($level === 'Wali Kelas') {
                        // Ambil id_kelas dari tabel wali_kelas
                        $query_kelas = "SELECT id_kelas FROM wali_kelas WHERE id_guru = '$id_siswa_login' AND status_wali_kelas = '1'";
                        $result_kelas = mysqli_query($conn, $query_kelas);
                        $data_kelas = mysqli_fetch_assoc($result_kelas);
                        $id_kelas = $data_kelas['id_kelas'];
                        $query = "SELECT t.*, s.nama_siswa 
                                  FROM tabungan t
                                  JOIN siswa s ON t.id_siswa = s.id_siswa
                                  where t.id_kelas = '$id_kelas'
                                  ORDER BY t.tanggal_transaksi DESC";
                        }else{
                          $query = "SELECT t.*, s.nama_siswa 
                                  FROM tabungan t
                                  JOIN siswa s ON t.id_siswa = s.id_siswa
                                  ORDER BY t.tanggal_transaksi DESC";
                        }
                        $result = mysqli_query($conn, $query);
                        while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $row['nama_siswa'] ?></td>
                            <td><?= $row['jenis_transaksi'] ?></td>
                            <td>Rp <?= number_format($row['jumlah'], 2, ',', '.') ?></td>
                            <td><?= $row['tanggal_transaksi'] ?></td>
                            <td>Rp <?= number_format($row['saldo'], 2, ',', '.') ?></td>
                            
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

        <?php else: ?>
            <!-- View Tabungan untuk Siswa -->
            <h4>Saldo Tabungan Anda</h4>
            <?php
            $id_siswa_login = $_SESSION['id_user'];
            $query_saldo = "SELECT saldo FROM tabungan WHERE id_siswa = '$id_siswa_login' ORDER BY tanggal_transaksi DESC LIMIT 1";
            $saldo_result = mysqli_query($conn, $query_saldo);
            $saldo = $saldo_result && mysqli_num_rows($saldo_result) > 0 ? mysqli_fetch_assoc($saldo_result)['saldo'] : 0;
            ?>
            <h3>Saldo: Rp <b><?= number_format($saldo, 2, ',', '.') ?></b></h3><hr>

            <div class="table-responsive mt-3">
                <table class="table table-bordered" id="example1">
                    <thead>
                        <tr>
                            <th>Jenis Transaksi</th>
                            <th>Jumlah</th>
                            <th>Tanggal</th>
                            <th>Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query_riwayat = "SELECT * FROM tabungan WHERE id_siswa = '$id_siswa_login'
                                          ORDER BY tanggal_transaksi DESC";
                        $riwayat_result = mysqli_query($conn, $query_riwayat);
                        while ($r = mysqli_fetch_assoc($riwayat_result)) {
                        ?>
                        <tr>
                            <td><?= $r['jenis_transaksi'] ?></td>
                            <td>Rp <?= number_format($r['jumlah'], 2, ',', '.') ?></td>
                            <td><?= $r['tanggal_transaksi'] ?></td>
                            <td>Rp <?= number_format($r['saldo'], 2, ',', '.') ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</section>
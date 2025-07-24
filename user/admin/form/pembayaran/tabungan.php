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

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Halaman Tabungan Siswa</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        <?php if ($level !== 'Siswa'): ?>
            <!-- Tombol Tambah Transaksi -->
            <button type="button" class="btn btn-primary" style="margin-bottom:20px;" data-toggle="modal" data-target="#modalTambahTransaksi">
                + Tambah Transaksi
            </button>
            <button type="button" class="btn btn-success" style="margin-bottom:20px;" onclick="printPembayaran()">
                <i class="fa fa-print"></i> Cetak
            </button>

            <script>
                function printPembayaran() {
                    var printWindow = window.open('form/pembayaran/print_tabungan.php');
                    printWindow.onload = function () {
                        printWindow.print();
                        printWindow.onafterprint = function () {
                            printWindow.close();
                        };
                    };
                }
            </script>


            <!-- Modal Tambah Transaksi -->
            <div class="modal fade" id="modalTambahTransaksi" tabindex="-1" role="dialog" aria-labelledby="modalLabelTransaksi" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form method="POST">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLabelTransaksi">Tambah Transaksi Tabungan</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <?php
                                // Ambil data id_kelas dari session atau database jika user adalah Wali Kelas
                                $id_siswa_login = $_SESSION['id_user'];
                                $level = $_SESSION['level'];

                                if ($level === 'Wali Kelas') {
                                    // Ambil id_kelas dari tabel wali_kelas
                                    $query_kelas = "SELECT id_kelas FROM wali_kelas WHERE id_guru = '$id_siswa_login' AND status_wali_kelas = '1'";
                                    $result_kelas = mysqli_query($conn, $query_kelas);
                                    $data_kelas = mysqli_fetch_assoc($result_kelas);
                                    $id_kelas = $data_kelas['id_kelas'];
                                    // Ambil siswa berdasarkan kelas wali kelas
                                    $query_siswa = "SELECT s.* FROM siswa s JOIN kelas_siswa ks ON s.id_siswa = ks.id_siswa WHERE ks.id_kelas = '$id_kelas'";
                                    
                                } else {
                                    // Admin atau level lain bisa melihat semua siswa
                                    $query_siswa = "SELECT * FROM siswa";
                                }

                                $result_siswa = mysqli_query($conn, $query_siswa);
                                ?>
                                <div class="form-group">
                                    <label>Nama Siswa</label>
                                    <select name="id_siswa" class="form-control" required>
                                        <option value="">-- Pilih Siswa --</option>
                                        <?php while ($s = mysqli_fetch_assoc($result_siswa)): ?>
                                            <option value="<?= $s['id_siswa'] ?>">
                                                <?= $s['nama_siswa'] ?> (<?= $s['nis'] ?>)
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Jenis Transaksi</label>
                                    <select name="jenis_transaksi" class="form-control" required>
                                        <option value="Setoran">Setoran</option>
                                        <option value="Penarikan">Penarikan</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Jumlah</label>
                                    <input type="number" name="jumlah" step="0.01" class="form-control" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="simpan_transaksi" class="btn btn-primary">Simpan</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

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
                            <th>Aksi</th>
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
                            <?php if ($level === 'Administrator' || $level === 'Wali Kelas'): ?>
                            <td>
                                <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modalEditTransaksi<?= $row['id_tabungan'] ?>">Edit</button>
                                <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modalHapusTransaksi<?= $row['id_tabungan'] ?>">Hapus</button>
                            </td>
                            <?php endif; ?>
                        </tr>
                        <!-- Modal Edit Transaksi -->
                        <div class="modal fade" id="modalEditTransaksi<?= $row['id_tabungan'] ?>" tabindex="-1" role="dialog" aria-labelledby="modalLabelEdit" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <form method="POST">
                                    <input type="hidden" name="id_tabungan" value="<?= $row['id_tabungan'] ?>">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Transaksi</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Jenis Transaksi</label>
                                                <select name="jenis_transaksi_edit" class="form-control" required>
                                                    <option value="Setoran" <?= ($row['jenis_transaksi'] == 'Setoran') ? 'selected' : '' ?>>Setoran</option>
                                                    <option value="Penarikan" <?= ($row['jenis_transaksi'] == 'Penarikan') ? 'selected' : '' ?>>Penarikan</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Jumlah</label>
                                                <input type="number" name="jumlah_edit" step="0.01" class="form-control" value="<?= $row['jumlah'] ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Tanggal Transaksi</label>
                                                <input type="date" name="tanggal_transaksi_edit" class="form-control" value="<?= $row['tanggal_transaksi'] ?>" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" name="edit_transaksi" class="btn btn-primary">Simpan</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Modal Hapus Transaksi -->
                        <div class="modal fade" id="modalHapusTransaksi<?= $row['id_tabungan'] ?>" tabindex="-1" role="dialog" aria-labelledby="modalLabelHapus" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <form method="POST">
                                    <input type="hidden" name="id_tabungan" value="<?= $row['id_tabungan'] ?>">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Konfirmasi Hapus</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Yakin ingin menghapus transaksi ini?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" name="hapus_transaksi" class="btn btn-danger">Hapus</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
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
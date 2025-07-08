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
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Halaman Pembayaran SPP</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        <?php if ($level !== 'Siswa'): ?>
            <!-- Form Input SPP -->
            <button type="button" class="btn btn-primary mb-3" style="margin-bottom:20px;" data-toggle="modal" data-target="#modalTambahTagihan">
                + Tambah Tagihan SPP
            </button>

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
            <!-- Modal Tambah Tagihan -->
            <div class="modal fade" id="modalTambahTagihan" tabindex="-1" role="dialog" aria-labelledby="modalTambahTagihanLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form method="POST">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahTagihanLabel">Tambah Tagihan SPP</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="modal-body">
                        <?php
                        $query_kelas_modal = "SELECT * FROM kelas";
                        $result_kelas_modal = mysqli_query($conn, $query_kelas_modal);
                        ?>
                        <?php if ($level === 'Administrator'): ?>
                        <div class="form-group">
                            <label for="id_kelas">Pilih Kelas</label>
                            <select name="id_kelas" class="form-control" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php while ($row_kelas = mysqli_fetch_assoc($result_kelas_modal)): ?>
                                <option value="<?= $row_kelas['id_kelas'] ?>"><?= $row_kelas['nama_kelas'] ?></option>
                            <?php endwhile; ?>
                            </select>
                        </div>
                        <?php else:?>
                            <input type="hidden" name="id_kelas" value="<?php echo $id_kelas;?>">
                        <?php endif;?>
                        <div class="form-group">
                            <label>Jumlah SPP</label>
                            <input type="number" name="jumlah_spp" class="form-control" required>
                        </div>
                        </div>
                        <div class="modal-footer">
                        <button type="submit" name="buat_tagihan" class="btn btn-primary">Buat Tagihan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered" id="example1">
                    <thead>
                        <tr>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Bulan</th>
                            <th>Tahun</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Tanggal Bayar</th>
                            <th>Aksi</th>
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
                            <td>
                                <?php if ($data['status_pembayaran'] !== 'Lunas'): ?>
                                    <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#modalKonfirmasi<?= $data['id_spp'] ?>">
                                        Konfirmasi
                                    </button>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>

                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php
                mysqli_data_seek($result_spp, 0); // reset pointer untuk ulangi loop modal
                while ($data = mysqli_fetch_assoc($result_spp)):
                    if ($data['status_pembayaran'] === 'Lunas') continue;
                ?>
                <div class="modal fade" id="modalKonfirmasi<?= $data['id_spp'] ?>" tabindex="-1" role="dialog" aria-labelledby="modalLabel<?= $data['id_spp'] ?>" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form method="POST">
                    <input type="hidden" name="id_pembayaran" value="<?= $data['id_spp'] ?>">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel<?= $data['id_spp'] ?>">Konfirmasi Pembayaran</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="modal-body">
                        <p>Apakah kamu yakin ingin mengkonfirmasi pembayaran bulan <strong><?= $data['bulan'] ?> <?= $data['tahun'] ?></strong> untuk <strong><?= $data['nama_siswa'] ?> (<?= $data['nis'] ?>)</strong>?</p>
                        </div>
                        <div class="modal-footer">
                        <button type="submit" name="edit_pembayaran" value="1" class="btn btn-success">Ya, Konfirmasi</button>
                        <input type="hidden" name="status_pembayaran" value="Lunas">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        </div>
                    </div>
                    </form>
                </div>
                </div>
                <?php endwhile; ?>

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
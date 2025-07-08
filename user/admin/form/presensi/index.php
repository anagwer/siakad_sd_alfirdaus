<?php
// Koneksi ke database
include '../../../../assets/koneksi.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

$level = $_SESSION['level'];
$id_user = $_SESSION['id_user'];

// Mendapatkan tahun ajaran aktif
$tahun_ajaran_aktif = date('Y');
$semester_aktif = (date('n') <= 6) ? 1 : 2; // Ganjil (Jan-Jun), Genap (Jul-Dec)

$query_tahun_ajaran = "SELECT * FROM tahun_ajaran WHERE semester = $semester_aktif AND status_ta = 'Aktif'";
$result_tahun_ajaran = mysqli_query($conn, $query_tahun_ajaran);
$data_tahun_ajaran = mysqli_fetch_assoc($result_tahun_ajaran);

$id_ta_aktif = $data_tahun_ajaran['id_ta'];

// Proses Edit Presensi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_absen_siswa'])) {
    $id_siswa = $_POST['id_siswa'];
    $tanggal = $_POST['tanggal'];
    $status_presensi = $_POST['status_presensi'];

    // Cek apakah sudah ada data presensi
    $query_check = "SELECT * FROM presensi WHERE id_siswa = '$id_siswa' AND tanggal = '$tanggal'";
    $result_check = mysqli_query($conn, $query_check);

    $kelas = mysqli_query($conn, "SELECT * FROM kelas_siswa WHERE id_siswa = $id_siswa");
    $kelas1 = mysqli_fetch_assoc($kelas);

    $id_kelas = $kelas1['id_kelas'];
    $id_ta = $kelas1['id_ta'];

    $surat_izin = '';
    if ($status_presensi === 'Izin') {
        if (isset($_FILES['surat_izin']) && $_FILES['surat_izin']['error'] === UPLOAD_ERR_OK) {
            $target_dir = "uploads/";
            if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
            $target_file = $target_dir . basename($_FILES["surat_izin"]["name"]);
            move_uploaded_file($_FILES["surat_izin"]["tmp_name"], $target_file);
            $surat_izin = $target_file;
        } else {
            echo "<script>alert('Surat izin wajib diupload.'); window.location.href='index.php';</script>";
            exit();
        }
    }

    if (mysqli_num_rows($result_check) > 0) {
        // Update data presensi
        $query_update = "UPDATE presensi SET 
                         status_presensi = '$status_presensi',
                         surat_izin = '$surat_izin'
                         WHERE id_siswa = '$id_siswa' AND id_kelas = '$id_kelas' AND id_ta = '$id_ta' AND tanggal = '$tanggal'";
        mysqli_query($conn, $query_update);
    } else {
        // Insert data presensi baru
        $query_insert = "INSERT INTO presensi (id_siswa, id_kelas, id_ta, tanggal, status_presensi, surat_izin) 
                         VALUES ('$id_siswa', '$id_kelas', '$id_ta', '$tanggal', '$status_presensi', '$surat_izin')";
        mysqli_query($conn, $query_insert);
    }

    echo "<script>alert('Presensi berhasil diperbarui.'); window.location.href='?a=presensi';</script>";
    exit();
}

// Proses Absen Siswa
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['absen_siswa'])) {
    $id_siswa = $_POST['id_siswa'];
    $id_ta = $_POST['id_ta'];
    $id_kelas = $_POST['id_kelas'];
    $tanggal = $_POST['tanggal'];
    $status_presensi = $_POST['status_presensi'];
    $surat_izin = '';

    if ($status_presensi === 'Izin') {
        if (isset($_FILES['surat_izin']) && $_FILES['surat_izin']['error'] === UPLOAD_ERR_OK) {
            $target_dir = "uploads/";
            if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
            $target_file = $target_dir . basename($_FILES["surat_izin"]["name"]);
            move_uploaded_file($_FILES["surat_izin"]["tmp_name"], $target_file);
            $surat_izin = $target_file;
        } else {
            echo "<script>alert('Surat izin wajib diupload.'); window.location.href='index.php';</script>";
            exit();
        }
    }

    // Simpan data presensi
    $query_simpan = "INSERT INTO presensi (id_siswa, id_kelas, id_ta, tanggal, status_presensi, surat_izin) 
                     VALUES ('$id_siswa', '$id_kelas', '$id_ta', '$tanggal', '$status_presensi', '$surat_izin')";
    mysqli_query($conn, $query_simpan);

    echo "<script>alert('Absen berhasil disimpan.'); window.location.href='?a=presensi';</script>";
    exit();
}
?>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Halaman Presensi</h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <?php if ($level === 'Wali Kelas'): ?>
                        
                    <?php
                    // Data Wali Kelas
                    $query_kelas = "SELECT id_kelas FROM wali_kelas WHERE id_guru = '$id_user' AND status_wali_kelas = '1'";
                    $result_kelas = mysqli_query($conn, $query_kelas);
                    $data_kelas = mysqli_fetch_assoc($result_kelas);

                    if (!$data_kelas) {
                        echo "<p>Anda bukan Wali Kelas aktif.</p>";
                    } else {
                        $id_kelas = $data_kelas['id_kelas'];

                        // Data siswa dalam kelas
                        $query_siswa = "SELECT s.id_siswa, s.nama_siswa, s.nis, ks.id_ta 
                                        FROM kelas_siswa ks 
                                        JOIN siswa s ON ks.id_siswa = s.id_siswa 
                                        WHERE ks.id_kelas = '$id_kelas'";
                        $result_siswa = mysqli_query($conn, $query_siswa);

                        $tanggal_awal_bulan = date('Y-m-01'); // Tanggal awal bulan
                        $tanggal_akhir_bulan = date('Y-m-t'); // Tanggal akhir bulan

                    ?>

                    <!-- Tombol Edit Presensi -->
                        <button type="button" class="btn btn-warning" style="margin-bottom:20px;" data-toggle="modal" data-target="#editPresensiModal">
                            <i class="fa fa-edit"></i> Edit Presensi
                        </button>

                        <!-- Modal Edit Presensi -->
                        <div class="modal fade" id="editPresensiModal" tabindex="-1" aria-labelledby="editPresensiModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="" method="POST" enctype="multipart/form-data">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editPresensiModalLabel">Edit Presensi Siswa</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="tanggal">Tanggal Presensi</label>
                                                <input type="date" name="tanggal" class="form-control" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="id_siswa">Pilih Siswa</label>
                                                <select name="id_siswa" class="form-control" required>
                                                    <option value="">-- Pilih Siswa --</option>
                                                    <?php
                                                    // Ambil semua siswa
                                                    $query_modal_siswa = "SELECT s.id_siswa, s.nama_siswa, ks.id_kelas 
                                                                        FROM kelas_siswa ks 
                                                                        JOIN siswa s ON ks.id_siswa = s.id_siswa WHERE ks.id_kelas = '$id_kelas'";
                                                    $result_modal_siswa = mysqli_query($conn, $query_modal_siswa);
                                                    while ($row = mysqli_fetch_assoc($result_modal_siswa)) {
                                                        echo "<option value='{$row['id_siswa']}'>{$row['nama_siswa']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="status_presensi">Status Presensi</label>
                                                <select name="status_presensi" class="form-control" id="status_presensi_edit" required>
                                                    <option value="">-- Pilih Status --</option>
                                                    <option value="Hadir">Hadir</option>
                                                    <option value="Terlambat">Terlambat</option>
                                                    <option value="Alfa">Alfa</option>
                                                    <option value="Izin">Izin</option>
                                                </select>
                                            </div>
                                            <div class="form-group" id="surat_izin_field_edit" style="display: none;">
                                                <label for="surat_izin">Upload Surat Izin</label>
                                                <input type="file" name="surat_izin" class="form-control">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                            <button type="submit" name="edit_absen_siswa" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    <div class="table-responsive">
                    <table class="table table-bordered" id="example1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <?php for ($i = 1; $i <= date('t'); $i++): ?>
                                    <th><?= date('D', strtotime(date('Y-m-' . $i))) ?></th>
                                <?php endfor; ?>
                                <th>Total Hadir</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            while ($data_siswa = mysqli_fetch_assoc($result_siswa)):
                                echo "<tr>
                                        <td>$no</td>
                                        <td>{$data_siswa['nis']}</td>
                                        <td>{$data_siswa['nama_siswa']}</td>";
                                $id_ta = $data_siswa['id_ta'];
                                $total_hadir = 0;

                                for ($i = 1; $i <= date('t'); $i++) {
                                    $tanggal = date('Y-m-' . $i);
                                    $status_presensi = '';

                                    // Cek apakah ada presensi pada tanggal tersebut
                                    $query_cari_presensi = "SELECT status_presensi 
                                                            FROM presensi 
                                                            WHERE id_siswa = '{$data_siswa['id_siswa']}' 
                                                            AND id_kelas = '$id_kelas' 
                                                            AND id_ta = '$id_ta' 
                                                            AND tanggal = '$tanggal'";
                                    $result_cari_presensi = mysqli_query($conn, $query_cari_presensi);
                                    $data_cari_presensi = mysqli_fetch_assoc($result_cari_presensi);

                                    if ($data_cari_presensi) {
                                        $status_presensi = $data_cari_presensi['status_presensi'];
                                    }

                                    switch ($status_presensi) {
                                        case 'Hadir':
                                            $class = 'bg-green text-white';
                                            break;
                                        case 'Terlambat':
                                            $class = 'bg-primary text-white';
                                            break;
                                        case 'Alfa':
                                            $class = 'bg-red text-white';
                                            break;
                                        case 'Libur':
                                            $class = 'bg-dark text-white';
                                            break;
                                        case 'Izin':
                                            $class = 'bg-orange text-white';
                                            break;
                                        default:
                                            $class = '';
                                            break;
                                    }

                                    echo "<td class='$class'>$status_presensi</td>";

                                    if ($status_presensi === 'Hadir') {
                                        $total_hadir++;
                                    }
                                }

                                echo "<td>$total_hadir</td>
                                      </tr>";

                                $no++;
                            endwhile;
                            ?>
                        </tbody>
                    </table>
                    </div>
                    <?php } ?>

                <?php elseif ($level === 'Siswa'): ?>
                    <?php

                    $id_user = $_SESSION['id_user'];

                    $query_siswa = "SELECT s.id_siswa, s.nama_siswa, s.nis 
                                    FROM siswa s 
                                    WHERE s.id_siswa = '$id_user'";
                    $result_siswa = mysqli_query($conn, $query_siswa);
                    $data_siswa = mysqli_fetch_assoc($result_siswa);

                    if (!$data_siswa) {
                        echo "<p>Data siswa tidak ditemukan.</p>";
                        exit;
                    }

                    $id_siswa = $data_siswa['id_siswa'];

                    // Ambil id_kelas dari tabel kelas_siswa
                    $query_kelas = "SELECT ks.id_kelas, ks.id_ta 
                                    FROM kelas_siswa ks 
                                    WHERE ks.id_siswa = '$id_siswa' 
                                    LIMIT 1";
                    $result_kelas = mysqli_query($conn, $query_kelas);
                    $data_kelas = mysqli_fetch_assoc($result_kelas);

                    if (!$data_kelas) {
                        echo "<p>Kelas tidak ditemukan untuk siswa ini.</p>";
                        exit;
                    }

                    $id_kelas = $data_kelas['id_kelas']; // Sekarang $id_kelas bisa digunakan
                    $tanggal_awal_bulan = date('Y-m-01'); 
                    $tanggal_akhir_bulan = date('Y-m-t'); 
                    $id_ta_aktif = $data_kelas['id_ta'];

                    // Data presensi siswa
                    $query_presensi = "SELECT p.tanggal, p.status_presensi 
                                    FROM presensi p 
                                    WHERE p.id_siswa = '$id_siswa' 
                                    AND p.id_ta = '$id_ta_aktif' 
                                    AND p.tanggal BETWEEN '$tanggal_awal_bulan' AND '$tanggal_akhir_bulan'";
                    $result_presensi = mysqli_query($conn, $query_presensi);
                    ?>

                    <?php
                    // Ambil semua presensi
                    $presensi_hari_ini = false;
                    while ($data_presensi = mysqli_fetch_assoc($result_presensi)) {
                        if ($data_presensi['tanggal'] == date('Y-m-d')) {
                            $presensi_hari_ini = true;
                        }
                    }
                    // Reset pointer hasil query supaya bisa dibaca ulang
                    mysqli_data_seek($result_presensi, 0);
                    ?>

                    <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Status Presensi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($result_presensi) > 0): ?>
                                <?php while ($data_presensi = mysqli_fetch_assoc($result_presensi)): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($data_presensi['tanggal']) ?></td>
                                        <td class="<?= getStatusClass($data_presensi['status_presensi']) ?>">
                                            <?= htmlspecialchars($data_presensi['status_presensi']) ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="2" class="text-center">Belum ada data presensi.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    </div>

                    <!-- Form Absen Siswa -->
                    <?php if (!$presensi_hari_ini): ?>
                        <h2>Absen Hari Ini</h2>
                        <form action="" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id_siswa" value="<?= htmlspecialchars($id_siswa) ?>" readonly>
                            <input type="hidden" name="id_ta" value="<?= htmlspecialchars($id_ta_aktif) ?>" readonly>
                            <input type="hidden" name="id_kelas" value="<?= htmlspecialchars($id_kelas) ?>" readonly>
                            <input type="hidden" name="tanggal" value="<?= date('Y-m-d') ?>" readonly>

                            <div class="form-group">
                                <label for="status_presensi">Status Presensi:</label>
                                <select name="status_presensi" class="form-control" required>
                                    <option value="">Pilih Status</option>
                                    <?php if (date('H') < 7): ?>
                                        <option value="Hadir">Hadir</option>
                                    <?php endif; ?>
                                    <option value="Terlambat">Terlambat</option>
                                    <option value="Alfa">Alfa</option>
                                    <option value="Izin">Izin</option>
                                </select>
                            </div>

                            <div class="form-group" id="surat_izin_field" style="display:none;">
                                <label for="surat_izin">Upload Surat Izin:</label>
                                <input type="file" name="surat_izin" class="form-control">
                            </div>

                            <button type="submit" name="absen_siswa" class="btn btn-primary">Absen</button>
                        </form>
                    <?php else: ?>
                        <p class="alert alert-success">Anda sudah melakukan presensi hari ini.</p>
                    <?php endif; ?>

                    <script>
                        document.querySelector('[name="status_presensi"]').addEventListener('change', function() {
                            var suratIzinField = document.getElementById('surat_izin_field');
                            if (this.value === 'Izin') {
                                suratIzinField.style.display = 'block';
                            } else {
                                suratIzinField.style.display = 'none';
                            }
                        });
                    </script>

                <?php else: ?>
                    <div class="container-fluid">
                        <!-- Tombol Edit Presensi -->
                        <button type="button" style="margin-bottom:20px;" class="btn btn-warning" data-toggle="modal" data-target="#editPresensiModal">
                            <i class="fa fa-edit"></i> Edit Presensi
                        </button>

                        <!-- Modal Edit Presensi -->
                        <div class="modal fade" id="editPresensiModal" tabindex="-1" aria-labelledby="editPresensiModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="" method="POST" enctype="multipart/form-data">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editPresensiModalLabel">Edit Presensi Siswa</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="tanggal">Tanggal Presensi</label>
                                                <input type="date" name="tanggal" class="form-control" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="id_siswa">Pilih Siswa</label>
                                                <select name="id_siswa" class="form-control" required>
                                                    <option value="">-- Pilih Siswa --</option>
                                                    <?php
                                                    // Ambil semua siswa
                                                    $query_modal_siswa = "SELECT s.id_siswa, s.nama_siswa, ks.id_kelas 
                                                                        FROM kelas_siswa ks 
                                                                        JOIN siswa s ON ks.id_siswa = s.id_siswa";
                                                    $result_modal_siswa = mysqli_query($conn, $query_modal_siswa);
                                                    while ($row = mysqli_fetch_assoc($result_modal_siswa)) {
                                                        echo "<option value='{$row['id_siswa']}'>{$row['nama_siswa']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="status_presensi">Status Presensi</label>
                                                <select name="status_presensi" class="form-control" id="status_presensi_edit" required>
                                                    <option value="">-- Pilih Status --</option>
                                                    <option value="Hadir">Hadir</option>
                                                    <option value="Terlambat">Terlambat</option>
                                                    <option value="Alfa">Alfa</option>
                                                    <option value="Izin">Izin</option>
                                                </select>
                                            </div>
                                            <div class="form-group" id="surat_izin_field_edit" style="display: none;">
                                                <label for="surat_izin">Upload Surat Izin</label>
                                                <input type="file" name="surat_izin" class="form-control">
                                            </div>
                                            <!-- Hidden fields untuk id_kelas dan id_ta -->
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                            <button type="submit" name="edit_absen_siswa" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php
                        // Data siswa dalam kelas
                        $query_siswa = "SELECT s.id_siswa, s.nama_siswa, s.nis, ks.id_ta, ks.id_kelas 
                                        FROM kelas_siswa ks 
                                        JOIN siswa s ON ks.id_siswa = s.id_siswa";
                        $result_siswa = mysqli_query($conn, $query_siswa);

                        $tanggal_awal_bulan = date('Y-m-01'); // Tanggal awal bulan
                        $tanggal_akhir_bulan = date('Y-m-t'); // Tanggal akhir bulan

                    ?>
                    <div class="table-responsive mt-3">
                    <table class="table table-bordered" id="example1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <?php for ($i = 1; $i <= date('t'); $i++): ?>
                                    <th><?= date('D', strtotime(date('Y-m-' . $i))) ?></th>
                                <?php endfor; ?>
                                <th>Total Hadir</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            while ($data_siswa = mysqli_fetch_assoc($result_siswa)):
                                echo "<tr>
                                        <td>$no</td>
                                        <td>{$data_siswa['nis']}</td>
                                        <td>{$data_siswa['nama_siswa']}</td>";
                                $id_ta = $data_siswa['id_ta'];
                                $id_kelas = $data_siswa['id_kelas'];
                                $total_hadir = 0;

                                for ($i = 1; $i <= date('t'); $i++) {
                                    $tanggal = date('Y-m-' . $i);
                                    $status_presensi = '';

                                    // Cek apakah ada presensi pada tanggal tersebut
                                    $query_cari_presensi = "SELECT status_presensi 
                                                            FROM presensi 
                                                            WHERE id_siswa = '{$data_siswa['id_siswa']}' 
                                                            AND id_kelas = '$id_kelas' 
                                                            AND id_ta = '$id_ta' 
                                                            AND tanggal = '$tanggal'";
                                    $result_cari_presensi = mysqli_query($conn, $query_cari_presensi);
                                    $data_cari_presensi = mysqli_fetch_assoc($result_cari_presensi);

                                    if ($data_cari_presensi) {
                                        $status_presensi = $data_cari_presensi['status_presensi'];
                                    }

                                    switch ($status_presensi) {
                                        case 'Hadir':
                                            $class = 'bg-green text-white';
                                            break;
                                        case 'Terlambat':
                                            $class = 'bg-primary text-white';
                                            break;
                                        case 'Alfa':
                                            $class = 'bg-red text-white';
                                            break;
                                        case 'Libur':
                                            $class = 'bg-dark text-white';
                                            break;
                                        case 'Izin':
                                            $class = 'bg-orange text-white';
                                            break;
                                        default:
                                            $class = '';
                                            break;
                                    }

                                    echo "<td class='$class'>$status_presensi</td>";

                                    if ($status_presensi === 'Hadir') {
                                        $total_hadir++;
                                    }
                                }

                                echo "<td>$total_hadir</td>
                                      </tr>";

                                $no++;
                            endwhile;
                            ?>
                        </tbody>
                    </table>
                    </div>
                </div>
            <?php endif; ?>
            </div>
        </section>
<?php
function getStatusClass($status) {
    switch ($status) {
        case 'Hadir':
            return 'bg-success text-white';
        case 'Terlambat':
            return 'bg-primary text-white';
        case 'Alfa':
            return 'bg-danger text-white';
        case 'Libur':
            return 'bg-dark text-white';
        case 'Izin':
            return 'bg-warning text-white';
        default:
            return '';
    }
}
?>
<script>
    document.getElementById('status_presensi_edit').addEventListener('change', function() {
        var suratIzinField = document.getElementById('surat_izin_field_edit');
        if (this.value === 'Izin') {
            suratIzinField.style.display = 'block';
        } else {
            suratIzinField.style.display = 'none';
        }
    });
</script>
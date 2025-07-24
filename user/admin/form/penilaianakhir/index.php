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

        <button type="button" class="btn btn-primary"  style="margin-bottom:20px;" data-toggle="modal" data-target="#tambahModal">
            <i class="fa fa-plus"></i> Tambah Nilai
        </button>

        <?php endif; ?>
        <button type="button" class="btn btn-success" style="margin-bottom:20px;" onclick="printPenilaian()">
            <i class="fa fa-print"></i> Cetak
        </button>

        <script>
            function printPenilaian() {
                var printWindow = window.open('form/penilaianakhir/print_penilaian.php');
                printWindow.onload = function () {
                    printWindow.print();
                    printWindow.onafterprint = function () {
                        printWindow.close();
                    };
                };
            }
        </script>

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
                        <th>Aksi</th>
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
                                <td>
                                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal<?= $nilai['id_nilai'] ?>">Edit</button>
                                    <form method='POST' style='display:inline;' onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                    <input type='hidden' name='id_nilai' value='<?php echo $nilai['id_nilai']?>'>
                                    <button type='submit' name='hapus' class='btn btn-danger btn-sm'>Hapus</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Edit (Unik per ID) -->
                            <div class="modal fade" id="editModal<?= $nilai['id_nilai'] ?>" tabindex="-1" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Edit Penilaian</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div><form method="post" class="modal-content">
                                        <div class="modal-body">

                                            <input type="hidden" name="id_nilai" value="<?= $nilai['id_nilai'] ?>">
                                            <input type="hidden" name="id_kelas" value="<?= $id_kelas_loop ?>">
                                            <input type="hidden" name="semester" value="<?= $semester_aktif ?>">
                                            <input type="hidden" name="id_ta_aktif" value="<?= $id_ta_aktif ?>">

                                            <div class="form-group">
                                                <label>Siswa</label>
                                                <input type="text" class="form-control" value="<?= $siswa['nama_siswa'] ?>" disabled>
                                            </div>
                                            <div class="form-group">
                                                <label>Mapel</label>
                                                <input type="text" class="form-control" value="<?= $nilai['nama_mapel'] ?>" disabled>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3 col-sm-6">
                                                    <label>Ulangan 1</label>
                                                    <input type="number" name="ulangan1" class="form-control" value="<?= $nilai['ulangan1'] ?>">
                                                </div>
                                                <div class="col-md-3 col-sm-6">
                                                    <label>Ulangan 2</label>
                                                    <input type="number" name="ulangan2" class="form-control" value="<?= $nilai['ulangan2'] ?>">
                                                </div>
                                                <div class="col-md-3 col-sm-6">
                                                    <label>Ulangan 3</label>
                                                    <input type="number" name="ulangan3" class="form-control" value="<?= $nilai['ulangan3'] ?>">
                                                </div>
                                                <div class="col-md-3 col-sm-6">
                                                    <label>Ulangan 4</label>
                                                    <input type="number" name="ulangan4" class="form-control" value="<?= $nilai['ulangan4'] ?>">
                                                </div>
                                            </div>

                                            <br>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>UTS</label>
                                                    <input type="number" name="uts" class="form-control" value="<?= $nilai['uts'] ?>">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>UAS</label>
                                                    <input type="number" name="uas" class="form-control" value="<?= $nilai['uas'] ?>">
                                                </div>
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" name="simpan_edit" class="btn btn-primary">Simpan</button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <?php $no++; endwhile; }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Modal Tambah -->
<!-- Modal Tambah -->
<div class="modal fade" id="tambahModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="post" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Nilai</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id_ta_aktif" value="<?= $id_ta_aktif ?>">
                <input type="hidden" name="semester_aktif" value="<?= $semester_aktif ?>">
                <input type="hidden" name="id_kelas" value="<?= $id_kelas ?>">

                <div class="form-group">
                    <label>Siswa</label>
                    <select name="id_siswa" class="form-control" required>
                        <option value="">Pilih Siswa</option>
                        <?php
                        $q_siswa = ($level === 'Wali Kelas')
                            ? "SELECT s.* FROM kelas_siswa ks JOIN siswa s ON ks.id_siswa = s.id_siswa WHERE ks.id_kelas = '$id_kelas'"
                            : "SELECT * FROM siswa";
                        $res_siswa = mysqli_query($conn, $q_siswa);
                        while ($s = mysqli_fetch_assoc($res_siswa)) {
                            echo "<option value='{$s['id_siswa']}'>{$s['nama_siswa']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Mapel</label>
                    <select name="id_mapel" class="form-control" required>
                        <option value="">Pilih Mapel</option>
                        <?php
                        if ($level === 'Wali Kelas') {
                            $kelas = substr($nama_kelas, 0, 1);
                            $res_mapel = mysqli_query($conn, "SELECT * FROM mapel where tingkat='$kelas'");
                        } else {
                            $res_mapel = mysqli_query($conn, "SELECT * FROM mapel where tingkat<7");
                        }
                        while ($m = mysqli_fetch_assoc($res_mapel)) {
                            echo "<option value='{$m['id_mapel']}'>Kls: {$m['tingkat']} {$m['nama_mapel']} (KKM: {$m['kkm']})</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Input Nilai -->
                <div class="row">
                    <div class="col-md-3">
                        <label>Ulangan 1</label>
                        <input type="number" name="ulangan1" class="form-control" placeholder="Ulangan 1">
                    </div>
                    <div class="col-md-3">
                        <label>Ulangan 2</label>
                        <input type="number" name="ulangan2" class="form-control" placeholder="Ulangan 2">
                    </div>
                    <div class="col-md-3">
                        <label>Ulangan 3</label>
                        <input type="number" name="ulangan3" class="form-control" placeholder="Ulangan 3">
                    </div>
                    <div class="col-md-3">
                        <label>Ulangan 4</label>
                        <input type="number" name="ulangan4" class="form-control" placeholder="Ulangan 4">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <label>UTS</label>
                        <input type="number" name="uts" class="form-control" placeholder="UTS">
                    </div>
                    <div class="col-md-6">
                        <label>UAS</label>
                        <input type="number" name="uas" class="form-control" placeholder="UAS">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="simpan_nilai" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
        </form>
    </div>
</div>
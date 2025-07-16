<?php
// Koneksi ke database
include '../../../../assets/koneksi.php';


$level = $_SESSION['level'];
$id_user = $_SESSION['id_user'];

// Query untuk mendapatkan data guru
$qGuru = mysqli_query($conn, "SELECT * FROM guru");
$dataGuru = [];
while ($row = mysqli_fetch_assoc($qGuru)) {
    $dataGuru[] = $row;
}

// Query untuk mendapatkan data kelas
$qKelas = mysqli_query($conn, "SELECT * FROM kelas");
$dataKelas = [];
while ($row = mysqli_fetch_assoc($qKelas)) {
    $dataKelas[] = $row;
}

// Query untuk mendapatkan data mapel dengan tingkat=7
$qMapel = mysqli_query($conn, "SELECT * FROM mapel WHERE tingkat=7");
$dataMapel = [];
while ($row = mysqli_fetch_assoc($qMapel)) {
    $dataMapel[] = $row;
}

// Proses Tambah Data
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah'])) {
    $hari = $_POST['hari'];
    $waktu_mulai = $_POST['waktu_mulai'];
    $waktu_selesai = $_POST['waktu_selesai'];
    $id_kelas = $_POST['id_kelas'];
    $id_guru = $_POST['id_guru'];
    $id_mapel = $_POST['id_mapel'];

    // Daftar rentang waktu yang dilarang
    $rentangDilarang = [
        ['mulai' => '07:00', 'selesai' => '07:15'],
        ['mulai' => '09:00', 'selesai' => '09:15'],
        ['mulai' => '11:00', 'selesai' => '11:15']
    ];

    // Fungsi untuk memeriksa apakah waktu berada dalam rentang yang dilarang
    function isWaktuDilarang($waktu, $rentangDilarang) {
        foreach ($rentangDilarang as $rentang) {
            if ($waktu >= $rentang['mulai'] && $waktu <= $rentang['selesai']) {
                return true;
            }
        }
        return false;
    }

    // Periksa waktu mulai dan selesai
    if (isWaktuDilarang($waktu_mulai, $rentangDilarang) || isWaktuDilarang($waktu_selesai, $rentangDilarang)) {
        echo "<script>alert('Tidak bisa input jam ini karena termasuk jam istirahat atau pembuka sekolah.'); window.location.href='';</script>";
        exit;
    }

    // Jika valid, lanjutkan dengan query INSERT
    $query = "INSERT INTO jadwal (hari, waktu_mulai, waktu_selesai, id_kelas, id_guru, id_mapel) 
              VALUES ('$hari', '$waktu_mulai', '$waktu_selesai', '$id_kelas', '$id_guru', '$id_mapel')";
    mysqli_query($conn, $query);
}

// Proses Edit Data
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])) {
    $id_jadwal = $_POST['id_jadwal'];
    $hari = $_POST['hari'];
    $waktu_mulai = $_POST['waktu_mulai'];
    $waktu_selesai = $_POST['waktu_selesai'];
    $id_kelas = $_POST['id_kelas'];
    $id_guru = $_POST['id_guru'];
    $id_mapel = $_POST['id_mapel'];

    // Daftar rentang waktu yang dilarang
    $rentangDilarang = [
        ['mulai' => '07:00', 'selesai' => '07:15'],
        ['mulai' => '09:00', 'selesai' => '09:15'],
        ['mulai' => '11:00', 'selesai' => '11:15']
    ];

    // Fungsi untuk memeriksa apakah waktu berada dalam rentang yang dilarang
    function isWaktuDilarang($waktu, $rentangDilarang) {
        foreach ($rentangDilarang as $rentang) {
            if ($waktu >= $rentang['mulai'] && $waktu <= $rentang['selesai']) {
                return true;
            }
        }
        return false;
    }

    // Periksa waktu mulai dan selesai
    if (isWaktuDilarang($waktu_mulai, $rentangDilarang) || isWaktuDilarang($waktu_selesai, $rentangDilarang)) {
        echo "<script>alert('Tidak bisa input jam ini karena termasuk jam istirahat atau pembuka sekolah.'); window.location.href='';</script>";
        exit;
    }

    // Jika valid, lanjutkan dengan query UPDATE
    $query = "UPDATE jadwal SET 
              hari='$hari', 
              waktu_mulai='$waktu_mulai', 
              waktu_selesai='$waktu_selesai', 
              id_kelas='$id_kelas', 
              id_guru='$id_guru', 
              id_mapel='$id_mapel' 
              WHERE id_jadwal='$id_jadwal'";
    mysqli_query($conn, $query);
}

// Proses Hapus Data
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['hapus'])) {
    $id_jadwal = $_POST['id_jadwal_hapus'];

    $id_jadwal = mysqli_real_escape_string($conn, $id_jadwal);
    $query = "DELETE FROM jadwal WHERE id_jadwal='$id_jadwal'";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Data berhasil dihapus.'); window.location.href='';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data: " . mysqli_error($conn) . "');</script>";
    }
}
?>

    <!-- Content Wrapper -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Jadwal Pelajaran</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <div class="card-tools" style="margin-bottom:20px;">
                            <?php 
                            if ($_SESSION['level']=="Administrator") { ?>
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addJadwalModal">
                                <i class="fa fa-plus"></i> Tambah Jadwal
                            </button>
                            <?php }?>
                            <button type="button" class="btn btn-success btn-sm" onclick="printJadwal()">
                                <i class="fa fa-print"></i> Cetak
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped" id="example1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Hari</th>
                                    <th>Waktu</th>
                                    <th>Kelas</th>
                                    <th>Guru</th>
                                    <th>Mata Pelajaran</th>
                                    <?php 
                                    if ($_SESSION['level']=="Administrator") { ?>
                                    <th>Aksi</th>
                                    <?php }?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if($level === 'Siswa'){
                                    $query = mysqli_query($conn, "SELECT j.*, g.nama_guru, m.nama_mapel FROM jadwal j 
                                                            LEFT JOIN guru g ON j.id_guru = g.id_guru 
                                                            LEFT JOIN mapel m ON j.id_mapel = m.id_mapel
                                                            JOIN kelas_siswa k ON k.id_siswa ='$id_user'");
                                }else{
                                $query = mysqli_query($conn, "SELECT j.*, g.nama_guru, m.nama_mapel FROM jadwal j 
                                                            LEFT JOIN guru g ON j.id_guru = g.id_guru 
                                                            LEFT JOIN mapel m ON j.id_mapel = m.id_mapel");
                                }
                                $no = 1;
                                while ($row = mysqli_fetch_assoc($query)) {
                                    echo "<tr>";
                                    echo "<td>" . $no++ . "</td>";
                                    echo "<td>" . $row['hari'] . "</td>";
                                    echo "<td>" . date("H:i", strtotime($row['waktu_mulai'])) . " - " . date("H:i", strtotime($row['waktu_selesai'])) . "</td>";
                                    echo "<td>" . $row['id_kelas'] . "</td>";
                                    echo "<td>" . $row['nama_guru'] . "</td>";
                                    echo "<td>" . $row['nama_mapel'] . "</td>";
                            if ($_SESSION['level']=="Administrator") {
                                    echo "<td>
                                            <a href='#' class='btn btn-warning btn-xs' data-toggle='modal' data-target='#editJadwalModal' onclick='editJadwal(" . json_encode($row) . ")'>Edit</a>";
                                    echo "<form method='POST' style='display:inline;' onsubmit=\"return confirm('Apakah Anda yakin ingin menghapus data ini?');\">";
                                    echo "<input type='hidden' name='id_jadwal_hapus' value='" . $row['id_jadwal'] . "'>";
                                    echo "<button type='submit' name='hapus' class='btn btn-danger btn-xs'>Hapus</button>";
                                    echo "</form>";
                                    echo "</td>";
                            }
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script>
    function printJadwal() {
        var printWindow = window.open('form/jadwal/print_jadwal.php');
        printWindow.onload = function () {
            printWindow.print();
            printWindow.onafterprint = function () {
                printWindow.close();
            };
        };
    }
</script>

    <!-- Modal Tambah Jadwal -->
    <div class="modal fade" id="addJadwalModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Jadwal</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Hari</label>
                            <select name="hari" class="form-control" required>
                                <option value="Senin">Senin</option>
                                <option value="Selasa">Selasa</option>
                                <option value="Rabu">Rabu</option>
                                <option value="Kamis">Kamis</option>
                                <option value="Jumat">Jumat</option>
                                <option value="Sabtu">Sabtu</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Waktu Mulai</label>
                            <input type="time" name="waktu_mulai" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Waktu Selesai</label>
                            <input type="time" name="waktu_selesai" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Kelas</label>
                            <select name="id_kelas" class="form-control" required>
                                <?php foreach ($dataKelas as $kelas): ?>
                                    <option value="<?php echo $kelas['id_kelas']; ?>"><?php echo $kelas['nama_kelas']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Guru</label>
                            <select name="id_guru" class="form-control" required>
                                <?php foreach ($dataGuru as $guru): ?>
                                    <option value="<?php echo $guru['id_guru']; ?>"><?php echo $guru['nama_guru']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Mata Pelajaran</label>
                            <select name="id_mapel" class="form-control" required>
                                <?php foreach ($dataMapel as $mapel): ?>
                                    <option value="<?php echo $mapel['id_mapel']; ?>"><?php echo $mapel['nama_mapel']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="submit" name="tambah" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="confirmDeleteModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Konfirmasi Hapus</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus data ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" name="hapus" class="btn btn-danger">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Jadwal -->
    <div class="modal fade" id="editJadwalModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Jadwal</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" id="edit_id_jadwal" name="id_jadwal">
                        <div class="form-group">
                            <label>Hari</label>
                            <select id="edit_hari" name="hari" class="form-control" required>
                                <option value="Senin">Senin</option>
                                <option value="Selasa">Selasa</option>
                                <option value="Rabu">Rabu</option>
                                <option value="Kamis">Kamis</option>
                                <option value="Jumat">Jumat</option>
                                <option value="Sabtu">Sabtu</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Waktu Mulai</label>
                            <input type="time" id="edit_waktu_mulai" name="waktu_mulai" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Waktu Selesai</label>
                            <input type="time" id="edit_waktu_selesai" name="waktu_selesai" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Kelas</label>
                            <select id="edit_id_kelas" name="id_kelas" class="form-control" required>
                                <?php foreach ($dataKelas as $kelas): ?>
                                    <option value="<?php echo $kelas['id_kelas']; ?>"><?php echo $kelas['nama_kelas']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Guru</label>
                            <select id="edit_id_guru" name="id_guru" class="form-control" required>
                                <?php foreach ($dataGuru as $guru): ?>
                                    <option value="<?php echo $guru['id_guru']; ?>"><?php echo $guru['nama_guru']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Mata Pelajaran</label>
                            <select id="edit_id_mapel" name="id_mapel" class="form-control" required>
                                <?php foreach ($dataMapel as $mapel): ?>
                                    <option value="<?php echo $mapel['id_mapel']; ?>"><?php echo $mapel['nama_mapel']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="submit" name="edit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<script>
    function editJadwal(data) {
        document.getElementById('edit_id_jadwal').value = data.id_jadwal;
        document.getElementById('edit_hari').value = data.hari;
        document.getElementById('edit_waktu_mulai').value = data.waktu_mulai;
        document.getElementById('edit_waktu_selesai').value = data.waktu_selesai;

        // Set nilai dropdown kelas
        document.getElementById('edit_id_kelas').value = data.id_kelas;

        document.getElementById('edit_id_guru').value = data.id_guru;
        document.getElementById('edit_id_mapel').value = data.id_mapel;
    }
    function validateForm() {
        const waktuMulai = document.querySelector('input[name="waktu_mulai"]').value;
        const waktuSelesai = document.querySelector('input[name="waktu_selesai"]').value;

        // Daftar rentang waktu yang dilarang
        const rentangDilarang = [
            { mulai: '01:00', selesai: '07:15' },
            { mulai: '09:00', selesai: '09:15' },
            { mulai: '11:00', selesai: '11:15' }
        ];

        // Fungsi untuk memeriksa apakah waktu berada dalam rentang yang dilarang
        function isWaktuDilarang(waktu, rentangDilarang) {
            return rentangDilarang.some(rentang => waktu >= rentang.mulai && waktu <= rentang.selesai);
        }

        // Periksa waktu mulai dan selesai
        if (isWaktuDilarang(waktuMulai, rentangDilarang) || isWaktuDilarang(waktuSelesai, rentangDilarang)) {
            alert('Tidak bisa input jam ini karena termasuk jam istirahat atau pembuka sekolah.');
            return false; // Hentikan proses submit
        }

        return true; // Lanjutkan proses submit
    }

    // Tambahkan event listener untuk form tambah
    document.querySelector('form[method="POST"]').addEventListener('submit', function (e) {
        if (!validateForm()) {
            e.preventDefault(); // Hentikan submit jika validasi gagal
        }
    });
</script>

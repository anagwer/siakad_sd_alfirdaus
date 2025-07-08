<?php
// Koneksi ke database
include '../../../../assets/koneksi.php';

// Query untuk mendapatkan data jadwal
$query = "SELECT 
            j.hari,
            j.waktu_mulai,
            j.waktu_selesai,
            k.nama_kelas,
            g.id_guru,
            g.nama_guru,
            m.nama_mapel
          FROM jadwal j
          LEFT JOIN kelas k ON j.id_kelas = k.id_kelas
          LEFT JOIN guru g ON j.id_guru = g.id_guru
          LEFT JOIN mapel m ON j.id_mapel = m.id_mapel
          ORDER BY k.nama_kelas ASC, j.hari ASC, j.waktu_mulai ASC";

$result = mysqli_query($conn, $query);

// Mengelompokkan data berdasarkan kelas
$dataJadwal = [];
while ($row = mysqli_fetch_assoc($result)) {
    $kelas = $row['nama_kelas'];
    $hari = $row['hari'];
    $waktu = date("H:i", strtotime($waktu_mulai)) . ' - ' . date("H:i", strtotime($waktu_selesai));
    $mapel = $row['nama_mapel'];
    $guru = $row['id_guru'];

    $dataJadwal[$kelas][$hari][$waktu] = [
        'mapel' => $mapel,
        'guru' => 'A'.$guru
    ];
}

$dataManual = [
    'Senin' => [
        '07.00 - 07.15' => ['mapel' => 'Upacara', 'guru' => '-'],
        '09.00 - 09.15' => ['mapel' => 'Istirahat', 'guru' => '-'],
        '11.00 - 11.15' => ['mapel' => 'Istirahat', 'guru' => '-'],
    ],
    'Selasa' => [
        '07.00 - 07.15' => ['mapel' => 'Literasi', 'guru' => '-'],
        '09.00 - 09.15' => ['mapel' => 'Istirahat', 'guru' => '-'],
        '11.00 - 11.15' => ['mapel' => 'Istirahat', 'guru' => '-'],
    ],
    'Rabu' => [
        '07.00 - 07.15' => ['mapel' => 'Literasi', 'guru' => '-'],
        '09.00 - 09.15' => ['mapel' => 'Istirahat', 'guru' => '-'],
        '11.00 - 11.15' => ['mapel' => 'Istirahat', 'guru' => '-'],
    ],
    'Kamis' => [
        '07.00 - 07.15' => ['mapel' => 'Asmaul Husna', 'guru' => '-'],
        '09.00 - 09.15' => ['mapel' => 'Istirahat', 'guru' => '-'],
        '11.00 - 11.15' => ['mapel' => 'Istirahat', 'guru' => '-'],
    ],
    'Jumat' => [
        '07.00 - 07.15' => ['mapel' => 'Jumat Bersih', 'guru' => '-'],
        '09.00 - 09.15' => ['mapel' => 'Istirahat', 'guru' => '-'],
        '11.00 - 11.15' => ['mapel' => 'Istirahat', 'guru' => '-'],
    ],
    'Sabtu' => [
        '07.00 - 07.15' => ['mapel' => 'Literasi', 'guru' => '-'],
        '09.00 - 09.15' => ['mapel' => 'Istirahat', 'guru' => '-'],
        '11.00 - 11.15' => ['mapel' => 'Istirahat', 'guru' => '-'],
    ],
];

// Gabungkan data manual dengan data jadwal dari database
foreach ($dataJadwal as $kelas => $jadwalHari) {
    foreach ($jadwalHari as $hari => $jadwalWaktu) {
        if (isset($dataManual[$hari])) {
            $dataJadwal[$kelas][$hari] = array_merge_recursive($dataManual[$hari], $jadwalWaktu);
        }
    }
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
    <div class="header">
        <h3>PEMERINTAH KOTA MAGELANG</h3>
        <h3>DINAS PENDIDIKAN DAN KEBUDAYAAN</h3>
        <h3>SD NEGERI REJOWINANGUN SELATAN 4</h3>
        <p>Jalan Beringin V No 6 Magelang telp 0293-314373 Kode Pos 56124</p>
        <h3>JADWAL PELAJARAN TAHUN 2022/2023</h3>
    </div>

    <?php foreach ($dataJadwal as $kelas => $jadwalHari): ?>
    <h4>Kelas: <?php echo $kelas; ?></h4>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Waktu</th>
                <th colspan="2">Senin</th>
                <th colspan="2">Selasa</th>
                <th colspan="2">Rabu</th>
                <th colspan="2">Kamis</th>
                <th colspan="2">Jumat</th>
                <th colspan="2">Sabtu</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            $no = 1;
            $allTimes = [];

            // Mengumpulkan semua waktu unik
            foreach ($jadwalHari as $hari => $jadwalWaktu) {
                foreach ($jadwalWaktu as $waktu => $data) {
                    $allTimes[] = $waktu;
                }
            }
            $allTimes = array_unique($allTimes);
            sort($allTimes);

            foreach ($allTimes as $waktu): 
                // Lewati waktu 11.00 - 11.15 untuk kelas 1A dan 2A
                if (($kelas == '1A' || $kelas == '2A') && $waktu == '11.00 - 11.15') {
                    continue; // Lewati iterasi ini
                }
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $waktu; ?></td>
                    <?php foreach ($hariList as $hari): ?>
                        <td>
                            <?php
                            if (isset($jadwalHari[$hari][$waktu])) {
                                echo $jadwalHari[$hari][$waktu]['mapel'] . '<br></td>';
                                echo '<td>' . $jadwalHari[$hari][$waktu]['guru'] . '</td>';
                            } else {
                                echo '</td><td></td>';
                            }
                            ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <br>
<?php endforeach; ?>

    <div class="kode-guru">
        <h4>KODE GURU</h4>
        <table>
            <?php
            $qGuru = mysqli_query($conn, "SELECT * FROM guru");
            while ($guru = mysqli_fetch_assoc($qGuru)): ?>
                <tr>
                    <td>A<?php echo $guru['id_guru']; ?></td>
                    <td><?php echo $guru['nama_guru']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <div style="text-align: right; margin-top: 20px;">
        <p>Magelang, 4 Januari 2022</p>
        <p>Kepala Sekolah</p>
        <br><br>
        <p>Ulfiyanti, S.Pd.SD.</p>
        <p>NIP 19690818 200312 2 002</p>
    </div>
</body>
</html>
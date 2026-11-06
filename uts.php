<?php
// ===== KONEKSI DATABASE (XAMPP default) =====
mysqli_report(MYSQLI_REPORT_OFF);
$conn = new mysqli("localhost", "root", "", "db_uts");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error . ". Pastikan MySQL di XAMPP sudah Start dan database.sql sudah di-import.");
}

$pesan = "";

// ===== PROSES SIMPAN =====
if (isset($_POST['simpan'])) {

    $tgl_input      = $_POST['tgl_input'];
    $kode           = strtoupper(trim($_POST['kode']));
    $nama           = $_POST['nama'];
    $jk             = $_POST['jk'];
    $asal_sekolah   = $_POST['asal_sekolah'];
    $pekerjaan_ortu = $_POST['pekerjaan_ortu'];
    $tempat_lahir   = $_POST['tempat_lahir'];
    $tgl_lahir      = $_POST['tgl_lahir'];
    $mat            = (int) $_POST['mat'];
    $bing           = (int) $_POST['bing'];
    $umum           = (int) $_POST['umum'];

    // Hitung rata-rata
    $rata = ($mat + $bing + $umum) / 3;

    // Menentukan keterangan
    if ($rata >= 70) {
        $keterangan = "Lulus";
    } elseif ($rata >= 60) {
        $keterangan = "Cadangan";
    } else {
        $keterangan = "Tidak Lulus";
    }

    // Menentukan tempat tes berdasarkan kode
    $kode_awal = substr($kode, 0, 1);
    if ($kode_awal == "A") {
        $tempat_tes = "Gedung A";
    } elseif ($kode_awal == "B") {
        $tempat_tes = "Gedung B";
    } elseif ($kode_awal == "V") {
        $tempat_tes = "Viktor";
    } else {
        $tempat_tes = "Tidak Diketahui";
    }

    // Simpan ke database (prepared statement)
    $sql = "INSERT INTO pendaftar
            (tgl_input, kode, nama, jk, asal_sekolah, pekerjaan_ortu, tempat_lahir, tgl_lahir, mat, bing, umum, rata, keterangan, tempat_tes)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssssssiiidss",
        $tgl_input, $kode, $nama, $jk, $asal_sekolah, $pekerjaan_ortu,
        $tempat_lahir, $tgl_lahir, $mat, $bing, $umum, $rata, $keterangan, $tempat_tes
    );

    if ($stmt->execute()) {
        // Redirect agar data tidak tersimpan dua kali saat halaman di-refresh
        header("Location: " . $_SERVER['PHP_SELF'] . "?status=sukses");
        exit;
    } elseif ($stmt->errno == 1062) {
        $pesan = "<div class='alert gagal'>Kode pendaftaran <b>" . htmlspecialchars($kode) . "</b> sudah terdaftar.</div>";
    } else {
        $pesan = "<div class='alert gagal'>Gagal menyimpan: " . htmlspecialchars($stmt->error) . "</div>";
    }
    $stmt->close();
}

if (isset($_GET['status']) && $_GET['status'] == "sukses") {
    $pesan = "<div class='alert sukses'>Data berhasil disimpan ke database.</div>";
}

// ===== AMBIL DATA DARI DATABASE =====
$hasil = $conn->query("SELECT * FROM pendaftar ORDER BY id DESC");

$jumlah_pendaftar = 0;
$jumlah_lulus = 0;
$jumlah_tidak_lulus = 0;
$rows = [];
if ($hasil) {
    while ($r = $hasil->fetch_assoc()) {
        $rows[] = $r;
        $jumlah_pendaftar++;
        if ($r['keterangan'] == "Lulus") {
            $jumlah_lulus++;
        } else {
            $jumlah_tidak_lulus++;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UTS PHP - Pendaftaran Mahasiswa</title>

    <style>
        body{
            font-family: Arial, Helvetica, sans-serif;
            margin: 30px;
            background: #f4f4f4;
        }

        .container{
            width: 900px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }

        h2{
            text-align: center;
        }

        table{
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td{
            border: 1px solid black;
        }

        th, td{
            padding: 10px;
            text-align: center;
        }

        input[type=text],
        input[type=number],
        input[type=date]{
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        .btn{
            background: blue;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }

        .btn:hover{
            background: darkblue;
        }

        .info{
            margin-top: 20px;
            padding: 10px;
            background: #eef;
        }

        .alert{
            margin-top: 15px;
            padding: 10px;
            border-radius: 5px;
        }
        .sukses{ background: #d4edda; color: #155724; }
        .gagal{ background: #f8d7da; color: #721c24; }

        .scroll{ overflow-x: auto; }
    </style>
</head>
<body>

<div class="container">

    <h2>FORM PENDAFTARAN MAHASISWA BARU</h2>

    <form method="POST">

        <label>Tanggal Input</label>
        <input type="date" name="tgl_input" required>

        <label>Kode Pendaftaran</label>
        <input type="text" name="kode" placeholder="Contoh : A2" required>

        <label>Nama Pendaftar</label>
        <input type="text" name="nama" required>

        <label>Jenis Kelamin</label><br>
        <input type="radio" name="jk" value="Laki-Laki" required> Laki-Laki
        <input type="radio" name="jk" value="Perempuan"> Perempuan
        <br><br>

        <label>Asal Sekolah</label>
        <input type="text" name="asal_sekolah" required>

        <label>Pekerjaan Orang Tua</label>
        <input type="text" name="pekerjaan_ortu" required>

        <label>Tempat Lahir</label>
        <input type="text" name="tempat_lahir" required>

        <label>Tanggal Lahir</label>
        <input type="date" name="tgl_lahir" required>

        <label>Matematika</label>
        <input type="number" name="mat" required>

        <label>Bahasa Inggris</label>
        <input type="number" name="bing" required>

        <label>Umum</label>
        <input type="number" name="umum" required>

        <button type="submit" name="simpan" class="btn">SIMPAN</button>

    </form>

    <?= $pesan ?>

    <div class="scroll">
    <table>
        <tr>
            <th>Kode</th>
            <th>Nama</th>
            <th>JK</th>
            <th>Tgl Lahir</th>
            <th>Asal Sekolah</th>
            <th>Pekerjaan Ortu</th>
            <th>Tempat Tes</th>
            <th>MAT</th>
            <th>B. Inggris</th>
            <th>Umum</th>
            <th>Rata-rata</th>
            <th>Keterangan</th>
        </tr>

        <?php if (count($rows) == 0): ?>
            <tr><td colspan="12">Belum ada data.</td></tr>
        <?php else: foreach ($rows as $r): ?>
            <tr>
                <td><?= htmlspecialchars($r['kode']) ?></td>
                <td><?= htmlspecialchars($r['nama']) ?></td>
                <td><?= htmlspecialchars($r['jk']) ?></td>
                <td><?= htmlspecialchars($r['tempat_lahir']) ?>, <?= $r['tgl_lahir'] ?></td>
                <td><?= htmlspecialchars($r['asal_sekolah']) ?></td>
                <td><?= htmlspecialchars($r['pekerjaan_ortu']) ?></td>
                <td><?= htmlspecialchars($r['tempat_tes']) ?></td>
                <td><?= $r['mat'] ?></td>
                <td><?= $r['bing'] ?></td>
                <td><?= $r['umum'] ?></td>
                <td><?= number_format($r['rata'], 2) ?></td>
                <td><?= $r['keterangan'] ?></td>
            </tr>
        <?php endforeach; endif; ?>
    </table>
    </div>

    <div class="info">
        <h3>Rekap Data:</h3>
        <p>Jumlah Pendaftar : <b><?= $jumlah_pendaftar ?></b></p>
        <p>Jumlah Peserta Lulus : <b><?= $jumlah_lulus ?></b></p>
        <p>Jumlah Tidak Lulus : <b><?= $jumlah_tidak_lulus ?></b></p>
    </div>

    <div class="info">
        <h3>Keterangan:</h3>
        <p><b>Kode Tempat Tes:</b></p>
        <ul>
            <li>A = Gedung A</li>
            <li>B = Gedung B</li>
            <li>V = Viktor</li>
        </ul>

        <p><b>Hasil Seleksi:</b></p>
        <ul>
            <li>Rata-rata &ge; 70 = Lulus</li>
            <li>Rata-rata 60 - 69 = Cadangan</li>
            <li>Rata-rata &lt; 60 = Tidak Lulus</li>
        </ul>
    </div>

</div>

</body>
</html>
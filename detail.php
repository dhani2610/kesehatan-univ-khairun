<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] === "GET") {
  $id = intval($_GET['id']);
  // Ambil tanggal_pemeriksaan dari tabel jadwals berdasarkan fakultas
  $result = $conn->query("SELECT * FROM mahasiswa WHERE id = '$id' LIMIT 1");

  if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // $tanggal_pemeriksaan = $row['tanggal'];
  } else {
    echo "Data Pemeriksaan tidak ditemukan.";
    exit();
  }
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Pemeriksaan Kesehatan</title>
  <link rel="stylesheet" href="dashbordstyle.css">
  <!-- <link rel="stylesheet" href="adminstyle.css"> -->

</head>

<body>
  <header>
    <h1>Dashboard Pemeriksaan Kesehatan Universitas Khairun</h1>
  </header>
  <div class="container">
    <div class="output" id="jadwalOutput">
      <h3>Jadwal Pemeriksaan Kesehatan</h3>
      <p><strong>Nama:</strong> <span id="outputNama"><?php echo $row['nama_mahasiswa'] ?></span></p>
      <p><strong>NPM:</strong> <span id="outputNim"><?php echo $row['nim'] ?></span></p>
      <p><strong>Fakultas:</strong> <span id="outputFakultas"><?php echo $row['fakultas'] ?></span></p>
      <p><strong>Jurusan:</strong> <span id="outputJurusan"><?php echo $row['jurusan'] ?></span></p>
      <h4>Jadwal Pemeriksaan:</h4>

      <p> <span id="outputJurusan" style="padding: 10px;background:white;width:100%">
          <?php
          // Pastikan locale Bahasa Indonesia sudah diatur
          setlocale(LC_TIME, 'id_ID.utf8', 'id_ID', 'id');

          // Format tanggal dari database
          $tanggal = new DateTime($row['tanggal_pemeriksaan']);
          $hariTanggal = strftime('%A, %d %B %Y', $tanggal->getTimestamp());
          ?>
          <?php echo htmlspecialchars($hariTanggal); ?>
        </span></p>

      <button style="margin-top:10px;" onclick="window.location.href='dashbordmhs.php';">Kembali</button>


    </div>
  </div>
  </div>


  <script src="dashboard.js"></script>
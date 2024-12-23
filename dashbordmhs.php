<?php
session_start(); // Memulai session

// Periksa apakah user sudah login
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    // Jika belum login atau bukan mahasiswa, arahkan ke halaman login
    header('Location: login.php');
    exit();
}

include 'koneksi.php';

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_mahasiswa = $_POST['nama_mahasiswa'];
    $nim = $conn->real_escape_string($_POST['nim']);
    $fakultas = $conn->real_escape_string($_POST['fakultas']);
    $jurusan = $conn->real_escape_string($_POST['jurusan']);
    $tanggal_pemeriksaan = $conn->real_escape_string($_POST['tanggal_pemeriksaan']);

    if (isset($_FILES['buktiPembayaran'])) {
        $fileName = $_FILES['buktiPembayaran']['name'];
        $fileTmp = $_FILES['buktiPembayaran']['tmp_name'];
        $uploadDir = "uploads/";
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($fileExtension, $allowedExtensions)) {
            echo "Ekstensi file tidak diizinkan. Silakan unggah file dengan ekstensi .jpg, .jpeg, .png, atau .pdf.";
            exit();
        }
        if ($_FILES['buktiPembayaran']['size'] > 5 * 1024 * 1024) {
            echo "Ukuran file terlalu besar. Maksimal 5MB.";
            exit();
        }

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $filePath = $uploadDir . basename($fileName);

        if (move_uploaded_file($fileTmp, $filePath)) {
            $sql = "INSERT INTO mahasiswa (nama_mahasiswa, nim, fakultas, jurusan, bukti_pembayaran, tanggal_pemeriksaan) 
                    VALUES ('$nama_mahasiswa', '$nim', '$fakultas', '$jurusan', '$filePath','$tanggal_pemeriksaan')";

            if ($conn->query($sql) === TRUE) {
                echo "Data berhasil disimpan.";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Gagal mengunggah file. Pastikan folder 'uploads/' ada dan memiliki izin tulis.";
        }
    } else {
        echo "File tidak ditemukan. Pastikan Anda memilih file sebelum mengirim.";
    }
}
// echo json_encode($data);
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Pemeriksaan Kesehatan</title>
  <link rel="stylesheet" href="dashbordstyle.css">
</head>
<body>
<header>
  <h1>Dashboard Pemeriksaan Kesehatan Universitas Khairun</h1>
</header>

<div class="container" id="formContainer">
  <h2>Masukkan Data</h2>
  <form id="inputForm" action="dashbordmhs.php" method="POST" enctype="multipart/form-data">
    <label for="nama">Nama Mahasiswa:</label>
    <input type="text" id="nama" name="nama_mahasiswa" placeholder="Masukkan nama mahasiswa" required>
  
    <label for="nim">NPM:</label>
    <input type="text" id="nim" name="nim" placeholder="Masukkan NPM mahasiswa" required>
  
    <label for="fakultas">Fakultas:</label>
    <select id="fakultas" name="fakultas" required>
      <option value="">Pilih Fakultas</option>
      <option value="Fakultas Teknik">Fakultas Teknik</option>
      <option value="Fakultas Ekonomi">Fakultas Ekonomi</option>
      <option value="Fakultas Hukum">Fakultas Hukum</option>
      <option value="Fakultas Kedokteran">Fakultas Kedokteran</option>
    </select>
  
    <label for="jurusan">Jurusan:</label>
    <select id="jurusan" name="jurusan" required>
      <option value="">Pilih Jurusan</option>
      <option value="Informatika">Informatika</option>
      <option value="Teknik Sipil">Teknik Sipil</option>
      <option value="Manajemen">Manajemen</option>
      <option value="Hukum Pidana">Hukum Pidana</option>
    </select>
  
    <label for="buktiPembayaran">Tanggal Pemeriksaan:</label>
    <input type="date" id="tanggal_pemeriksaan" name="tanggal_pemeriksaan">

    <label for="buktiPembayaran">Unggah Bukti Pembayaran:</label>
    <input type="file" id="buktiPembayaran" name="buktiPembayaran" accept=".jpg,.jpeg,.png,.pdf" required>
    <button type="submit">Kirim Data</button>
    <button type="button" class="btn-keluar" onclick="window.location.href='logout.php'">Keluar</button>
  </form>
</div>

<div class="output" id="jadwalOutput" style="display:none;">
  <h3>Jadwal Pemeriksaan Kesehatan</h3>
  <p><strong>Nama:</strong> <span id="outputNama"></span></p>
  <p><strong>NPM:</strong> <span id="outputNim"></span></p>
  <p><strong>Fakultas:</strong> <span id="outputFakultas"></span></p>
  <p><strong>Jurusan:</strong> <span id="outputJurusan"></span></p>
  <h4>Jadwal Pemeriksaan:</h4>
  <ul id="jadwalList"></ul>
</div>

<script src="dashboard.js"></script>
</body>
</html>

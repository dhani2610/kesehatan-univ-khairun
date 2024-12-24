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
  $nama_mahasiswa = $conn->real_escape_string($_POST['nama_mahasiswa']);
  $nim = $conn->real_escape_string($_POST['nim']);
  $fakultas = $conn->real_escape_string($_POST['fakultas']);
  $jurusan = $conn->real_escape_string($_POST['jurusan']);

  // Ambil tanggal_pemeriksaan dari tabel jadwals berdasarkan fakultas
  $result = $conn->query("SELECT tanggal FROM jadwals WHERE fakultas = '$fakultas' LIMIT 1");

  if ($result && $result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $tanggal_pemeriksaan = $row['tanggal'];
  } else {
      echo "Tanggal pemeriksaan untuk fakultas '$fakultas' tidak ditemukan.";
      exit();
  }

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
      $id_user = $_SESSION['id'];

      if (move_uploaded_file($fileTmp, $filePath)) {
          $sql = "INSERT INTO mahasiswa (id_user ,nama_mahasiswa, nim, fakultas, jurusan, bukti_pembayaran, tanggal_pemeriksaan) 
                  VALUES ('$id_user','$nama_mahasiswa', '$nim', '$fakultas', '$jurusan', '$filePath', '$tanggal_pemeriksaan')";

          if ($conn->query($sql) === TRUE) {
            $inserted_id = $conn->insert_id;

            header('Location: detail.php?id='.$inserted_id);
            exit();
              // echo "Data berhasil disimpan.";
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
  <!-- <link rel="stylesheet" href="adminstyle.css"> -->

</head>
<body>
<header>
  <h1>Dashboard Pemeriksaan Kesehatan Universitas Khairun</h1>
</header>
<!-- <header>
  <div style="display: flex; align-items: center;">
    <img src="images/logo.png" Logo Universitas Khairun">
    <div>
      <h1>Dashboard Admin</h1>
      <p>Kelola Data Mahasiswa</p>
    </div>
  </div>
  <button class="btn-keluar" onclick="window.location.href='logout.php'">Keluar</button>
</header> -->

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
      <option value="FKIP">FKIP</option>
      <option value="Fakultas Kedokteran">Fakultas Kedokteran</option>
      <option value="FIB">FIB</option>
      <option value="Pertanian">Pertanian</option>
      <option value="FPIK">FPIK</option>
    </select>

    <label for="jurusan">Jurusan:</label>
    <select id="jurusan" name="jurusan" required>
      <option value="">Pilih Jurusan</option>
    </select>


    <label for="buktiPembayaran">Unggah Bukti Pembayaran:</label>
    <input type="file" id="buktiPembayaran" name="buktiPembayaran" accept="image/jpeg,image/jpg, image/png, application/pdf" required>

    <!-- <input type="file" id="buktiPembayaran" name="buktiPembayaran" accept=".jpg,.jpeg,.png,.pdf" required> -->
    <button type="submit">Kirim Data</button>
    <button type="button" class="btn-keluar" onclick="window.location.href='logout.php'">Keluar</button>
  </form>

  
</div>


<script src="dashboard.js"></script>
<script>
  document.getElementById('fakultas').addEventListener('change', function() {
  const fakultas = this.value;
  const jurusanSelect = document.getElementById('jurusan');
  jurusanSelect.innerHTML = '<option value="">Pilih Jurusan</option>';
  console.log(fakultas);
  
  let jurusanOptions = [];

  switch (fakultas) {
      case 'Fakultas Teknik':
          jurusanOptions = [
              'Mesin', 'Elektro', 'Arsitektur', 'Sipil', 
              'Industri', 'Informatika', 'Pertambangan'
          ];
          break;
      case 'Fakultas Ekonomi':
          jurusanOptions = [
              'Ekonomi Pembangunan', 'Manajemen', 'Akuntansi'
          ];
          break;
      case 'Fakultas Hukum':
          jurusanOptions = ['Ilmu Hukum'];
          break;
      case 'FKIP':
          jurusanOptions = [
              'PGPAUD', 'Matematika', 'Bahasa Inggris', 'PGSD', 
              'Geografi', 'Bahasa Indonesia', 'PPKN', 
              'Biologi', 'Kimia', 'Fisika'
          ];
          break;
      case 'Fakultas Kedokteran':
          jurusanOptions = ['Pendidikan Dokter', 'Farmasi', 'Psikologi'];
          break;
      case 'FIB':
          jurusanOptions = ['Sastra Inggris', 'Sastra Indonesia', 'Ilmu Sejarah'];
          break;
      case 'Pertanian':
          jurusanOptions = [
              'Kehutanan', 'Agribisnis', 'Agreteknologi', 
              'Peternakan', 'Teknologi Pertanian', 'Ilmu Tanah'
          ];
          break;
      case 'FPIK':
          jurusanOptions = [
              'Ilmu Kelautan', 'Manajemen Sumber Daya Perairan', 
              'Budidaya Perairan', 'Pemanfaatan Sumber Daya Perairan'
          ];
          break;
      default:
          jurusanOptions = [];
  }

  jurusanOptions.forEach(function(jurusan) {
      const option = document.createElement('option');
      option.value = jurusan;
      option.textContent = jurusan;
      jurusanSelect.appendChild(option);
  });
});
</script>
</body>
</html>

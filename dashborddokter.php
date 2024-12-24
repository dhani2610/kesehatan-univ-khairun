<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'dokter') {
  header('Location: login.php');
  exit();
}

include 'koneksi.php';

// Query untuk mengambil data mahasiswa, diurutkan berdasarkan fakultas dan tanggal pemeriksaan
$sql = "SELECT id, fakultas, nama_mahasiswa, nim, jurusan, tanggal_pemeriksaan, status 
        FROM mahasiswa 
        ORDER BY fakultas, tanggal_pemeriksaan DESC";

$result = $conn->query($sql);

// Cek apakah query berhasil
if (!$result) {
  die("Query error: " . $conn->error);
}

// Mengelompokkan data berdasarkan fakultas
$data = [];
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $data[$row['fakultas']][] = $row;
  }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Dokter</title>
  <!-- <link rel="stylesheet" href="dokterstyle.css"> -->
  <link rel="stylesheet" href="adminstyle.css">

  <style>
     /* Menambahkan kemampuan scroll pada tabel */
     .table-responsive {
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
      margin-bottom: 20px;
      /* Opsional: memberikan jarak di bawah tabel */
    }

    /* Menambahkan lebar tabel agar tetap responsif */
    table {
      width: 100%;
      border-collapse: collapse;
    }

    /* Menambahkan beberapa styling untuk tabel */
    th,
    td {
      padding: 10px;
      text-align: left;
      border: 1px solid #ddd;
    }

  </style>

</head>

<body>


  <header>
    <div style="display: flex; align-items: center;">
      <img src="images/logo.png" Logo Universitas Khairun">
      <div>
        <h1>Dashboard Dokter</h1>
        <p>Kelola Data Mahasiswa</p>
      </div>
    </div>
    <button class="btn-keluar" onclick="window.location.href='logout.php'">Keluar</button>
  </header>

  <div class="container">
    <h2>Data Mahasiswa</h2>
    <div id="fakultasData">
      <?php foreach ($data as $fakultas => $mahasiswa): ?>
        <div class="table-responsive">
          <h3><?php echo htmlspecialchars($fakultas); ?></h3>
          <table>
            <thead>
              <tr>
                <th>Nama</th>
                <th>NPM</th>
                <th>Jurusan</th>
                <th>Tanggal Pemeriksaan</th>
                <th>Sudah Pemeriksaan</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($mahasiswa as $mhs): ?>
                <tr>
                  <td><?php echo htmlspecialchars($mhs['nama_mahasiswa']); ?></td>
                  <td><?php echo htmlspecialchars($mhs['nim']); ?></td>
                  <td><?php echo htmlspecialchars($mhs['jurusan']); ?></td>
                  <?php
                  setlocale(LC_TIME, 'id_ID.utf8', 'id_ID', 'id');

                  $tanggal = new DateTime($mhs['tanggal_pemeriksaan']);
                  $hariTanggal = strftime('%A, %d %B %Y', $tanggal->getTimestamp());
                  ?>
                  <td><?php echo htmlspecialchars($hariTanggal); ?></td>
                  <td>
                    <center>
                      <input type="checkbox"
                        onclick="toggleHealthCheck(this, <?php echo $mhs['id']; ?>)"
                        <?php echo $mhs['status'] == 1 ? 'checked' : ''; ?>>
                    </center>
                  </td>

                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <script>
    function toggleHealthCheck(checkbox, id) {
      const status = checkbox.checked ? 1 : 0;

      fetch('pemeriksaan.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: `id=${id}&status=${status}`
        })
        .then(response => response.text())
        .then(data => {
          console.log(data);
          if (status === 1) {
            alert('Mahasiswa telah menyelesaikan pemeriksaan kesehatan.');
          } else {
            alert('Mahasiswa belum menyelesaikan pemeriksaan kesehatan.');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Terjadi kesalahan, silakan coba lagi.');
        });
    }
  </script>

</body>

</html>
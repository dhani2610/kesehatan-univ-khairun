<?php
session_start(); 

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'dokter') {
    header('Location: login.php');
    exit();
}

include 'koneksi.php';
$sql = "SELECT * FROM datamhs ORDER BY fakultas, nama, npm, jurusan";
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
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
  <title>Dashboard Admin</title>
  <link rel="stylesheet" href="dokterstyle.css">
</head>
<body>
<header>
  <div class="logo-container">
    <img src="images/logo.png" alt="Logo Universitas Khairun">
  </div>
  <div class="header-text">
    <h1>Dashboard Dokter</h1>
    <p>Kelola Data Mahasiswa</p>
  </div>
  <button class="btn-keluar" onclick="window.location.href='login.php'">Keluar</button>
</header>

<div class="container">
  <h2>Data Mahasiswa</h2>
  <button onclick="showFacultyData()">Lihat Data Mahasiswa per Fakultas</button>

  <!-- Fakultas Data -->
  <div id="fakultasData">
    <div>
      <h3>Fakultas Teknik</h3>
      <table>
        <thead>
          <tr>
            <th>Nama</th>
            <th>NPM</th>
            <th>Jurusan</th>
            <th>Sudah Pemeriksaan Kesehatan</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Ali</td>
            <td>07352211001</td>
            <td>Informatika</td>
            <td>
              <input type="checkbox" onclick="toggleHealthCheck(this)">
            </td>
          </tr>
          <tr>
            <td>Budi</td>
            <td>08352211002</td>
            <td>Elektro</td>
            <td>
              <input type="checkbox" onclick="toggleHealthCheck(this)">
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div>
      <h3>Fakultas Ekonomi</h3>
      <table>
        <thead>
          <tr>
            <th>Nama</th>
            <th>NPM</th>
            <th>Jurusan</th>
            <th>Sudah Pemeriksaan Kesehatan</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Charlie</td>
            <td>09352211003</td>
            <td>Akuntansi</td>
            <td>
              <input type="checkbox" onclick="toggleHealthCheck(this)">
            </td>
          </tr>
          <tr>
            <td>Diana</td>
            <td>10352211004</td>
            <td>Manajemen</td>
            <td>
              <input type="checkbox" onclick="toggleHealthCheck(this)">
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div>
      <h3>Fakultas Kedokteran</h3>
      <table>
        <thead>
          <tr>
            <th>Nama</th>
            <th>NPM</th>
            <th>Jurusan</th>
            <th>Sudah Pemeriksaan Kesehatan</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Eva</td>
            <td>11352211005</td>
            <td>Kedokteran Umum</td>
            <td>
              <input type="checkbox" onclick="toggleHealthCheck(this)">
            </td>
          </tr>
          <tr>
            <td>Faisal</td>
            <td>12352211006</td>
            <td>Kedokteran Gigi</td>
            <td>
              <input type="checkbox" onclick="toggleHealthCheck(this)">
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
</script>

</body>
</html>
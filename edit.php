<?php
require 'koneksi.php';

$id = $_GET ['id'];

$query = "SELECT * FROM mahasiswa WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data</title>
</head>
<body>
    <h1>Edit Data Mahasiswa</h1>
    <form action="proses_edit.php" method="POST">
        <input type="hidden" name="id" value="<?= $row['id'] ?>">
        <label>Nama:</label>
        <input type="text" name="nama" value="<?= $row['nama'] ?>" required><br>

        <label>NPM:</label>
        <input type="text" name="npm" value="<?= $row['npm'] ?>" required><br>

        <label>Fakultas:</label>
        <input type="text" name="fakultas" value="<?= $row['fakultas'] ?>" required><br>

        <label>Jurusan:</label>
        <input type="text" name="jurusan" value="<?= $row['jurusan'] ?>" required><br>

        <label>Jadwal Pemeriksaan:</label>
        <input type="date" name="jadwal_pemeriksaan" value="<?= $row['jadwal_pemeriksaan'] ?>" required><br>

        <button type="submit">Simpan</button>
    </form>
</body>
</html>
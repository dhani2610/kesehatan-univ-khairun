<?php 
$host = "localhost";
$user = "root";
$password = "";
$database = "db_p11"; 

$conn = mysqli_connect($host, $user, $password, $database);
// Pastikan ini mengarah ke file koneksi yang benar

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $confirm_password = isset($_POST['confirm-password']) ? trim($_POST['confirm-password']) : '';
    $role = isset($_POST['role']) ? trim($_POST['role']) : '';

    // Validasi input
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password) || empty($role)) {
        echo "Semua kolom harus diisi!";
        exit;
    }

    if ($password !== $confirm_password) {
        echo "Password dan konfirmasi password tidak cocok!";
        exit;
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Simpan ke database
    $query = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die('Error preparing query: ' . $conn->error);
    }

    // Bind parameter
    $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);

    // Eksekusi query
    if ($stmt->execute()) {
        echo "Pendaftaran berhasil!";
    } else {
        echo "Terjadi kesalahan saat mendaftar: " . $stmt->error;
    }

    // Tutup statement
    $stmt->close();
}

// Tutup koneksi
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi Akun - Sistem Pelayanan Kesehatan</title>
  <link rel="stylesheet" href="regisstyle.css"> 
  <script src="script.js" defer></script> 
</head>
<body>

  <header>
    <img src="images/logo.png" alt="Logo" style="max-width: 40px; max-height: 40px;"> 
    <h1>Sistem Pelayanan Kesehatan</h1>
    <p>Universitas Khairun</p>
  </header>

  <div class="container">
    <h2>Registrasi Akun</h2>
    <form method="POST" action="register.php">
      <label for="username">username</label>
      <input type="text" id="username" name="username" placeholder="Masukkan username" required>

      <label for="email">email</label>
      <input type="email" id="email" name="email" placeholder="Masukkan email" required>

      <label for="password">password</label>
      <input type="password" id="password" name="password" placeholder="Masukkan password" required>

      <label for="confirm-password">Konfirmasi Password</label>
      <input type="password" id="confirm-password" name="confirm-password" placeholder="Konfirmasi password" required>

      <label for="role">Pilih Role</label>
      <select id="role" name="role" required>
        <option value="user">User</option>
        <!-- <option value="admin">Admin</option> -->
        <option value="dokter">Dokter</option>
      </select>

      <button type="submit">Daftar</button>
    </form>
    <div class="link">
      <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>
  </div>
</body>
</html>


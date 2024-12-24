<?php
session_start();
include('koneksi.php');

if (!$conn) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

if (isset($_SESSION['role'])) { 
  if ($_SESSION['role'] === 'admin') {
    header('Location: dashbordadmin.php');
    exit();
  } elseif ($_SESSION['role'] === 'user') {
    header('Location: dashbordmhs.php');
    exit();
  } elseif ($_SESSION['role'] === 'dokter') {
    header('Location: dashborddokter.php');
    exit();
  }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['login-username'] ?? null);
    $password = trim($_POST['login-password'] ?? null);
    $role = $_POST['login-role'] ?? null;

    if (empty($username) || empty($password) || empty($role)) {
        die("Semua input harus diisi!");
    }

    $tableCheckQuery = "SHOW TABLES LIKE 'users'";
    $tableCheckResult = $conn->query($tableCheckQuery);
    if ($tableCheckResult->num_rows == 0) {
        die("Tabel 'users' tidak ditemukan di database.");
    }

    $sql = $conn->prepare("SELECT * FROM users WHERE username = ? AND role = ?");
    if ($sql === false) {
        die("Query gagal disiapkan: " . $conn->error);
    }

    $sql->bind_param("ss", $username, $role);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      // var_dump(password_verify($password, $row['password']));
        if (password_verify($password, $row['password'])) {
            session_start();
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;

            // echo "Login berhasil!";
            header('Location: index.php');
            exit();

            // if ($role === 'user') {
            //     header('Location: dashbordmhs.php');
            // } elseif ($role === 'admin') {
            //     header('Location: dashbordadmin.php'); 
            // } elseif ($role === 'dokter') {
            //     header('Location: dashborddokter.php'); 
            // }
            // exit();
        } else {
            echo "Password salah!".$password.'-'.$role;
        }
    } else {
        echo "User tidak ditemukan!";
    }

    $sql->close();
}

?>


<!DOCTYPE html> 
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Akun - Sistem Pelayanan Kesehatan</title>
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
    <h2>Login Akun</h2>
    <form action="login.php" method="POST"> <!-- Ganti action ke login.php -->
      <label for="login-username">Username</label>
      <input type="text" id="login-username" name="login-username" placeholder="Masukkan username" required>

      <label for="login-password">Password</label>
      <input type="password" id="login-password" name="login-password" placeholder="Masukkan password" required>

      <label for="login-role">Pilih Role</label>
      <select id="login-role" name="login-role" required>
      <option value="user">User</option>
      <option value="admin">Admin</option>
      <option value="dokter">Dokter</optio>
       </select>
      <button type="submit">Login</button>
    </form>
    <div class="link">
      <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </div>
  </div>

</body>
</html>

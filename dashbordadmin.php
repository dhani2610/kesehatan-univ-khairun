<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
  header('Location: login.php');
  exit();
}

include('koneksi.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'add') {
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
      }
    }
    if ($action === 'edit') {
      $id = $_POST['id'];
      $nama_mahasiswa = $_POST['nama_mahasiswa'];
      $nim = $_POST['nim'];
      $fakultas = $_POST['fakultas'];
      $jurusan = $_POST['jurusan'];
      $tanggal_pemeriksaan = $_POST['tanggal_pemeriksaan'];

      if (isset($_FILES['buktiPembayaran']) && $_FILES['buktiPembayaran']['name'] !== '') {
        $fileName = $_FILES['buktiPembayaran']['name'];
        $fileTmp = $_FILES['buktiPembayaran']['tmp_name'];
        $uploadDir = "uploads/";
        $filePath = $uploadDir . basename($fileName);
        move_uploaded_file($fileTmp, $filePath);

        $sql = "UPDATE mahasiswa SET nama_mahasiswa='$nama_mahasiswa', nim='$nim', fakultas='$fakultas', jurusan='$jurusan', bukti_pembayaran='$filePath', tanggal_pemeriksaan='$tanggal_pemeriksaan' WHERE id='$id'";
      } else {
        $sql = "UPDATE mahasiswa SET nama_mahasiswa='$nama_mahasiswa', nim='$nim', fakultas='$fakultas', jurusan='$jurusan', tanggal_pemeriksaan='$tanggal_pemeriksaan' WHERE id='$id'";
      }

      if ($conn->query($sql) === TRUE) {
        echo "Data berhasil diperbarui.";
      } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
      }
    }

    if ($action === 'delete') {
      $id = $_POST['id'];

      $sql = "DELETE FROM mahasiswa WHERE id = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("i", $id);
      if ($stmt->execute()) {
        echo "Data mahasiswa berhasil dihapus!";
      } else {
        echo "Gagal menghapus data mahasiswa!";
      }
      $stmt->close();
    }
  }
}


$sql = "SELECT * FROM mahasiswa ORDER BY tanggal_pemeriksaan DESC";
$result = $conn->query($sql);



// JADWAL 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['action'])) {
    $action = $_POST['action'];

    // Tambah Jadwal
    if ($action === 'add_jadwal') {
      $fakultas = $_POST['fakultas'];
      $tanggal = $_POST['tanggal'];
      $waktu_dari = $_POST['waktu_dari'];
      $waktu_sampai = $_POST['waktu_sampai'];

      $sql = "INSERT INTO jadwals (fakultas, tanggal, waktu_dari,waktu_sampai) VALUES ('$fakultas', '$tanggal', '$waktu_dari', '$waktu_sampai')";
      if ($conn->query($sql) === TRUE) {
        echo "Jadwal berhasil ditambahkan.";
      } else {
        echo "Error: " . $conn->error;
      }
    }

    // Edit Jadwal
    if ($action === 'edit_jadwal') {
      $id = $_POST['id'];
      $fakultas = $_POST['fakultas'];
      $tanggal = $_POST['tanggal'];
      $waktu_dari = $_POST['waktu_dari'];
      $waktu_sampai = $_POST['waktu_sampai'];

      $sql = "UPDATE jadwals SET fakultas='$fakultas', tanggal='$tanggal', waktu_dari='$waktu_dari', waktu_sampai='$waktu_sampai'  WHERE id='$id'";
      if ($conn->query($sql) === TRUE) {
        echo "Jadwal berhasil diperbarui.";
      } else {
        echo "Error: " . $conn->error;
      }
    }

    // Hapus Jadwal
    if ($action === 'delete_jadwal') {
      $id = $_POST['id'];

      $sql = "DELETE FROM jadwals WHERE id='$id'";
      if ($conn->query($sql) === TRUE) {
        echo "Jadwal berhasil dihapus.";
      } else {
        echo "Error: " . $conn->error;
      }
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin</title>
  <link rel="stylesheet" href="adminstyle.css">
  <link rel="stylesheet" href="formstyle.css">


  <style>
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      overflow: auto;
    }

    .modal-content {
      background-color: #fff;
      margin: 10% auto;
      padding: 20px;
      border-radius: 5px;
      width: 50%;
    }

    .close {
      float: right;
      font-size: 24px;
      font-weight: bold;
      cursor: pointer;
    }

    .btn-container {
      margin-top: 20px;
      display: flex;
      gap: 10px;
    }

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
        <h1>Dashboard Admin</h1>
        <p>Kelola Data Mahasiswa</p>
      </div>
    </div>
    <button class="btn-keluar" onclick="window.location.href='logout.php'">Keluar</button>
  </header>

  <div class="container">
    <h2>Data Mahasiswa</h2>
    <button onclick="showFormModal()">Tambah/Modifikasi Data</button>
    <div class="table-responsive">
      <table>
        <thead>
          <tr>
            <th>Nama</th>
            <th>NPM</th>
            <th>Fakultas</th>
            <th>Jurusan</th>
            <th>Jadwal Pemeriksaan</th>
            <th>Bukti Pembayaran</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody id="dataMahasiswa">
          <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?php echo htmlspecialchars($row['nama_mahasiswa']); ?></td>
                <td><?php echo htmlspecialchars($row['nim']); ?></td>
                <td><?php echo htmlspecialchars($row['fakultas']); ?></td>
                <td><?php echo htmlspecialchars($row['jurusan']); ?></td>
                <td><?php echo htmlspecialchars($row['tanggal_pemeriksaan']); ?></td>
                <td>
                  <a target="_blank" href="<?php echo $row['bukti_pembayaran']; ?>">Download</a>
                </td>
                <td>
                  <button onclick="editDataAdmin(
                                <?php echo $row['id']; ?>, 
                                '<?php echo htmlspecialchars($row['nama_mahasiswa']); ?>', 
                                '<?php echo htmlspecialchars($row['nim']); ?>', 
                                '<?php echo htmlspecialchars($row['fakultas']); ?>', 
                                '<?php echo htmlspecialchars($row['jurusan']); ?>', 
                                '<?php echo htmlspecialchars($row['tanggal_pemeriksaan']); ?>'
                              )">Edit</button>
                  <button onclick="confirmDelete(<?php echo $row['id']; ?>)" style="background: red;">Delete</button>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="6">Tidak ada data mahasiswa.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  </div>

  <div class="container">
    <h2>Mengatur Jadwal Mahasiswa</h2>
    <button onclick="showFormModalJadwal()">Tambah Jadwal</button>
    <div class="table-responsive">
    <table>
      <thead>
        <tr>
          <th>Fakultas</th>
          <th>Hari / Tanggal</th>
          <th>Waktu</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody id="dataMahasiswa">
        <?php
        $sqlJadwal = "SELECT * FROM jadwals ORDER BY id DESC";
        $resultJadwal = $conn->query($sqlJadwal);

        // Periksa apakah query berhasil
        if (!$resultJadwal) {
          die("Query Error: " . $conn->error);
        }
        ?>
        <?php if ($resultJadwal->num_rows > 0): ?>
          <?php while ($row = $resultJadwal->fetch_assoc()): ?>
            <tr>
              <td><?php echo htmlspecialchars($row['fakultas']); ?></td>
              <?php
              // Pastikan locale Bahasa Indonesia sudah diatur
              setlocale(LC_TIME, 'id_ID.utf8', 'id_ID', 'id');

              // Format tanggal dari database
              $tanggal = new DateTime($row['tanggal']);
              $hariTanggal = strftime('%A, %d %B %Y', $tanggal->getTimestamp());
              ?>
              <td><?php echo htmlspecialchars($hariTanggal); ?></td>
              <!-- <td><?php echo htmlspecialchars($row['tanggal']); ?></td> -->
              <td><?php echo htmlspecialchars($row['waktu_dari']) . ' - ' . htmlspecialchars($row['waktu_sampai']); ?></td>
              <td>
                <button onclick="editDataAdminJadwal(
                              <?php echo $row['id']; ?>, 
                              '<?php echo htmlspecialchars($row['fakultas']); ?>', 
                              '<?php echo htmlspecialchars($row['tanggal']); ?>', 
                              '<?php echo htmlspecialchars($row['waktu_dari']); ?>', 
                              '<?php echo htmlspecialchars($row['waktu_sampai']); ?>'
                            )">Edit</button>
                <button onclick="confirmDeleteJadwal(<?php echo $row['id']; ?>)" style="background: red;">Delete</button>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="6">Tidak ada data jadwal.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
    </table>


  </div>


  <!-- Modal Create Data Mahasiswa -->
  <div id="modalCreate" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal()">&times;</span>
      <h3>Tambah Data Mahasiswa</h3>
      <form id="inputForm" action="dashbordadmin.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="add">


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

        <label for="tanggal_pemeriksaan">Tanggal Pemeriksaan:</label>
        <input type="date" id="tanggal_pemeriksaan" name="tanggal_pemeriksaan" required>

        <label for="buktiPembayaran">Unggah Bukti Pembayaran:</label>
        <input type="file" id="buktiPembayaran" name="buktiPembayaran" accept=".jpg,.jpeg,.png,.pdf" required>

        <div class="btn-container">
          <button type="submit">Kirim Data</button>
          <button type="button" onclick="closeModal()">Batal</button>
        </div>
      </form>
    </div>
  </div>


  <!-- Modal Edit Data Mahasiswa -->
  <div id="modalEdit" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeEditModal()">&times;</span>
      <h3>Edit Data Mahasiswa</h3>
      <form id="editForm" action="dashbordadmin.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="edit">
        <input type="hidden" id="edit_id" name="id">

        <label for="edit_nama">Nama Mahasiswa:</label>
        <input type="text" id="edit_nama" name="nama_mahasiswa" required>

        <label for="edit_nim">NPM:</label>
        <input type="text" id="edit_nim" name="nim" required>

        <label for="edit_fakultas">Fakultas:</label>
        <select id="edit_fakultas" name="fakultas" required>
          <option value="Fakultas Teknik">Fakultas Teknik</option>
          <option value="Fakultas Ekonomi">Fakultas Ekonomi</option>
          <option value="Fakultas Hukum">Fakultas Hukum</option>
          <option value="Fakultas Kedokteran">Fakultas Kedokteran</option>
        </select>

        <label for="edit_jurusan">Jurusan:</label>
        <select id="edit_jurusan" name="jurusan" required>
          <option value="Informatika">Informatika</option>
          <option value="Teknik Sipil">Teknik Sipil</option>
          <option value="Manajemen">Manajemen</option>
          <option value="Hukum Pidana">Hukum Pidana</option>
        </select>

        <label for="edit_tanggal_pemeriksaan">Tanggal Pemeriksaan:</label>
        <input type="date" id="edit_tanggal_pemeriksaan" name="tanggal_pemeriksaan" required>

        <label for="edit_buktiPembayaran">Unggah Bukti Pembayaran:</label>
        <input type="file" id="edit_buktiPembayaran" name="buktiPembayaran" accept=".jpg,.jpeg,.png,.pdf">

        <div class="btn-container">
          <button type="submit">Simpan Perubahan</button>
          <button type="button" onclick="closeEditModal()">Batal</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal Konfirmasi Delete -->
  <div id="modalDelete" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeDeleteModal()">&times;</span>
      <h3>Konfirmasi Hapus Data</h3>
      <p>Apakah Anda yakin ingin menghapus data ini?</p>
      <form id="deleteForm" action="dashbordadmin.php" method="POST">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" id="delete_id" name="id">
        <div class="btn-container">
          <button type="submit">Ya, Hapus</button>
          <button type="button" onclick="closeDeleteModal()">Batal</button>
        </div>
      </form>
    </div>
  </div>


  <!-- jadwal -->
  <!-- Modal Create Data Mahasiswa -->
  <div id="modalCreateJadwal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModalJadwal()">&times;</span>
      <h3>Tambah Data Jadwal</h3>
      <form id="inputForm" action="dashbordadmin.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="add_jadwal">

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

        <label for="tanggal_pemeriksaan">Tanggal:</label>
        <input type="date" id="tanggal" name="tanggal" required>

        <label for="buktiPembayaran">Waktu Dari:</label>
        <input type="time" id="waktu_dari" name="waktu_dari" required>

        <label for="buktiPembayaran">Waktu Sampai:</label>
        <input type="time" id="waktu_sampai" name="waktu_sampai" required>

        <div class="btn-container">
          <button type="submit">Kirim Data</button>
          <button type="button" onclick="closeModalJadwal()">Batal</button>
        </div>
      </form>
    </div>
  </div>


  <div id="modalEditJadwal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeEditModalJadwal()">&times;</span>
      <h3>Edit Data Mahasiswa</h3>
      <form id="editForm" action="dashbordadmin.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="edit_jadwal">
        <input type="hidden" id="edit_id_jadwal" name="id">

        <label for="fakultas">Fakultas:</label>
        <select id="edit_fakultas_jadwal" name="fakultas" required>
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

        <label for="tanggal_pemeriksaan">Tanggal:</label>
        <input type="date" id="edit_tanggal_jadwal" name="tanggal" required>

        <label for="buktiPembayaran">Waktu Dari:</label>
        <input type="time" id="edit_waktu_dari" name="waktu_dari" required>

        <label for="buktiPembayaran">Waktu Sampai:</label>
        <input type="time" id="edit_waktu_sampai" name="waktu_sampai" required>

        <div class="btn-container">
          <button type="submit">Simpan Perubahan</button>
          <button type="button" onclick="closeEditModalJadwal()">Batal</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal Konfirmasi Delete -->
  <div id="modalDeleteJadwal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeDeleteModalJadwal()">&times;</span>
      <h3>Konfirmasi Hapus Data</h3>
      <p>Apakah Anda yakin ingin menghapus data ini?</p>
      <form id="deleteForm" action="dashbordadmin.php" method="POST">
        <input type="hidden" name="action" value="delete_jadwal">
        <input type="hidden" id="delete_id_jadwal" name="id">
        <div class="btn-container">
          <button type="submit">Ya, Hapus</button>
          <button type="button" onclick="closeDeleteModalJadwal()">Batal</button>
        </div>
      </form>
    </div>
  </div>



  <script>
    // Tampilkan Modal Create
    function showFormModalJadwal() {
      document.getElementById('modalCreateJadwal').style.display = 'block';
    }

    function showFormModal() {
      document.getElementById('modalCreateJadwal').style.display = 'block';
    }

    // Tutup Modal
    function closeModal() {
      document.getElementById('modalCreate').style.display = 'none';
    }

    function closeModalJadwal() {
      document.getElementById('modalCreateJadwal').style.display = 'none';
    }

    // Fungsi untuk Menampilkan Modal Edit
    function editDataAdmin(id, nama, nim, fakultas, jurusan, tanggal) {
      document.getElementById('edit_id').value = id;
      document.getElementById('edit_nama').value = nama;
      document.getElementById('edit_nim').value = nim;
      document.getElementById('edit_fakultas').value = fakultas;
      document.getElementById('edit_jurusan').value = jurusan;
      document.getElementById('edit_tanggal_pemeriksaan').value = tanggal;

      document.getElementById('modalEdit').style.display = 'block';
    }
    // Fungsi untuk Menampilkan Modal Edit
    function editDataAdminJadwal(id, fakultas, tanggal, waktu_dari, waktu_sampai) {
      console.log(id);

      document.getElementById('edit_id_jadwal').value = id;
      document.getElementById('edit_fakultas_jadwal').value = fakultas;
      document.getElementById('edit_tanggal_jadwal').value = tanggal;
      document.getElementById('edit_waktu_dari').value = waktu_dari;
      document.getElementById('edit_waktu_sampai').value = waktu_sampai;

      document.getElementById('modalEditJadwal').style.display = 'block';
    }

    // Tutup Modal Edit
    function closeEditModal() {
      document.getElementById('modalEdit').style.display = 'none';
    }

    function closeEditModalJadwal() {
      document.getElementById('modalEditJadwal').style.display = 'none';
    }

    // Fungsi untuk Menampilkan Modal Delete
    function confirmDelete(id) {
      document.getElementById('delete_id').value = id;
      document.getElementById('modalDelete').style.display = 'block';
    }

    function confirmDeleteJadwal(id) {
      console.log(id);

      document.getElementById('delete_id_jadwal').value = id;
      document.getElementById('modalDeleteJadwal').style.display = 'block';
    }

    // Tutup Modal Delete
    function closeDeleteModal() {
      document.getElementById('modalDelete').style.display = 'none';
    }

    function closeDeleteModalJadwal() {
      document.getElementById('modalDeleteJadwal').style.display = 'none';
    }
  </script>
  <script src="scriptadmin.js"></script>
  <script src="utils.js"></script>
</body>

</html>
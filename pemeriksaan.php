<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST['id']);
    $status = intval($_POST['status']);

    // Pastikan ID valid
    if ($id <= 0) {
        die("ID tidak valid");
    }

    $sql = "UPDATE mahasiswa SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $status, $id);

    if ($stmt->execute()) {
        echo "Sukses";
    } else {
        echo "Gagal: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>

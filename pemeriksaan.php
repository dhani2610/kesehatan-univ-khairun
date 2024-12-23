<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST['id']);
    $status = intval($_POST['status']);

    $sql = "UPDATE datamhs SET sudah_pemeriksaan = ? WHERE id = ?";
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

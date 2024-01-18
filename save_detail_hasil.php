<?php
// Koneksi ke database (sesuaikan dengan konfigurasi database Anda)
include 'connection.php';

// Ambil data dari permintaan POST
$data = json_decode(file_get_contents("php://input"), true);

// Data yang diperlukan untuk disimpan di tabel detail_hasil
$detailDataArray = $data['detailDataArray'];

foreach ($detailDataArray as $detailData) {
    $id_hasil = $detailData['id_hasil'];
    $id_rule = $detailData['id_rule'];
    $support = $detailData['support'];
    $confidence = $detailData['confidence'];

    // Query untuk menyimpan data ke tabel detail_hasil
    $sql = "INSERT INTO detail_hasil (`hasil_id`, `rule`, `sup_detail`, `conf_detail`) VALUES ('$id_hasil', '$id_rule', '$support', '$confidence')";

    if ($conn->query($sql) !== TRUE) {
        echo "Error: " . $sql . "<br>" . $conn->error;
        // You may choose to break the loop or handle the error in another way
    }
}

echo "Data detail_hasil berhasil disimpan.";

// Tutup koneksi
$conn->close();
?>

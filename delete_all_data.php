<?php
include 'connection.php'; // Sesuaikan dengan nama file koneksi yang Anda gunakan

// Jalankan query untuk menghapus semua data dari tabel transaksi
$query = "TRUNCATE TABLE transaksi";
$result = mysqli_query($conn, $query);

if ($result) {
    echo "All data deleted successfully!";
} else {
    echo "Error deleting data: " . mysqli_error($conn);
}

mysqli_close($conn);
?>

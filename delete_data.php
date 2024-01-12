<?php
include 'layouts/config.php'; // Include your database configuration file

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the 'id_transaksi' parameter is set
    if (isset($_POST['id_transaksi'])) {
        $id_transaksi = $_POST['id_transaksi'];

        // Prepare and execute the DELETE query
        $deleteStmt = $link->prepare("DELETE FROM transaksi WHERE id_transaksi = ?");
        $deleteStmt->bind_param('s', $id_transaksi);

        if ($deleteStmt->execute()) {
            echo 'Data deleted successfully.';
        } else {
            echo 'Error deleting data.';
        }

        // Close the statement
        $deleteStmt->close();
    } else {
        echo 'Invalid request. Missing id_transaksi parameter.';
    }
} else {
    echo 'Invalid request method. Only POST requests are allowed.';
}
?>

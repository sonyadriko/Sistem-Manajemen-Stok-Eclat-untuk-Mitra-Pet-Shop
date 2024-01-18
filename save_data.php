<?php
include 'connection.php';

$data = json_decode(file_get_contents("php://input"), true);

$response = []; // Inisialisasi array respons

if (isset($data['support']) && isset($data['confidence'])) {
    $minSupportPercentage = $data['support'];
    $minConfidencePercentage = $data['confidence'];

    $sql = "INSERT INTO hasil (`min_sup`, `min_con`) VALUES ('$minSupportPercentage', '$minConfidencePercentage')";

    if ($conn->query($sql) === TRUE) {
        $last_id = $conn->insert_id;
        $response = ['last_id' => $last_id];
    } else {
        $response = ['error' => "Error: " . $sql . "<br>" . $conn->error];
    }
}

// Mengonversi array respons ke format JSON dan mencetaknya
echo json_encode($response);

$conn->close();
?>

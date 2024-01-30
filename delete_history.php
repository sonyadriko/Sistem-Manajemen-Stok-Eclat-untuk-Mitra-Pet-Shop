<?php 
	
	include 'connection.php';

	if (isset($_GET['id'])) {
		// code...
		$id_history = $_GET['id'];
		$query = "DELETE FROM hasil WHERE id_hasil = '".$id_history."'";
		$result = mysqli_query($conn, $query);

		if ($result) {
			// code...
			header("Location:history.php");
		}else {
			echo "Please Check Again";
		}
	}
?>
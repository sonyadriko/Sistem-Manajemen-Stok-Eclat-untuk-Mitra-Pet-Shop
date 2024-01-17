<?php 
include 'connection.php';
session_start();
 if (!isset($_SESSION['id_user'])) {
     header("Location: login.php");
 }
?>
<?php include 'layouts/head-main.php'; ?>
<?php include 'layouts/config.php'; ?>

<?php
require 'vendor/autoload.php'; // Menggunakan Composer untuk mengelola dependensi PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\IOFactory;

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     if (isset($_POST['import'])) {
//         // Process the uploaded Excel file for import
//         if (isset($_FILES['excelFile']) && $_FILES['excelFile']['error'] == UPLOAD_ERR_OK) {
//             $excelFile = $_FILES['excelFile']['tmp_name'];

//             // Load the Excel file with allowOnly setting
//             $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
//             $reader->setReadDataOnly(true);
//             $spreadsheet = $reader->load($excelFile);

//             $worksheet = $spreadsheet->getActiveSheet();

//             // Prepare the statement for checking if the data already exists in the 'transaksi' table
//             $checkStmt = $link->prepare('SELECT COUNT(*) FROM transaksi WHERE id_transaksi = ? AND kode_barang = ?');
//             $checkStmt->bind_param('ss', $id_transaksi, $kode_barang);

//             // Prepare the statement for inserting data into the 'transaksi' table
//             $stmt = $link->prepare('INSERT INTO transaksi (id_transaksi, kode_barang) VALUES (?, ?)');

//             // Iterate through rows and insert or skip data based on existence in the 'transaksi' table
//             foreach ($worksheet->getRowIterator() as $row) {
//                 $rowData = [];
//                 foreach ($row->getCellIterator() as $cell) {
//                     $rowData[] = $cell->getValue();
//                 }

//                 // Assuming the Excel columns are in the order of 'id_transaksi', 'kode_barang'
//                 if (count($rowData) == 2) {
//                     $id_transaksi = $rowData[0];
//                     $kode_barang = $rowData[1];

//                     // Check if data with the same 'id_transaksi' and 'kode_barang' already exists
//                     $checkStmt->execute();
//                     $checkStmt->store_result();
//                     $checkStmt->bind_result($count);
//                     $checkStmt->fetch();

//                     if ($count == 0) {
//                         // Data doesn't exist, proceed with the insertion
//                         if (!empty($id_transaksi) && !empty($kode_barang)) {
//                             if ($stmt->execute([$id_transaksi, $kode_barang])) {
//                                 echo 'Sukses: Data berhasil diimport.';
//                             } else {
//                                 echo 'Error: Gagal menyimpan data.';
//                             }
//                         } else {
//                             echo 'Error: id_transaksi or kode_barang is empty.';
//                         }
//                     } else {
//                         // Data already exists, you may choose to skip or handle it differently
//                         echo "Data with id_transaksi $id_transaksi and kode_barang $kode_barang already exists. Skipping...\n";
//                     }
//                 }
//             }

//             // Close the statements
//             $stmt->close();
//             $checkStmt->close();

//             echo 'Import successful!';
//         } else {
//             echo 'Error uploading the file.';
//         }
//     }
// }


$importSuccess = false;
$duplicateDataEncountered = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['import'])) {
        // Process the uploaded Excel file for import
        if (isset($_FILES['excelFile']) && $_FILES['excelFile']['error'] == UPLOAD_ERR_OK) {
            $excelFile = $_FILES['excelFile']['tmp_name'];

            // Load the Excel file with allowOnly setting
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($excelFile);

            $worksheet = $spreadsheet->getActiveSheet();

            // Prepare the statement for checking if the data already exists in the 'transaksi' table
            $checkStmt = $link->prepare('SELECT COUNT(*) FROM transaksi WHERE id_transaksi = ? AND kode_barang = ?');
            $checkStmt->bind_param('ss', $id_transaksi, $kode_barang);

            // Prepare the statement for inserting data into the 'transaksi' table
            $stmt = $link->prepare('INSERT INTO transaksi (id_transaksi, kode_barang) VALUES (?, ?)');

            // Iterate through rows and insert or skip data based on existence in the 'transaksi' table
            // foreach ($worksheet->getRowIterator() as $row) {
            //     $rowData = [];
            //     foreach ($row->getCellIterator() as $cell) {
            //         $rowData[] = $cell->getValue();
            //     }

            //     // Assuming the Excel columns are in the order of 'id_transaksi', 'kode_barang'
            //     if (count($rowData) == 2) {
            //         $id_transaksi = $rowData[0];
            //         $kode_barang = $rowData[1];

            //         // Check if data with the same 'id_transaksi' and 'kode_barang' already exists
            //         $checkStmt->execute();
            //         $checkStmt->store_result();
            //         $checkStmt->bind_result($count);
            //         $checkStmt->fetch();

            //         if ($count == 0) {
            //             // Data doesn't exist, proceed with the insertion
            //             if (!empty($id_transaksi) && !empty($kode_barang)) {
            //                 if ($stmt->execute([$id_transaksi, $kode_barang])) {
            //                     $importSuccess = true;
            //                 } else {
            //                     echo 'Error: Gagal menyimpan data.';
            //                 }
            //             } else {
            //                 echo 'Error: id_transaksi or kode_barang is empty.';
            //             }
            //         } else {
            //             // Data already exists, you may choose to skip or handle it differently
            //             $duplicateDataEncountered = true;
            //         }
            //     }
            // }
            foreach ($worksheet->getRowIterator() as $row) {
                $rowData = [];
                foreach ($row->getCellIterator() as $cell) {
                    $rowData[] = $cell->getValue();
                }
            
                // Assuming the Excel columns are in the order of 'id_transaksi', 'kode_barang'
                if (count($rowData) == 2) {
                    $id_transaksi = $rowData[0];
                    $kode_barang = $rowData[1];
            
                    // Check if data with the same 'id_transaksi' and 'kode_barang' already exists
                    $checkStmt->execute();
                    $checkStmt->store_result();
                    $checkStmt->bind_result($count);
                    $checkStmt->fetch();
            
                    if ($count == 0) {
                        // Data doesn't exist, proceed with the insertion
                        if (!empty($id_transaksi) && !empty($kode_barang)) {
                            // Bind parameters before executing the statement
                            $stmt->bind_param('ss', $id_transaksi, $kode_barang);
                            
                            if ($stmt->execute()) {
                                $importSuccess = true;
                            } else {
                                echo 'Error: Gagal menyimpan data.';
                            }
                        } else {
                            echo 'Error: id_transaksi or kode_barang is empty.';
                        }
                    } else {
                        // Data already exists, you may choose to skip or handle it differently
                        $duplicateDataEncountered = true;
                    }
                }
            }

            // Close the statements
            $stmt->close();
            $checkStmt->close();
            
            if ($importSuccess) {
                echo 'Import successful!';
            }

            if ($duplicateDataEncountered) {
                echo "Data with id_transaksi $id_transaksi and kode_barang $kode_barang already exists. Skipping...\n";
            }
        } else {
            echo 'Error uploading the file.';
        }
    }
}




?>

<head>
    <title>Transaksi</title>

    <?php include 'layouts/head.php'; ?>

    <link href="assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
     <!-- DataTables -->
    <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    
    <?php include 'layouts/head-style.php'; ?>
</head>

<?php include 'layouts/body.php'; ?>

<!-- Begin page -->
<div id="layout-wrapper">

    <?php include 'layouts/menu.php'; ?>

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    
    <div class="main-content">

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Transaksi</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <?php
        if($_SESSION['role'] == 'admin'){

        
        ?>
        <form action="transaksi.php" method="post" enctype="multipart/form-data">
            <label for="excelFile">Choose Excel File:</label>
            <input type="file" name="excelFile" id="excelFile" accept=".xls, .xlsx" required>
            <button type="submit" name="import">Import</button>
        </form>
        <?php } ?>
    

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Data Transaksi</h4>
                    </div>
                    <div class="card-body">

                    <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
    <thead>
        <tr>
            <th>ID Transaksi</th>
            <!-- <th>Kode</th> -->
            <th>Nama Barang</th>
            <?php 
            if($_SESSION['role'] == 'admin'){ ?>
                <th>Action</th>
            <?php } ?>
            
                
        </tr>
    </thead>

    <tbody>
        <?php
        $query = "SELECT id_transaksi, GROUP_CONCAT(kode_barang) AS kode_barang_concatenated FROM TRANSAKSI GROUP BY id_transaksi";

        $result = mysqli_query($link, $query);
        
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>{$row['id_transaksi']}</td>";
            echo "<td>{$row['kode_barang_concatenated']}</td>";
            if($_SESSION['role'] == 'admin'){ 
            echo "<td><button class='btn btn-danger' onclick='deleteData(\"{$row['id_transaksi']}\")'>Delete</button></td>"; // Add the delete button
            }
            echo "</tr>";
        }        
        ?>
    </tbody>
</table>


                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>
<!-- End Page-content -->


<?php include 'layouts/footer.php'; ?>
</div>
    <!-- end main content-->

</div>
<!-- END layout-wrapper -->

<!-- JAVASCRIPT -->
<?php include 'layouts/vendor-scripts.php'; ?>

<!-- Required datatable js -->
<script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<!-- apexcharts -->
<script src="assets/libs/apexcharts/apexcharts.min.js"></script>

<!-- Buttons examples -->
<script src="assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
<script src="assets/libs/jszip/jszip.min.js"></script>
<script src="assets/libs/pdfmake/build/pdfmake.min.js"></script>
<script src="assets/libs/pdfmake/build/vfs_fonts.js"></script>
<script src="assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="assets/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>


<!-- Plugins js-->
<script src="assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js"></script>

<!-- dashboard init -->
<script src="assets/js/pages/dashboard.init.js"></script>

<!-- Datatable init js -->
<script src="assets/js/pages/datatables.init.js"></script>

<!-- App js -->
<script src="assets/js/app.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    <?php
    if ($importSuccess) {
        echo 'Swal.fire("Success", "Data berhasil diimport.", "success");';
    }

    if ($duplicateDataEncountered) {
        echo 'Swal.fire("Warning", "Data with the same id_transaksi and kode_barang already exists. Skipping...", "warning");';
    }
    ?>
</script>
<script>
    function deleteData(id_transaksi) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // You can use AJAX to send a request to the server for deleting the data
                // Here's a basic example, assuming you have a separate PHP file for handling deletions

                // Create a new FormData object and append the id_transaksi to it
                var formData = new FormData();
                formData.append("id_transaksi", id_transaksi);

                // Send an AJAX request to the server to handle the deletion
                fetch("delete_data.php", {
                    method: "POST",
                    body: formData,
                })
                .then(response => response.text())
                .then(data => {
                    // Handle the response from the server
                    Swal.fire({
                        title: 'Deleted!',
                        text: data,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload(); // Reload the page after the user clicks "OK"
                    });
                })
                .catch(error => {
                    console.error("Error:", error);
                    Swal.fire('Error!', 'An error occurred while deleting data.', 'error');
                });
            }
        });
    }
</script>
<!-- <script>
    function deleteData(id_transaksi) {
        if (confirm("Are you sure you want to delete this data?")) {
            // You can use AJAX to send a request to the server for deleting the data
            // Here's a basic example, assuming you have a separate PHP file for handling deletions

            // Create a new FormData object and append the id_transaksi to it
            var formData = new FormData();
            formData.append("id_transaksi", id_transaksi);

            // Send an AJAX request to the server to handle the deletion
            fetch("delete_data.php", {
                method: "POST",
                body: formData,
            })
            .then(response => response.text())
            .then(data => {
                // Handle the response from the server
                alert(data); // You may want to update the UI or take other actions based on the server response
                location.reload(); // Reload the page to reflect the changes
            })
            .catch(error => {
                console.error("Error:", error);
            });
        }
    }
</script> -->
</body>

</html>
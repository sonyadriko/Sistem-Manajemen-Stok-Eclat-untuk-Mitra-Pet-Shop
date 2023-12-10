<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>
<?php include 'layouts/config.php'; ?>

<?php
require 'vendor/autoload.php'; // Menggunakan Composer untuk mengelola dependensi PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\IOFactory;
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

            // Prepare the statement for inserting data into the 'barang' table

            $stmt = $link->prepare('INSERT INTO barang (kode_barang, nama_barang) VALUES (?, ?)');

            // Iterate through rows and insert data into the 'barang' table
            foreach ($worksheet->getRowIterator() as $row) {
                $rowData = [];
                foreach ($row->getCellIterator() as $cell) {
                    $rowData[] = $cell->getValue();
                }

                // Assuming the Excel columns are in the order of 'kode', 'nama_barang'
                if (count($rowData) == 2) {
                    $kode = $rowData[0];
                    $nama_barang = $rowData[1];

                    // Insert data into the 'barang' table
                    if (!empty($kode) && !empty($nama_barang)) {
                        if ($stmt->execute([$kode, $nama_barang])) {
                            echo 'Sukses: Data berhasil diimport.';
                        } else {
                            echo 'Error: Gagal menyimpan data.';
                        }
                    } else {
                        echo 'Error: Kode or Nama barang is empty.';
                    }
                }
            }
            echo 'Import successful!';
        } else {
            echo 'Error uploading the file.';
        }
    }
}


?>

<head>
    <title><?php echo $language["Dashboard"]; ?> | Minia - Admin & Dashboard Template</title>

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
                    <h4 class="mb-sm-0 font-size-18">DataTables</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <form action="barang.php" method="post" enctype="multipart/form-data">
            <label for="excelFile">Choose Excel File:</label>
            <input type="file" name="excelFile" id="excelFile" accept=".xls, .xlsx" required>
            <button type="submit" name="import">Import</button>
        </form>


        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Default Datatable</h4>
                        <p class="card-title-desc">DataTables has most features enabled by
                            default, so all you need to do to use it with your own tables is to call
                            the construction function: <code>$().DataTable();</code>.
                        </p>
                    </div>
                    <div class="card-body">

                        <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>ID Barang</th>
                                    <th>Kode</th>
                                    <th>Nama Barang</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                // Sesuaikan dengan query SQL dan koneksi database Anda
                                $query = "SELECT id_barang, kode, nama_barang FROM barang";
                                $result = mysqli_query($link, $query);

                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>{$row['id_barang']}</td>";
                                    echo "<td>{$row['kode']}</td>";
                                    echo "<td>{$row['nama_barang']}</td>";
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

</body>

</html>
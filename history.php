<?php 
include 'connection.php';
session_start();
 if (!isset($_SESSION['id_user'])) {
     header("Location: login.php");
 }
?>
<?php include 'layouts/head-main.php'; ?>
<?php include 'layouts/config.php'; ?>

<head>
    <title>History</title>

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
                    <h4 class="mb-sm-0 font-size-18">History</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Data History</h4>
                    </div>
                    <div class="card-body">

                    <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <!-- <th>Kode</th> -->
                                <th>Pengujian Ke</th>
                                <th>Support</th>
                                <th>Confidence</th>
                                <th>Action</th>
                                
                                    
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $query = "SELECT * FROM HASIL";
                            $no = 1;
                            $result = mysqli_query($link, $query);
                            
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>{$no}</td>";
                                echo "<td>{$row['id_hasil']}</td>";
                                echo "<td>{$row['min_sup']}</td>";
                                echo "<td>{$row['min_con']}</td>";
                            echo "<td><a class='btn btn-info' href='detail_history.php?id={$row['id_hasil']}'>Detail</a></td>";
                                echo "</tr>";
                            $no++;
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

</body>

</html>
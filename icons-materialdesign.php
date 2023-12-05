<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>

<head>

    <title>Material Design Icons | Minia - Admin & Dashboard Template</title>
    <?php include 'layouts/head.php'; ?>
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
                            <h4 class="mb-sm-0 font-size-18">Material Design Icons</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Icons</a></li>
                                    <li class="breadcrumb-item active">Material Design Icons</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row icons-demo-content">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">New Icons</h4>
                                <p class="card-title-desc">Use <code>&lt;i class="mdi mdi-speedometer-slow"&gt;&lt;/i&gt;</code> <span class="badge bg-success">v6.5.95</span>.</p>
                            </div>
                            <div class="card-body">
                                <div class="row icon-demo-content" id="newIcons"></div>
                            </div> <!-- end card-body -->
                        </div> <!-- end card -->

                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">All Icons</h4>
                            </div>
                            <div class="card-body">
                                <div class="row icon-demo-content" id="icons"></div>
                            </div> <!-- end card-body -->
                        </div> <!-- end card -->
                    </div> <!-- end col -->
                </div> <!-- end row -->

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Size</h4>
                            </div>
                            <div class="card-body">

                                <div class="row icon-demo-content">
                                    <div class="col-xl-3 col-lg-4 col-sm-6">
                                        <i class="mdi mdi-18px mdi-account"></i> mdi-18px
                                    </div>

                                    <div class="col-xl-3 col-lg-4 col-sm-6">
                                        <i class="mdi mdi-24px mdi-account"></i> mdi-24px
                                    </div>

                                    <div class="col-xl-3 col-lg-4 col-sm-6">
                                        <i class="mdi mdi-36px mdi-account"></i> mdi-36px
                                    </div>

                                    <div class="col-xl-3 col-lg-4 col-sm-6">
                                        <i class="mdi mdi-48px mdi-account"></i> mdi-48px
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Rotate</h4>
                            </div>
                            <div class="card-body">

                                <div class="row icon-demo-content">
                                    <div class="col-xl-3 col-lg-4 col-sm-6">
                                        <i class="mdi mdi-rotate-45 mdi-account"></i> mdi-rotate-45
                                    </div>
                                    <div class="col-xl-3 col-lg-4 col-sm-6">
                                        <i class="mdi mdi-rotate-90 mdi-account"></i> mdi-rotate-90
                                    </div>
                                    <div class="col-xl-3 col-lg-4 col-sm-6">
                                        <i class="mdi mdi-rotate-135 mdi-account"></i> mdi-rotate-135
                                    </div>
                                    <div class="col-xl-3 col-lg-4 col-sm-6">
                                        <i class="mdi mdi-rotate-180 mdi-account"></i> mdi-rotate-180
                                    </div>
                                    <div class="col-xl-3 col-lg-4 col-sm-6">
                                        <i class="mdi mdi-rotate-225 mdi-account"></i> mdi-rotate-225
                                    </div>
                                    <div class="col-xl-3 col-lg-4 col-sm-6">
                                        <i class="mdi mdi-rotate-270 mdi-account"></i> mdi-rotate-270
                                    </div>
                                    <div class="col-xl-3 col-lg-4 col-sm-6">
                                        <i class="mdi mdi-rotate-315 mdi-account"></i> mdi-rotate-315
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Spin</h4>
                            </div>
                            <div class="card-body">
                                <h4 class="card-title">Spin</h4>

                                <div class="row icon-demo-content">
                                    <div class="col-xl-3 col-lg-4 col-sm-6">
                                        <i class="mdi mdi-spin mdi-loading"></i> mdi-spin
                                    </div>
                                    <div class="col-xl-3 col-lg-4 col-sm-6">
                                        <i class="mdi mdi-spin mdi-star"></i> mdi-spin
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->

            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->


        <?php include 'layouts/footer.php'; ?>
    </div>
    <!-- end main content-->

</div>
<!-- END layout-wrapper -->


<!-- Right Sidebar -->
<?php include 'layouts/right-sidebar.php'; ?>
<!-- /Right-bar -->

<!-- JAVASCRIPT -->

<?php include 'layouts/vendor-scripts.php'; ?>

<!-- materialdesign icon js-->
<script src="assets/js/pages/materialdesign.init.js"></script>

<script src="assets/js/app.js"></script>

</body>

</html>
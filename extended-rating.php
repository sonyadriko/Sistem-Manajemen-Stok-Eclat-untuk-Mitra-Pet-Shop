<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>

<head>

    <title>Rating | Minia - Admin & Dashboard Template</title>
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
                            <h4 class="mb-sm-0 font-size-18">Rating</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Extended</a></li>
                                    <li class="breadcrumb-item active">Rating</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Rater Js</h4>
                                <p class="card-title-desc">Rater Js Different Example For Rating</p>
                            </div>
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="p-lg-5 p-4 text-center" dir="ltr">
                                            <h5 class="font-size-15 mb-4">Basic Rater</h5>
                                            <div id="basic-rater"></div>
                                        </div>
                                    </div>
                                    <!-- end col -->
                                    <div class="col-sm-6">
                                        <div class="p-lg-5 p-4 text-center" dir="ltr">
                                            <h5 class="font-size-15 mb-4">Rater with Step</h5>
                                            <div id="rater-step"></div>
                                        </div>
                                    </div>
                                    <!-- end col -->
                                    <div class="col-sm-6">
                                        <div class="p-lg-5 p-4 text-center" dir="ltr">
                                            <h5 class="font-size-15 mb-4">Custom Messages</h5>
                                            <div id="rater-message"></div>
                                        </div>
                                    </div>
                                    <!-- end col -->
                                    <div class="col-sm-6">
                                        <div class="p-lg-5 p-4 text-center" dir="ltr">
                                            <h5 class="font-size-15 mb-4">Example with unlimited number of stars. readOnly option is set to true.</h5>
                                            <div id="rater-unlimitedstar"></div>
                                        </div>
                                    </div>
                                    <!-- end col -->
                                    <div class="col-sm-6">
                                        <div class="p-lg-5 p-4 text-center" dir="ltr">
                                            <h5 class="font-size-15 mb-4">On Hover event</h5>
                                            <div id="rater-onhover" class="align-middle"></div>
                                            <span class="ratingnum badge bg-info align-middle ms-2"></span>
                                        </div>
                                    </div>
                                    <!-- end col -->
                                    <div class="col-sm-6">
                                        <div class="p-lg-5 p-4 text-center" dir="ltr">
                                            <h5 class="font-size-15 mb-4">Clear/Reset rater</h5>
                                            <div id="raterreset" class="align-middle"></div>
                                            <span class="clear-rating"></span>
                                            <button id="raterreset-button" class="btn btn-outline-primary btn-sm ms-2">Reset</button>
                                        </div>
                                    </div>
                                    <!-- end col -->
                                </div>
                                <!-- end row -->

                            </div>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->

            </div> <!-- end container-fluid -->
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

<!-- rater-js plugin -->
<script src="assets/libs/rater-js/index.js"></script>
<!-- rating init -->
<script src="assets/js/pages/rating.init.js"></script>

<script src="assets/js/app.js"></script>

</body>

</html>
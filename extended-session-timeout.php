<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>

<head>

    <title>Session Timeout | Minia - Admin & Dashboard Template</title>
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
                            <h4 class="mb-sm-0 font-size-18">Session Timeout</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Extended</a></li>
                                    <li class="breadcrumb-item active">Session Timeout</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Bootstrap-session-timeout</h5>
                                <p class="card-title-desc">Session timeout and keep-alive control
                                    with a nice Bootstrap warning dialog.</p>
                            </div>
                            <div class="card-body">
                                <div>
                                    <p>After a set amount of idle time, a Bootstrap warning dialog is shown
                                        to the user with the option to either log out, or stay connected. If
                                        "Logout" button is selected, the page is redirected to a logout URL.
                                        If "Stay Connected" is selected the dialog closes and the session is
                                        kept alive. If no option is selected after another set amount of
                                        idle time, the page is automatically redirected to a set timeout
                                        URL.</p>

                                    <p>
                                        Idle time is defined as no mouse, keyboard or touch event activity registered by the browser.
                                    </p>

                                    <p class="mb-0">
                                        As long as the user is active, the (optional) keep-alive URL keeps
                                        getting pinged and the session stays alive. If you have no need to
                                        keep the server-side session alive via the keep-alive URL, you can
                                        also use this plugin as a simple lock mechanism that redirects to
                                        your lock-session or log-out URL after a set amount of idle time.
                                    </p>
                                </div>
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


<!-- Right Sidebar -->
<?php include 'layouts/right-sidebar.php'; ?>
<!-- /Right-bar -->

<!-- JAVASCRIPT -->

<?php include 'layouts/vendor-scripts.php'; ?>

<!-- Session timeout js -->
<script src="assets/libs/@curiosityx/bootstrap-session-timeout/index.js"></script>

<!-- Session timeout init js -->
<script src="assets/js/pages/session-timeout.init.js"></script>

<script src="assets/js/app.js"></script>

</body>

</html>
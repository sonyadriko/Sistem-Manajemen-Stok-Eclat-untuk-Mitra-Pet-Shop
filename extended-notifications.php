<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>

<head>

    <title>Notifications | Minia - Admin & Dashboard Template</title>
    <?php include 'layouts/head.php'; ?>

    <!-- alertifyjs Css -->
    <link href="assets/libs/alertifyjs/build/css/alertify.min.css" rel="stylesheet" type="text/css" />

    <!-- alertifyjs default themes  Css -->
    <link href="assets/libs/alertifyjs/build/css/themes/default.min.css" rel="stylesheet" type="text/css" />

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
                            <h4 class="mb-sm-0 font-size-18">Notifications</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Extended</a></li>
                                    <li class="breadcrumb-item active">Notifications</li>
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
                                <h4 class="card-title">AlertifyJs</h4>
                                <p class="card-title-desc">Notifications examples of using AlertifyJS.</p>
                            </div>
                            <div class="card-body">

                                <h4 class="card-title mb-4">Default Dialogs</h4>

                                <div class="row text-center">
                                    <div class="col-sm-4">
                                        <div class="my-4">
                                            <h5 class="mb-4">Alert</h5>

                                            <a href="javascript: void(0);"  id="alert" class="btn btn-primary waves-effect waves-light">Click me</a>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="my-4">
                                            <h5 class="mb-4">Confirm</h5>

                                            <a href="javascript: void(0);"  id="alert-confirm" class="btn btn-primary waves-effect waves-light">Click me</a>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="my-4">
                                            <h5 class="mb-4">Prompt</h5>

                                            <a href="javascript: void(0);"  id="alert-prompt" class="btn btn-primary waves-effect waves-light">Click me</a>
                                        </div>
                                    </div>
                                </div>


                                <h4 class="card-title mt-5 mb-3">Default Notifications</h4>

                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        
                                        <tbody>
                                            <tr>
                                                <td>Default alert</td>
                                                <td><a href="javascript: void(0);"  id="alert-message" class="btn btn-primary btn-sm waves-effect waves-light">Click me</a></td>
                                            </tr>
                                            <tr>
                                                <td>Success alert</td>
                                                <td><a href="javascript: void(0);"  id="alert-success" class="btn btn-primary btn-sm waves-effect waves-light">Click me</a></td>
                                            </tr>
                                            <tr>
                                                <td>Error alert</td>
                                                <td><a href="javascript: void(0);"  id="alert-error" class="btn btn-primary btn-sm waves-effect waves-light">Click me</a></td>
                                            </tr>
                                            <tr>
                                                <td>Warning alert</td>
                                                <td><a href="javascript: void(0);"  id="alert-warning" class="btn btn-primary btn-sm waves-effect waves-light">Click me</a></td>
                                            </tr>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- end card body -->
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

<script src="assets/libs/alertifyjs/build/alertify.min.js"></script>
<script src="assets/js/pages/notification.init.js"></script>

<script src="assets/js/app.js"></script>
</body>

</html>
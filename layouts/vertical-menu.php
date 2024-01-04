<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="index.php" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="assets/images/logo-sm.svg" alt="" height="24">
                    </span>
                    <span class="logo-lg">
                        <img src="assets/images/logo-sm.svg" alt="" height="24"> <span class="logo-txt">Eclat</span>
                    </span>
                </a>

                <a href="index.php" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="assets/images/logo-sm.svg" alt="" height="24">
                    </span>
                    <span class="logo-lg">
                        <img src="assets/images/logo-sm.svg" alt="" height="24"> <span class="logo-txt">Eclat</span>
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 header-item" id="vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>

            
        </div>

        <div class="d-flex">

            <div class="dropdown d-inline-block d-lg-none ms-2">
                <button type="button" class="btn header-item" id="page-header-search-dropdown"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i data-feather="search" class="icon-lg"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-search-dropdown">
        
                    <form class="p-3">
                        <div class="form-group m-0">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="<?php echo $language["Search"]; ?>" aria-label="Search Result">

                                <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item right-bar-toggle me-2">
                    <i data-feather="settings" class="icon-lg"></i>
                </button>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item bg-light-subtle border-start border-end" id="page-header-user-dropdown"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user" src="assets/images/users/avatar-1.jpg"
                        alt="Header Avatar">
                    <span class="d-none d-xl-inline-block ms-1 fw-medium"><?php echo $language["Shawn_L"]; ?>.</span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <a class="dropdown-item" href="apps-contacts-profile.php"><i class="mdi mdi mdi-face-man font-size-16 align-middle me-1"></i> <?php echo $language["Profile"]; ?></a>
                    <a class="dropdown-item" href="auth-lock-screen.php"><i class="mdi mdi-lock font-size-16 align-middle me-1"></i> <?php echo $language["Lock_screen"]; ?> </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="logout.php"><i class="mdi mdi-logout font-size-16 align-middle me-1"></i> <?php echo $language["Logout"]; ?></a>
                </div>
            </div>

        </div>
    </div>
</header>

<!-- ========== Left Sidebar Start ========== -->
<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" data-key="t-menu"><?php echo $language["Menu"]; ?></li>

                <li>
                    <a href="index.php">
                        <i data-feather="home"></i>
                        <span data-key="t-dashboard"><?php echo $language["Dashboard"]; ?></span>
                    </a>
                </li>
                
                <!-- <li>
                    <a href="barang.php">
                        <i data-feather="barang"></i>
                        <span data-key="t-dashboard">Barang</span>
                    </a>
                </li> -->

                <li>
                    <a href="transaksi.php">
                        <i data-feather="barang"></i>
                        <span data-key="t-dashboard">Transaksi</span>
                    </a>
                </li>

                <li>
                    <a href="analisa.php">
                        <i data-feather="barang"></i>
                        <span data-key="t-dashboard">Analisa</span>
                    </a>
                </li>

                <!-- <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="grid"></i>
                        <span data-key="t-apps"><?php echo $language["Apps"]; ?></span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li>
                            <a href="apps-calendar.php">
                                <span data-key="t-calendar"><?php echo $language["Calendar"]; ?></span>
                            </a>
                        </li>
        
                        <li>
                            <a href="apps-chat.php">
                                <span data-key="t-chat"><?php echo $language["Chat"]; ?></span>
                            </a>
                        </li>
        
                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <span data-key="t-email"><?php echo $language["Email"]; ?></span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="apps-email-inbox.php" data-key="t-inbox"><?php echo $language["Inbox"]; ?></a></li>
                                <li><a href="apps-email-read.php" data-key="t-read-email"><?php echo $language["Read_Email"]; ?></a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <span data-key="t-invoices"><?php echo $language["Invoices"]; ?></span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="apps-invoices-list.php" data-key="t-invoice-list"><?php echo $language["Invoice_List"]; ?></a></li>
                                <li><a href="apps-invoices-detail.php" data-key="t-invoice-detail"><?php echo $language["Invoice_Detail"]; ?></a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <span data-key="t-contacts"><?php echo $language["Contacts"]; ?></span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="apps-contacts-grid.php" data-key="t-user-grid"><?php echo $language["User_Grid"]; ?></a></li>
                                <li><a href="apps-contacts-list.php" data-key="t-user-list"><?php echo $language["User_List"]; ?></a></li>
                                <li><a href="apps-contacts-profile.php" data-key="t-profile"><?php echo $language["Profile"]; ?></a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript: void(0);" class="">
                                <span data-key="t-blog"><?php echo $language["Blog"]; ?></span>
                                <span class="badge rounded-pill badge-soft-danger float-end" key="t-new"><?php echo $language["New"]; ?></span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="apps-blog-grid.php" data-key="t-blog-grid"><?php echo $language["Blog_Grid"]; ?></a></li>
                                <li><a href="apps-blog-list.php" data-key="t-blog-list"><?php echo $language["Blog_List"]; ?></a></li>
                                <li><a href="apps-blog-detail.php" data-key="t-blog-details"><?php echo $language["Blog_Details"]; ?></a></li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="users"></i>
                        <span data-key="t-authentication"><?php echo $language["Authentication"]; ?></span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="pages-login.php" data-key="t-login"><?php echo $language["Login"]; ?></a></li>
                        <li><a href="pages-register.php" data-key="t-register"><?php echo $language["Register"]; ?></a></li>
                        <li><a href="pages-recoverpw.php" data-key="t-recover-password"><?php echo $language["Recover_Password"]; ?></a></li>
                        <li><a href="auth-lock-screen.php" data-key="t-lock-screen"><?php echo $language["Lock_Screen"]; ?></a></li>
                        <li><a href="auth-confirm-mail.php" data-key="t-confirm-mail"><?php echo $language["Confirm_Mail"]; ?></a></li>
                        <li><a href="auth-email-verification.php" data-key="t-email-verification"><?php echo $language["Email_Verification"]; ?></a></li>
                        <li><a href="auth-two-step-verification.php" data-key="t-two-step-verification"><?php echo $language["Two_Step_Verification"]; ?></a></li>
                    </ul>
                </li> -->

                <!-- <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="file-text"></i>
                        <span data-key="t-pages"><?php echo $language["Pages"]; ?></span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="pages-starter.php" data-key="t-starter-page"><?php echo $language["Starter_Page"]; ?> </a></li>
                        <li><a href="pages-maintenance.php" data-key="t-maintenance"><?php echo $language["Maintenance"]; ?></a></li>
                        <li><a href="pages-comingsoon.php" data-key="t-coming-soon"><?php echo $language["Coming_Soon"]; ?></a></li>
                        <li><a href="pages-timeline.php" data-key="t-timeline"><?php echo $language["Timeline"]; ?></a></li>
                        <li><a href="pages-faqs.php" data-key="t-faqs"><?php echo $language["FAQs"]; ?></a></li>
                        <li><a href="pages-pricing.php" data-key="t-pricing"><?php echo $language["Pricing"]; ?></a></li>
                        <li><a href="pages-404.php" data-key="t-error-404"><?php echo $language["Error_404"]; ?></a></li>
                        <li><a href="pages-500.php" data-key="t-error-500"><?php echo $language["Error_500"]; ?></a></li>
                    </ul>
                </li> -->

               

                <!-- <li class="menu-title mt-2" data-key="t-components"><?php echo $language["Elements"]; ?></li> -->

                <!-- <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="briefcase"></i>
                        <span data-key="t-components"><?php echo $language["Components"]; ?></span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="ui-alerts.php" data-key="t-alerts"><?php echo $language["Alerts"]; ?></a></li>
                        <li><a href="ui-buttons.php" data-key="t-buttons"><?php echo $language["Buttons"]; ?></a></li>
                        <li><a href="ui-cards.php" data-key="t-cards"><?php echo $language["Cards"]; ?></a></li>
                        <li><a href="ui-carousel.php" data-key="t-carousel"><?php echo $language["Carousel"]; ?></a></li>
                        <li><a href="ui-dropdowns.php" data-key="t-dropdowns"><?php echo $language["Dropdowns"]; ?></a></li>
                        <li><a href="ui-grid.php" data-key="t-grid"><?php echo $language["Grid"]; ?></a></li>
                        <li><a href="ui-images.php" data-key="t-images"><?php echo $language["Images"]; ?></a></li>
                        <li><a href="ui-modals.php" data-key="t-modals"><?php echo $language["Modals"]; ?></a></li>
                        <li><a href="ui-offcanvas.php" data-key="t-offcanvas"><?php echo $language["Offcanvas"]; ?></a></li>
                        <li><a href="ui-progressbars.php" data-key="t-progress-bars"><?php echo $language["Progress_Bars"]; ?></a></li>
                        <li><a href="ui-tabs-accordions.php" data-key="t-tabs-accordions"><?php echo $language["Tabs_n_Accordions"]; ?></a></li>
                        <li><a href="ui-typography.php" data-key="t-typography"><?php echo $language["Typography"]; ?></a></li>
                        <li><a href="ui-video.php" data-key="t-video"><?php echo $language["Video"]; ?></a></li>
                        <li><a href="ui-general.php" data-key="t-general"><?php echo $language["General"]; ?></a></li>
                        <li><a href="ui-colors.php" data-key="t-colors"><?php echo $language["Colors"]; ?></a></li>
                        <li><a href="ui-utilities.php" data-key="t-utilities"><?php echo $language["Utilities"]; ?></a></li>
                    </ul>
                </li> -->

                <!-- <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="gift"></i>
                        <span data-key="t-ui-elements"><?php echo $language["Extended"]; ?></span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="extended-lightbox.php" data-key="t-lightbox"><?php echo $language["Lightbox"]; ?></a></li>
                        <li><a href="extended-rangeslider.php" data-key="t-range-slider"><?php echo $language["Range_Slider"]; ?></a></li>
                        <li><a href="extended-sweet-alert.php" data-key="t-sweet-alert"><?php echo $language["SweetAlert_2"]; ?></a></li>
                        <li><a href="extended-session-timeout.php" data-key="t-session-timeout"><?php echo $language["Session_Timeout"]; ?></a></li>
                        <li><a href="extended-rating.php" data-key="t-rating"><?php echo $language["Rating"]; ?></a></li>
                        <li><a href="extended-notifications.php" data-key="t-notifications"><?php echo $language["Notifications"]; ?></a></li>
                    </ul>
                </li> -->

                <!-- <li>
                    <a href="javascript: void(0);">
                        <i data-feather="box"></i>
                        <span class="badge rounded-pill badge-soft-danger  text-danger float-end">7</span>
                        <span data-key="t-forms"><?php echo $language["Forms"]; ?></span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="form-elements.php" data-key="t-form-elements"><?php echo $language["Basic_Elements"]; ?></a></li>
                        <li><a href="form-validation.php" data-key="t-form-validation"><?php echo $language["Validation"]; ?></a></li>
                        <li><a href="form-advanced.php" data-key="t-form-advanced"><?php echo $language["Advanced_Plugins"]; ?></a></li>
                        <li><a href="form-editors.php" data-key="t-form-editors"><?php echo $language["Editors"]; ?></a></li>
                        <li><a href="form-uploads.php" data-key="t-form-upload"><?php echo $language["File_Upload"]; ?></a></li>
                        <li><a href="form-wizard.php" data-key="t-form-wizard"><?php echo $language["Wizard"]; ?></a></li>
                        <li><a href="form-mask.php" data-key="t-form-mask"><?php echo $language["Mask"]; ?></a></li>
                    </ul>
                </li> -->

                <!-- <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="sliders"></i>
                        <span data-key="t-tables"><?php echo $language["Tables"]; ?></span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="tables-basic.php" data-key="t-basic-tables"><?php echo $language["Bootstrap_Basic"]; ?></a></li>
                        <li><a href="tables-datatable.php" data-key="t-data-tables"><?php echo $language["DataTables"]; ?></a></li>
                        <li><a href="tables-responsive.php" data-key="t-responsive-table"><?php echo $language["Responsive"]; ?></a></li>
                        <li><a href="tables-editable.php" data-key="t-editable-table"><?php echo $language["Editable"]; ?></a></li>
                    </ul>
                </li> -->

                <!-- <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="pie-chart"></i>
                        <span data-key="t-charts"><?php echo $language["Charts"]; ?></span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="charts-apex.php" data-key="t-apex-charts"><?php echo $language["Apexcharts"]; ?></a></li>
                        <li><a href="charts-echart.php" data-key="t-e-charts"><?php echo $language["Echarts"]; ?></a></li>
                        <li><a href="charts-chartjs.php" data-key="t-chartjs-charts"><?php echo $language["Chartjs"]; ?></a></li>
                        <li><a href="charts-knob.php" data-key="t-knob-charts"><?php echo $language["Jquery_Knob"]; ?></a></li>
                        <li><a href="charts-sparkline.php" data-key="t-sparkline-charts"><?php echo $language["Sparkline"]; ?></a></li>
                    </ul>
                </li> -->

                <!-- <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="cpu"></i>
                        <span data-key="t-icons"><?php echo $language["Icons"]; ?></span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="icons-boxicons.php" data-key="t-boxicons"><?php echo $language["Boxicons"]; ?></a></li>
                        <li><a href="icons-materialdesign.php" data-key="t-material-design"><?php echo $language["Material_Design"]; ?></a></li>
                        <li><a href="icons-dripicons.php" data-key="t-dripicons"><?php echo $language["Dripicons"]; ?></a></li>
                        <li><a href="icons-fontawesome.php" data-key="t-font-awesome"><?php echo $language["Font_Awesome_5"]; ?></a></li>
                    </ul>
                </li> -->

                <!-- <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="map"></i>
                        <span data-key="t-maps"><?php echo $language["Maps"]; ?></span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="maps-google.php" data-key="t-g-maps"><?php echo $language["Google"]; ?></a></li>
                        <li><a href="maps-vector.php" data-key="t-v-maps"><?php echo $language["Vector"]; ?></a></li>
                        <li><a href="maps-leaflet.php" data-key="t-l-maps"><?php echo $language["Leaflet"]; ?></a></li>
                    </ul>
                </li> -->

                <!-- <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="share-2"></i>
                        <span data-key="t-multi-level"><?php echo $language["Multi_Level"]; ?></span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li><a href="javascript: void(0);" data-key="t-level-1-1"><?php echo $language["Level_1_1"]; ?></a></li>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow" data-key="t-level-1-2"><?php echo $language["Level_1_2"]; ?></a>
                            <ul class="sub-menu" aria-expanded="true">
                                <li><a href="javascript: void(0);" data-key="t-level-2-1"><?php echo $language["Level_2_1"]; ?></a></li>
                                <li><a href="javascript: void(0);" data-key="t-level-2-2"><?php echo $language["Level_2_2"]; ?></a></li>
                            </ul>
                        </li>
                    </ul>
                </li> -->

            </ul>

            <!-- <div class="card sidebar-alert border-0 text-center mx-4 mb-0 mt-5">
                <div class="card-body">
                    <img src="assets/images/giftbox.png" alt="">
                    <div class="mt-4">
                        <h5 class="alertcard-title font-size-16"><?php echo $language["Unlimited_Access"]; ?></h5>
                        <p class="font-size-13"><?php echo $language["Upgrade_your_plan_from_a_Free_trial,_to_select_‘Business_Plan’"]; ?>.</p>
                        <a href="#!" class="btn btn-primary mt-2"><?php echo $language["Upgrade_Now"]; ?></a>
                    </div>
                </div>
            </div> -->
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
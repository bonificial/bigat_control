<?php include './common/session_check.php'; ?>
<?php

include 'config/constants.php';
!isset($_SESSION) ? session_start() : null;
$user = $_SESSION;
?>
<!doctype html>
<html lang="en">

<head>
    <title>Dashboard</title>
    <?php include './common/header.php'; ?>
    <link href="assets/styles/main.css" rel="stylesheet">
</head>
<body>
<?php include './common/loader.php' ?>

<div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
    <?php include './common/topnav.php'; ?>

    <div class="app-main">
        <?php include './common/sidebar.php'; ?>
        <div class="app-main__outer">
            <div class="app-main__inner">
                <!-- App Page Title Starts Here -->
                <div class="app-page-title">
                    <div class="page-title-wrapper">
                        <div class="page-title-heading">
                            <div class="page-title-icon">
                                <i class="fa fa-chart-bar text-success">
                                </i>
                            </div>
                            <div>Dashboard Analytics
                                <div class="page-title-subheading">Brief breakdown of BIGAT stats
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- App Page Title Ends Here -->

                <!-- App page Main Content Starts Here -->
                <div class="content bg-royal-purple p-3">
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <div class="mb-3 card">
                                <div class="card-header-tab card-header-tab-animation card-header">
                                    <div class="card-header-title">
                                        <i class="header-icon lnr-apartment icon-gradient bg-love-kiss"> </i>
                                        Usage Report
                                    </div>

                                </div>

                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-4 col-xl-4">
                            <div class="card mb-3 widget-content bg-royal-purple-darker">
                                <div class="widget-content-wrapper text-white">
                                    <div class="widget-content-left">
                                        <div class="widget-heading">Total - Members</div>
                                        <div class="widget-subheading">Registered until today</div>
                                    </div>
                                    <div class="widget-content-right">
                                        <div class="widget-numbers text-white"><span id="members_count">_</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-xl-4">
                            <div class="card mb-3 widget-content bg-royal-purple-darker">
                                <div class="widget-content-wrapper text-white">
                                    <div class="widget-content-left">
                                        <div class="widget-heading">Total - Events</div>
                                        <div class="widget-subheading">Past + Future Events</div>
                                    </div>
                                    <div class="widget-content-right">
                                        <div class="widget-numbers text-white"><span id="events_count">_</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-xl-4">
                            <div class="card mb-3 widget-content bg-royal-purple-darker">
                                <div class="widget-content-wrapper text-white">
                                    <div class="widget-content-left">
                                        <div class="widget-heading">Total - Posts</div>
                                        <div class="widget-subheading">Video + Text + Image Posts</div>
                                    </div>
                                    <div class="widget-content-right">
                                        <div class="widget-numbers text-white"><span id="posts_count">_</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>


                    <div class="row">
                        <div class="col-md-6 col-xl-6">
                            <div class="card mb-3 widget-content bg-royal-purple-darker">
                                <div class="widget-content-wrapper text-white">
                                    <div class="widget-content-left">
                                        <div class="widget-heading">Leading County - Members</div>
                                        <div class="widget-subheading">County with the most registered members</div>
                                    </div>
                                    <div class="widget-content-right">
                                        <div class="widget-numbers text-white"><span id="members_count_max">_</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-xl-6">
                            <div class="card mb-3 widget-content bg-royal-purple-darker">
                                <div class="widget-content-wrapper text-white">
                                    <div class="widget-content-left">
                                        <div class="widget-heading">Events</div>
                                        <div class="widget-subheading">Closest upcoming Event</div>
                                    </div>
                                    <div class="widget-content-right" style="width: 40%">
                                        <div class="  text-white"><span id="upcoming_event">_</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- App page Main Content Ends Here -->

            </div>

        </div>
    </div>
</div>
<?php
include './common/footer.php';
?>
<script src="<?php asset(SCRIPTS);?>/dashboard.js"></script>
</body>
</html>

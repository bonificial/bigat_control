<?php

include 'config/constants.php';
!isset($_SESSION) ? session_start() : null;
$user = $_SESSION;
?>
<!doctype html>
<html lang="en">

<head>
    <title>New Event</title>
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
                                <i class="text-success fa fa-pencil-alt">
                                </i>
                            </div>
                            <div>Add New Event
                                <div class="page-title-subheading">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- App Page Title Ends Here -->

                <!-- App page Main Content Starts Here -->
                <div class="content bg-royal-purple p-3">
                    <div class="main-card mb-3 card">
                        <div class="card-body"><h5 class="card-title">CONTENT DETAILS</h5>
                      <?php include "./common/event_form.php" ;?>
                            <div class="d-flex mt-5 flex-row justify-content-center bg-heavy-rain"  >
                                <div class="p-2">
                                    <button type="button" id="add_event" class="btn btn-success"><b class="fa fa-save"></b> Save Changes</button>
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
<script src="<?php asset(SCRIPTS);?>/events.js"></script>

</body>
</html>

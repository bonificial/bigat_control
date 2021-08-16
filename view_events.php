<?php include './common/session_check.php'; ?>
<?php

include 'config/constants.php';
!isset($_SESSION) ? session_start() : null;
$user = $_SESSION;
?>
<!doctype html>
<html lang="en">

<head>
    <title>Posted Events</title>
    <?php include './common/header.php'; ?>
    <link href="assets/styles/main.css" rel="stylesheet">
</head>
<body>
<?php include './common/loader.php'?>
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
                                <i class="pe-7s-graph text-success">
                                </i>
                            </div>
                            <div>View All Posted Events as of <?php echo Date('Y M d D, H:i:s A') ?>

                            </div>
                        </div>

                    </div>
                </div>
                <!-- App Page Title Ends Here -->

                <!-- App page Main Content Starts Here -->
                <div class="content bg-royal-purple p-3">
                <div class="main-card mb-3 card">
                    <div class="card-body"><h5 class="card-title">List of Events</h5>
                        <table id="tbl_posts" class="table table-sm table-striped table-hover table_layout_fixed">
                            <thead>
                            <tr>
                                <th class="text-right">#</th>
                                <th class="text-left">Title</th>
                                <th class="text-left">Date /Time</th>
                                <th class="text-left">County / Location</th>
                                <th class="text-left">Users Notified</th>

                                <th class="text-left">Actions</th>
                            </tr>
                            </thead>
                            <tbody id="">
                          
                            </tbody>
                        </table>
                    </div>
                </div>
                </div>
                <!-- App page Main Content Ends Here -->

            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="openEventModal" tabindex="-1" role="dialog" aria-labelledby="openEventModal"
     aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="postModal"></h5>
                <button id="closeModal" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <?php include "./common/event_form.php" ;?>

                <div class="d-flex mt-5 flex-row justify-content-center bg-heavy-rain"  >
                    <div class="p-2">
                        <button type="button" id="submit_updates" class="btn btn-success"><b class="fa fa-save"></b> Save Changes</button>
                    </div>
                </div>
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

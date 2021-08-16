<?php include './common/session_check.php'; ?>
<?php

include 'config/constants.php';
!isset($_SESSION) ? session_start() : null;
$user = $_SESSION;
?>
<!doctype html>
<html lang="en">

<head>
    <title>Posted Content</title>
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
                            <div>View All Posted Content as of <?php echo Date('Y M d D, H:i:s A') ?>
                                <div class="page-title-subheading">A list of all posted video, image, and textual content posted to Bigat.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- App Page Title Ends Here -->

                <!-- App page Main Content Starts Here -->
                <div class="content bg-royal-purple p-3">
                <div class="main-card mb-3 card">
                    <div class="card-body"><h5 class="card-title">List of Posts</h5>
                        <table id="tbl_posts" class="table table-sm table-striped table-hover table_layout_fixed">
                            <thead>
                            <tr>
                                <th class="text-right">#</th>
                                <th class="text-left">Title / Subtitle</th>
                                <th class="text-left">Category</th>
                                <th class="text-left">Type / Status</th>
                                <th class="text-left">Created at</th>
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
<div class="modal fade" id="openPostModal" tabindex="-1" role="dialog" aria-labelledby="openPostModal"
     aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="postModal"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

<?php include './common/post_form.php' ?>

                <div class="d-flex mt-5 flex-row justify-content-center bg-heavy-rain"  >
                    <div class="p-2">
                        <button type="button" id="submit_updates" class="btn btn-success"><b class="fa fa-save"></b> Save Changes</button>
                    </div>
                    <div class="p-2">
                        <button type="button" id="pin_post" class="btn btn-primary"><b class="fa fa-map-pin"></b> Pin Post</button>
                    </div>
                    <div class="p-2">
                        <button type="button" id="delete_post" class="btn btn-outline-danger"><b class="fa fa-trash"></b> Delete Post</button>
                    </div>
                    <div class="p-2 align-self-end">
                        <button data-dismiss="modal" type="button" class="btn btn-outline-secondary "><b class="fa fa-times"></b> Close</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php
include './common/footer.php';

?>
<script src="<?php asset(SCRIPTS);?>/viewposts.js"></script>
</body>
</html>

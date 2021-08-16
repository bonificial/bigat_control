<?php

include 'config/constants.php';
!isset($_SESSION) ? session_start() : null;
$user = $_SESSION;


?>
<!doctype html>
<html lang="en">

<head>
    <title>BIGAT Users</title>

    <style type="text/css">
        /* Set the size of the div element that contains the map */
        #map {
            height: 400px;
            /* The height is 400 pixels */
            width: 100%;
            /* The width is the width of the web page */
        }
    </style>
    <script>
        // Initialize and add the map

    </script>
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
                                <i class="pe-7s-graph text-success">
                                </i>
                            </div>
                            <div>View All BIGAT Admin Console Users as of <?php echo Date('Y M d D, H:i:s A') ?>

                            </div>
                        </div>

                    </div>
                </div>
                <!-- App Page Title Ends Here -->

                <!-- App page Main Content Starts Here -->
                <div class="content bg-royal-purple p-3">
                    <div class="bg-white" style="padding:20px ">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item tab-switcher">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#view" role="tab" aria-controls="home" aria-selected="true">View/Edit Users</a>
                            </li>
                            <li class="nav-item tab-switcher">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#add" role="tab" aria-controls="profile" aria-selected="false">Add New User</a>
                            </li>

                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="view" role="tabpanel" aria-labelledby="home-tab">
                                <div class="row">
                                    <div class="col-md-2">
                                        <button class="btn btn-link refreshRooms"><i class="fa fa-history"></i>  Refresh </button>
                                    </div>
                                </div>
                                <table id="tbl_users" class="table  table-responsive-lg table-striped table-hover  ">
                                    <thead>
                                    <tr>

                                        <th class=""> Name</th>
                                        <th class=""> Email</th>
                                        <th class=""> Status</th>
                                     <th class=""> Access Level</th>
                                        <th class=""> Action</th>

                                    </tr>
                                    </thead>
                                    <tbody id="">

                                    </tbody>
                                </table>

                            </div>
                            <div class="tab-pane fade" id="add" role="tabpanel" aria-labelledby="profile-tab">
                                <?php include "./common/manage_users_form.php" ;?>
                                <div class="d-flex mt-5 flex-row justify-content-center bg-heavy-rain"  >
                                    <div class="p-2">
                                        <button type="button" id="addUser" class="btn btn-success"><b class="fa fa-save"></b>  Add User</button>
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
<div class="modal fade " id="manageUser" tabindex="-1" role="dialog" aria-labelledby="ManageuserModal"
     aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">View Member</h5>
                <h6 id="status"></h6>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php include "./common/manage_users_form_edit.php" ;?>


                <div class="d-flex mt-5 flex-row justify-content-center bg-heavy-rain">
                    <div class="p-2">

                        <button   type="button" id="editUser" class="btn btn-outline-success"><b
                                    class="fa fa-check"></b> Update
                        </button>
                        <button   type="button" id="deleteUser" class="btn btn-outline-danger"><b
                                    class="fa fa-trash"></b> Delete
                        </button>
                    </div>

                    <div class="p-2 align-self-end">
                        <button id="close-modal" data-dismiss="modal" type="button" class="btn btn-outline-secondary "><b
                                    class="fa fa-times"></b> Close
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?php
include './common/footer.php';
?>
<script src="<?php asset(SCRIPTS); ?>/users.js"></script>

<script>

</body>
</html>


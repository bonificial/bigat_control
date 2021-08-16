<?php

include 'config/constants.php';
!isset($_SESSION) ? session_start() : null;
$user = $_SESSION;
?>
<!doctype html>
<html lang="en">

<head>
    <title>BIGAT ROOMS</title>
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
               <div class="bg-white" style="padding:20px ">
                   <ul class="nav nav-tabs" id="myTab" role="tablist">
                       <li class="nav-item">
                           <a class="nav-link active" id="home-tab" data-toggle="tab" href="#view" role="tab" aria-controls="home" aria-selected="true">View/Edit Rooms</a>
                       </li>
                       <li class="nav-item">
                           <a class="nav-link" id="profile-tab" data-toggle="tab" href="#add" role="tab" aria-controls="profile" aria-selected="false">Add New Room</a>
                       </li>

                   </ul>
                   <div class="tab-content" id="myTabContent">
                       <div class="tab-pane fade show active" id="view" role="tabpanel" aria-labelledby="home-tab">
                           <div class="row">
                               <div class="col-md-2">
                                   <button class="btn btn-link refreshRooms"><i class="fa fa-history"></i>  Refresh </button>
                               </div>
                           </div>
                           <table id="tbl_posts" class="table table_layout_fixed table-responsive-lg table-striped table-hover  ">
                               <thead>
                               <tr>

                                   <th class="">Room Name</th>
                                   <th class="">Member Count</th>
                                   <th class=""> Status</th>
                                   <th class=""> Created at</th>
                                   <th class=""> Action</th>

                               </tr>
                               </thead>
                               <tbody id="">

                               </tbody>
                           </table>

                       </div>
                       <div class="tab-pane fade" id="add" role="tabpanel" aria-labelledby="profile-tab">
                           <?php include "./common/room_manage_form.php" ;?>
                           <div class="d-flex mt-5 flex-row justify-content-center bg-heavy-rain"  >
                               <div class="p-2">
                                   <button type="button" id="addRoom" class="btn btn-success"><b class="fa fa-save"></b>  Add Room</button>
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
<div class="modal fade" id="openRoomModal" tabindex="-1" role="dialog" aria-labelledby="openRoomModal"
     aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="roomModal"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form class="">
                    <div class="position-relative row form-group">
                        <label for="room_title" class="col-sm-2 col-form-label">Room Name</label>
                        <div class="col-sm-8">
                            <input name="edit_room_key" hidden id="edit_room_key" type="text"
                                   class="form-control">
                            <input name="edit_room_name" id="edit_room_name" placeholder="Enter the Room Name"
                                   type="text"
                                   class="form-control"/>
                        </div>

                    </div>



                    <div class="position-relative row form-group">
                        <label for="room_description" class="col-sm-2 col-form-label">Room Description</label>
                        <div class="col-sm-10">
                            <textarea name="edit_room_description" id="edit_room_description" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="position-relative row form-group " id="fi_area" >
                        <label for="set_fi" class="col-sm-2 col-form-label">Set Featured Image</label>

                        <div class="col-sm-10">
                            <a href="" target="_blank" id="edit_featured_image_show_link"><img src="" id="edit_featured_image_show" style="height: 75px"/> Click to View Full Image</a>
                        </div>
                    </div>


                    <div class="position-relative row form-group">
                        <label for="exampleFile" class="col-sm-2 col-form-label">Featured Image</label>
                        <div class="col-sm-10">
                            <input   id="edit_featured_image" type="file"
                                   class="form-control-file">
                            <small class="form-text text-muted">Insert a Featured Image Here.</small>
                        </div>
                    </div>



                </form>

                <div class="d-flex mt-5 flex-row justify-content-center bg-heavy-rain"  >
                    <div class="p-2">
                        <button type="button" id="submit_updates" class="btn btn-success"><b class="fa fa-save"></b> Save Changes</button>
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

<script src="<?php asset(SCRIPTS);?>/rooms.js"></script>

</body>
</html>

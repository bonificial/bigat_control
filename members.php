<?php

include 'config/constants.php';
!isset($_SESSION) ? session_start() : null;
$user = $_SESSION;
?>
<!doctype html>
<html lang="en">

<head>
    <title>Members / Users.</title>

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
                            <div>View Registered Members as of <?php echo Date('Y M d D, H:i:s A') ?>
                                <div class="page-title-subheading">A list of all members registered as at now.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- App Page Title Ends Here -->

                <!-- App page Main Content Starts Here -->
                <div class="content bg-royal-purple p-3">
                    <div class="main-card mb-3 card">

                        <div class="card-body"><h5 class="card-title">List of Users</h5>
                            <table id="members_list"
                                   class="align-middle mb-0 table table-borderless table-striped table-hover">
                                <thead>
                                <tr>
                                    <th class="text-left">#</th>

                                    <th>Name</th>
                                    <th class="text-left">Registered County</th>
                                    <th class="text-left">Device Notifications</th>

                                    <th class="text-left">Actions</th>
                                </tr>
                                </thead>
                                <tbody>

                                <tr>
                                    <td class="text-center text-muted">#345</td>
                                    <td>
                                        <div class="widget-content p-0">
                                            <div class="widget-content-wrapper">
                                                <div class="widget-content-left mr-3">
                                                    <div class="widget-content-left">
                                                        <img alt="" class="rounded-circle"
                                                             src="assets/images/avatars/4.jpg" width="40">
                                                    </div>
                                                </div>
                                                <div class="widget-content-left flex2">
                                                    <div class="widget-heading">John Doe</div>
                                                    <div class="widget-subheading opacity-7">Web Developer</div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="text-center">Madrid</td>
                                    <td class="text-center">Thurday, 24th Sep. 9.15 PM</td>
                                    <td class="text-center">
                                        <div class="badge badge-warning">Pending</div>
                                    </td>
                                    <td class="text-center">
                                        <button data-toggle="tooltip" title="View Profile"
                                                data-placement="bottom" class="btn btn-link"><b
                                                    class="fa fa-eye"></b></button>
                                        <button data-toggle="tooltip" title="Reach Out" data-placement="bottom"
                                                class="btn btn-link"><b class="fa fa-paper-plane"></b></button>
                                        <button data-toggle="tooltip" title="Send Email" data-placement="bottom"
                                                class="btn btn-link"><b class="fa fa-envelope"></b></button>
                                        <button data-toggle="tooltip" title="Disable Account" data-placement="bottom"
                                                class="btn btn-link"><b class="fa fa-ban"></b></button>

                                    </td>
                                </tr>
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
<div class="modal fade" id="viewMemberModal" tabindex="-1" role="dialog" aria-labelledby="openPostModal"
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

                <div class="container register">
                    <div class="row">

                        <div class="col-md-12 register-right">

                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="home" role="tabpanel"
                                     aria-labelledby="home-tab">
                                    <h3 class="register-heading" id="profileDesc"></h3>
                                    <div class="row register-form member-info-form">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="mName" class=" col-form-label">User ID</label>
                                                <input type="text" disabled class="form-control" id="userID" value=""/>
                                            </div>

                                            <div class="form-group">
                                                <label for="mName" class="col-sm-2 col-form-label">Name</label>
                                                <input type="text" class="form-control" id="mName" value=""/>
                                            </div>
                                            <div class="form-group">
                                                <label for="mEmail" class="col-sm-2 col-form-label">Email</label>
                                                <input type="email" class="form-control" id="mEmail" value=""/>
                                            </div>
                                            <div class="form-group">
                                                <label for="mDOB" class="col-sm-2 col-form-label">DOB</label>
                                                <input type="text" class="form-control" disabled id="mDOB" value=""/>
                                            </div>
                                            <div class="form-group">
                                                <label for="County" class="col-sm-2 col-form-label">County</label>
                                                <select class="form-control" id="mCounty">
                                                    <?php require './common/counties.php' ?>>
                                                </select>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="lastLoc" class=" col-form-label">Last Approx. Location

                                                </label>
                                                <button   type="button" id="view_location" class=" btn  btn-link"><b
                                                            class="fa fa-map-marker"></b> View on Map
                                                </button>
                                                 <input type="text" class=" form-control" disabled id="lastLoc" value=""/>

                                            </div>

                                            <div class="form-group">
                                                <label for="" class=" col-form-label">User Status</label>
                                                <div class="" id="notifs">
                                                </div>
                                            </div>

                                            <div class="notifs_send">
                                                <div class="form-group">
                                                    <label for="Token" class=" col-form-label">Device Token
                                                        </label>
                                                    <input disabled="true" type="text" class="form-control" name="Token" id="deviceToken"   value=""/>
                                                </div>
                                                <div class="form-group">
                                                    <label for="nTitle" class=" col-form-label">Notification
                                                        Title</label>
                                                    <input type="text" class="form-control" name="nTitle" id="nTitle" placeholder="Enter Notification Title" value=""/>
                                                </div>
                                                <div class="form-group">
                                                    <label for="nBody" class=" col-form-label">Notification Body</label>
                                                    <textarea class="form-control" name="nBody" placeholder="Enter the Notification Content" id="nBody"></textarea>
                                                </div>
                                                <input type="button" class="btnRegister" value="Send Notification"
                                                       id="sendNotif"/>
                                            </div>


                                        </div>
                                    </div>
                                    <div class="row">

                                        <div id="map_modal" class="modal-custom">

                                            <!-- Modal content -->
                                            <div class="modal-content-custom">
                                                <span  id="loctitle"> </span>
                                                <span id="close_map">Close Map</span>
                                                <div id="map"></div>
                                            </div>

                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="d-flex mt-5 flex-row justify-content-center bg-heavy-rain">
                    <div class="p-2">
                        <button   type="button" id="disable_user" class="btn btn-outline-danger"><b
                                    class="fa fa-ban"></b> Disable
                        </button>
                        <button   type="button" id="enable_user" class="btn btn-outline-success"><b
                                    class="fa fa-check"></b> Enable
                        </button>
                    </div>

                    <div class="p-2 align-self-end">
                        <button data-dismiss="modal" type="button" class="btn btn-outline-secondary "><b
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
<script src="<?php asset(SCRIPTS); ?>/members.js"></script>
<script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCX0chvrZAprlquX-pSM9cWzR2If62ZLHU&libraries=&v=weekly"
        defer
></script>
<script>

</script>
</body>
</html>


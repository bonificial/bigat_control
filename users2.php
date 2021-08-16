<?php

include 'config/constants.php';
include_once './controller/executor.php';
$exec=new ExecutingEngine();
!isset($_SESSION) ? session_start() : null;
$user = $_SESSION;
?>
<!doctype html>
<html lang="en">

<head>
    <title> BIGAT Users</title>

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
<?php // include './common/loader.php' ?>


<?php

function getUsers()
{
    global $connector, $exec;
    $users = $exec->select_all('users', " ORDER BY  EMAIL");
    $response = array();
    foreach ($users as $user) {
        $user['links'] = '';
        $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]/";
        $user['links'] .= '<a href="#"  title="Edit Account" data-toggle="modal"  data-email="' . $user["email"] . '" data-target="#manageUser" class="  btn-link edit_user" data-userid="' . $user['id'] . '"><span class="fa fa-edit"></span> </a>&nbsp;&nbsp;';
        $user['links'] .= '<a href="#"  title="Change Password"  data-email="' . $user["email"] . '"  class="  btn-link change_sec" data-userid="' . $user['id'] . '"><span class="">Reset PW</span> </a>&nbsp;&nbsp;';
        array_push($response, $user);
    }
    return $response;
}

$users = getUsers();
if (!is_array($users)) {
    $no_users = 0;
} else {
    $no_users = count($users);
    $total = $no_users;
    $inactive = 0;
    $active = 0;
    $admins = 0;
    $d_o = 0;
    foreach ($users as $user) {
        if (strtoupper($user['user_level']) == 'ADMIN') {
            $admins += 1;
        }
        if (strtoupper($user['user_level']) == 'DATA_OFFICER') {
            $d_o += 1;
        }
        if (strtoupper($user['status']) == 'INACTIVE') {
            $inactive += 1;
        }
        if (strtoupper($user['status']) == 'ACTIVE') {
            $active += 1;
        }

    }

}


?>


</div>
<!-- END WRAPPER -->
<!-- Javascript -->

<script src="<?php asset(SCRIPTS) ?>/admin_users.js"></script>
</body>

</html>

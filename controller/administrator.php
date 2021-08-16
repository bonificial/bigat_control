<?php
ini_set("log_errors", 1);
ini_set("error_log", "processors/php-error.log");
ini_set('display_errors', 1);
include_once 'executor.php';
ini_set('max_execution_time', 0);
$exec = new ExecutingEngine();
isset($_SESSION) ?: @session_start();
if (!isset($_SESSION['user'])) {
    exit("Reload the Page");
}
function updateUser($obj)
{
    global $connector, $query_dsn, $exec;
   // print_r($obj);
    $sql = "SELECT * FROM users  WHERE id = '" . $exec->sanitize_trim($obj['userID']) . "'";
    $output = '';
    $result = mysqli_query($connector, $sql);
    if (!$result) {
        return mysqli_error($connector);
    }
    $count = mysqli_num_rows($result);
    if ($count > 0) {

        $columns_and_newvalues = '';
        $columns_and_newvalues = $columns_and_newvalues . 'email= "' . $obj['edit_email'] . '", ';
        $columns_and_newvalues = $columns_and_newvalues . 'firstname= "' . $obj['edit_fname'] . '", ';
        $columns_and_newvalues = $columns_and_newvalues . 'lastname= "' . $obj['edit_lname'] . '", ';

        $columns_and_newvalues = $columns_and_newvalues . 'phone= "' . $obj['edit_phone'] . '", ';


        $columns_and_newvalues = $columns_and_newvalues . 'user_level= "' . $obj['edit_access_level'] . '", ';
        $columns_and_newvalues = $columns_and_newvalues . 'status= "' . $obj['edit_status'] . '" ';
        $result = $exec->update_entries('users', $columns_and_newvalues, 'id', "'" . $exec->sanitize_trim($obj['userID']) . "'");
        if ($result == 'success') {
            $output = 'success';
        } else {
            $output = 'error - ' . $result;
        }
    } else {
        $output = "no_exists";
    }
    return $output;
}

function deleteUser($obj)
{
    global $connector, $query_dsn, $exec;
    // print_r($obj);
    $sql = "SELECT * FROM users  WHERE id = '" . $exec->sanitize_trim($obj['userID']) . "'";
    $output = '';
    $result = mysqli_query($connector, $sql);
    if (!$result) {
        return mysqli_error($connector);
    }
    $count = mysqli_num_rows($result);
    if ($count > 0) {
        $ref = $exec->delete('users','id',$obj['userID']);
if($ref){
    $output = "success";
}else{
    $output = "error";
}

    } else {
        $output = "no_exists";
    }
    return $output;
}

function getUserJson($email)
{
    global $connector, $query_dsn;
    $sql = "SELECT * FROM view_users_details  WHERE email = '" . $email . "'";
    $output = '';
    $result = mysqli_query($connector, $sql);
    if (!$result) {
        return mysqli_error($connector);
    }
    $count = mysqli_num_rows($result);
    if ($count > 0) {
        $row = mysqli_fetch_assoc($result);
        $output = json_encode($row);
    } else {
        $output = "No Results";
    }
    return $output;
}

function addUser($obj)
{
    global $connector, $exec;
    $exists = $exec->checkifexists_with_extra('users', "  where  email = '" . $obj['email'] . "' OR phone = '" . $obj['phone'] . "'");
    if ($exists) {
        return 'exists';
    } else {
        $table = 'users';
        $columns = "user_level,status,email,firstname,lastname,phone";
        $values = [$obj['access_level'],
            $obj['status'],
            $obj['email'],
            $obj['fname'],
            $obj['lname'],
            $obj['phone']];

        $sql = "INSERT into users (" . $columns . ") values(?,?,?,?,?,?)";


        $ref = $exec->run_query($sql, $values);


        if ($ref) {
            $insertrespose = 'success';
        } else {
            $insertrespose = 'error';
        }
        return $insertrespose;
    }
}

function getUsers()
{
    global $connector, $exec;
    $users = $exec->select_all('users', " ORDER BY  email");
    $response = array();
    foreach ($users as $user) {
        $user['links'] = '';
        $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]/";
        $user['links'] .= '<a href="#"  title="Edit Account" data-toggle="modal"  data-email="' . $user["email"] . '" data-target="#manageUser" class="  btn-link edit_user" data-userid="' . $user['id'] . '"><span class="fa fa-edit"></span> </a>&nbsp;&nbsp;';
        $user['links'] .= '<a href="#"  title="Change Password"  data-email="' . $user["email"] . '"  class="  btn-link change_sec" data-userid="' . $user['id'] . '"><span class="">Reset PW</span> </a>&nbsp;&nbsp;';
        array_push($response, $user);
    }
    $response = "";
    foreach ($users as $user) {
       //data-toggle="modal" data-target="#manageUser"
        $userAction = "<Button data-toggle='modal' data-email='".$user['email']."' data-target='#manageUser' class='btn btn-info edit_user_link'  data-userid='".$user['id']."'>Edit <i class='fa fa-pencil-alt'></i> </Button>";


        $response .= '<tr style="">
<td>' . ucfirst($user['firstname'] . " " . $user['lastname']) . '</td>
<td> ' . $user['email'] . ' </td>
<td> ' . strtoupper($user['status']) . ' </td>
 
<td>' . $user['user_level'] . '</td>';
        if ($user['email'] != $_SESSION['user']['email']) {
            $response .= '<td>' . $userAction . '</td>';
        } else {
            $response .= '<td>Current User</td>';
        }
        $response .= '</tr>';
    }
    return $response;
}

if (isset($_POST['addUser'])) {
    echo addUser($_POST);
}
if (isset($_POST['editUser'])) {
    echo updateUser($_POST);
}
if (isset($_POST['deleteUser'])) {

    echo deleteUser($_POST);
}
if (isset($_POST['getUserJSON'])) {
    echo getUserJson($_POST['email']);
}
if (isset($_POST['loadUsers'])) {
    echo getUsers();
}


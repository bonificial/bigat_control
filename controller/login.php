<?php
//#e4c6ae  #53717e
include_once __DIR__ . '/executor.php';

$exec = new ExecutingEngine();



function login($username, $password_value)
{
    //session_destroy();
    global $connector, $exec;
    $message = '';
$user = $exec->get_user_by_username( $username);
if(!$user){
    return 'USER_NOT_FOUND';
}else{
    if(strtoupper($user['status']) != 'ACTIVE'){
        return 'INACTIVE';
    }
    if(md5($password_value) != $user['password']){
        return 'FAILP';
    }else{


        $_SESSION['user'] = $user;
        $_SESSION['loggedIn'] = true;

        return 'SUCCESS';
    }
}

}

function login_gmail($id_token)
{


    $client = new Google_Client(['client_id' => '830613963245-6uff749i3hb4a3dnp0oeuolkmdahtq7f.apps.googleusercontent.com']);  // Specify the CLIENT_ID of the app that accesses the backend
    try {
        $payload = $client->verifyIdToken($id_token);
        if ($payload) {
            $userid = $payload['sub'];
            // If request specified a G Suite domain:
            //$domain = $payload['hd'];
            //echo json_encode($payload);
            echo process_login_gmail($payload['email']);

        } else {
            echo 'invalid token';
            // Invalid ID token
        }
    } catch (Exception $e) {
echo 'Exception:'.$e;
        echo 'fatal_error';
    }
}
function sanitize_trim($text)
{
    return trim(html_entity_decode(addslashes($text)));
}
function process_login_gmail($email)
{
    global $connector, $exec;
    $message = '';
    $sql = "SELECT * FROM users WHERE email = '" . $email . "'";
    // echo $sql;
    $resource = mysqli_query($connector, $sql);
    if (!$resource) {
        //echo "queried";
        $message = $message . " -  " . mysqli_error($connector);
        // echo mysqli_error($connector);
    } else {
        //  echo "testing email//....";
        // echo "queried";
        $count = mysqli_num_rows($resource);
        if ($count > 0) {


            $data = mysqli_fetch_array($resource);

            $_SESSION['user'] = $data;
            $_SESSION['loggedIn'] = true;
            $message = 'success';



        } else {
            //echo "testing phone//....";
            $message = 'fail';
        }
    }
    return $message;
}

if (isset($_POST['userlogin'])) {

    echo login($exec->sanitize_trim($_POST['username']),$exec-> sanitize_trim($_POST['password']));
}
if (isset($_POST['userlogin_gmail'])) {

    echo login_gmail(sanitize_trim($_POST['token']));
}

?>
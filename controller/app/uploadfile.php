<!DOCTYPE html>
<html>
<head>
    <title>Upload your Image</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
          integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no"/>
</head>
<body>
<!-- === File Upload ===
Design a file upload element. Is it the loading screen and icon? A progress element? Are folders being uploaded by flying across the screen like Ghostbusters? ;)
-->
<style>
    body {
        background-color: whitesmoke;
        background-image: url("https://www.transparenttextures.com/patterns/lyonnette.png");
        border-bottom: 0px solid black;
        text-align: center;

        height: 100vh;
        width: 100vw;

    }

    /* === Wrapper Styles === */
    #FileUpload {
        display: flex;
        justify-content: center;
    }

    .upload p {
        margin-top: 12px;
        line-height: 0;
        font-size: 22px;
        color: #0c3214;
        letter-spacing: 1.5px;
    }

    label {
        display: block;
        width: 60vw;
        max-width: 300px;
        margin: 0 auto;
        background-color: slateblue;
        color:#fff;
        border-radius: 2px;
        font-size: 1em;
        line-height: 2.5em;
        text-align: center;
    }

    label:hover {
        background-color: cornflowerblue;
    }

    label:active {
        background-color: mediumaquamarine;
    }

    input {
        border: 0;
        clip: rect(1px, 1px, 1px, 1px);
        height: 1px;
        margin: -1px;
    // overflow: hidden;
        padding: 0;
        position: absolute;
        width: 1px;
    }


</style>
<?PHP
ini_set("log_errors", 1);
ini_set("error_log", "/php-error.log");
error_log( "Hello, errors!" );
include_once __DIR__ . '/../../config/config.php';
$uploads_path = PROFPIC_UPLOADS;

require __DIR__.'/../../vendor/autoload.php';
use Kreait\Firebase\Factory;
//$factory = (new Factory)->withServiceAccount('./hwwk-bigat-firebase-adminsdk-1ag6o-ffd6897781.json');




if (!empty($_FILES['profile_pic'])) {

    $target_dir = "uploads/";
    $randomName =  generateRandomString(8,'LETTERS');
    $target_file_name = str_replace(" ","", $randomName.  basename($_FILES["profile_pic"]["name"]));
    $target_file = str_replace(" ","",$target_dir . $target_file_name);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));


// Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {

        //  echo json_encode(array('type'=>'error','message'=>'Sorry, your file was not uploaded.','link'=>null));
        echo '<h1>Upload Failed</h1>';
// if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {

            $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $link = htmlspecialchars( $uploads_path ."/". $target_file_name);
            //  echo json_encode(array('type'=>'success','message'=>'File Upload successful.','link'=>htmlspecialchars( $uploads_path ."/". $target_file_name)));
            //     echo '<h2>Profile Picture Uploaded Successfully</h2>';

if(isset( $_GET['key'])){
    $key = $_GET['key'];
}else {
    if (isset($_POST['key'])) {
        $key = $_POST['key'];
    } else {
        die('<h1>Key not Provided</h1>');
    }
}


            if($key == null || $key == ""){
                die('<h1>Upload Failed.Key not found</h1>');
            }
            $factory = (new Factory)->withDatabaseUri('https://hwwk-bigat.firebaseio.com/');
            $database = $factory->createDatabase();
            $uid =$key;
            $postData = [
                'profile_pic' => $link
            ];



            try {
                $ref = $database->getReference('participants/' . $key) // this is the root reference
                ->update($postData);
                echo '<h1>Profile Picture Upload was successful. You may now close this page.</h1>';
            } catch (\Kreait\Firebase\Exception\DatabaseException $e) {
                echo '<h1>Error Occured. Try again later</h1>';
            }




        } else {
            //echo json_encode(array('type'=>'error','message'=>'Sorry, there was an error and the file was not uploaded.','link'=>null));
            echo '<h1>Upload failed.Moving failed. File issues</h1>';
        }
    }
}

function generateRandomString($length = 6, $type = 'MIX')
{

    if ($type == 'MIX') {
        $characters = '23456789abcdefghjklmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
    } elseif ($type == 'NUMBERS') {
        $characters = '123456789';
    } elseif ($type == 'LETTERS') {
        $characters = 'abcdefghjklmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
    } elseif ($type == 'LETTERS_CAPS_ONLY') {
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
    }
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

?>
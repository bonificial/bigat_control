
<?PHP
ini_set("log_errors", 1);
ini_set("error_log", "/php-error.log");
error_log( "Hello, errors!" );
include_once __DIR__ . '/../../config/config.php';
$uploads_path = PROFPIC_UPLOADS;
 $json = file_get_contents('php://input');
echo 'starting';
print_r( $json);

print_r($_POST);
require __DIR__.'/../../vendor/autoload.php';
use Kreait\Firebase\Factory;
//$factory = (new Factory)->withServiceAccount('./hwwk-bigat-firebase-adminsdk-1ag6o-ffd6897781.json');



if(!isset($_POST['key'])){
    echo 'Key not Provided';
    return 0;
}
if (!empty($_FILES['profile_pic'])) {
$key =$_POST['key'];
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
                $ref = $database->getReference('participants/' . $key. '/profile_pic') // this is the root reference
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
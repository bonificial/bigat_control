
<?PHP
ini_set("log_errors", 1);
ini_set("error_log", "/php-error.log");
error_log( "Hello, errors!" );
include_once __DIR__ . '/../../config/config.php';
$uploads_path = PROFPIC_UPLOADS;

require __DIR__.'/../../vendor/autoload.php';
use Kreait\Firebase\Factory;
//$factory = (new Factory)->withServiceAccount('./hwwk-bigat-firebase-adminsdk-1ag6o-ffd6897781.json');
if(isset($_POST['uploadProfilePic'])){
  echo uploadProfileImage();
}
if(isset($_POST['updateProfile'])){
    echo updateProfile();
}

function uploadProfileImage(){
    global  $uploads_path;
    if(!isset($_POST['key']) || empty($_FILES['profile_pic'])){
        return json_encode(array('status'=>'error','message'=>'A profile picture along with a User\'s key must be provided'));
    }else{
        $key =$_POST['key'];
        $target_dir = "uploads/";
        $randomName =  generateRandomString(8,'LETTERS');
        $target_file_name = str_replace(" ","", $randomName.  basename($_FILES["profile_pic"]["name"]));
        $target_file = str_replace(" ","",$target_dir . $target_file_name);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        if ($uploadOk == 0) {
            return json_encode(array('status'=>'error','message'=>'Upload Failed'));
        } else {
            if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {

                $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                $link = htmlspecialchars( $uploads_path ."/". $target_file_name);


                $factory = (new Factory)->withDatabaseUri('https://hwwk-bigat.firebaseio.com/');
                $database = $factory->createDatabase();
                $uid =$key;

                return json_encode(array('status'=>'success','message'=>'success', 'profile_pic'=>$link));

            /*    try {
                    $ref = $database->getReference('participants/' . $key)   ->update($postData);
                    if($ref){
                        return json_encode(array('status'=>'success','message'=>'success'));
                    }else{
                        return json_encode(array('status'=>'error','message'=>'Profile pic not saved'));
                    }

                } catch (\Kreait\Firebase\Exception\DatabaseException $e) {
                    return json_encode(array('status'=>'error','message'=>'An error occured, try again later'));
                }*/

            } else {
                //echo json_encode(array('type'=>'error','message'=>'Sorry, there was an error and the file was not uploaded.','link'=>null));
                return json_encode(array('status'=>'error','message'=>'File moving issues'));
            }
        }
    }

}

function updateProfile($postData){


    $factory = (new Factory)->withDatabaseUri('https://hwwk-bigat.firebaseio.com/');
    $database = $factory->createDatabase();
    $key = $postData['key'];
    $updateData = $postData['updateData'];


    try {
        $ref = $database->getReference('participants/' . $key) ->update($updateData);
        if($ref){
            return json_encode(array('status'=>'success','message'=>'success'));
        }else{
            return json_encode(array('status'=>'error','message'=>'Profile pic not saved'));
        }

    } catch (\Kreait\Firebase\Exception\DatabaseException $e) {
        return json_encode(array('status'=>'error','message'=>'An error occured, try again later'));
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
<?PHP
ini_set("log_errors", 1);
ini_set("error_log", "/php-error.log");
error_log( "Hello, errors!" );
include_once __DIR__ . '/../../config/config.php';
$uploads_path = PROFPIC_UPLOADS;

require __DIR__.'/../../vendor/autoload.php';
use Kreait\Firebase\Factory;
//$factory = (new Factory)->withServiceAccount('./hwwk-bigat-firebase-adminsdk-1ag6o-ffd6897781.json');

$json = file_get_contents('php://input');
$obj = json_decode($json, TRUE);
header('Content-Type: application/json');

if(isset($obj['updateProfile'])){
    echo updateProfile($obj);
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
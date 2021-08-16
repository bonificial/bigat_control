<?php
//#e4c6ae  #53717e
include_once __DIR__ . '/executor.php';
include_once __DIR__ . '/executor_firestore.php';
$exec = new ExecutingEngine();
$execf = new ExecutingEngineFirestore();


use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

error_reporting(E_ALL);
ini_set('display_errors', 1);


//loadMembersJSON();


function getMember($key)
{
    global $execf;

  //  $participant = $exec->fbgetChildren('participants/'.$key);
    $participant = $execf->getSingleDocument('users/'.$key);
  //  print_r($participant);
    if(isset($participant['profile_pic'])){
        $participant['profile_pic'] = APP_CONTROLLER_URL."/app".$participant['profile_pic'];
    }
    $participant['key'] = $key;


    return  json_encode($participant);
}
function sendNotification2($data)
{
    global $exec;
    $participant = $exec->fbgetChildren('participants/'.$data['userID']);
    if(!isset($participant['device_token'])){
     return json_encode(array('status'=>'error','message'=>'Device Token not Found'));
    }
    if(isset($participant['device_token'])){
     $res=  $exec->dispatchNotification($data['nTitle'],$data['nBody'],[$participant['device_token']]);
     if($res){
         return json_encode(array('status'=>'success','message'=>'Notification Sent Successful'));
     }else{
         return json_encode(array('status'=>'error','message'=>'An Error Occured Sending Notification'));
     }
    }

}
function sendNotification($data)
{

    $title = $data['title'];
    $body = $data['content'];
    $deviceToken = $data['deviceToken'];
    $type = $data['type'];

    global $exec;
//$factory = (new Factory)->withDatabaseUri('./hwwk-bigat-80f0f8ed34b3.json');
    $factory = (new Factory)->withServiceAccount('./hwwk-bigat-firebase-adminsdk-1ag6o-46426111a5.json')
        ->withDatabaseUri('https://hwwk-bigat.firebaseio.com');

    $messaging = $factory->createMessaging();

    $count = 1;
    $recipient = 'recipient';
    $deviceTokens = [$deviceToken];

    $message = CloudMessage::withTarget('token', $deviceToken)
        ->withNotification(Notification::create($title, $body))
        ->withData(['key' => 'value']);;

    try {
      //  echo 'Attempting Multicast with '. json_encode($message);
        $res = $messaging->send($message);

        return $res;

    } catch (Exception $e) {
       // print_r( $e->getMessage());
        return $e->getMessage();
    }
}

function disableAccount($data)
{
    global $exec;
    $ref = $exec->updateEntry('participants/'.$data['userID'].'/active',false);
    if($ref){
        return json_encode(array('status'=>'success','message'=>'Account Disabled Successfully'));
    }else{
        return json_encode(array('status'=>'error','message'=>'An Error Occured'));
    }

}
if (isset($_POST['sendNotif'])) {
    header('Content-Type: application/json');
    $res = sendNotification($_POST);

    $response= null;
    if($res == 'SenderId mismatch' || $res == 'The registration token is not a valid FCM registration token'){
        $response = ['status'=>'error', 'message'=>'Device Token Needs Updating. Try Again later once the user reopens app'];
    }else{
        $response = ['status'=>'success'];
    }
    echo json_encode($response);

}
if (isset($_POST['disableAccount'])) {
    header('Content-Type: application/json');
    echo disableAccount($_POST );

}
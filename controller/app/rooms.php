<?php
//#e4c6ae  #53717e

include_once __DIR__ . '/../executor.php';
use Kreait\Firebase\Factory;
$exec = new ExecutingEngine();

error_reporting(E_ALL);
ini_set('display_errors', 1);
$json = file_get_contents('php://input');

$obj = json_decode($json, TRUE);
header('Content-Type: application/json');

function calculate_time_difference_years($startdatetime, $enddatetime)
{
    $d1 = new DateTime($startdatetime);
    $d2 = new DateTime($enddatetime);
    $interval = $d2->diff($d1);

    return $interval->format('%y years');
}
function sendNotification($title, $body)
{
    global $exec;
    //$factory = (new Factory)->withDatabaseUri('./hwwk-bigat-80f0f8ed34b3.json');
    $factory = (new Factory)->withServiceAccount('./hwwk-bigat-80f0f8ed34b3.json');

    $messaging = $factory->createMessaging();


    $participants = $exec->fbgetChildren('participants');
    $channelName = 'bigat' . $exec->generateRandomString('4');
//print_r($participants);
    $count = 1;
    $recipient = 'recipient';
    $deviceTokens = [];
    foreach ($participants as $participant) {
        if (isset($participant['device_token'])) {
            array_push($deviceTokens, $participant['device_token']);
        }
    }


    $message = CloudMessage::new()
        ->withNotification(Notification::create($title, $body))
        ->withData(['key' => 'value']);;


    try {
        $res = $messaging->sendMulticast($message, $deviceTokens);
        //print_r($res);
        return $res;

    } catch (\Kreait\Firebase\Exception\MessagingException $e) {
        print_r($e);
        return false;
    } catch (\Kreait\Firebase\Exception\FirebaseException $e) {
        print_r($e);
        return false;
    }


}
function loadRecentRoomChats($data)
{
    global $exec;

    $rooms = $exec->fbgetChildren('rooms');
    $recentGroupMessages = [];

    $currentUser = $data['currentUserID'];

    foreach ($rooms as $key => $room) {
        $lastMessage = [];
        $members = $exec->fbgetChildren('rooms/' . $key . '/members');
        $currentUserLastOpened = $exec->fbgetChildren('rooms/' . $key . '/members/' . $data['currentUserID'] . '/lastOpened');
        // echo  ($currentUserLastOpened);
        if ($members != null && in_array($currentUser, array_keys($members))) {
            $messages = $exec->fbgetChildren('rooms/' . $key . '/thread');
            if ($messages != null) {
                $lastMessage = array_values($messages)[0];
                if ($currentUserLastOpened == null || (strtotime($lastMessage['createdAt']) > $currentUserLastOpened)) {
                    $lastMessage['unread'] = true;
                }else{
                    $lastMessage['unread'] = false;
                }


                $lastMessage['room'] = $room;
                $lastMessage['room']['key'] = $key;
                $lastMessage['room_name'] = $room['name'];
                $lastMessage['name'] = $room['name'];
                array_push($recentGroupMessages, $lastMessage);
            }

        }

    }

    return array_slice($recentGroupMessages, 0, 4);


}
function loadRooms($data){
    global $exec;

    $rooms = $exec->fbgetChildren('rooms');
$roomz = [];

    $currentUser = $data['currentUserID'];
    foreach ($rooms as $key=>$room) {
        $members = $exec->fbgetChildren('rooms/' . $key . '/members');
     if ($members) {
if($members!=null){
    if(in_array($currentUser,$members)){
        $room['joined'] = true;
    }else{
        $room['joined'] = false;
    }
}
         $room['memberCount'] = ($members != null ? sizeof($members) : 0);
         $room['created'] = date('jS M Y', strtotime($room['created']));
         $room['key'] = $key;
         array_push($roomz, $room);
     }
    }
return $roomz;
}
function getRoomThread($data){
    global $exec;
    $roomKey = $data['roomKey'];
    $thread = null;
    $thread = $exec->fbgetChildren('rooms/'.$roomKey.'/thread');

    $messages = [];
  if(empty($thread)){
      $thread = null;
   // echo 'thread empty';
  }else{
    //  echo 'thread popd';
      function sortFunction( $a, $b ) {
          return strtotime($a["createdAt"]) - strtotime($b["createdAt"]);
      }
      usort($thread, "sortFunction");

      foreach ($thread as $message){
          $message['key'] = $message['_id'];
          array_push($messages,$message);
      }
  }
    $exec->updateEntryWithMerging('rooms/' .$roomKey . '/members/' . $data['currentUserID'].'/', ['lastOpened'=>time()]);

    return $messages;

}
function sendRoomMessage($data){
    global $exec;
    $roomKey = $data['roomKey'];


        $ref = $exec->addEntry('/rooms/'.$roomKey.'/thread', $data['message']);
    $exec->updateLastUserActivity($data['currentUserID']);
        if($ref){
            //Send Notifs
            return getRoomThread(['roomKey'=>$roomKey,'currentUserID'=>$data['currentUserID']]);
        }else{
            return json_encode(array('status'=>'error','message'=>'An Error Occured Sending Message'));
        }



}
function joinAroom($data){
    global $exec;
    $roomKey = $data['roomKey'];
$currentUserID = $data['currentUserID'];
$newMember = [$currentUserID=>$currentUserID];
    $ref = $exec->setEntry('/rooms/'.$roomKey.'/members', $newMember);

    if($ref){

        return (array('status'=>'success','message'=>'Join Successful'));
    }else{
        return  (array('status'=>'error','message'=>'An Error Occured Joining Space'));
    }



}
function leaveAroom($data){
    global $exec;
    $roomKey = $data['roomKey'];
    $currentUserID = $data['currentUserID'];

    $ref = $exec->removeEntry('/rooms/'.$roomKey.'/members', $currentUserID);
    if($ref){

        return (array('status'=>'success','message'=>'Leaving Successful'));
    }else{
        return  (array('status'=>'error','message'=>'An Error Occured Leaving Space'));
    }



}

if(isset($obj['type'])){
    if ( ($obj['type'] == 'joinAroom')) {
        header('Content-Type: application/json');
        echo json_encode(joinAroom($obj));
    }
    if (($obj['type'] == 'leaveAroom')  ) {
        header('Content-Type: application/json');
        echo json_encode(leaveAroom($obj));
    }
}

if (isset($obj['loadAllRooms'])) {
    header('Content-Type: application/json');
     echo json_encode(loadRooms($obj));
}
if (isset($obj['sendRoomMessage'])) {
    header('Content-Type: application/json');
    echo json_encode(sendRoomMessage($obj));
}
if (isset($obj['compileThread'])) {
    header('Content-Type: application/json');
    echo json_encode(['threadkeytouse'=>compileThreadIDs($obj['currentUserID'],$obj['friendID'])]);
}
if (isset($obj['fetchRoomThread'])) {
    header('Content-Type: application/json');
    echo json_encode(getRoomThread($obj));
}
if (isset($obj['fetchRecentRoomChats'])) {
    header('Content-Type: application/json');
    echo json_encode(loadRecentRoomChats($obj));
}
if (isset($obj['distanceMatrix'])) {
    header('Content-Type: application/json');
    echo  json_encode(distanceMatrix($obj['me'],$obj['them']));
}


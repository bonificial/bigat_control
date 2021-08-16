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

function compileThreadIDs($currentUserID, $secondUserID)
{
    global $exec;
    //  $possibilities =  [substr($currentUserID, 0, 4) . substr($secondUserID, 0, 4), substr($secondUserID, 0, 4) . substr($currentUserID, 0, 4)];
    $possibilities = [$currentUserID . "-" . $secondUserID, $secondUserID . "-" . $currentUserID,];
    $threadKeytouse = "new";
    $threadkeyset = [];
    if ($threadKeytouse == 'new') {
        foreach ($possibilities as $threadID) {
            $thread = $exec->fbgetChildren('threads/' . $threadID);
            if ($thread != null) {
                $threadKeytouse = $threadID;
                $threadkeyset['thread'] = $thread;
            }
        }
    }
    if ($threadKeytouse == 'new') {
        return $possibilities[0];
    } else {
        return $threadKeytouse;
    }

}

function distanceMatrix($meLoc, $themLoc)
{
    $KEY = "AIzaSyCX0chvrZAprlquX-pSM9cWzR2If62ZLHU";
    $th_lat = $themLoc['lat'];
    $th_lng = $themLoc['lng'];
    $me_lat = $meLoc['lat'];
    $me_lng = $meLoc['lng'];
    $themLocale = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?latlng=$th_lat,$th_lng&key=$KEY");
    $themLocaleDistance = file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins=$me_lat,$me_lng&destinations=$th_lat,$th_lng&key=$KEY");

    $result = ['themLocale' => json_decode($themLocale, true), 'themDistance' => json_decode($themLocaleDistance, true)];

    return $result;

}

function loadFriends($data)
{
    global $exec;

    $participants = $exec->fbgetChildren('participants');
    $partps = [];

    /*
     * export const prepareThreadID =  (firstUserID, secondUserID)=> {
        return (firstUserID).substr(0, 4) + (secondUserID).substr(0, 4)
    }
     */
    $currentUser = $data['currentUserID'];
    foreach ($participants as $key => $participant) {
        if(is_array($participant)) {
            //calculate_time_difference($event['date_time'], date(DATE_ISO8601));
            $participant['key'] = $key;
            if (isset($participant['dob'])) {
                $participant['age'] = calculate_time_difference_years(date(DATE_ISO8601, strtotime($participant['dob'])), date(DATE_ISO8601));
            } else {
                $participant['age'] = 0;
            }
            //$participant['threadKeytouse'] = compileThreadIDs($currentUser,$key);
            if ($participant['age'] >= 18 && $participant['key'] != $currentUser) {
                array_push($partps, $participant);
                //array_push($partps, $participant);
            }
        }
    }
    return $partps;
}

function loadThread($data)
{
    global $exec;
    $threadID = $data['threadID'];
    $thread = null;
    $thread = $exec->fbgetChildren('threads/' . $threadID);
    // echo 'getting '.$threadID;
    $messages = [];
    if (empty($thread)) {
        $thread = null;
        // echo 'thread empty';
    } else {
        //  echo 'thread popd';
        function sortFunctionLocal($a, $b)
        {
            return strtotime($a["createdAt"]) - strtotime($b["createdAt"]);
        }

        usort($thread, "sortFunctionLocal");
        foreach ($thread as $message) {
            $message['key'] = $message['_id'];
            array_push($messages, $message);
        }
        $exec->updateEntryWithMerging('participants/' . $data['currentUserID'] . '/threads/' . $threadID, ['lastOpened'=>time()]);
        $exec->updateLastUserActivity($data['currentUserID']);
    }

    return $messages;

}

  function loadRecentMessages($data,$limit=false)
{
    global $exec;
    function sortFunctionRecents($a, $b)
    {
        return strtotime($b["createdAt"]) - strtotime($a["createdAt"]);
    }

    $thread = null;
    $participant_thread_node = 'participants/' . $data['currentUserID'] . '/threads/';
    $threads = $exec->fbgetChildren($participant_thread_node);
    // echo 'getting '.$threadID;
    $recentMessages = [];

    if ($threads != null) {
        foreach ($threads as $threadkey => $thread) {

            $unread = 0;
            //  $lastMessage =  (array) ($exec->fbgetFirstChild('threads/'.$thread));
            $messages = $exec->fbgetChildren('threads/' . $threadkey);

            $lastMessage = array_values($messages)[0];

            foreach ($messages as $message) {
                //       echo strtotime($message['createdAt']) . "<br><br>";
                if (!isset($thread['lastOpened']) || (strtotime($message['createdAt']) > $thread['lastOpened'])) {
$unread ++;
                }
            }
            $lastMessage['unread'] = $unread;
            $lastMessage['threadkey'] = $threadkey;
            $ptps = explode("-", $threadkey);
            $otherParticipant = "";

            foreach ($ptps as $ptp) {
                if ($ptp != $data['currentUserID']) {
                    $otherParticipant = $ptp;
                }
            }
            $other = $exec->fbgetChildren('participants/' . $otherParticipant);
            $lastMessage['otherParticipant'] = $other;
            if (!isset($other['lastActivity']) ) {
                $lastMessage['otherParticipant']['online'] = false;
            }else{
                if((time() - $other['lastActivity']) < 300){
                    $lastMessage['otherParticipant']['online'] = true;
                }else{
                    $lastMessage['otherParticipant']['online'] = false;
                }
            }

            array_push($recentMessages, $lastMessage);

            usort($recentMessages, "sortFunctionRecents");

        }
    }
    $exec->updateLastUserActivity($data['currentUserID']);
    return ($recentMessages);

}

function sendMessage($data)
{
    global $exec;
    $threadID = $data['threadID'];
//print_r($data);

    $ref = $exec->updateEntry('participants/' . $data['fromID'] . '/threads/' . $threadID, $threadID);
    $ref2 = $exec->updateEntry('participants/' . $data['toID'] . '/threads/' . $threadID, $threadID);
    if (!$ref || !$ref2) {
        return json_encode(array('status' => 'error', 'message' => 'An Error Occured'));
    } else {
        $ref3 = $exec->addEntry('threads/' . $threadID, $data['message']);
        if ($ref3) {

            return loadThread(['threadID' => $threadID, 'currentUserID'=>$data['fromID']]);
        } else {
            return json_encode(array('status' => 'error', 'message' => 'An Error Occured Sending the Message'));
        }
    }


}

if (isset($obj['loadRecentMessages'])) {
    header('Content-Type: application/json');
    echo json_encode(loadRecentMessages($obj));
}
if (isset($obj['loadFriends'])) {
    header('Content-Type: application/json');
    echo json_encode(loadFriends($obj));
}
if (isset($obj['sendMessage'])) {
    header('Content-Type: application/json');
    echo json_encode(sendMessage($obj));
}
if (isset($obj['compileThread'])) {
    header('Content-Type: application/json');
    echo json_encode(['threadkeytouse' => compileThreadIDs($obj['currentUserID'], $obj['friendID'])]);
}
if (isset($obj['loadThreadID'])) {
    header('Content-Type: application/json');
    echo json_encode(loadThread($obj));
}
if (isset($obj['distanceMatrix'])) {
    header('Content-Type: application/json');
    echo json_encode(distanceMatrix($obj['me'], $obj['them']));
}

//////
//mysql://bcef23ecd96b4f:45acb00e@us-cdbr-east-02.cleardb.com/heroku_ef24ecdfb6a8080?reconnect=true


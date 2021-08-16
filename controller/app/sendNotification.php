<?php
//#e4c6ae  #53717e

include_once __DIR__ . '/../executor.php';
$exec = new ExecutingEngine();

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

error_reporting(E_ALL);
ini_set('display_errors', 1);
//setting error logging to be active
$log_file = "./error_log.log";
ini_set("log_errors", TRUE);

// setting the logging file in php.ini
ini_set('error_log', $log_file);
$json = file_get_contents('php://input');

$obj = json_decode($json, TRUE);

header('Content-Type: application/json');

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
        echo 'Attempting Multicast with '. json_encode($message);
        $res = $messaging->send($message);
        return $res;

    } catch (Exception $e) {
        print_r( $e->getMessage());
        return false;
    }
}


if (isset($obj['sendNotification'])) {
    header('Content-Type: application/json');
    echo json_encode(sendNotification($obj));
}
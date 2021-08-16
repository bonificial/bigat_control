<?php
//#e4c6ae  #53717e
include_once __DIR__ . '/executor.php';

$exec = new ExecutingEngine();

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

error_reporting(E_ALL);
ini_set('display_errors', 1);
function retrieveEvents($data)
{

    global $exec;

    $allEvents = $exec->run_fetch_query("SELECT events.* FROM events ORDER by date_time DESC");
    $response = '';

    if (!($allEvents)) {
        $response .= 'No Records Found';
    } else {
        foreach ($allEvents as $event) {

            $fulltitle = $event["event_title"];
            $eventURL = $exec->getURL() . "?fetchevent&id=" . $event["id"];
            $title = ucfirst(strtolower(mb_strimwidth($fulltitle, 0, 25, "...")));
            $fi = $event["featured_image"] != "" ? APP_CONTROLLER_URL . $event["featured_image"] : "";
            $response .= '<tr title="' . $event["description"] . '"> <td class="text-right text-muted">' . $event["id"] . '</td>';
            $response .= '<td title="' . $fulltitle . '"><div class="widget-content p-0">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left flex2">
                                                <div class="widget-heading">' . $title . '</div>
                                              
                                            </div>
                                        </div>
                               </div>
                           </td>';
            $response .= '<td class="text-left">' . $event['date_time'] . '</td>';

            $response .= '<td class="text-left">' . $event["county"] . '</td>';
            $response .= '<td class="text-left">' . ($event["notified"] ? '<i class="fa fa-check"></i>' :  '<i class="fa fa-times"></i>') . '</td>';

            $response .= '  <td class="text-center">
                            <button type="button" data-link="' . $eventURL . '" data-post-id="' . $event["id"] . '" class="btn btn-link btn_view_post" title="View Post" data-toggle="modal" data-target="#openEventModal">
                            <b class="fa fa-eye"></b></button> </td>';
        }
    }
    return $response;
}
header('Content-Type: application/json');
function retrieveEventsJSON($data)
{
    global $exec;
    //  $sql_where = (isset($data['category'])) ? ' WHERE post_category = "' . $data['category'] . '"' : '';
    $allEvents = $exec->run_fetch_query("SELECT events.* FROM events ORDER by date_time DESC");
    return (array)$allEvents;
}

function getEvent($id)
{
    global $exec;
    $result = $exec->select_all('events', 'WHERE id = ' . $id);

    $result[0]['date_time'] = date(DATE_TIME_FORMAT, strtotime($result[0]['date_time']));
    $result[0]['featured_image'] = $result[0]['featured_image'] != "" ? APP_CONTROLLER_URL . $result[0]['featured_image'] : "";
    return $result;
}

function editEvent($data)
{
//print_r($data);
    global $exec;
    $featured_image_path = "";
    $eventExist = $exec->select_all('events', 'WHERE event_title = "' . $data  ['event_title'] . '" and county="' . $data  ['event_county'] . '"and date_time="' . $data  ['event_datetime'] . '"');
    if ($eventExist) {
        return array('response' => 'exists');
    }
    //print_r($_FILES);
    if (!empty($_FILES)) {


        $uploads_path = EVENT_UPLOADS;
        $target_dir = "uploads/";
        $randomName = strtolower($exec->generateRandomString(8, 'LETTERS'));
        $target_file_name = str_replace(" ", "", $randomName . basename($_FILES["featured_image"]["name"]));
        $target_file = str_replace(" ", "", $target_dir . $target_file_name);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));


// Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {

            //  echo json_encode(array('type'=>'error','message'=>'Sorry, your file was not uploaded.','link'=>null));
            echo '<h1>Upload Failed</h1>';
// if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["featured_image"]["tmp_name"], $target_file)) {

                $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                $featured_image_path = htmlspecialchars($uploads_path . "/" . $target_file_name);

            }
        }
    }
    $query_param_string = "";
    $count = 1;
    $id = $data['event_id'];


    $query_param_string = $query_param_string . 'event_title= "' . $data['event_title'] . '", ';
    $query_param_string = $query_param_string . 'date_time= "' . $data['event_datetime'] . '", ';
    $query_param_string = $query_param_string . 'county= "' . $data['event_county'] . '", ';
    $query_param_string = $query_param_string . 'description= "' . $data['event_description'] . '", ';
    if (!empty($_FILES)) {
        $query_param_string = $query_param_string . 'featured_image= "' . $featured_image_path . '", ';
    }
    $query_param_string = $query_param_string . 'notified= "' . $data['notify'] . '" ';


    $sql = "UPDATE events SET  $query_param_string WHERE id=$id";

    $ref = $exec->run_update_query($sql);
    if ($ref) {
        // echo  $values['notify'] ;
        if ($data['notify'] === "0") {
            $response = array('response' => 'success');
        }
        if ($data['notify'] === "1") {
            if (sendNotification('Checkout Event', $data  ['event_title'] . " in " . $data  ['event_county'])) {
                $response = array('response' => 'success_nfd');
            } else {
                $response = array('response' => 'success_no_nfd');
            }
        }


    } else {
        $response = array('response' => 'error');
    }
    return $response;
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

function addEvent($values)
{
    global $exec;
    $featured_image_path = "";
    $eventExist = $exec->select_all('events', 'WHERE event_title = "' . $values  ['event_title'] . '" and county="' . $values  ['event_county'] . '"and date_time="' . $values  ['date_time'] . '"');
    if ($eventExist) {
        return array('response' => 'exists');
    }
    if (!empty($_FILES)) {
        $uploads_path = EVENT_UPLOADS;
        $target_dir = "uploads/";
        $randomName = strtolower($exec->generateRandomString(8, 'LETTERS'));
        $target_file_name = str_replace(" ", "", $randomName . basename($_FILES["featured_image"]["name"]));
        $target_file = str_replace(" ", "", $target_dir . $target_file_name);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));


// Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {

            //  echo json_encode(array('type'=>'error','message'=>'Sorry, your file was not uploaded.','link'=>null));
            echo '<h1>Upload Failed</h1>';
// if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["featured_image"]["tmp_name"], $target_file)) {

                $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                $featured_image_path = htmlspecialchars($uploads_path . "/" . $target_file_name);

            } else {
                return array('response' => 'Upload Failed');
            }
        }
    }

    $sql = "INSERT into events (event_title,date_time,county,location_lat,location_long,description,notified,featured_image)
 values(?,?,?,? ,?,?,?,?)";
    //print_r($data);
    $values  ['featured_image'] = $featured_image_path;

    $ref = $exec->run_query($sql,
        [
            $values  ['event_title'],
            date('Y-m-d H:i:s', strtotime($values  ['date_time'])),
            $values  ['event_county'],
           0,
            0,
            $values  ['description'],
            $values  ['notify'],
            $values  ['featured_image']
        ]);

    if ($ref) {

        if ($values['notify'] === "0") {
            $response = array('response' => 'success');
        }
        if ($values['notify'] === "1") {

            if (sendNotification('Checkout Event', $values  ['event_title'] . " in " . $values  ['event_county'] .' County(ies)')) {
                $response = array('response' => 'success_nfd');
            } else {
                $response = array('response' => 'success_no_nfd');
            }
        }
    } else {
        $response = array('response' => 'error');
    }
    return $response;
}

function addCategory($data)
{

    global $exec;


    $sql = "INSERT into categories (category_name) values(?)";

    $ref = $exec->run_query($sql, [$data['category_name']]);
    if ($ref) {
        $response = array('response' => 'success');
    } else {
        $response = array('response' => 'error');
    }
    return $response;
}

function get_categories()
{
    global $exec;
    return $exec->select_all('categories', '');
}


if (isset($_POST['loadEvents'])) {
    header('Content-Type: application/json');
    echo retrieveEvents($_POST);
    // echo retrievePostsjson($_POST);
}
if (isset($_POST['loadPostsjson'])) {
    header('Content-Type: application/json');

    // echo json_encode(retrievePostsjson($_POST));
}
if (isset($_GET['fetchevent'])) {

    echo json_encode(getEvent($_GET['id']),DEFINED('JSON_INVALID_UTF8_IGNORE') ? JSON_INVALID_UTF8_IGNORE : 0);
}
if (isset($_GET['getcategories'])) {
    echo json_encode(get_categories());
}
if (isset($_POST['editEvent'])) {
    echo json_encode(editEvent($_POST));
}
if (isset($_POST['addEvent'])) {
    echo json_encode(addEvent($_POST));
}



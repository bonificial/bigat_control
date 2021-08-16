<?php
require './members.php';
//echo loadMembersJSON();
//header('Content-Type: application/json');

  function calculate_time_difference($startdatetime, $enddatetime)
{

    $start = strtotime($startdatetime);
    $end = strtotime($enddatetime);
    $days = ($end - $start) / 86400;
    return $days;

}
  function calculate_time_difference_years($startdatetime, $enddatetime)
{


    $d1 = new DateTime($startdatetime);
    $d2 = new DateTime($enddatetime);
    $interval = $d2->diff($d1);

    return $interval->format('%d days, %H hours, %I minutes, %S seconds');
}
function loadMembersJSON()
{
    global $execf;

    $users = $execf->getCollectionDocuments('users')->documents();
    $participants = [];
    foreach ($users as $user) {
      array_push($participants,$user->data());
    }
    $members = [];
    $counties = [];
   //print_r($participants);
    foreach ($participants as $key => $participant) {
        if(is_array($participant) && isset($participant['id'])) {
            if (isset($participant['county'])) {
                array_push($counties, $participant['county']);
            }
            if (isset($participant['profile_pic'])) {
                $participant['profile_pic'] = MAIN_APP_CONTROLLER_URL . "/app" . $participant['profile_pic'];

            }
            $participant['key'] = $participant['id'];
            $participant['link'] = APP_CONTROLLER_URL . "/db_control.php?getMember&key=" . $participant['id'];
            array_push($members, $participant);
        }
    }
    $county_max = @array_keys(array_count_values($counties), max(array_count_values($counties)));

    $result = @['count' => sizeof($participants), 'members' => $members, 'county_max' => $county_max];

    return json_encode($result);
}

function loadEvents_JSON()
{
    global $exec;
    $evs = [];
    $events = $exec->select_all('events', '');
    $nearest_days = 100;
    $nearest_event = "NO UPCOMING EVENT SCHEDULED";

 //   echo json_last_error_msg();
    foreach ($events as $event) {

        $timediff = calculate_time_difference($event['date_time'], date(DATE_ISO8601));
        if ($timediff < $nearest_days && $timediff < 0) {
            $nearest_days = $timediff;
            $nearest_event = '<span title="'.$event['event_title'].'">' . "Event # ". $event['id'] ."  " . $event['county'] . " County(ies) ,<br>" . date('d D,M Y h:i:A', strtotime($event['date_time'])) .'</span>';
        }
        $event['timediff'] = $timediff;
        array_push($evs, $event);
    }

    $result = ['count' => sizeof($evs), 'events' => $evs, 'nearest_event' => $nearest_event];
echo json_encode($result,DEFINED('JSON_INVALID_UTF8_IGNORE') ? JSON_INVALID_UTF8_IGNORE : 0);

}

function loadPostsJSON()
{
    global $exec;

    $posts = $exec->select_all('posts', '');
    $result = ['count' => sizeof($posts), 'events' => $posts];

    return json_encode($result,JSON_INVALID_UTF8_SUBSTITUTE);
}


if (isset($_POST['loadmembers'])) {
    echo loadMembersJSON();
}
if (isset($_GET['getMember'])) {
    echo getMember($_GET['key']);
}
if (isset($_POST['loadEvents'])) {
    echo loadEvents_JSON();
}
if (isset($_POST['loadPosts'])) {

    echo loadPostsJSON();
}

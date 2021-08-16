<?php
//#e4c6ae  #53717e

include_once __DIR__ . '/../executor.php';
require_once 'chats.php';

$exec = new ExecutingEngine();

error_reporting(E_ALL);
ini_set('display_errors', 1);
$json = file_get_contents('php://input');

$obj = json_decode($json, TRUE);
header('Content-Type: application/json');

function loadRecentChatMessages($data)
{
    global $exec;
    function sortFunctionRecents($a, $b)
    {
        return strtotime($b["createdAt"]) - strtotime($a["createdAt"]);
    }

    $thread = null;
    $participant_thread_node = 'participants/' . $data['currentUserID'] . '/threads';
    //echo 'particpant node '. $participant_thread_node ."<br>";
    //  $threads = $exec->fbgetLastChildren($participant_thread_node, 3);
    $threads = $exec->fbgetChildren($participant_thread_node);
    // echo 'getting '.$threadID;
    $recentMessages = [];
    $count = 1;
    if ($threads != null) {

        foreach ($threads as $threadkey => $thread) {

            $unread = 0;
            $messages = $exec->fbgetLastChildren('threads/' . $threadkey, 3);

            $lastMessage = array_values($messages)[0];

            foreach ($messages as $message) {
                //       echo strtotime($message['createdAt']) . "<br><br>";
                if (!isset($thread['lastOpened']) || (strtotime($message['createdAt']) > $thread['lastOpened'])) {
                    $unread++;
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
            $lastMessage['otherParticipant']['key'] = $otherParticipant;
            if (!isset($other['lastActivity'])) {
                $lastMessage['otherParticipant']['online'] = false;


            } else {
                if ((time() - $other['lastActivity']) < 300) {
                    $lastMessage['otherParticipant']['online'] = true;
                } else {
                    $lastMessage['otherParticipant']['online'] = false;
                }
            }

            array_push($recentMessages, $lastMessage);

            usort($recentMessages, "sortFunctionRecents");
            $count++;
        }

    }

    // return ($recentMessages);
    return array_slice($recentMessages, 0, 4);

}

function loadRecentChatGroupMessages($data)
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
            array_push($recentGroupMessages, $lastMessage);
        }

    }

}

return array_slice($recentGroupMessages, 0, 4);


}


function loadLatestContent($data)
{
    global $exec;
    $latestPost = $exec->run_fetch_query("SELECT posts.*, categories.category_name as category FROM posts RIGHT JOIN categories ON posts.post_category = categories.id where status='ACTIVE'   GROUP BY created_at order by created_at DESC LIMIT 6");
    $posts = [];
    foreach ($latestPost as $post) {
        $description = "";
        if ($post['post_content'] != "") {
            $description = $post['post_content'];
        } else {
            if ($post['post_subtitle'] != "") {
                $description = $post['post_subtitle'];
            } else {
                if ($post['category'] != "") {
                    $description = 'Category:' . $post['category'];
                } else {
                    $description = 'View Post for more Info';
                }
            }
        }
        $post['post_title'] = ucfirst($post['post_title']);
        $post['posted_on'] = date(' D jS M Y', strtotime($post['created_at']));
        $post['description'] = $description;
        array_push($posts, $post);
    }
    $latestEvent = $exec->run_fetch_query("SELECT events.* FROM events ORDER by date_time DESC LIMIT 1");

    $latestEvent[0]['date_time'] = date('D jS M Y g:ia', strtotime($latestEvent[0]['date_time']));
    $latestEvent[0]['date_time_iso'] = date(DateTime::ISO8601,strtotime(  $latestEvent[0]['date_time'] ));

  /*  $recentMessages = loadRecentChatMessages(['currentUserID' => $data['currentUserID']]);
    $recentGropChats = loadRecentChatGroupMessages(['currentUserID' => $data['currentUserID']]);*/

    $recentMessages = [];
    $recentGropChats = [];

    $result = ['posts' => $posts, 'event' => $latestEvent, 'chats' => $recentMessages, 'groupChats' => $recentGropChats];
    $exec->updateLastUserActivity($data['currentUserID']);
    return ($result);
}


if (isset($obj['loadLatestContent'])) {

    header('Content-Type: application/json');
    echo json_encode(loadLatestContent($obj),JSON_INVALID_UTF8_IGNORE);
}



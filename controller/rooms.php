<?php
//#e4c6ae  #53717e

include_once __DIR__ . '/executor.php';
include_once __DIR__ . '/executor_firestore.php';
$exec = new ExecutingEngine();
$execf = new ExecutingEngineFirestore();
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

function loadRooms($data)
{
    global $exec,$execf;


    $roomsData = $execf->getCollectionDocuments('rooms')->documents();
    $rooms = [];
    foreach ($roomsData as $rm) {
        $data = $rm->data();
        $data["id"] = $rm->id();
        array_push($rooms,$data);
    }

    $response = "";
if($rooms == null){
    return '<tr><td>No rooms added</td>';
}

    foreach ($rooms as $key => $room) {
        $members = $exec->fbgetChildren('rooms/' . $room['id'] . '/members');
        $status = 'ACTIVE';
        if (isset($room['status']) && $room['status'] == 'INACTIVE') {
            $status = 'INACTIVE';
        }
        //  if (($members != null && !in_array($currentUser, $members)) || $members == null) {
        if((is_array($room) && isset($room['name']))) {
            $roomURL = $exec->getURL() . "?fetchRoom&key=" . $room['id'];
            $room['memberCount'] = ($members != null ? sizeof($members) : 0);
            $fi = (isset($room["featured_image"] ) && $room["featured_image"] != "" ? APP_CONTROLLER_URL . $room["featured_image"] : null);
            $fi_link ='';
            if($fi!=null){
               $fi_link ='<a target="_blank" title="View Profile Picture" href="'.$fi.'"><i class="fa fa-image"></i></a>';
            }
            $room['key'] = $room['id'];
            $response .= '<tr title="'.ucfirst($room['description']).'">  ';
            $response .= '<td title="' . $room['name'] . '"><div class="widget-content p-0">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left flex2">
                                                <div class="widget-heading" style="overflow: hidden">' . $room['name'] . '</div>
                                                
                                            </div>
                                        </div>
                               </div>
                           </td>';
            $response .= '<td class="">' . $room['memberCount'] . '</td>';
            $response .= '<td class="">' . $status . '</td>';
            $response .= '<td class="">' . date('D jS M Y',strtotime($room["created"])) . '</td>  ';
            $response .= '  <td class="">
                            <button type="button" data-link="' . $roomURL . '" data-room-key="' . $room['id'] . '" class="btn btn-link btn_view_room" title="View Room" data-toggle="modal" data-target="#openRoomModal"><b class="fa fa-eye"></b></button>
                             </td></tr>';
        }
    }
    return $response;
}

function getRoom($key)
{
    global $exec,$execf;
    $room =   $execf->getSingleDocument('rooms/' . $key);
    if ($room != null) {
        $fi = (isset($room["featured_image"] ) && $room["featured_image"] != "" ? APP_CONTROLLER_URL . $room["featured_image"] : null);       $fi_link ='';
        if($fi!=null){
            $fi_link = $fi ;
        }
        $room['fi'] = $fi_link;
        $room['key'] = $key;
    }
    return $room;

}

function addRoom($data)
{
    global $execf,$exec;
    //$key = $exec->generateRandomString(12,'LETTERS');
    $key = 'room_'.strtolower(preg_replace('/[^a-zA-Z0-9-_\.]/','', $data['room_name']));

    $room =   $execf->getSingleDocument('rooms/' . $key);


    if($room){
        return ['status'=>'exists','message'=>'room already exists'];
    }
    $dataset = [];
    $dataset["created"] = date(DateTime::ISO8601);
    $dataset["name"] = $data['room_name'];
    $dataset["description"] = $data['room_description'];
    $ref = $execf->addDocumentCollection('rooms', $key, $dataset);
if($ref){
    return ['status'=>'success','message'=>'room added successfully'];
}else{
    return ['status'=>'error','message'=>'room not added  '];
}

}


function editRoom($data)
{

    global $exec,$execf;
$key = $data['room_key'];
    $featured_image_path = "";
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
    }else{
        $featured_image_path = "";
    }
$newData = [['path'=>'description', 'value'=>$data['room_description']],
    ['path'=>'name', 'value'=>$data['room_name']],
        ['path'=>'featured_image', 'value'=>$featured_image_path]
   ];

    $ref = $execf->updateSingleDocument('rooms' , $key, $newData);
    if($ref){
        return ['status'=>'success','message'=>'room edited successfully'];
    }else{
        return ['status'=>'error','message'=>'room not edited '];
    }



}

if (isset($_POST['loadRooms'])) {
    header('Content-Type: application/json');
    echo(loadRooms($_POST));
}

if (isset($_GET['fetchRoom'])) {
    echo json_encode(getRoom($_GET['key']));
}
if (isset($_POST['editRoom'])) {
    echo json_encode(editRoom($_POST));
}
if (isset($_POST['addRoom'])) {
    echo json_encode(addRoom($_POST));
}

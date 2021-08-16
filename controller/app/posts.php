<?php

//#e4c6ae  #53717e
include_once __DIR__ . '/../executor.php';

$exec = new ExecutingEngine();

ini_set("log_errors", 1);
ini_set("error_log", "/php-error.log");
error_log( "Hello, errors!" );


$json = file_get_contents('php://input');
$obj = json_decode($json, TRUE);
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

function retrievePostsjson($data)
{
    global $exec;
  //  $sql_where = (isset($data['category'])) ? ' WHERE post_category = "' . $data['category'] . '"' : '';
    $offset = isset($data['start_at'])  ? $data['start_at'] : 0;
    $limit = 5;
    $posts = [];

;    $allPosts = $exec->run_fetch_query("SELECT posts.*, categories.category_name as category FROM posts left JOIN categories ON posts.post_category = categories.id ORDER by created_at  ");
    foreach ($allPosts as $post){
        $description = "";
        if($post['post_content'] != ""){
            $description = $post['post_content'];
        }else{
            if($post['post_subtitle'] != ""){
                $description = $post['post_subtitle'];
            }else{
                if($post['category'] != ""){
                    $description = 'Category:' . $post['category'];
                }else{
                    $description = 'View Post for more Info';
                }
            }
        }
        $post['post_title'] = ucfirst( $post['post_title']);
        $post['posted_on'] = date('D jS M Y',strtotime($post['created_at']));
        $post['description'] = $description;
        if($post['pinned'] == true){
            array_unshift($posts,$post);
        }else{
            array_push($posts,$post);
        }

    }
    $response = ['stopped_at'=>$offset + $limit,'posts'=>$posts];
return $response;
}

function get_post($id){
    global $exec;
    return $exec->select_all('posts', 'WHERE id = '.$id);
}
function editPost($data){

    global $exec;
    $query_param_string = "";
    $count = 1;
    $id = $data['post_id'];
    unset($data['post_id']);  unset($data['editPost']); unset($data['proceed']);
    $values = [];
    foreach ($data as $key => $value) {
        $query_param_string .= $key . "='$value'";
        array_push($values, $value);
        $query_param_string .= $count == sizeof($data) ? "" : ",";
        $count++;
    }

    $sql = "UPDATE posts SET  $query_param_string WHERE id=$id";
//echo $sql;
 $ref = $exec->run_update_query($sql);
 if($ref){
     $response = array('response'=>'success');
 }else{
     $response =  array('response'=>'error');
 }
return $response;
}
function get_categories(){
    global $exec;
    return $exec->select_all('categories', '');
}



if (isset($obj['loadPostsjson'])) {
    header('Content-Type: application/json');
    echo json_encode(retrievePostsjson($obj),JSON_INVALID_UTF8_IGNORE);
}
if (isset($_GET['fetchpost'])) {
    echo json_encode(get_post($_GET['id']));
}
if (isset($_GET['getcategories'])) {
    echo json_encode(get_categories());
}
if (isset($_POST['editPost'])) {
    echo json_encode(editPost($_POST));
}
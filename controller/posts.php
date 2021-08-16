<?php
//#e4c6ae  #53717e
include_once __DIR__ . '/executor.php';

$exec = new ExecutingEngine();

error_reporting(E_ALL);
ini_set('display_errors', 1);

function retrievePosts($data)
{

    global $exec;
    $sql_where = (isset($data['category'])) ? ' WHERE post_category = "' . $data['category'] . '"' : '';
    $allPosts = $exec->run_fetch_query("SELECT posts.*, categories.category_name as category FROM posts LEFT JOIN categories ON posts.post_category = categories.id " . $sql_where . "ORDER by id");
    $response = '';

    if (!($allPosts)) {
        $response .= 'No Records Found';
    } else {
        foreach ($allPosts as $post) {

            $fulltitle = $post["post_title"];
            $postURL = $exec->getURL() . "?fetchpost&id=" . $post["id"];
            $title = ucfirst(strtolower(mb_strimwidth($fulltitle, 0, 25, "...")));
            $type = ucfirst($post['post_type']) == 'Image' ? '<b class="fa fa-image"></b>' :
                (ucfirst($post['post_type']) == 'Text' ? '<b class="fa fa-sticky-note"></b>' :
                    (ucfirst($post['post_type']) == 'Video' ? '<b class="fa fa-video"></b>' : ''));

            $response .= '<tr> <td class="text-right text-muted">' . $post["id"] . '</td>';
            $response .= '<td title="' . $fulltitle . '"><div class="widget-content p-0">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left flex2">
                                                <div class="widget-heading">' . $title . '</div>
                                                <div class="widget-subheading opacity-7">' . $post["post_subtitle"] . '</div>
                                            </div>
                                        </div>
                               </div>
                           </td>';
            $response .= '<td class="text-left">' . $post['category'] . '</td>';
            $response .= ' <td class="text-left">' . $type . '</b>  / ' . $post["status"] . '</td>';
            $response .= '<td class="text-left">' . $post["created_at"] . '</td>';

            $response .= '  <td class="text-center">
                            <button type="button" data-link="' . $postURL . '" data-post-id="' . $post["id"] . '" class="btn btn-link btn_view_post" title="View Post" data-toggle="modal" data-target="#openPostModal">
                            <b class="fa fa-eye"></b></button> </td></tr>';
        }
    }
    return $response;
}

function retrievePostsjson($data)
{
    global $exec;
    //  $sql_where = (isset($data['category'])) ? ' WHERE post_category = "' . $data['category'] . '"' : '';
    $allPosts = $exec->run_fetch_query("SELECT posts.*, categories.category_name as category FROM posts LEFT JOIN categories ON posts.post_category = categories.id ORDER by id");
    return (array)$allPosts;
}

function get_post($id)
{
    global $exec;
    return $exec->select_all('posts', 'WHERE id = ' . $id);
}

function editPost($data)
{

    global $exec;
    $query_param_string = "";
    $count = 1;
    $id = $data['post_id'];
    unset($data['post_id']);
    unset($data['editPost']);
    unset($data['proceed']);
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
    if ($ref) {
        $response = array('response' => 'success');
    } else {
        $response = array('response' => 'error');
    }
    return $response;
}

function addPost($data)
{

    global $exec;
    $query_param_string = "";
    $count = 1;
    $id = $data['post_id'];
    unset($data['post_id']);
    unset($data['addPost']);
    unset($data['proceed']);
    $values = [];


    $sql = "INSERT into posts (post_title,post_subtitle,post_category,post_type,post_media_link,post_content,status) values(?,?,?,?,?,?,?)";
    //print_r($data);
    $values  ['post_title'] = isset($data['post_title']) ? $data['post_title'] : "";
    $values  ['post_subtitle'] = isset($data['post_subtitle']) ? $data['post_subtitle'] : "";
    $values  ['post_category'] = isset($data['post_category']) ? $data['post_category'] : "";
    $values  ['post_type'] = isset($data['post_type']) ? $data['post_type'] : "";
    $values  ['post_media_link'] = isset($data['post_media_link']) ? $data['post_media_link'] : "";
    $values  ['post_content'] = isset($data['post_content']) ? $data['post_content'] : "";
    $values  ['status'] = 'ACTIVE';
 //   print_r($values);
    $ref = $exec->run_query($sql, [$values  ['post_title'], $values  ['post_subtitle'], $values  ['post_category'], $values  ['post_type'], $values  ['post_media_link'], $values  ['post_content'], $values  ['status']]);
    if ($ref) {
        $response = array('response' => 'success');
    } else {
        $response = array('response' => 'error');
    }
    return $response;
}
function addCategory($data)
{

    global $exec;

    global $exec;

    $categoryExist = $exec->select_all('categories', 'WHERE category_name = "' . $data  ['category_name'] .'"');
    if ($categoryExist) {
        return array('response' => 'exists');
    }

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


if (isset($_POST['loadPosts'])) {

    echo retrievePosts($_POST);
    // echo retrievePostsjson($_POST);
}
if (isset($_POST['loadPostsjson'])) {
    header('Content-Type: application/json');

    // echo json_encode(retrievePostsjson($_POST));
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
if (isset($_POST['addPost'])) {
    echo json_encode(addPost($_POST));
}
if (isset($_POST['addCategory'])) {
    echo json_encode(addCategory($_POST));
}
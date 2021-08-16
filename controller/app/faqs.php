<?php
//#e4c6ae  #53717e

include_once __DIR__ . '/../executor.php';

$exec = new ExecutingEngine();

error_reporting(E_ALL);
ini_set('display_errors', 1);
$json = file_get_contents('php://input');

$obj = json_decode($json, TRUE);


use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

function retrieveFAQsJSON($data)
{
    global $exec;

    $allfaqs = $exec->run_fetch_query("SELECT faqs.* FROM faqs");
    $response = ['success'=>true,'faqs'=>$allfaqs];
    return $response;
}



if (isset($obj['loadFAQs'])) {
    header('Content-Type: application/json');

     echo json_encode(retrieveFAQsJSON($obj));
}



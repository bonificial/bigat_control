<?php
ini_set('MAX_EXECUTION_TIME', '-1');
include_once __DIR__ . '/../config/config.php';
require __DIR__.'/../vendor/autoload.php';
$con = new connection();
$connector = $con->connect();
$pdoconnector = $con->pdoconnect();
isset($_SESSION) ?: @session_start();
use Kreait\Firebase\Factory;
use Google\Cloud\Firestore\FirestoreClient;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
class ExecutingEngineFirestore
{
    public $sql;
    public $echo_sql = false;
public $db ;
    public function __construct() {
        $this->db = $this->initializeFirestoreDB();
    }
    public function pdo_connect()
    {
        global $pdoconnector;
        return $pdoconnector;
    }

public function initializeFirestoreDB(){

    $factory = (new Factory)->withServiceAccount('./hwwk-bigat-firebase-adminsdk-1ag6o-46426111a5.json');
   $firestore = $factory->createFirestore();
    $firestoredb = $firestore->database();
    $this->db = $firestoredb;
    return $firestoredb;
}


public function getCollectionDocuments($collectionPath, $except=null){


    $collection = $this->db->collection($collectionPath);
    return $collection;
}
    public function getSingleDocument($documentPath, $except=null){
        $document = $this->db->document($documentPath)->snapshot()->data();
        return $document;
    }

    public function addDocumentCollection ($collectionPath,$key,$dataset) {
            $ref = $this->db->collection($collectionPath)->document($key)->create($dataset);
            return $ref;
    }

    public function updateSingleDocument ($collectionPath,$key,$dataset) {
        echo $collectionPath . " -- ". $key . " -- ". json_encode($dataset);
        $ref = $this->db->collection($collectionPath)->document($key)->update($dataset);
        return $ref;
    }
}



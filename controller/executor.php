<?php
ini_set('MAX_EXECUTION_TIME', '-1');
include_once __DIR__ . '/../config/config.php';
require __DIR__.'/../vendor/autoload.php';
$con = new connection();
$connector = $con->connect();
$pdoconnector = $con->pdoconnect();
isset($_SESSION) ?: @session_start();
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
class ExecutingEngine
{
    public $sql;
    public $echo_sql = false;


    public function pdo_connect()
    {
        global $pdoconnector;
        return $pdoconnector;
    }

public function initializeFirebaseDB(){


    $factory = (new Factory)->withDatabaseUri('https://hwwk-bigat.firebaseio.com/');
    $database = $factory->createDatabase();
    return $database;
}
public function fbgetChildren($node){
$database = $this->initializeFirebaseDB();
    $ref = $database->getReference($node ); // this is the root reference

    $value = $ref->getValue();
    return $value;
}
    public function fbgetLastChildren($node,$lastLimit)
    {
        $database = $this->initializeFirebaseDB();
        $ref = $database->getReference($node )->orderByKey()->limitToFirst(3);
        $value = $ref->getSnapshot()->getValue();

        return array_values($value);
    }
    public function fbgetFirstChild($node)
    {
        $database = $this->initializeFirebaseDB();
        try {
            $ref = $database->getReference($node)->orderByKey()->limitToLast(1);// this is the root reference
          $value = $ref->getSnapshot()->getValue();
           return array_values($value);
        } catch (Exception $e) {
            echo $e->getMessage();
        }


    }
    public function checkifexists_with_extra($table, $extra)
    {
        global $connector;
        $sql = "SELECT * FROM " . $table . $extra;
        // echo $sql;
        if ($this->echo_sql == true) {
            echo $sql . '<br>';
        }
        $result = mysqli_query($connector, $sql);
        if (!$result) {
            echo mysqli_error($connector);
        }
        $res = 0;
        $count = mysqli_num_rows($result);

        if ($count > 0) {
            $res = 1;
        }
        return $res;

    }
    public function removeEntry($node_with_field_to_update,$value){
        $database = $this->initializeFirebaseDB();
        $ref = $database->getReference($node_with_field_to_update )->remove($value);
        return $ref;
    }
    public function setEntry($node_with_field_to_update,$value){
        $database = $this->initializeFirebaseDB();
        $ref = $database->getReference($node_with_field_to_update )->set($value);
        return $ref;
    }
    public function updateEntryWithMerging($node_with_field_to_update, $value){
        $database = $this->initializeFirebaseDB();
        $ref = $database->getReference($node_with_field_to_update )->update($value);
        return $ref;
    }
    public function updateEntry($node_with_field_to_update,$value){
        $database = $this->initializeFirebaseDB();
        $ref = $database->getReference($node_with_field_to_update )->set($value);
       return $ref;
    }
    public function updateLastUserActivity($userID){
      //  echo 'updating to ' . 'participants/'.$userID.'/lastActivity';
       return  $this->updateEntry('participants/'.$userID.'/lastActivity',time());
    }
    function sortFunction( $a, $b ) {
        return strtotime($a["createdAt"]) - strtotime($b["createdAt"]);
    }
    public function addEntry($node_with_field_to_update,$value){
        $database = $this->initializeFirebaseDB();
        $ref = $database->getReference($node_with_field_to_update )->push($value);
        return $ref;

    }
public function dispatchNotification($title,$body,$deviceTokens){
    $factory = (new Factory)->withServiceAccount('./hwwk-bigat-80f0f8ed34b3.json');

    $messaging = $factory->createMessaging();

    $message = CloudMessage::new()
        ->withNotification(Notification::create($title, $body))
        ->withData(['key' => 'value']);;


    try {
        $res = $messaging->sendMulticast($message, $deviceTokens);
    //    print_r($res);
        return $res;

    } catch (\Kreait\Firebase\Exception\MessagingException $e) {
       // print_r($e);
        return false;
    } catch (\Kreait\Firebase\Exception\FirebaseException $e) {
       // print_r($e);
        return false;
    }

}

    public function sanitize($input)
    {

        $output = html_entity_decode(addslashes($input));
        return trim($output);
    }

    public function select_all($table, $extra)
    {

        $sql = "SELECT * FROM $table " . $extra;
        //echo $sql;
        $stmt = $this->pdo_connect()->prepare($sql);
        $res = $stmt->execute();
        $res = $stmt->fetchAll();
        $stmt = null;
        return $res;

    }
    public function run_fetch_query($query, $data = [])
    {


        $stmt = $this->pdo_connect()->prepare($query);
        $res = $stmt->execute($data);
        $res = $stmt->fetchAll();
        $stmt = null;
        return $res;

    }
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
    function update_entries($table, $columns_and_newvalues, $keycolumn, $keyvalue)
    {
        global $dh;
        if ($this->update($table, $columns_and_newvalues, $keycolumn, $keyvalue)) {

            return "success";
        } else {

            //  echo "User could not be added";

            return "error";
        }

    }
    public function update($table, $columns_and_newvalues, $keycolumn, $keyvalue)
    {
        global $connector;
        $done = false;
        //UPDATE table_name SET column1=value, column2=value2,... WHERE some_column=some_value
//echo " UPDATE " .$table. " SET($columns_and_newvalues)  WHERE " . $keycolumn . " = ". $keyvalue;
        // echo "<br>";
        $sql = " UPDATE " . $table . " SET $columns_and_newvalues   WHERE " . $keycolumn . " = " . $keyvalue;
        // echo $sql;
        if ($this->echo_sql == true) {
            echo $sql . '<br>';
        }
        if (!mysqli_query($connector, $sql)) {
            $done = false;
            echo "<br>" . $sql . "<br>";
            echo mysqli_error($connector);
        } else {
            $done = true;
        }
        return $done;

    }
    public function run_update_query($query,$data = [])
    {


        $stmt = $this->pdo_connect()->prepare($query);
        $res = $stmt->execute($data);

        $stmt = null;
        return $res;

    }
    public function run_query($query,$data = [])
    {


        $stmt = $this->pdo_connect()->prepare($query);
        $res = $stmt->execute($data);

        $stmt = null;
        return $res;

    }

    public function getURL()
    {
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
            $url = "https://";
        else
            if($_SERVER['HTTP_HOST'] == 'localhost'){
                $url = "http://";
            }else{
                $url = "https://";
            }
        // Append the host(domain name, ip) to the URL.
        $url.= $_SERVER['HTTP_HOST'];

        // Append the requested resource location to the URL
        $url.= $_SERVER['REQUEST_URI'];

        return $url;
    }
    public function get_user_by_username($value)
    {
        $sql = "SELECT * FROM users WHERE email=? or username=?";

        $stmt = $this->pdo_connect()->prepare($sql);
        $stmt->execute([$value, $value]);
        return $stmt->fetch();
    }

    public function sanitize_trim($text)
    {
        return trim(html_entity_decode(addslashes($text)));
    }
    public function delete($table, $idcolumn, $id)
    {
        global $connector;

            $sql = "DELETE FROM " . $table . " WHERE " . $idcolumn . " = " . $id;
            // echo $sql;
            if ($this->echo_sql == true) {
                echo $sql . '<br>';
            }
            mysqli_query($connector, $sql);

            if (!mysqli_query($connector, $sql)) {
                $done = "Fail";
                echo mysqli_error($connector);
            } else {
                $done = "Success";
            }

        return $done;
    }
}
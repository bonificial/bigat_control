<?php
include_once __DIR__. '/constants.php';


Class connection
{


       private $hostname = HOSTNAME;
    private $username = USERID;
    private $password = PASSWORD;
    private $db = DATABASE;



    public $connLink;

    // protected 'connect()' method

    public function connect()
    {

        // establish connection
        $conn = $this->connLink = mysqli_connect($this->hostname, $this->username, $this->password, $this->db);
        if (!$conn) {

            //    throw new Exception('Error connecting to MySQL: '.mysql_error());
          //  echo 'Error connecting to MySQL: '.mysql_error();
            return 'conn-error';

        }else{
            return $conn;
        }

        // select database



    }

    public function pdoconnect(){


        $pdo = false;

        try {
            $dsn = "mysql:host=$this->hostname;dbname=$this->db";
            $pdo = new PDO($dsn,$this->username, $this->password);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          //  echo "Connected successfully";
        } catch(PDOException $e) {
          //  echo "Connection failed: " . $e->getMessage();
        }
        return $pdo;
    }


 
}

?>
<?php
require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

class Connection{
    // private $hostName = getenv('DB_HOST');
    // private $userName = getenv('DB_USERNAME');
    // private $password = getenv('DB_PASSWORD');
    // private $collection = getenv('DB_COLLECTION');
    private $hostName = "localhost";
    private $userName = "nobelium24";
    private $password = "oluwatobi";
    private $collection = "socialmediaproject";
    private $conn;

    public function __construct(){
        $this->connect();
    }

    public function getConnection(){
        return $this->conn;
    }

    private function connect(){
        $this->conn = new mysqli($this->hostName, $this->userName, $this->password, $this->collection);
        if($this->conn->connect_error){
            die("Connection failed: " . $this->conn->connect_error);
        }else{
            // echo "Connection successful";
        }
    }
}

?>
<?php

require __DIR__.'/../vendor/autoload.php';

$Loader = new josegonzalez\Dotenv\Loader(__DIR__.'/../.env');

// Parse the .env file
$Loader->parse();

// Send the parsed .env file to the $_ENV variable
$Loader->toEnv();

class Database{

    private $host;
    private $user;
    private $password;
    private $database;

    public function __construct() {
        $this->host = $_ENV['server_name'];
        $this->user = $_ENV['user_name'];
        $this->password = $_ENV['db_password'];
        $this->database = $_ENV['db_name'];
    }

    public function getConnection(){

        try{
            $conn = new PDO('mysql:host='.$this->host.';dbname='.$this->database.';charset=utf8', $this->user, $this->password);
            $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            return $conn;

        }catch(PDOException $error){
            echo "connection failed".$error->getMessage(); //get error message
        }
    }
}
?>

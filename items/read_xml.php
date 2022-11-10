<?php
header("Access-Control-Allow-Origin: *");
header('Content-type: text/xml');

//xml paser
use XMLParser\XMLParser;

include_once '../config/Database.php';
include_once '../class/Items.php';

$database = new Database();
$db = $database->getConnection();

$items = new Items($db);

//check for auth key 'api-key in header'
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['HTTP_API_KEY']) && $_SERVER['HTTP_API_KEY'] != ''){

    $items->key = $_SERVER['HTTP_API_KEY'];

    $auth = $items->auth();

    //authenticate key
    if($auth){

        $items->id = (isset($_GET['id']) && $_GET['id']) ? $_GET['id'] : '0';

        $result = $items->read();

        //get data
        if($result){
            http_response_code(200);

            $xml = XMLParser::encode($result, 'response');
            echo $xml->asXML();

        }else{
            http_response_code(404);
            $message = array("message" => "No item found.");
            $xml = XMLParser::encode($message, 'response');
            echo $xml->asXML();

        }
    }else{
        http_response_code(401);
        $message = array("message" => "authentication failed.");
        $xml = XMLParser::encode($message, 'response');
        echo $xml->asXML();

    }
}else{
    http_response_code(401);
    $message = array("message" => "Error!!! Input API key/Check request method.");
    $xml = XMLParser::encode($message, 'response');
    echo $xml->asXML();

}


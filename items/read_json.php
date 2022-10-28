<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

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
            echo json_encode($result, JSON_PRETTY_PRINT);
        }else{
            http_response_code(404);
            echo json_encode(
                array("message" => "No item found.")
            );
        }
    }else{
       echo json_encode(
            array("message" => "authentication failed.")
        );
    }
}else{
    echo json_encode(
        array("message" => "Error!!! Input API key/Check request method.")
    );
}


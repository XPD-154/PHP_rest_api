<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

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

        $data = json_decode(file_get_contents("php://input"));

        if(!empty($data->id) &&
           !empty($data->user_ip_address) &&
           !empty($data->user_agent) &&
           !empty($data->page_url) &&
           !empty($data->referrer_url) &&
           !empty($data->user) &&
           !empty($data->message) &&
           !empty($data->project)){

            $items->id = $data->id;
        	$items->user_ip_address = $data->user_ip_address;
            $items->user_agent = $data->user_agent;
            $items->page_url = $data->page_url;
            $items->referrer_url = $data->referrer_url;
            $items->user = $data->user;
            $items->message = $data->message;
            $items->project = $data->project;


        	if($items->update()){
        		http_response_code(200);
        		echo json_encode(array("message" => "Item was updated."));
        	}else{
        		http_response_code(503);
        		echo json_encode(array("message" => "Unable to update items."));
        	}

        } else {
        	http_response_code(404);
            echo json_encode(array("message" => "Error!!! Unable to update items. Data is incomplete."));
        }

    }else{
        http_response_code(401);
        echo json_encode(
            array("message" => "authentication failed.")
        );
    }

}else{
    http_response_code(401);
    echo json_encode(
        array("message" => "Error!!! Input API key/Check request method")
    );
}

/*
Nature of Data
{
"id": "61",
"name": "Usha Sewing Automatic Machine",
"description": "its best machine",
"price":"90000",
"category_id":"6",
"created": "2019-11-09 04:30:00"
}
*/
?>

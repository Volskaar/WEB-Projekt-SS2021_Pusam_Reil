<?php
include("businesslogic/simpleLogic.php");

//declare and set param and method variables used in handlerequest method from logic.php
$param = "";
$method = "";

if(isset($_GET["method"])){
    $method = $_GET["method"];
}
else{
    false;
}

//create new logic object and handle request
$logic = new SimpleLogic();
$result = $logic->handleRequest($method, $param);

if ($result == null) {
    response("GET", 400, null);
} else {
    response("GET", 200, $result);
}

//response function
function response($method, $httpStatus, $data)
{
    header('Content-Type: application/json');
    switch ($method) {
        case "GET":
            http_response_code($httpStatus);
            echo (json_encode($data));
            break;
        default:
            http_response_code(405);
            echo ("Method not supported yet!");
    }
}
?>
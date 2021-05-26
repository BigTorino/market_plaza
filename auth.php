<?php

    require_once 'class/auth.class.php';
    require_once 'class/response.class.php';

    $_auth = new auth;
    $_response = new response;

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $postBody = file_get_contents("php://input");
        $dataArray = $_auth->sign_in($postBody);
        header('Content-type: application/json');
        if(isset($dataArray)){
            http_response_code(200);
            echo $dataArray;
        }else{
            echo "algo anda mal";
        }
    }else{
        header('Content-Type: application/json');
        $dataArray = $_respuestas->error_405();
        echo $dataArray;
    }

?>
<?php

    require_once 'class/Auth.class.php';
    require_once 'class/Response.class.php';

    $_auth = new Auth;
    $_response = new Response;

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
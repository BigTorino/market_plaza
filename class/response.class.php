<?php

    class response{

        private $response = [
            "status" => "",
            "msg" => "",
            "data" => array()
        ];

        // Recibe el array de los datos y envia una respuesta positiva en formato json
        public function success($array, $message = "ok."){
            $this->response["status"] = "200";
            $this->response["msg"] = $message;
            $this->response["data"] = $array;
            return json_encode($this->response);
        }

        // Recibe un array con los datos faltantes del campo y retorna el error en formato json
        public function error_400($array, $message = "Incomplete data."){
            $this->response["status"] = "400";
            $this->response["msg"] =  $message;
            $this->response["data"] = $array;
            return json_encode($this->response);
        }

        public function error_405($message = "Incompatible method ."){
            $this->response["status"] = "405";
            $this->response["msg"] = $message;
            $this->response["data"] = [];
            return json_encode($this->response);
        }

        public function error_200($message = "Invalid Data."){
            $this->response["status"] = "200";
            $this->response["msg"] = $message;
            return json_encode($this->response);
        }

        public function error_500($message = "Internal Server Error."){
            $this->response["status"] = "500";
            $this->response["msg"] = $message;
            return json_encode($this->response);
        }

    }

?>
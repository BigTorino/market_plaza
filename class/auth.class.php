<?php

    require_once 'connection/Connection.php';
    require_once 'Response.class.php';

    class Auth {

        private $_response;
        private $_connection;

        function __construct(){
            $this->_response = new response;
            $this->_connection = new connection;
        }

        public function sign_in($json){
            $data = json_decode($json, true);
            if(!isset($data['user']) || !isset($data['password']) || !isset($data['tranPayId'])){
                return $this->_response->error_400([], "user, password or tranPayId incomplete or empty.");
            }else{
                $user = $data['user'];
                $password = $data['password'];
                $password = $this->_connection->encrypt($password);
                $tranPayId = $data['tranPayId'];
                $date = date("y-m-d M:i");
                $state = "Active";
                $query = "INSERT INTO users_api (user,password,tranPayId,inclusionDate,state) VALUES('$user','$password','$tranPayId','$date','$state')";
                $res = $this->_connection->queryData($query);
                if($res >= 1){
                    $query = "SELECT id FROM users_api WHERE user = '$user'";
                    $res = $this->_connection->getData($query)["data"];
                    $token = $this->insertToken($res[0]["id"]);
                    return $this->_response->success(["token" => $token], "User '$user' successfully registered.");
                }else{
                    return $this->_response->error_500();
                }
            }
        }

        public function log_in($json){
            $data = json_decode($json, true);
            $res = array();
            if(!isset($data['user'])){
                array_push($res, "user");
            }
            if(!isset($datos['password'])){
                array_push($res, "password");
            }
            if(!empty($res)){
                return $this->_response->error_400($res);
            }else{
                $user = $data['user'];
                $password = $data['password'];
                $password = $this->_connection->encrypt($password);
                $data = $this->getUserData($user);
                if($data){
                    if($password == $data[0]['password']){
                        if($data[0]['state'] == "Active"){
                            // Registro
                            $token = $this->confirmToken($data[0]['id']);
                            if($token){
                                return $this->_response->success(["token" => $token], "Welcome " . $data[0]['user']);
                            }else{
                                return $this->_response->error_500("The token is not registered.");
                            }
                        }else{
                            return $this->_response->error_200("The user $user is inactive.");
                        }
                    }else{
                        return $this->_response->error_200("Invalid password.");
                    }
                }else{
                    return $this->_response->error_200("The user $user does not exist.");
                }
            }
        }

        private function getUserData($user){
            $query = "SELECT id,user,password,state FROM users_api WHERE user = '$user'";
            $data = $this->_connection->getData($query)["data"];
            if(isset($data[0]['id'])){
                return $data;
            }else{
                return 0;
            }
        }

        private function confirmToken($userId){
            $date = date("y-m-d M:i");
            $query = "SELECT token,state FROM tokens WHERE '$date' < limitDate AND userId = '$userId'";
            $token = $this->_connection->getData($query);
            if($token["rows"] < 1){
                $query = "UPDATE tokens SET status = 'Inactive' WHERE userId = '$userId'";
                $saveData = $this->_connection->queryData($query);
                return false;
            }else{
                return $token["data"];
            }
        }

        private function insertToken($userId){
            $val = true;
            $token = bin2hex(openssl_random_pseudo_bytes(16,$val));
            $date = date("y-m-d M:i");
            $limitDate = date("y-m-d M:i",strtotime($date."+ 1 month"));
            $state = "Actvive";
            $query = "INSERT INTO tokens (userId,token,status,limitDate) VALUES('$userId','$token','$state','$limitDate')";
            $check = $this->_connection->queryData($query);
            if($check){
                return $token;
            }else{
                return false;
            }
        }
    }

?>
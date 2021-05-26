<?php
/*
    //$id = $_GET['id'];

    try {
        $conexion = new PDO('mysql:host=localhost;dbname=marketplaza', 'root', '');
        //echo 'Concexion OK <br/> <br/>';

        // Metodo prepare statements ****

        $statement = $conexion->prepare("SELECT * FROM users_api"); // WHERE id = :id
        // se ejecuta el statement
        $statement->execute(
        //    array(':id' => $id) //se prepara la variable id de esta forma se pueden pasar parametros externos
        //    este tipo de variable se llama 'Placeholder'
        );

        $resultados = $statement->fetchall();
        //fetch(); devuelve un dato
        //fetchall(); devuelve todos los datos
        print_r($resultados);

        //para agregar contenido a al base de datos
        //$statement = $conexion->prepare('INSERT INTO usuarios VALUES(null, "Maria")');
        //$statement->execute();

        // Metodo query ****

        //echo '<br/>Metodo Query <br/>';
        //$resultados = $conexion->query("SELECT * FROM usuarios");
        //foreach ($resultados as $fila) {
        //    echo $fila['Nombre'] . '<br/>';
        //}

    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
*/

    $conexion = new PDO('mysql:host=localhost;dbname=marketplaza', 'root', '');

    $get  = "id,user,password";

    $con = array(
        "user" => "usuario2@gmail.com",
        "password" => "e10adc3949ba59abbe56e057f20f883e"
    );

    $conVal = array(
        "user" => "=",
        "password" => "="
    );

    $where = true;

    $query = "SELECT " . $get . " FROM users_api ";
    if($where){
        $query = $query . "WHERE";
        $keys = array_keys($con);
        foreach($con as $key => $value) { 
            if ($key != end($keys)) {
                $query = $query . " " . $key . " ". $conVal[$key] . " :" . $key . " AND";
                $key = ":" . $key;
            } else {
                $query = $query . " " . $key . " ". $conVal[$key] . " :" . $key;
                $key = ":" . $key;
            }   
        }
    }
    $stmt = $conexion->prepare($query);
    if($where){
        $stmt->execute($con);
    }else{
        $stmt->execute();
    }
    print_r($query);
    echo "<br>";
    print_r($stmt->fetchAll());

?>
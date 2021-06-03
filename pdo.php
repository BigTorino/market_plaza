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

    $table  = "tokens";

    $cols = array(
        "userId",
        "token",
        "status",
        "limitDate"

    );

    $values = array(
        "userId" => "1",
        "token" => "312321qwe1eqe",
        "status" => "Active",
        "limitDate" => "y-m-d M:i"
    );

    $query = "INSERT INTO " . $table . " (";
            //$keys = array_keys($cols);
            foreach ($cols as $value) {
                if($value == end($cols)){
                    $query = $query . $value . ") VALUES(";
                }else{
                    $query = $query . $value . ",";
                }
            }
            //$keyV = array_keys($values);
            foreach ($values as $key => $value) {
                if($value == end($values)){
                    $query = $query . ":". $key . ")";
                }else{
                    $query = $query .":". $key . ",";
                }
            }

    print_r($query);
    echo "<br>";
    

?>
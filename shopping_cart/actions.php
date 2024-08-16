<?php
include "connection.php";
extract ($_POST);
$fecha = date("Y-m-d");
$hora = date("h:i:s A");

// CASES
switch ($oculto) {
    // CASE 1 - INSERTAR REGISTROS EN BASE DE DATOS
    case 1:
        $query = "INSERT INTO users (email, nick, password, status) VALUES ('$email','$subject', MD5('$password'),'0')";
    // EJECUTAR LA CONSULTA
    $result= pg_query($conn, $query);
    break;

    case 2:
        // en el formulario se agrega enctype="multipart/form-data" ya que se estan agregando files 
        // $_FILES variable global que acepta el name y otros parametros
        $file = $_FILES['product_img']['tmp_name'];
        $type = $_FILES['product_img']['type'];

        // verificacion de archivo valido - png, gif, jpg, jpeg
        if (strpos($type, "gif") || strpos($type, "jpeg") || strpos($type, "jpg") || strpos($type, "png")) {
            // para saber cual fue la ultima imagen en base al id
            $sql = "SELECT max(id_products) FROM products";
            // EJECUTAR LA CONSULTA
            $result = pg_query($conn, $sql);
            $row = pg_fetch_array($result);
            // trnasformar a arreglo 


            if (isset($row['id_products'])) {
                $max = $row[0] + 1;
            } else {
                $max = 1;
            };

            $extension = getimagesize($file);

            // getimagesize($file); funcion que devuelve la extension del archivo
            if ($extension[2] == 1) {
                $max = $max.".gif";
            };

            if ($extension[2] == 2) {
                $max = $max.".jpg";
            };

            if ($extension[2] == 3) {
                $max = $max.".png";
            };

            move_uploaded_file($file, '../../../php3_fotos/'.$max);
            $ima ='../../../php3_fotos/'.$max;
        };

        // no se agrega el id porque es autoincremento
        $insert_into_database = "INSERT INTO products(name_products, description, image, price, amount, status) 
        VALUES('$product', '$description', '$ima', '$product_price', '$inventory', '0')";
        pg_query($conn, $insert_into_database);

        break;

    case 3:
        $query = "INSERT INTO cart (id_users_cart, id_products_cart, amount_cart, date_cart, hour_cart, status) 
        VALUES ('1', '$indice', '$product_quanity', '$fecha', '$hora', '0')";
        // EJECUTAR LA CONSULTA
        $consulta = pg_query($conn, $query);
        break;
        // RESULT: 3, 3, 1, 8, 3, 2024-08-14, 11:00:00 PM, 0


    // para LOG IN
    case 4:
        // sentencia para permitir el login del user con el nick o correo PERO que coincida con la contraseña.
        $sql = "SELECT id, email, nick FROM users WHERE password = MD5('$password') AND nick ='$pase' or email = '$pase'";
      
        // (nick='$pase' or mail='$pase') -> $pase name en el LOG IN -> para que tome ambas entradas del input (tanto mail como user)
      
      
        // consulta que va a la base de datos
        $query = pg_query($conn, $sql);
      
        if ($ver = pg_fetch_array($query)) {
            session_start();
            $_SESSION["quien"] = $ver[0];
            $_SESSION["nick"] = $ver[2];
            $_SESSION["correo"] = $ver[1];
            header("location:index.php");   
      
        } else { 
            header("location:login.php?respuesta=5");
        }
        break;
      
    
    // para cerrar sesion
    case 5:
      
        // se coloca session_start() para trabajar con SESIONES COMO TAL 
        session_start();
      
        // se vacia la variable $SESSION 
        session_unset();
      
        // se elimina la sesion
        session_destroy();
      
        // lo mandamos a pagina principal
        header("location:index.php");
        break;
}

?>


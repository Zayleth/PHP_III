<?php
include "connection.php";
extract ($_POST);

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
        $file = $_FILES['product_img'] ['tmp_name'];
        $type = $_FILES['product_img'] ['type'];

        // verificacion de archivo valido - png, gif, jpg, jpeg
        if (strpos($type, "gif") || strpos($type, "jpeg") || strpos($type, "jpg") || strpos($type, "png")) {
            // para saber cual fue la ultima imagen en base al id
            $sql = "SELECT max(id_products) FROM products";
            // EJECUTAR LA CONSULTA
            $result = pg_query($conn, $sql);
            // trnasformar a arreglo 
            $row = pg_fetch_assoc($result);
            $max = $row[0] + 1;
            $extension = getimagesize($file);

            // getimagesize($file); funcion que devuelve la extension del archivo
            if ($extension[2] == 1) {
                $max = $max.".gif";
            }

            if ($extension[2] == 2) {
                $max = $max.".jpg";
            }

            if ($extension[2] == 3) {
                $max = $max.".png";
            }

            move_uploaded_file($archivo, '../php3_fotos'.$max);
        } 

}
?>
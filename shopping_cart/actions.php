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
        VALUES ('1', '$indice', 'product_quanity', '$fecha', '$hora', '0')";
        // EJECUTAR LA CONSULTA
        $consulta = pg_query($conn, $query);
        break;

}

?>


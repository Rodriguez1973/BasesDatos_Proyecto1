<?php
/*
 * Created on : 24 ene 2023, 11:19:53
 * Author     : José A. Rodríguez López
 */
$mensaje = ""; //Mensaje en la acción de borrado o actualización.
$flag_borrado = false; //Señala si está en una operación de borrado.
//Consultar un producto.
if (isset($_POST['consultar'])) {
    $datos = unserialize($_POST['producto']);
    $nombreProducto = $datos['nombreProducto'];
    $id_producto = $datos['idProducto'];
//Actualizar la cantidad de producto.
} else if (isset($_POST['actualizar'])) {
    $datos = unserialize($_POST['actualizar']);
    $nameUnidades = $datos['nameUnidades'];
    $unidades = $_POST[$nameUnidades];
    $id_tienda = $datos['idTienda'];
    $id_producto = $datos['idProducto'];
    $nombreProducto = $datos['nombreProducto'];

    //Realiza la conexión a la base de datos.
    require_once './ConexionBaseDatos.php';
    //Si ha sido posible la conexión.
    if (!$conexionBD->connect_error) {
        $consulta = "update stocks set unidades=" . $unidades . " where tienda=" . $id_tienda . 
                " and producto=" . $id_producto . ";";
        //Consulta a la base de datos. Actualiza en númedero de unidades de la tabla stocks.
        $resultado = $conexionBD->query($consulta);
        if (!$resultado) {
            die("Consulta inválida: " . mysql_error());
        } else {
            if ($conexionBD->affected_rows == 1) {
                $mensaje = "Se ha actualizado el registro en la base de datos.";
            } else {
                $mensaje = "No se ha actualizado ningún registro.";
            }
        }
    }
//Borrar un registro del producto.
} else if (isset($_POST['borrar'])) {
    $datos = unserialize($_POST['borrar']);
    $id_tienda = $datos['idTienda'];
    $id_producto = $datos['idProducto'];
    $nombreProducto = $datos['nombreProducto'];

    //Realiza la conexión a la base de datos.
    require_once './ConexionBaseDatos.php';
    //Si ha sido posible la conexión.
    if (!$conexionBD->connect_error) {
        $consulta = "delete from stocks where tienda=" . $id_tienda . " and producto=" . 
                $id_producto . ";";
        //Consulta a la base de datos. Actualiza en númedero de unidades de la tabla stocks.
        $resultado = $conexionBD->query($consulta);
        if (!$resultado) {
            die("Consulta inválida: " . mysql_error());
        } else {
            if ($conexionBD->affected_rows == 1) {
                $mensaje = "Se ha eliminado el registro en la base de datos.";
                $flag_borrado = true;
            } else {
                $mensaje = "No ha sido posible realizar la acción de borrado.";
            }
        }
    }
}
?>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Consultar stock</title>
        <link rel="stylesheet" href="./css/estilos.css"/>
    </head>
    <body>
        <h1>Consultar stock</h1>
        <form name="formulario" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <?php
            //Realiza la conexión a la base de datos.
            require_once './ConexionBaseDatos.php';
            //Si ha sido posible la conexión.
            if (!$conexionBD->connect_error) {
                //Consulta a la base de datos. Selecciona el nombre e id de la tabla productos.
                $resultado = $conexionBD->query("select t.nombre as nombreTienda, t.id as idTienda, "
                        . "s.unidades as unidades from productos p inner join stocks s on p.id="
                        . "s.producto inner join tiendas t on s.tienda=t.id where p.id=" . 
                        $id_producto . ";");
                $producto = $resultado->fetch_object();
                echo "<p id='unidadesProducto'>Unidades del producto: " . $nombreProducto . "<p>";
                //La consulta a devuelto datos.
                if ($producto != null) {
                    //Genera la tabla de datos.
                    echo "<table>";
                    echo "<thead><tr>";
                    echo "<th>Tienda</th>";
                    echo "<th>Unidades</th>";
                    echo "<th>Acciones</th>";
                    echo "</tr></thead>";
                    echo "<tbody>";
                    $cont = 0;    //Contador de número de registro. 
                    while ($producto != null) {
                        echo "<tr><td>" . $producto->nombreTienda . "</td><td><input type='number' "
                        . " name='registro" . $cont . "' min='0' max='2147483647' required value='" . 
                                $producto->unidades . "'</td><td>";
                        $datos = ["nombreProducto" => $nombreProducto, "idProducto" => $id_producto,
                            "idTienda" => $producto->idTienda, "nameUnidades" => "registro" . $cont];
                        echo "<button type='submit' name='actualizar' value='" . serialize($datos) .
                                "'>Actualizar";
                        $datos = ["nombreProducto" => $nombreProducto, "idProducto" => $id_producto,
                            "idTienda" => $producto->idTienda];
                        echo "<button type='submit' name='borrar' value='" . serialize($datos) . "'>"
                                . "Borrar</td></tr>";
                        $cont++;
                        $producto = $resultado->fetch_object();
                    }
                    echo "</tbody></table>";
                } else {
                    if (!$flag_borrado) {
                        $mensaje = "No existen registros del producto seleccionado.";
                    }
                }
                $conexionBD->close();   //Cierra la conexión a la base de datos.
                echo "<p id='mensaje'>" . $mensaje . "</p>"; //Muestra el mensaje.
                $flag_borrado = false;
            }
            ?>
        </form>
        <button type='button' id='otro' name='otro' value=''><a href="./stock.php">Consultar otro 
                producto</a>
    </body>
</html>

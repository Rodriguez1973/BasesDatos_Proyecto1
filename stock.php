<!DOCTYPE html>
<!--
    Created on : 20 ene 2023, 11:19:53
    Author     : José A. Rodríguez López
-->

<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Elegir producto</title>
    </head>
    <body>
        <h1>Consultar stock</h1>
        <label for="producto">Elige un producto</label><br>
        <form name="formulario" action="procesa.php" method="POST">
            <link rel="stylesheet" href="./css/estilos.css"/>
            <?php
            //Realiza la conexión a la base de datos.
            require_once './ConexionBaseDatos.php';
            //Si ha sido posible la conexión.
            if (!$conexionBD->connect_error) {
                //Consulta a la base de datos. Selecciona el nombre e id de la tabla productos.
                $resultado = $conexionBD->query("select nombre, id from productos;");
                $producto = $resultado->fetch_object();
                //Genera la select con los resultados de la consulta, en el value de las option se encuentra el id del producto.
                echo "<select id='producto' name='producto' id=`producto'>";
                while ($producto != null) {
                    $datos = ["idProducto" => $producto->id, "nombreProducto" => $producto->nombre];
                    echo "<option value='" . serialize($datos) . "'>" . $producto->nombre . "</option>";
                    $producto = $resultado->fetch_object();
                }
                echo "</select>";
                $conexionBD->close();   //Cierra la conexión a la base de datos.
            }
            ?>
            <button type="submit" id="consultar" name="consultar" value="">Consultar Stock
        </form>
    </body>
</html>


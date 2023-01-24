<?php
/*
 * Created on : 20 ene 2023, 11:19:53
 * Author     : José A. Rodríguez López
 */

if (isset($_POST['consultar'])) {
    $id_producto = $_POST['producto'];
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
            <form name="formulario" action=". <?php echo $_SERVER['PHP_SELF']; ?> method="POST">
                <?php
                //Realiza la conexión a la base de datos.
                require_once './ConexionBaseDatos.php';
                //Si ha sido posible la conexión.
                if (!$conexionBD->connect_error) {
                    //Consulta a la base de datos. Selecciona el nombre e id de la tabla productos.
                    $resultado = $conexionBD->query("select t.nombre as nombre, s.unidades as unidades "
                            . "from stocks as s inner join tiendas as t on s.tienda=t.id where s.producto=" . $id_producto.";");
                    $producto = $resultado->fetch_object();
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
                        while ($producto != null) {
                            echo "<tr><td>" . $producto->nombre . "</td><td><input type='number' "
                            . " name='unidades' min='0' value='" . $producto->unidades . "'</td><td>"
                            . "<button type='submit' name='actualizar' value=''>Actualizar"
                            . "<button type='submit' name='borrar' value=''>Borrar"
                            . "</td></tr>";
                            $producto = $resultado->fetch_object();
                        }
                        echo "</tbody></table>";
                    }
                    $conexionBD->close();   //Cierra la conexión a la base de datos.
                }
                ?>
            </form>
            <button type='button' id='otro' name='otro' value=''><a href="./stock.php">Consultar otro producto</a>
        <body>
    </html>
<?php }

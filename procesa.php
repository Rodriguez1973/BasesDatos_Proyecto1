<?php
/*
 * Created on : 20 ene 2023, 11:19:53
 * Author     : José A. Rodríguez López
 */
$act_correcta=false;   //Bandera que controla que se ha realizado una actualización

if (isset($_POST['consultar'])) {
    $id_producto = $_POST['producto'];
} else if (isset($_POST['actualizar'])) {
    $datos = unserialize($_POST['actualizar']);
    $nameUnidades=$datos['nameUnidades'];
    $unidades=$_POST[$nameUnidades];
    $id_tienda=$datos['idTienda'];
    $id_producto=$datos['idProducto'];
    
    //Realiza la conexión a la base de datos.
    require_once './ConexionBaseDatos.php';
    //Si ha sido posible la conexión.
    if (!$conexionBD->connect_error) {
        $consulta="update stocks set unidades=".$unidades." where tienda=".$id_tienda." and producto=".$id_producto.";";
        //Consulta a la base de datos. Actualiza en númedero de unidades de la tabla stocks.
        $resultado = $conexionBD->query($consulta);
        if(!$resultado){
            die("Consulta inválida: ".mysql_error());
        }else{
            if($conexionBD->affected_rows>=1){
                echo "Se ha actualizado el registro en la base de datos";
            }else{
                echo  "No se ha actualizado ningún registro.";
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
                $resultado = $conexionBD->query("select p.id as idProducto, p.nombre as nombreProducto, t.nombre as "
                        . "nombreTienda, t.id as idTienda, s.unidades as unidades from productos p inner join stocks "
                        . "s on p.id=s.producto inner join tiendas t on s.tienda=t.id where p.id=" . $id_producto . ";");
                $producto = $resultado->fetch_object();
                //La consulta a devuelto datos.p
                if ($producto != null) {
                    echo "<p id='unidadesProducto'>Unidades del producto: " . $producto->nombreProducto . "<p>";
                    //Genera la tabla de datos.
                    echo "<table>";
                    echo "<thead><tr>";
                    echo "<th>Tienda</th>";
                    echo "<th>Unidades</th>";
                    echo "<th>Acciones</th>";
                    echo "</tr></thead>";
                    echo "<tbody>";
                    $cont=0;    //Contador de número de registro. 
                    while ($producto != null) {
                        echo "<tr><td>" . $producto->nombreTienda . "</td><td><input type='number' "
                        . " name='registro" . $cont . "' min='0' max='2147483647' value='" . $producto->unidades . "'</td><td>";
                        $datos = ["idProducto"=>$producto->idProducto, "idTienda"=>$producto->idTienda, "nameUnidades"=>"registro".$cont];
                        echo "<button type='submit' name='actualizar' value='" . serialize($datos) . "'>Actualizar"
                        . "<button type='submit' name='borrar' value='" . $producto->idTienda . "'>Borrar"
                        . "</td></tr>";
                        $cont++;
                        $producto = $resultado->fetch_object();
                    }
                    echo "</tbody></table>";
                }
                $conexionBD->close();   //Cierra la conexión a la base de datos.
            }
            ?>
        </form>
        <button type='button' id='otro' name='otro' value=''><a href="./stock.php">Consultar otro producto</a>
    </body>
</html>

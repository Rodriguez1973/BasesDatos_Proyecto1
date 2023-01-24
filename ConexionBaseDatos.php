<?php
$nombreServidor='localhost';
$usuario='gestor';
$contraseña='secreto';
$baseDatos='proyecto';

$conexionBD=mysqli_connect($nombreServidor,$usuario , $contraseña, $baseDatos);

if($conexionBD->connect_error){
    die('Error en la conexión: '. $conexionBD->connect_error);
    $conexionBD->close(); //Cerramos la conexión.
}
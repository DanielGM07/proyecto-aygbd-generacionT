<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "matriz_resp_prueba";
$port = "3306";

// Creo la conexion a la BDD
$conexion = new mysqli($servername, $username, $password, $dbname, $port);
$conexion->set_charset("utf8");
if ($conexion->connect_error) {
    die("Coneexion fallida : ".$conexion->connect_error);
}
?>
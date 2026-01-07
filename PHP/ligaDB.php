<?php

$server = "localhost";
$user = "root";
$pass = "";
$db = "lava";

$connect = mysqli_connect($server, $user, $pass, $db);

if (!$connect) {
    echo "<script>alert('Erro na ligação à base de dados');</script>";
    echo "erro". mysqli_connect_error();
}
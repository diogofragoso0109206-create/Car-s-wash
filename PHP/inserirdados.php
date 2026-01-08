<?php

$nome = $_POST['nome'];
$email = $_POST['email'];
$passwor = $_POST['passwor'];


include 'ligaDB.php';


$query = "INSERT INTO user (nome, email, passwor ) VALUES ('$nome', '$email', '$passwor')";

if (mysqli_query($connect, $query)) {
    echo "<script>alert('Registo efetuado com sucesso'); window.location='../index.html';</script>";
  
} else {
    echo "<script>alert('Erro ao efetuar registo'); window.location='../registaruser.html';</script>";
}

?>



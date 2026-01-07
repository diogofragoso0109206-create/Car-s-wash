<?php

$nome = $_POST['nome'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$messages = $_POST['messages'];


include 'ligaDB.php';

$query = "INSERT INTO emails (nome, email, phone, messages ) VALUES ('$nome', '$email', '$phone', '$messages')";

if (mysqli_query($connect, $query)) {
    echo "<script>alert('Registo efetuado com sucesso'); window.location='../registaruser.html';</script>";
} else {
    echo "<script>alert('Erro ao efetuar registo'); window.location='../registaruser.html';</script>";
}

?>
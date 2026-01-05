<?php
// send_email.php

// Se usas Composer
// require 'vendor/autoload.php';

// Se tens PHPMailer manualmente, ajusta o caminho
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Função simples para limpar input
function clean($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Recebe e valida POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? clean($_POST['name']) : '';
    $email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';
    $phone = isset($_POST['phone']) ? clean($_POST['phone']) : '';
    $message = isset($_POST['message']) ? clean($_POST['message']) : '';

    // Validações básicas
    if (empty($name) || empty($email) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Redireciona ou mostra erro simples
        echo 'Por favor preencha os campos obrigatórios corretamente.';
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        // Configuração SMTP - substitui pelos teus dados
        $mail->isSMTP();
        $mail->Host = 'smtp.exemplo.com';        // servidor SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'diogo.fragoso.0109206@gmail.com'; // teu utilizador SMTP
        $mail->Password = 'xprc sbtw hgkw olsi';          // tua senha SMTP
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // ou PHPMailer::ENCRYPTION_SMTPS
        $mail->Port = 587;                       // 587 para STARTTLS, 465 para SMTPS

        // Remetente e destinatário
        $mail->setFrom('no-reply@teudominio.com', 'Garcia CarWash');
        $mail->addAddress('contacto@teudominio.com', 'Garcia CarWash'); // onde queres receber os emails
        $mail->addReplyTo($email, $name);

        // Conteúdo do email
        $mail->isHTML(true);
        $mail->Subject = 'Novo contacto do site - ' . $name;
        $body  = "<h3>Novo contacto recebido</h3>";
        $body .= "<p><strong>Nome:</strong> {$name}</p>";
        $body .= "<p><strong>E-mail:</strong> {$email}</p>";
        $body .= "<p><strong>Telefone:</strong> {$phone}</p>";
        $body .= "<p><strong>Mensagem:</strong><br>" . nl2br($message) . "</p>";
        $mail->Body = $body;
        $mail->AltBody = "Novo contacto\n\nNome: {$name}\nEmail: {$email}\nTelefone: {$phone}\nMensagem:\n{$message}";

        $mail->send();

        // Redireciona para uma página de sucesso ou mostra mensagem
        header('Location: obrigado.html');
        exit;
    } catch (Exception $e) {
        // Em produção não mostres detalhes do erro ao utilizador
        error_log('Mailer Error: ' . $mail->ErrorInfo);
        echo 'Ocorreu um erro ao enviar a mensagem. Tenta novamente mais tarde.';
        exit;
    }
} else {
    echo 'Método inválido.';
    exit;
}

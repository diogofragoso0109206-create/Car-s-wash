<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

function clean($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? clean($_POST['name']) : '';
    $email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';
    $phone = isset($_POST['phone']) ? clean($_POST['phone']) : '';
    $message = isset($_POST['messages']) ? clean($_POST['messages']) : '';  // Note: campo POST é 'messages'

    // Validações básicas
    if (empty($name) || empty($email) || empty($phone) || empty($message) || 
        !filter_var($email, FILTER_VALIDATE_EMAIL) || 
        !preg_match('/^\d{9,}$/', $phone)) {  // Validação simples de telefone: apenas números, min 9 dígitos
        echo 'Por favor preencha os campos obrigatórios corretamente. O telefone deve conter apenas números e pelo menos 9 dígitos.';
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        // Configuração SMTP - use variáveis de ambiente em produção!
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';        // servidor SMTP
        $mail->SMTPAuth = true;
        $mail->Username = getenv('SMTP_USERNAME') ?: 'seu-email@gmail.com'; // Substitua ou use env
        $mail->Password = getenv('SMTP_PASSWORD') ?: 'sua-senha-app';      // Substitua ou use env
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Remetente e destinatário
        $mail->setFrom('no-reply@teudominio.com', 'Garcia CarWash');  // Use um email válido
        $mail->addAddress('diogofragoso206@gmail.com', 'Garcia CarWash');
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

        // Redireciona para uma página de sucesso
        header('Location: obrigado.html');
        exit;
    } catch (Exception $e) {
        // Em produção, não mostre detalhes do erro
        error_log('Mailer Error: ' . $mail->ErrorInfo);
        echo 'Ocorreu um erro ao enviar a mensagem. Tenta novamente mais tarde.';
        exit;
    }
} else {
    echo 'Método inválido.';
    exit;
}
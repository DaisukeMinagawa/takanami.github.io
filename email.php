<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
$config = require('config.php');

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !checkCsrfToken()) {
    setFeedback('不正なリクエストです。');
    redirectToForm();
    exit;
}

$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

if (empty($name) || empty($email) || empty($message)) {
    setFeedback("入力内容に誤りがあります。");
    redirectToForm();
    exit;
}

if (!sendEmail($name, $email, $message, $config['email'])) {
    setFeedback("メッセージの送信に失敗しました。");
    redirectToForm();
    exit;
}

setFeedback("メッセージは正常に送信されました。お問い合わせありがとうございました。");
redirectToForm();

function sendEmail($name, $email, $message, $emailConfig) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = $emailConfig['host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $emailConfig['username'];
        $mail->Password   = $emailConfig['password'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $emailConfig['port'];

        $mail->CharSet = 'UTF-8'; // 文字化けしないように文字セットを指定

        $mail->setFrom($emailConfig['from']['email'], $emailConfig['from']['name']);
        $mail->addAddress($emailConfig['addAddress']['email'], $emailConfig['addAddress']['name']);

        $mail->isHTML(false); // HTMLメールを無効に
        $mail->Subject = 'New contact from website';
        $mail->Body    = "Name: $name\nEmail: $email\nMessage: $message";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function redirectToForm() {
    header('Location: index.php');
    exit;
}

function setFeedback($message) {
    $_SESSION['feedback'] = $message;
}

function checkCsrfToken() {
    $token = $_POST['csrf_token'] ?? '';
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

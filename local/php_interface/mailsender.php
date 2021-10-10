<?php
class MailSender {
    /* данные для подкючения */
    private $default = array(
        'smtp_host'    => '127.0.0.1',
        'smtp_port'    => '25',
        'smtp_login'   => 'registration@vuchebe.com',
        'smtp_pass'    => 'adminregistration',
        'charset'      => 'utf-8',
        'from_name'    => 'My name',
        'from_email'   => 'registration@vuchebe.com',
        'email_errors' => 'registration@vuchebe.com',
    );

    /* функция отправки сообщений */
    public static function send($email, $subject, $message, $unsub, $message_id = '', $list_id = '', $headers = '', $type = 'html')
    {
        $config = self::$default;
        /* проверка существования заголовков */
        if(empty($headers)) {
            $headers = self::getHeaders($email, $subject, $type, $unsub, $message_id, $list_id);
        }
        /* добавление заголовков к сообщению */
        $message = $headers . $message;

        /* создание сокета для подключения к SMTP-серверу */
        if(!$socket = fsockopen($config['smtp_host'], $config['smtp_port'], $errno, $errstr, 30)) {
            echo $errno . "<br />" . $errstr;
            return false;
        }
        fputs($socket, "EHLO " . $config['smtp_host'] . "\r\n");
        fputs($socket, "AUTH LOGIN\r\n");
        fputs($socket, base64_encode($config['smtp_login']) . "\r\n");
        fputs($socket, base64_encode($config['smtp_pass']) . "\r\n");
        fputs($socket, "MAIL FROM: <" . $config['from_email'] . ">\r\n");
        fputs($socket, "RCPT TO: <" . $email['email'] . ">\r\n");
        fputs($socket, "DATA\r\n");
        fputs($socket, $message . "\r\n.\r\n");
        fputs($socket, "QUIT\r\n");
        fclose($socket);

        return true;
    }

    /* функция генерации заголовков */
    private static function getHeaders($email, $subject, $type, $unsub, $message_id, $list_id)
    {
        $config = self::$default;
        $result = '';
        $result = "Date: ".date("D, d M Y H:i:s") . " UT\r\n";
        $result .= "Subject: =?" . $config['charset'] . "?B?" . base64_encode($subject) . "=?=\r\n";
        $result .= "Reply-To: " . $config['from_name'] . " <" . $config['from_email'] . ">\r\n";
        $result .= "MIME-Version: 1.0\r\n";
        $result .= "Content-Type: text/" . $type . "; charset=\"" . $config['charset'] . "\"\r\n";
        $result .= "Content-Transfer-Encoding: 8bit\r\n";
        $result .= "From: " . $config['from_name'] . " <" . $config['from_email'] . ">\r\n";
        $result .= "To: " . $email['name'] . " <" . $email['email'] . ">\r\n";
        $result .= "Errors-To: <" . $config['email_errors']. ">\r\n";
        $result .= "X-Complaints-To: " . $config['email_errors'] . "\r\n";
        $result .= "List-Unsubscribe: <{$unsub}>\r\n";
        if(!empty($list_id)) $result .= "List-id: <" . $list_id . ">\r\n";
        $result .= "Precedence: bulk\r\n";

        return $result;
    }

}

$email = array(
    'email' => 'coregabe@yandex.ru',
    'name'  => 'Сергей',
);
$subject = 'Тестовое письмо';
$message = '<p>Это текст тестового письма.</p>';
$unsub = 'https://vuchebe.com/';

//MailSender::send($email, $subject, $message, $unsub);
?>
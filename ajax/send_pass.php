<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $USER;

$error = '';
$result = array();

ini_set( 'display_errors', 1 );
error_reporting( E_ALL );

$from 	 = "admin@vuchebe.com";
$to 	 = $_SESSION['USER_DATA']['EMAIL'];
$subject = "Ваш пароль vuchebe.com";
$message = "Пароль: " . $_SESSION['USER_DATA']['USER_PASS'] . "Пожалуйста, не отвечайте на это письмо. Связаться со службой поддержки vuchebe.com Вы можете через форму обратной связи.";

$headers = "From: " . $from . "\r\n";
$headers .= "Reply-To: ". $from . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=utf-8\r\n";

$message = '<html><body>';
$message .= 'Пароль: ' . $_SESSION['USER_DATA']['USER_PASS'];
$message .= '<br><br><hr><br>';
$message .= 'Пожалуйста, не отвечайте на это письмо.<br>';
$message .= 'Связаться со службой поддержки vuchebe.com Вы можете через <a href="https://vuchebe.com/company/feedback/" style="cursor: pointer; color: #ff471a;">форму обратной связи</a>.';
$message .= '</body></html>';

if(mail($to,$subject,$message, $headers))
	$data = array("status" => "success");
else
	$data = array("status" => "error");

die(json_encode($data));
?>
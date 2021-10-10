<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if($APPLICATION->CaptchaCheckCode($_REQUEST["captcha_word"], $_REQUEST["captcha_sid"])){

	$result['status'] = 'success';
	$result['message'] = 'Ok';

}else{

	$result['status'] = 'error';
	$result['message'] = 'Не правильный код картинки';
}

echo json_encode($result);
?>
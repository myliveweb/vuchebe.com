<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $USER;
global $DB;

$user = new CUser;
$fields = Array(
  	"PERSONAL_PHOTO" => CFile::MakeFileArray("/local/templates/vuchebe/images/user-1.png"),
);

if ($user->Update($USER->GetId(), $fields)) {

	$rsUser = CUser::GetByID($USER->GetId());
	$_SESSION['USER_DATA'] = $rsUser->Fetch();

	$result['status'] = 'success';
} else {
	$result['status'] = 'error';
	$result['message'] = html_entity_decode("Error: " . $user->LAST_ERROR);
}

$data = $result ? $result : array('error' => 'Ошибка загрузки файлов.');

die(json_encode($data));
?>
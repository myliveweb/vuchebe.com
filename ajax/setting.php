<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once('function.php');

$error = 0;
$result = array();

$input = filter_input_array(INPUT_POST);

$user_id = 0;
$user_name = 'Аноним';
if($_SESSION['USER_DATA']) {
	$user_id = $_SESSION['USER_DATA']['ID'];
	$user_name = $_SESSION['USER_DATA']['FULL_NAME'];
}

$teacherId = '';
$teacher = '';
$chat = '';
$offline = '';
$color = '';

if($input['teacher']) {
	$teacherId = $input['teacher'];
	$teacher = 'Учитель';
}

if($input['color_off']) {
	$color = 1;
}

if($input['chat_off']) {
	$chat = 1;
}

if($input['offline']) {
	$offline = 1;
}

if($input['url']) {
    $url = trim($input['url']);
}

if($user_id) {

	global $USER;
	global $DB;

	$user = new CUser;
	$fields = Array(
	  	"WORK_WWW"    		=> $teacherId,
	  	"WORK_POSITION"     => $teacher,
	  	"WORK_ZIP"     	    => $chat,
	  	"PERSONAL_PAGER"    => $offline,
	  	"WORK_STATE"		=> $color,
        "WORK_PHONE"		=> $url,
	);

	if ($user->Update($USER->GetId(), $fields)) {
		$result['status']  = 'success';
	} else {
		$result['status']  = 'error';
		$result['message'] = html_entity_decode("Error: " . $user->LAST_ERROR);
	}

	if(!$teacherId) {
		$sql = "DELETE FROM a_user_uz WHERE teacher = 1 AND user_id = :user_id";
		$stmt = $dbh->prepare($sql);
		$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
		$stmt->execute();
	}

}

$data = $result ? $result : array('error' => 'Ошибка изменения статуса.');

die(json_encode($data));
?>
<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once('function.php');

global $USER;

CModule::IncludeModule('iblock');

$error = 0;
$result = array();

$input = filter_input_array(INPUT_POST);

$id_user = 0;
if($_SESSION['USER_DATA']['ID'])
	$id_user = $_SESSION['USER_DATA']['ID'];

if($input['type'] == 'add') {
	if($id_user && $id_user != $input['id']) {
		$rsUserData = CUser::GetByID($input['id']);
		$userData = $rsUserData->Fetch();
		if($userData['ID']) {
			$test = $dbh->query('SELECT block_user from a_block_user WHERE id_user = ' . $id_user . ' AND block_user = ' . $input['id'] . ' ORDER BY id ASC')->fetch();
			if(!$test) {
				$stmt = $dbh->prepare("INSERT INTO a_block_user (id_user, block_user) VALUES (:id_user, :block_user)");
				$stmt->bindParam(':id_user', $id_user);
				$stmt->bindParam(':block_user', $input['id']);
				$stmt->execute();
			}
		}
	}
} elseif($input['type'] == 'del') {
	$ok = $dbh->exec('DELETE FROM a_block_user WHERE id_user = ' . $id_user . ' AND block_user = ' . $input['id']);
}

$users = $dbh->query('SELECT block_user from a_block_user WHERE id_user = ' . $id_user . ' ORDER BY id DESC')->fetchAll();

if($users) {
	foreach($users as $user) {
		$tempArray = array();
		$rsUserData = CUser::GetByID($user['block_user']);
		$userData = $rsUserData->Fetch();
		if($userData) {
			$tempArray['id'] = $userData['ID'];

			if (strlen(trim($userData['NAME'])) && strlen(trim($userData['LAST_NAME']))) {
				$format_name = '<span>' . strtoupper(mb_substr(trim($userData['NAME']), 0, 1)) . '</span>' . mb_substr(trim($userData['NAME']), 1);
				if($userData['SECOND_NAME']) {
					$format_name .= ' ';
					$format_name .= '<span>' . strtoupper(mb_substr($userData['SECOND_NAME'], 0, 1)) . '</span>' . mb_substr($userData['SECOND_NAME'], 1);
				}
				$format_name .= ' ';
				$format_name .= '<span>' . strtoupper(mb_substr(trim($userData['LAST_NAME']), 0, 1)) . '</span>' . mb_substr(trim($userData['LAST_NAME']), 1);
			} else {
				$format_name = '<span>' . strtoupper(mb_substr(trim($userData['LOGIN']), 0, 1)) . '</span>' . mb_substr(trim($userData['LOGIN']), 1);
			}

			$tempArray['format_name'] = $format_name;

			if($userData['PERSONAL_PHOTO']) {
				$tempArray['avatar'] = CFile::GetPath($userData['PERSONAL_PHOTO']);
			} else {
				$tempArray['avatar'] = SITE_TEMPLATE_PATH . "/images/user-1.png";
			}

			if($userData['WORK_WWW']) {
				$tempArray['teacher'] = 'Y';
			} else {
				$tempArray['teacher'] = 'N';
			}

			$tempArray['online'] = $userData['IS_ONLINE'];

			$result[] = $tempArray;
		}
	}
}

$data = array("status" => "success", 'res' => $result);
die(json_encode($data));
?>
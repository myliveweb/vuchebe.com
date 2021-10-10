<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once($_SERVER["DOCUMENT_ROOT"].'/ajax/function.php');

$error = 0;
$result = array();

$input = filter_input_array(INPUT_POST);

$user_id = 0;
if($_SESSION['USER_DATA'])
	$user_id = $_SESSION['USER_DATA']['ID'];
	$user_name = $_SESSION['USER_DATA']['FULL_NAME'];

if($user_id) {
	if($input['type'] == 'like') {
		$tab = 'a_like_user';
	} elseif($input['type'] == 'deslike') {
		$tab = 'a_deslike_user';
	}

	if($input['status']) {
		$count = $dbh->exec('DELETE FROM ' . $tab . ' WHERE id_user = ' . $user_id . ' AND id_vuz = ' . $input['id_vuz'] . ' AND id_post = ' . $input['id_post']);
	} else {
		$stmt = $dbh->prepare("INSERT INTO " . $tab . " (id_user, id_vuz, id_post, user_name, user_avatar) VALUES (:id_user, :id_vuz, :id_post, :user_name, :user_avatar)");
		$stmt->bindParam(':id_user', $user_id);
		$stmt->bindParam(':id_vuz', $input['id_vuz']);
		$stmt->bindParam(':id_post', $input['id_post']);
		$stmt->bindParam(':user_name', $user_name);
		$stmt->bindParam(':user_avatar', $_SESSION['USER_DATA']['AVATAR']);
		$stmt->execute();
	}

	$users = $dbh->query('SELECT * from ' . $tab . ' WHERE id_vuz = ' . $input['id_vuz'] . ' AND id_post = ' . $input['id_post'] . ' ORDER BY id DESC')->fetchAll();
}

if($users) {
	foreach($users as $user) {
		$tempArray = array();
		$rsUserData = CUser::GetByID($user['id_user']);
		$userData = $rsUserData->Fetch();
		if($userData) {
			$tempArray['id'] = $userData['ID'];

			if (strlen(trim($userData['NAME'])) && strlen(trim($userData['LAST_NAME']))) {
				$format_name = $userData['NAME'];
				if($userData['SECOND_NAME']) {
					$format_name .= ' ';
					$format_name .= $userData['SECOND_NAME'];
				}
				$format_name .= ' ';
				$format_name .= $userData['LAST_NAME'];
			} else {
				$format_name = $userData['LOGIN'];
			}

			$tempArray['format_name'] = $format_name;

			if($userData['PERSONAL_PHOTO']) {
				$tempArray['avatar'] = CFile::GetPath($userData['PERSONAL_PHOTO']);
			} else {
				$tempArray['avatar'] = SITE_TEMPLATE_PATH . "/images/user-1.png";
			}

			$result[] = $tempArray;
		}
	}
}

$data = array("status" => "success", 'res' => $result );
die(json_encode($data));
?>
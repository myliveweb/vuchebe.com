<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once('function.php');

$error = 0;
$result = array();
$arrIdChat = array();
$arrShow = array();

$input = filter_input_array(INPUT_POST);

if($input['owner_id'] && $input['from_id']) {

	CModule::IncludeModule('iblock');

	$rsUserData = CUser::GetByID($input['owner_id']);
	$userData = $rsUserData->Fetch();

	if($userData['WORK_WWW']) {
		$arrTeacher = $dbh->query('SELECT COUNT(id) as cnt from a_user_uz WHERE teacher = 1 AND user_id = ' . $userData['ID'])->fetch();
		if($arrTeacher['cnt'] > 0) {
			$userData['TEACHER'] = 1;
		} else {
			$userData['TEACHER'] = 0;
		}
	} else {
		$userData['TEACHER'] = 0;
	}

	if (strlen(trim($userData['NAME'])) && strlen(trim($userData['LAST_NAME']))) {
		$format_name = '<span style="font-size: 14px;">' . strtoupper(mb_substr(trim($userData['NAME']), 0, 1)) . '</span>' . mb_substr(trim($userData['NAME']), 1);
		if($userData['SECOND_NAME']) {
			$format_name .= ' ';
			$format_name .= '<span style="font-size: 14px;">' . strtoupper(mb_substr($userData['SECOND_NAME'], 0, 1)) . '</span>' . mb_substr($userData['SECOND_NAME'], 1);
		}
		$format_name .= ' ';
		$format_name .= '<span style="font-size: 14px;">' . strtoupper(mb_substr(trim($userData['LAST_NAME']), 0, 1)) . '</span>' . mb_substr(trim($userData['LAST_NAME']), 1);
	} else {
		$format_name = '<span style="font-size: 14px;">' . strtoupper(mb_substr(trim($userData['LOGIN']), 0, 1)) . '</span>' . mb_substr(trim($userData['LOGIN']), 1);
	}

	if($userData['PERSONAL_PHOTO']) {
		$avatar_url = CFile::GetPath($userData['PERSONAL_PHOTO']);
	} else {
		$avatar_url = SITE_TEMPLATE_PATH . "/images/user-1.png";
	}

	if(CUser::IsOnLine($userData['ID'], 30) && $userData['PERSONAL_PAGER'] != 1 && $_SESSION['USER_DATA']['PERSONAL_PAGER'] != 1) {
		$online = 1;
	} else {
		$online = 0;
	}

	$in = array();

	$resultArray = $dbh->query('SELECT * from a_chat WHERE del_owner = 0 AND owner_id = ' . $input['owner_id'] . ' AND from_id = ' . $input['from_id'] . ' AND success = 0 ORDER BY date_post ASC')->fetchAll();
	foreach($resultArray as $itemArray) {
		$itemArray['str_time']    = get_str_time($itemArray['date_post']);
		$itemArray['teacher']     = $userData['TEACHER'];
		$itemArray['format_name'] = $format_name;
		$itemArray['avatar_url']  = $avatar_url;
		$itemArray['online']      = $online;

		$bookType = 'bookmark';
		$bookName = 'в закладки';
		$bookCSS = '-79px';

		$itemArray['book_type'] = $bookType;
		$itemArray['book_name'] = $bookName;
		$itemArray['book_css']  = $bookCSS;

		$spamType = 'spam';
		$spamData = 0;
		$spamName = 'спам';
		$spamCSS = '-40px';

		$itemArray['spam_type'] = $spamType;
		$itemArray['spam_data'] = $spamData;
		$itemArray['spam_name'] = $spamName;
		$itemArray['spam_css']  = $spamCSS;

		$result[] = $itemArray;
		$arrIdChat[] = $itemArray['id'];
	}
	if($arrIdChat) {
		$in  = implode(',', $arrIdChat);
		$stmt= $dbh->prepare("UPDATE a_chat SET success = 1 WHERE id IN (" . $in . ")");
		$stmt->execute();
	}
	if($input['no_show']) {
		$arrShow = $dbh->query('SELECT id from a_chat WHERE del_owner = 0 AND owner_id = ' . $input['from_id'] . ' AND id IN (' . $input['no_show'] . ') AND success = 1 ORDER BY date_post ASC')->fetchAll();
	}

}

$data = array("status" => "success", 'update' => $result, 'show' => $arrShow);
die(json_encode($data));
?>
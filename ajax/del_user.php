<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once('function.php');

$error = 0;
$result = array();

$user_id = 0;
$user_name = 'Аноним';
if($_SESSION['USER_DATA']) {
	$user_id = $_SESSION['USER_DATA']['ID'];
	$user_name = $_SESSION['USER_DATA']['FULL_NAME'];
}

if($user_id) {

	global $USER;
	global $DB;

	CModule::IncludeModule('iblock');

	$user = new CUser;
	$fields = Array(
	  	"ACTIVE" => 'N',
	);

	if ($user->Update($USER->GetId(), $fields)) {

		$USER->Logout();
		$_SESSION = array();

		$sql = "DELETE FROM a_bookmark WHERE user_id = :user_id";
		$stmt = $dbh->prepare($sql);
		$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
		$stmt->execute();

		$sql = "DELETE FROM a_like WHERE id_user = :id_user";
		$stmt = $dbh->prepare($sql);
		$stmt->bindParam(':id_user', $user_id, PDO::PARAM_INT);
		$stmt->execute();

		$sql = "DELETE FROM a_deslike WHERE id_user = :id_user";
		$stmt = $dbh->prepare($sql);
		$stmt->bindParam(':id_user', $user_id, PDO::PARAM_INT);
		$stmt->execute();

		$sql = "DELETE FROM a_like_events WHERE id_user = :id_user";
		$stmt = $dbh->prepare($sql);
		$stmt->bindParam(':id_user', $user_id, PDO::PARAM_INT);
		$stmt->execute();

		$sql = "DELETE FROM a_deslike_events WHERE id_user = :id_user";
		$stmt = $dbh->prepare($sql);
		$stmt->bindParam(':id_user', $user_id, PDO::PARAM_INT);
		$stmt->execute();

		$sql = "DELETE FROM a_like_news WHERE id_user = :id_user";
		$stmt = $dbh->prepare($sql);
		$stmt->bindParam(':id_user', $user_id, PDO::PARAM_INT);
		$stmt->execute();

		$sql = "DELETE FROM a_deslike_news WHERE id_user = :id_user";
		$stmt = $dbh->prepare($sql);
		$stmt->bindParam(':id_user', $user_id, PDO::PARAM_INT);
		$stmt->execute();

		$sql = "DELETE FROM a_like_user WHERE id_user = :id_user";
		$stmt = $dbh->prepare($sql);
		$stmt->bindParam(':id_user', $user_id, PDO::PARAM_INT);
		$stmt->execute();

		$sql = "DELETE FROM a_deslike_user WHERE id_user = :id_user";
		$stmt = $dbh->prepare($sql);
		$stmt->bindParam(':id_user', $user_id, PDO::PARAM_INT);
		$stmt->execute();

		$sql = "DELETE FROM a_user_uz WHERE user_id = :user_id";
		$stmt = $dbh->prepare($sql);
		$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
		$stmt->execute();

		$sql = "DELETE FROM a_events_go WHERE id_user = :id_user";
		$stmt = $dbh->prepare($sql);
		$stmt->bindParam(':id_user', $user_id, PDO::PARAM_INT);
		$stmt->execute();

		$arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_USER_ID");
		$arFilter = array("IBLOCK_ID" => 21, "ACTIVE" => "Y", "PROPERTY_USER_ID" => $user_id);
		$res = CIBlockElement::GetList(array("ID" => "DESC"), $arFilter, false, false, $arSelect);
		while($row = $res->Fetch())
		{
			$el = new CIBlockElement;

			$arLoadProductArray = Array(
			  "MODIFIED_BY"       => $USER->GetID(),
			  "IBLOCK_ID"         => 21,
			  "ACTIVE"            => "N"
			);

			$resUpdate = $el->Update($row['ID'], $arLoadProductArray);
		}

		$result['status'] = 'success';
		$result['user'] = $user_id;
	} else {
		$result['status'] = 'error';
		$result['message'] = html_entity_decode("Error: " . $user->LAST_ERROR);
	}
}

$data = $result ? $result : array('error' => 'Ошибка удаления пользователя.');

die(json_encode($data));
?>
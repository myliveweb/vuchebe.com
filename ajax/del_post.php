<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $dbh;
global $USER;
global $DB;

$user_id = 0;
if($_SESSION['USER_DATA'])
	$user_id = $_SESSION['USER_DATA']['ID'];

$input = filter_input_array(INPUT_POST);

CModule::IncludeModule('iblock');

$arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_USER_ID");
$arFilter = array("IBLOCK_ID" => 21, "ACTIVE" => "Y", "ID" => $input['id_post']);
$res = CIBlockElement::GetList(array("ID" => "DESC"), $arFilter, false, false, $arSelect);
if($row = $res->Fetch())
{
	if($row["PROPERTY_USER_ID_VALUE"] == $user_id || isEdit()) {

		$arSelectTest = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_PARENT_ID");
		$arFilterTest = array("IBLOCK_ID" => 21, "ACTIVE" => "Y", "PROPERTY_PARENT_ID" => $input['id_post']);
		$resTest = CIBlockElement::GetList(array("ID" => "DESC"), $arFilterTest, false, false, $arSelectTest);
		if($rowTest = $resTest->Fetch())
		{
			$el = new CIBlockElement;

			$arLoadProductArray = Array(
			  "MODIFIED_BY"       => $USER->GetID(), // элемент изменен текущим пользователем
			  "IBLOCK_ID"         => 21,
			  "ACTIVE"            => "N"
			);

			$res = $el->Update($input['id_post'], $arLoadProductArray);

			$result['status'] = 'success';
			$result['message'] = 'Успешно деактивировано.';
		} else {
			if(!CIBlockElement::Delete($input['id_post']))
			{
			    $DB->Rollback();
				$result['status'] = 'error';
				$result['message'] = 'Не удалось удалить. Ошибка Битрикса.';
			} else {
			    $DB->Commit();
				$result['status'] = 'success';
				$result['message'] = 'Успешно удалено.';

				$sql = "DELETE FROM a_like_user WHERE id_post =  :id_post";
				$stmt = $dbh->prepare($sql);
				$stmt->bindParam(':id_post', $input['id_post'], PDO::PARAM_INT);
				$stmt->execute();

				$sql = "DELETE FROM a_deslike_user WHERE id_post =  :id_post";
				$stmt = $dbh->prepare($sql);
				$stmt->bindParam(':id_post', $input['id_post'], PDO::PARAM_INT);
				$stmt->execute();
			}
		}

		$abusePost = 0;

		$arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_POST_ID");
		$arFilter = array("IBLOCK_ID" => 23, "ACTIVE" => "Y", "PROPERTY_POST_ID" => $input['id_post']);
		$res = CIBlockElement::GetList(array("ID" => "DESC"), $arFilter, false, false, $arSelect);
		if($row = $res->Fetch())
		{
			$abusePost = $row['ID'];
		}

		if($abusePost) {
			CIBlockElement::Delete($abusePost);
		}

	} else {
		$result['status'] = 'error';
		$result['message'] = 'Не удалось удалить. В доступе отказано.';
	}
}

echo json_encode($result);
?>
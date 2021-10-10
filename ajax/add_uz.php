<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once('function.php');

CModule::IncludeModule('iblock');

$error = 0;
$result = 0;

$input = filter_input_array(INPUT_POST);

$country_id = 0;
$country_name = '';
$region = '';
$city_id = 0;
$city_name = '';

$user_id = 0;
$user_name = 'Аноним';
if($_SESSION['USER_DATA']) {
	$user_id = $_SESSION['USER_DATA']['ID'];
	$user_name = $_SESSION['USER_DATA']['FULL_NAME'];
}

if($user_id) {

	if($input['uz']) {
		$arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_CITY");
		$arFilter = array("IBLOCK_ID" => 2, "ACTIVE" => "Y", "ID" => $input['uz']);
		$res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
		if($row = $res->GetNext()) {
			$arSelectGeo = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_REGION", "IBLOCK_SECTION_ID");
			$arFilterGeo = array("IBLOCK_ID" => 32, "ACTIVE" => "Y", "ID" => $row['PROPERTY_CITY_VALUE']);
			$resGeo = CIBlockElement::GetList(array("ID" => "ASC"), $arFilterGeo, false, false, $arSelectGeo);
			if($rowGeo = $resGeo->GetNext()) {
				$country_id = $rowGeo['IBLOCK_SECTION_ID'];
				$region = $rowGeo['PROPERTY_REGION_VALUE'];
				$city_id = $rowGeo['ID'];
				$city_name = $rowGeo['NAME'];
				if($country_id) {
					$arSelectCounry = array("ID", "NAME", "IBLOCK_ID");
					$arFilterCounry = array("IBLOCK_ID" => 32, "ACTIVE" => "Y", "ID" => $country_id);
					$resCounry = CIBlockSection::GetList(array("ID" => "ASC"), $arFilterCounry, false, false, $arSelectCounry);
					if($rowCounry = $resCounry->GetNext()) {
						$country_name = $rowCounry['NAME'];
					}
				}
			}
		}
	}

	if(!$input['uz'] || !$city_id) {
		$country_id 	= $input['country_id'];
		$country_name 	= $input['country_name'];
		$region 		= $input['region'];
		$city_id 		= $input['city_id'];
		$city_name 		= $input['city_name'];
	}

	if($input['obr'] == 1) {
		if($input['type'] == 'add') {
			$stmt = $dbh->prepare("INSERT INTO a_user_uz (user_id, user_name, type, uz_id, fack, forma, status, grupe, spec, teacher, start_p, end_p, country_id, country_name, region, city_id, city_name) VALUES (:user_id, :user_name, :type, :uz_id, :fack, :forma, :status, :grupe, '', :teacher, :start_p, :end_p, :country_id, :country_name, :region, :city_id, :city_name)");
			$stmt->bindParam(':user_id', $user_id);
			$stmt->bindParam(':user_name', $user_name);
			$stmt->bindParam(':type', $input['obr']);
			$stmt->bindParam(':uz_id', $input['uz']);
			$stmt->bindParam(':fack', $input['fack']);
			$stmt->bindParam(':forma', $input['forma']);
			$stmt->bindParam(':status', $input['status']);
			$stmt->bindParam(':grupe', $input['group']);
			$stmt->bindParam(':teacher', $input['teacher']);
			$stmt->bindParam(':start_p', $input['start']);
			$stmt->bindParam(':end_p', $input['end']);
			$stmt->bindParam(':country_id', $country_id);
			$stmt->bindParam(':country_name', $country_name);
			$stmt->bindParam(':region', $region);
			$stmt->bindParam(':city_id', $city_id);
			$stmt->bindParam(':city_name', $city_name);
			$stmt->execute();
		} elseif($input['type'] == 'update') {
			$stmt= $dbh->prepare("UPDATE a_user_uz SET type=?, uz_id=?, fack=?, forma=?, status=?, grupe=?, start_p=?, end_p=?, country_id=?, country_name=?, region=?, city_id=?, city_name=? WHERE id=?");
			$stmt->execute(array($input['obr'], $input['uz'], $input['fack'], $input['forma'], $input['status'], $input['group'], $input['start'], $input['end'], $country_id, $country_name, $region, $city_id, $city_name, $input['id']));
		}
	} elseif($input['obr'] == 2) {
		if($input['type'] == 'add') {
			$stmt = $dbh->prepare("INSERT INTO a_user_uz (user_id, user_name, type, uz_id, fack, forma, status, grupe, spec, teacher, start_p, end_p, country_id, country_name, region, city_id, city_name) VALUES (:user_id, :user_name, :type, :uz_id, 0, '', '', :grupe, :spec, :teacher, :start_p, :end_p, :country_id, :country_name, :region, :city_id, :city_name)");
			$stmt->bindParam(':user_id', $user_id);
			$stmt->bindParam(':user_name', $user_name);
			$stmt->bindParam(':type', $input['obr']);
			$stmt->bindParam(':uz_id', $input['uz']);
			$stmt->bindParam(':grupe', $input['group']);
			$stmt->bindParam(':spec', $input['spec']);
			$stmt->bindParam(':teacher', $input['teacher']);
			$stmt->bindParam(':start_p', $input['start']);
			$stmt->bindParam(':end_p', $input['end']);
			$stmt->bindParam(':country_id', $country_id);
			$stmt->bindParam(':country_name', $country_name);
			$stmt->bindParam(':region', $region);
			$stmt->bindParam(':city_id', $city_id);
			$stmt->bindParam(':city_name', $city_name);
			$stmt->execute();
		} elseif($input['type'] == 'update') {
			$stmt= $dbh->prepare("UPDATE a_user_uz SET type=?, uz_id=?, grupe=?, spec=?, start_p=?, end_p=?, country_id=?, country_name=?, region=?, city_id=?, city_name=? WHERE id=?");
			$stmt->execute(array($input['obr'], $input['uz'], $input['group'], $input['spec'], $input['start'], $input['end'], $country_id, $country_name, $region, $city_id, $city_name, $input['id']));
		}
	} elseif($input['obr'] == 3) {
		if($input['type'] == 'add') {
			$stmt = $dbh->prepare("INSERT INTO a_user_uz (user_id, user_name, type, uz_id, fack, forma, status, grupe, spec, teacher, start_p, end_p, country_id, country_name, region, city_id, city_name) VALUES (:user_id, :user_name, :type, :uz_id, 0, '', '', :grupe, '', :teacher, :start_p, :end_p, :country_id, :country_name, :region, :city_id, :city_name)");
			$stmt->bindParam(':user_id', $user_id);
			$stmt->bindParam(':user_name', $user_name);
			$stmt->bindParam(':type', $input['obr']);
			$stmt->bindParam(':uz_id', $input['uz']);
			$stmt->bindParam(':grupe', $input['group']);
			$stmt->bindParam(':teacher', $input['teacher']);
			$stmt->bindParam(':start_p', $input['start']);
			$stmt->bindParam(':end_p', $input['end']);
			$stmt->bindParam(':country_id', $country_id);
			$stmt->bindParam(':country_name', $country_name);
			$stmt->bindParam(':region', $region);
			$stmt->bindParam(':city_id', $city_id);
			$stmt->bindParam(':city_name', $city_name);
			$stmt->execute();
		} elseif($input['type'] == 'update') {
			$stmt= $dbh->prepare("UPDATE a_user_uz SET type=?, uz_id=?, grupe=?, start_p=?, end_p=?, country_id=?, country_name=?, region=?, city_id=?, city_name=? WHERE id=?");
			$stmt->execute(array($input['obr'], $input['uz'], $input['group'], $input['start'], $input['end'], $country_id, $country_name, $region, $city_id, $city_name, $input['id']));
		}
	} elseif($input['obr'] == 4) {
		if($input['type'] == 'add') {
			$stmt = $dbh->prepare("INSERT INTO a_user_uz (user_id, user_name, type, uz_id, fack, forma, status, grupe, spec, teacher, start_p, end_p, country_id, country_name, region, city_id, city_name) VALUES (:user_id, :user_name, :type, :uz_id, 0, '', '', '', '', :teacher, :start_p, :end_p, :country_id, :country_name, :region, :city_id, :city_name)");
			$stmt->bindParam(':user_id', $user_id);
			$stmt->bindParam(':user_name', $user_name);
			$stmt->bindParam(':type', $input['obr']);
			$stmt->bindParam(':uz_id', $input['uz']);
			$stmt->bindParam(':teacher', $input['teacher']);
			$stmt->bindParam(':start_p', $input['start']);
			$stmt->bindParam(':end_p', $input['end']);
			$stmt->bindParam(':country_id', $country_id);
			$stmt->bindParam(':country_name', $country_name);
			$stmt->bindParam(':region', $region);
			$stmt->bindParam(':city_id', $city_id);
			$stmt->bindParam(':city_name', $city_name);
			$stmt->execute();
		} elseif($input['type'] == 'update') {
			$stmt= $dbh->prepare("UPDATE a_user_uz SET type=?, uz_id=?, start_p=?, end_p=?, country_id=?, country_name=?, region=?, city_id=?, city_name=? WHERE id=?");
			$stmt->execute(array($input['obr'], $input['uz'], $input['start'], $input['end'], $country_id, $country_name, $region, $city_id, $city_name, $input['id']));
		}
	}
}
$data = array("status" => "success");
die(json_encode($data));
?>
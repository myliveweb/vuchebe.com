<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once('function.php');

$result = array();

$input = filter_input_array(INPUT_POST);

CModule::IncludeModule('iblock');

if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

    if(!empty($input['email']) and !empty($input['firstname']) and !empty($input['lastname']) and !empty($input['password']) and !empty($input['password_confirm']) ) {

		global $USER;
		global $DB;

		$login 				= $input['email'];
		$firstname 			= $input['firstname'];
		$lastname 			= $input['lastname'];
		$email 				= $input['email'];
		$password 			= $input['password'];
		$password_confirm 	= $input['password_confirm'];

		$country_id 	= 0;
		$country_name 	= '';
		$region 		= '';
		$city_id 		= 0;
		$city_name 		= '';

		$bConfirmReq = (COption::GetOptionString("main", "new_user_registration_email_confirmation", "N")) == "Y";

		$activateStr = randString(32);

		$arFields = array(
		  "NAME"              => $input['firstname'],
		  "LAST_NAME"         => $input['lastname'],
		  "EMAIL"             => $input['email'],
		  "LOGIN"             => $input['email'],
		  "LID"               => SITE_ID,
		  "ACTIVE"            => "N",
		  "GROUP_ID"          => array(2),
		  "PASSWORD"          => $input['password'],
		  "CONFIRM_PASSWORD"  => $input['password_confirm'],
		  "CHECKWORD" 		  => md5(CMain::GetServerUniqID().uniqid()),
		  "~CHECKWORD_TIME"   => $DB->CurrentTimeFunction(),
		  "CONFIRM_CODE" 	  => $bConfirmReq ? randString(8) : "",
		  "PERSONAL_CITY"     => $input['city'],
		  "PERSONAL_PHONE"    => $input['phone'],
		  "PERSONAL_NOTES"    => $input['about'],
	  	  "UF_VK"             => $input['VK'],
	  	  "UF_FB"      		  => $input['FB'],
	  	  "UF_OK"     		  => $input['OK'],
	  	  "UF_TW"             => $input['TW'],
	  	  "UF_INST"      	  => $input['INST'],
	  	  "UF_YOU"     		  => $input['YOU'],
	  	  "UF_LJ"     		  => $input['LJ'],
	  	  "UF_ACTIVATE"		  => $activateStr
		);

		if($input['city_id']) {
			$arSelect = array("ID", "NAME", "IBLOCK_ID", "IBLOCK_SECTION_ID", "PROPERTY_REGION");
			$arFilter = array("IBLOCK_ID" => 32, "ACTIVE" => "Y", "ID" => $input['city_id']);
			$res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
			if($row = $res->GetNext()) {
				$arFields["UF_COUNTRY"] = $row["IBLOCK_SECTION_ID"];
				$arFields["UF_REGION"]  = $row["PROPERTY_REGION_VALUE"];
				$arFields["UF_CITY"] 	= $row["ID"];

				$country_id = $row["IBLOCK_SECTION_ID"];
				$region 	= $row["PROPERTY_REGION_VALUE"];
				$city_id 	= $row["ID"];
				$city_name 	= $row["NAME"];
			}
		}

		$CUser = new CUser;

		$USER_ID = $CUser->Add($arFields);

		if (intval($USER_ID) > 0){

			$USER->Authorize($USER_ID);

			$result['status'] = 'success';
			$result['message'] = 'Вы успешно зарегистрировались. Вам отправлено письмо для подтверждения, письмо могло уйти в ящик СПАМ.';
			$result['res'] = $USER_ID;

			$arFields['USER_ID']  = $USER_ID;
			$arFields['ACTIVATE'] = $activateStr;

			$arEventFields = $arFields;

			$event = new CEvent;
			if($bConfirmReq){
				$event->SendImmediate("NEW_USER_CONFIRM", SITE_ID, $arEventFields);
			}else{
				$event->SendImmediate("USER_INFO", SITE_ID, $arEventFields);
			}
			// Отправляем Оповешение администратору
			$event->SendImmediate("NEW_USER", SITE_ID, $arEventFields);

			// Заполняем сессию
			$name = trim($USER->GetFirstName()) . ' ' . trim($USER->GetLastName());
			if (strlen($name) <= 0)
				$name = $USER->GetLogin();

			$rsUser = CUser::GetByID($USER->GetId());
			$_SESSION['USER_DATA'] = $rsUser->Fetch();

			$_SESSION['USER_DATA']['FULL_NAME'] = $name;

			$avatar_url = SITE_TEMPLATE_PATH . "/img/foto-user.png";
			$_SESSION['USER_DATA']['AVATAR'] = $avatar_url;

			// Заполняем учебное заведение
			$select_uz 	= $input['select_uz'];
			$id_uz 		= $input['id_uz'];

			if($select_uz && $name_uz) {

				$select_start 	= $input['select_start'];
				$select_end 	= $input['select_end'];

				$stmt = $dbh->prepare("INSERT INTO a_user_uz (user_id, user_name, type, uz_id, fack, forma, status, grupe, spec, start_p, end_p, country_id, country_name, region, city_id, city_name) VALUES (:user_id, :user_name, :type, :uz_id, '', '', '', '', '', :start_p, :end_p, :country_id, :country_name, :region, :city_id, :city_name)");
				$stmt->bindParam(':user_id', $USER_ID);
				$stmt->bindParam(':user_name', $name);
				$stmt->bindParam(':type', $select_uz);
				$stmt->bindParam(':uz_id', $id_uz);
				$stmt->bindParam(':start_p', $select_start);
				$stmt->bindParam(':end_p', $select_end);

				$stmt->bindParam(':country_id', $country_id);
				$stmt->bindParam(':country_name', $country_name);
				$stmt->bindParam(':region', $region);
				$stmt->bindParam(':city_id', $city_id);
				$stmt->bindParam(':city_name', $city_name);
				$stmt->execute();

			}

		}
		else{
			$result['status'] = 'error';
			$result['message'] = html_entity_decode($CUser->LAST_ERROR);
		}

    }else{
        $result['status'] = 'error';
        $result['message'] = 'Все поля обязательны для заполнения';
    }

    echo json_encode($result);
}
?>
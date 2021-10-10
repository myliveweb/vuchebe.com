<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$result = array();

$input = filter_input_array(INPUT_POST);

CModule::IncludeModule('iblock');

if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

    if(!empty($input['email']) and !empty($input['password']) and !empty($input['password_confirm'])) {

		global $USER;
		global $DB;

		$bConfirmReq = (COption::GetOptionString("main", "new_user_registration_email_confirmation", "N")) == "Y";

		$activateStr = randString(32);

		if($input['pro'] == 6) {

			$arFields = array(
			  "NAME"              => $input['u_first_name'],
			  "LAST_NAME"         => $input['u_last_name'],
			  "SECOND_NAME"       => $input['u_second_name'],
			  "EMAIL"             => $input['email'],
			  "LOGIN"             => $input['email'],
			  "WORK_COMPANY"      => $input['u_name'],
			  "WORK_STREET"       => $input['u_address'],
			  "LID"               => SITE_ID,
			  "ACTIVE"            => "N",
			  "GROUP_ID"          => array(6),
			  "PASSWORD"          => $input['password'],
			  "CONFIRM_PASSWORD"  => $input['password_confirm'],
			  "CHECKWORD" 		  => md5(CMain::GetServerUniqID().uniqid()),
			  "~CHECKWORD_TIME"   => $DB->CurrentTimeFunction(),
			  "CONFIRM_CODE" 	  => $bConfirmReq ? randString(8) : "",
			  "PERSONAL_PHONE"    => $input['phone'],
		  	  "UF_OGRN"           => $input['u_ogrn'],
		  	  "UF_INN"            => $input['u_inn'],
		  	  "UF_KPP"     		  => $input['u_kpp'],
		  	  "UF_ACTIVATE"		  => $activateStr
			);
		} else {

			$bday = $input['f_day'] . '.' . $input['f_month'] . '.' . $input['f_year'];

			$arFields = array(
			  "NAME"              => $input['f_first_name'],
			  "LAST_NAME"         => $input['f_last_name'],
			  "SECOND_NAME"       => $input['f_second_name'],
			  "EMAIL"             => $input['email'],
			  "LOGIN"             => $input['email'],
			  "PERSONAL_BIRTHDAY" => $bday,
			  "LID"               => SITE_ID,
			  "ACTIVE"            => "N",
			  "GROUP_ID"          => array(7),
			  "PASSWORD"          => $input['password'],
			  "CONFIRM_PASSWORD"  => $input['password_confirm'],
			  "CHECKWORD" 		  => md5(CMain::GetServerUniqID().uniqid()),
			  "~CHECKWORD_TIME"   => $DB->CurrentTimeFunction(),
			  "CONFIRM_CODE" 	  => $bConfirmReq ? randString(8) : "",
			  "PERSONAL_PHONE"    => $input['phone'],
		  	  "UF_ACTIVATE"		  => $activateStr
			);
		}

		$CUser = new CUser;

		$USER_ID = $CUser->Add($arFields);

		if (intval($USER_ID) > 0) {

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

		} else {
			$result['status'] = 'error';
			$result['message'] = html_entity_decode($CUser->LAST_ERROR);
		}

    } else {
        $result['status'] = 'error';
        $result['message'] = 'Все поля обязательны для заполнения';
    }

    echo json_encode($result);
}
?>
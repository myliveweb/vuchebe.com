<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){

    if(!empty($_REQUEST['PROFILE_EMAIL']) and !empty($_REQUEST['PROFILE_FNAME']) and !empty($_REQUEST['PROFILE_LNAME'])){

		global $USER;
		global $DB;

		CModule::IncludeModule('iblock');

		$month_1 = array('', 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');

		$bday = '';
		$format_day = '';

		$day = strip_tags($_REQUEST['PROFILE_DAY']);
		$month = strip_tags($_REQUEST['PROFILE_MONTH']);
		$year = strip_tags($_REQUEST['PROFILE_YEAR']);

		if($day || $month || $year) {
			$bday = $day . '.' . $month . '.' . $year;
			$format_day = $day . ' ' . $month_1[$month] . ' ' . $year . ' г.';
		}



		$user = new CUser;
		$fields = Array(
		  	"NAME"              => strip_tags($_REQUEST['PROFILE_FNAME']),
		  	"LAST_NAME"         => strip_tags($_REQUEST['PROFILE_LNAME']),
		  	"SECOND_NAME"       => strip_tags($_REQUEST['PROFILE_SNAME']),
		  	"EMAIL"             => strip_tags($_REQUEST['PROFILE_EMAIL']),
		  	"PERSONAL_ICQ"      => strip_tags($_REQUEST['PROFILE_ICQ']),
		  	"PERSONAL_BIRTHDAY" => $bday,
		  	"PERSONAL_CITY"     => ucfirst(strip_tags($_REQUEST['PROFILE_CITY'])),
		  	"WORK_CITY"         => strip_tags($_REQUEST['PROFILE_R_CITY']),
		  	"PERSONAL_PHONE"    => strip_tags($_REQUEST['PROFILE_PHONE']),
		  	"PERSONAL_NOTES"    => strip_tags($_REQUEST['PROFILE_NOTES']),
		  	"PERSONAL_GENDER"   => strip_tags($_REQUEST['PROFILE_POL']),
			"UF_VK"             => strip_tags($_REQUEST['PROFILE_VK']),
			"UF_FB"      		=> strip_tags($_REQUEST['PROFILE_FB']),
			"UF_OK"     		=> strip_tags($_REQUEST['PROFILE_OK']),
			"UF_TW"             => strip_tags($_REQUEST['PROFILE_TW']),
			"UF_INST"      	  	=> strip_tags($_REQUEST['PROFILE_INST']),
			"UF_YOU"     		=> strip_tags($_REQUEST['PROFILE_YOU']),
			"UF_LJ"     		=> strip_tags($_REQUEST['PROFILE_LJ']),
			"UF_COUNTRY"      	=> strip_tags($_REQUEST['PROFILE_COUNTRY']),
			"UF_REGION"     	=> strip_tags($_REQUEST['PROFILE_REGION']),
			"UF_CITY"     		=> 0
		);

		if($_REQUEST['PROFILE_CITY']) {
			$arSelect = array("ID", "NAME", "IBLOCK_ID", "IBLOCK_SECTION_ID", "PROPERTY_REGION");
			$arFilter = array("IBLOCK_ID" => 32, "ACTIVE" => "Y", "NAME" => $_REQUEST['PROFILE_CITY']);

			if($_REQUEST['PROFILE_COUNTRY'])
				$arFilter['SECTION_ID'] = $_REQUEST['PROFILE_COUNTRY'];

			if($_REQUEST['PROFILE_REGION'])
				$arFilter['PROPERTY_REGION'] = $_REQUEST['PROFILE_REGION'];

			$res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
			if($row = $res->GetNext()) {
				$fields["UF_COUNTRY"] = $row["IBLOCK_SECTION_ID"];
				$fields["UF_REGION"] = $row["PROPERTY_REGION_VALUE"];
				$fields["UF_CITY"] = $row["ID"];
			}
		}

		if ($user->Update($USER->GetId(), $fields)) {

			$rsUser = CUser::GetByID($USER->GetId());
			$_SESSION['USER_DATA'] = $rsUser->Fetch();

			if (strlen($_SESSION['USER_DATA']['NAME']) && strlen($_SESSION['USER_DATA']['LAST_NAME'])) {
				$format_name = '<span>' . strtoupper(mb_substr($_SESSION['USER_DATA']['NAME'], 0, 1)) . '</span>' . mb_substr($_SESSION['USER_DATA']['NAME'], 1);
				if($_SESSION['USER_DATA']['SECOND_NAME']) {
					$format_name .= ' ';
					$format_name .= '<span>' . strtoupper(mb_substr($_SESSION['USER_DATA']['SECOND_NAME'], 0, 1)) . '</span>' . mb_substr($_SESSION['USER_DATA']['SECOND_NAME'], 1);
				}
				$format_name .= ' ';
				$format_name .= '<span>' . strtoupper(mb_substr($_SESSION['USER_DATA']['LAST_NAME'], 0, 1)) . '</span>' . mb_substr($_SESSION['USER_DATA']['LAST_NAME'], 1);
			} else {
				$format_name = '<span>' . strtoupper(mb_substr(trim($USER->GetLogin()), 0, 1)) . '</span>' . mb_substr(trim($USER->GetLogin()), 1);
			}

			$result['status'] = 'success';
			$result['message'] = $format_name;
			$result['bday'] = $format_day;
			$result['day'] = $day;
			$result['month'] = $month;
			$result['year'] = $year;

			$hideDay = strip_tags($_REQUEST['HIDE_DAY']);
			$hideStatus = strip_tags($_REQUEST['HIDE_STATUS']);
			$hideCity = strip_tags($_REQUEST['HIDE_R_CITY']);
			$hidePhone = strip_tags($_REQUEST['HIDE_PHONE']);
			$hideEmail = strip_tags($_REQUEST['HIDE_EMAIL']);
			$hideSoc = strip_tags($_REQUEST['HIDE_SOC']);
			$hideNote = strip_tags($_REQUEST['HIDE_NOTE']);
            $hidePol = strip_tags($_REQUEST['HIDE_POL']);

			$result['hideDay']    = $hideDay;
			$result['hideStatus'] = $hideStatus;
			$result['hideCity']   = $hideCity;
			$result['hidePhone']  = $hidePhone;
			$result['hideEmail']  = $hideEmail;
			$result['hideSoc']    = $hideSoc;
			$result['hideNote']   = $hideNote;

			$id_user = 0;
			if($_SESSION['USER_DATA']['ID'])
				$id_user = $_SESSION['USER_DATA']['ID'];

			if($id_user) {
				$test = $dbh->query('SELECT id from a_user_hide WHERE user_id = ' . $id_user . ' ORDER BY id ASC')->fetch();
				if($test) {
					$stmt= $dbh->prepare("UPDATE a_user_hide SET day = " . $hideDay . ", status = " . $hideStatus . ", r_city = " . $hideCity . ", phone = " . $hidePhone . ", email = " . $hideEmail . ", soc = " . $hideSoc . ", note = " . $hideNote . ", pol = " . $hidePol . " WHERE id = " . $test['id']);
					$stmt->execute();
				} else {
					$stmt = $dbh->prepare("INSERT INTO a_user_hide (user_id, day, status, r_city, phone, email, soc, note, pol) VALUES (:user_id, :day, :status, :r_city, :phone, :email, :soc, :note, :pol)");
					$stmt->bindParam(':user_id', $id_user);
					$stmt->bindParam(':day', $hideDay);
					$stmt->bindParam(':status', $hideStatus);
					$stmt->bindParam(':r_city', $hideCity);
					$stmt->bindParam(':phone', $hidePhone);
					$stmt->bindParam(':email', $hideEmail);
					$stmt->bindParam(':soc', $hideSoc);
					$stmt->bindParam(':note', $hideNote);
                    $stmt->bindParam(':pol', $hidePol);
					$stmt->execute();
				}
			}

		} else {
			$result['status'] = 'error';
			$result['message'] = html_entity_decode("Error: " . $user->LAST_ERROR);
		}
    }else{
        $result['status'] = 'error';
        $result['message'] = 'Имя, Фамилия, Email - обязательны для заполнения';
    }

    echo json_encode($result);
}
?>
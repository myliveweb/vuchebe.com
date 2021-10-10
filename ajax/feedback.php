<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
?>
<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){

    if(!empty($_REQUEST['email']) and !empty($_REQUEST['firstname']) and !empty($_REQUEST['message_fb'])){

			if($APPLICATION->CaptchaCheckCode($_REQUEST["captcha_word"], $_REQUEST["captcha_sid"])){

				global $USER;
				global $DB;

				$firstname = strip_tags($_REQUEST['firstname']);
				$email = strip_tags($_REQUEST['email']);
				$message = strip_tags($_REQUEST['message_fb']);


				$bConfirmReq = (COption::GetOptionString("main", "new_user_registration_email_confirmation", "N")) == "Y";

				$el = new CIBlockElement;

				$PROP = array();
				$PROP['FIRSTNAME'] = $firstname;
				$PROP['EMAIL'] = $email;

				$arLoadProductArray = Array(
				  "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
				  "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
				  "IBLOCK_ID"      => 1,
				  "PROPERTY_VALUES"=> $PROP,
				  "NAME"           => "Новое сообщение - " . $firstname,
				  "ACTIVE"         => "Y",            // активен
				  "PREVIEW_TEXT"   => $message
				  );


				if ($PRODUCT_ID = $el->Add($arLoadProductArray)){
					$USER->Authorize($USER_ID);

					$result['status'] = 'success';
					$result['message'] = 'Ваше сообщение успешно отправлено';

				}
				else{
					$result['status'] = 'error';
					$result['message'] = html_entity_decode("Error: ".$el->LAST_ERROR);
				}

			}else{
				$result['status'] = 'error';
				$result['message'] = 'Не правильный код картинки';
			}
    }else{
        $result['status'] = 'error';
        $result['message'] = 'Все поля обязательны для заполнения';
    }

    echo json_encode($result);
}
?>
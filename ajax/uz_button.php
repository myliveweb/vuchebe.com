<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule('iblock');

$error = array();
$result = array();

$user_id = 0;

if($_SESSION['USER_DATA']) {
    $user_id = $_SESSION['USER_DATA']['ID'];
}

$input = filter_input_array(INPUT_POST);

$result['id']    = (int) $input['id'];
$result['type']  = $input['type'];
$result['user']  = (int) $input['user'];
$result['uz_id'] = (int) $input['uz_id'];

if($result['id'] && $result['type']) {


    if($result['type'] === 'add') {

        $arrUz = array(2 => 'universities', 3 => 'colleges', 4 => 'schools', 6 => 'language-class');

        $row = array();

        $arSelect = array("ID", "NAME", "IBLOCK_ID");
        $arFilter = array("IBLOCK_ID" => 50, "ACTIVE" => "Y", "ID" => $result['id']);

        $res = CIBlockElement::GetList(array("ID" => "DESC"), $arFilter, false, false, $arSelect);
        if($obRes = $res->GetNextElement()) {

            $row   = $obRes->GetFields();
            $props = $obRes->GetProperties();

            $row['COUNTRY'] = $props['COUNTRY']['VALUE'];
            $row['REGION']  = $props['REGION']['VALUE'];
            $row['CITY']    = $props['CITY']['VALUE'];

            $row['COUNTRY_ID'] = $props['COUNTRY_ID']['VALUE'];
            $row['CITY_ID']    = $props['CITY_ID']['VALUE'];

            $row['ADRESS'] = $props['ADRESS']['VALUE'];
            $row['PHONE']  = $props['PHONE']['VALUE'];
            $row['EMAIL']  = $props['EMAIL']['VALUE'];
            $row['SITE']   = $props['SITE']['VALUE'];

            $row['TYPE']   = $props['TYPE']['VALUE'];
            $row['TICKET'] = $props['TICKET']['VALUE'];
            $row['AUTHOR'] = $props['AUTHOR']['VALUE'];

            $row['IBLOCK'] = $props['IBLOCK']['VALUE'];
        }

        if($row) {

            CIBlockElement::SetPropertyValueCode($result['id'], "ADD", 'Y');
            CIBlockElement::SetPropertyValueCode($result['id'], "PENDING", 'N');
            CIBlockElement::SetPropertyValueCode($result['id'], "DEL", 'N');

            $rowTest = array();

            $arSelectTest = array("ID", "NAME", "IBLOCK_ID");
            $arFilterTest = array("IBLOCK_ID" => $row['IBLOCK'], "ACTIVE" => "Y", "ID" => $result['uz_id']);

            $resTest = CIBlockElement::GetList(array("ID" => "DESC"), $arFilterTest, false, false, $arSelectTest);
            if($obResTest = $resTest->GetNextElement()) {
                $rowTest = $obResTest->GetFields();
            }

            if(!$rowTest['ID']) {

                $el = new CIBlockElement;

                $PROP = array();

                $PROP['FULL_NAME'] = $row['NAME'];
                $PROP['SITE'] = $row['SITE'];
                $PROP['PHONE'] = $row['PHONE'];
                $PROP['EMAIL'] = $row['EMAIL'];
                $PROP['ADRESS'] = $row['ADRESS'];
                $PROP['COUNTRY'] = $row['COUNTRY_ID'];
                $PROP['REGION'] = $row['REGION'];
                $PROP['CITY'] = $row['CITY_ID'];

                $arFields = array(
                    "MODIFIED_BY" => $USER->GetID(), // элемент изменен текущим пользователем
                    "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
                    "IBLOCK_ID" => $row['IBLOCK'],
                    "PROPERTY_VALUES" => $PROP,
                    "NAME" => $row['NAME'],
                    "ACTIVE" => "Y"
                );

                $code = strtolower(translit($row['NAME']));

                $arFields['CODE'] = $code;

                if ($ID = $el->Add($arFields)) {

                    $result['url'] = '/uchebnye-zavedeniya/' . $arrUz[$row['IBLOCK']] . '/' . $code . '/';

                    CIBlockElement::SetPropertyValueCode($result['id'], "UZ_ID", $ID);
                    $result['status'] = 'success';
                    $result['message'] = 'Ваша заявка отправлена';
                } else {
                    $result['status'] = 'error';
                    $result['message'] = html_entity_decode("Error: " . $el->LAST_ERROR);
                }
            }
        } else {
            $result['status'] = 'error';
            $result['message'] = 'Нудаётся найти заявку с ID: ' . $result['id'];
        }

    } elseif($result['type'] === 'pending') {

        CIBlockElement::SetPropertyValueCode($result['id'], "ADD", 'N');
        CIBlockElement::SetPropertyValueCode($result['id'], "PENDING", 'Y');
        CIBlockElement::SetPropertyValueCode($result['id'], "DEL", 'N');

        /*if($result['uz_id']) {
            //CIBlockElement::Delete($result['uz_id']);
        }*/

    } elseif($result['type'] === 'del') {

        $result['uz_id'] = (int) $input['uz_id'];

        CIBlockElement::SetPropertyValueCode($result['id'], "ADD", 'N');
        CIBlockElement::SetPropertyValueCode($result['id'], "PENDING", 'N');
        CIBlockElement::SetPropertyValueCode($result['id'], "DEL", 'Y');

    }

    /* Кто и когда изменял запись */
    CIBlockElement::SetPropertyValueCode($result['id'], "MODERATOR", $user_id);
    CIBlockElement::SetPropertyValueCode($result['id'], "MODERATE_TIME", date('d.m.Y H:i:s'));

    /* Сбор данных для отрисовки счётчиков */
    $arFilterCntNew = Array("IBLOCK_ID" => 50, "ACTIVE" => "Y", "!PROPERTY_ADD" => "Y", "!PROPERTY_PENDING" => "Y", "!PROPERTY_DEL" => "Y");
    $resCntNew = CIBlockElement::GetList(array(), $arFilterCntNew, Array(), false, Array());
    $result['NEW'] = $resCntNew ? $resCntNew : 0;

    $arFilterCntAdd = Array("IBLOCK_ID" => 50, "ACTIVE" => "Y", "PROPERTY_ADD" => "Y");
    $resCntAdd = CIBlockElement::GetList(array(), $arFilterCntAdd, Array(), false, Array());
    $result['ADD'] = $resCntAdd ? $resCntAdd : 0;

    $arFilterCntPending = Array("IBLOCK_ID" => 50, "ACTIVE" => "Y", "PROPERTY_PENDING" => "Y");
    $resCntPending = CIBlockElement::GetList(array(), $arFilterCntPending, Array(), false, Array());
    $result['PENDING'] = $resCntPending ? $resCntPending : 0;

    $arFilterCntDel = Array("IBLOCK_ID" => 50, "ACTIVE" => "Y", "PROPERTY_DEL" => "Y");
    $resCntDel = CIBlockElement::GetList(array(), $arFilterCntDel, Array(), false, Array());
    $result['DEL'] = $resCntDel ? $resCntDel : 0;

    $arFilterCntAll = Array("IBLOCK_ID" => 50, "ACTIVE" => "Y");
    $resCntAll = CIBlockElement::GetList(array(), $arFilterCntAll, Array(), false, Array());
    $result['ALL'] = $resCntAll ? $resCntAll : 0;

}

$data = $result ? array("status" => "success", "res" => $result ) : array("status" => "error", 'message' => 'Ошибка обработки запроса.');
die(json_encode($data));
?>
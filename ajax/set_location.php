<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once('function.php');

CModule::IncludeModule('iblock');

$error = 0;
$result = array();

global $USER;

$input = filter_input_array(INPUT_POST);

$type = $input['type'];

$user_id = 0;
if($_SESSION['USER_DATA'])
	$user_id = $_SESSION['USER_DATA']['ID'];

if(isEdit()) {

    $result['id']           = (int) $input['id'];
    $result['name']         = trim($input['name']);
    $result['country_id']   = (int) $input['country_id'];
    $result['region']       = trim($input['region']);
    $result['utc']          = trim($input['utc']);

    if($type == 'country') {
        $result['capital'] = trim($input['capital']);

        if(!$result['country_id']) {
            $result['country_id'] = array_shift(CIBlockSection::GetList(array(), array(
                'IBLOCK_ID' => 32,
                'NAME' => $result['name']
            ), false, array('ID'))->fetch());
        }

        if(!$result['country_id']) {

            $bs = new CIBlockSection;

            $arFields = array(
                "MODIFIED_BY"       => $USER->GetID(),
                "ACTIVE"            => "Y",
                "IBLOCK_SECTION_ID" => false,
                "IBLOCK_ID"         => 32,
                "NAME"              => $result['name'],
                "SORT"              => 500
            );

            $arFields['CODE'] = strtolower(translit($result['name']));

            $ID = $bs->Add($arFields);
            $res = ($ID > 0);

            if(!$res) {
                $result['message'] = html_entity_decode("Error: ".$bs->LAST_ERROR);
                $result['status'] = "error";
            } else {
                $result['country_id'] = $ID;
                $result['status'] = "success";
            }

            if($result['country_id'] && $result['capital'] && $result['region']) {

                $el = new CIBlockElement;

                list($utc, $temp) = explode(':', $result['utc']);
                $digit = (int) preg_replace('/[^\d-]/', '', $utc);
                $msk = (int) $digit - 3;

                if($msk == 0)
                    $chp = 'МСК';
                elseif($msk > 0)
                    $chp = 'МСК+' . $msk;
                elseif($msk < 0)
                    $chp = 'МСК' . $msk;

                $PROP = array();

                $PROP['REGION']     = $result['region'];
                $PROP['NALICHIE']   = 'Y';
                $PROP['TOP']        = 'Y';
                $PROP['TIP']        = '';
                $PROP['CHP']        = $chp;
                $PROP['UTM']        = $result['utc'];
                $PROP['CAPITAL']    = 'Y';
                $PROP['TOPCITY']    = 'Y';

                $arFields = array(
                    "MODIFIED_BY"       => $USER->GetID(),
                    "ACTIVE"            => "Y",
                    "IBLOCK_SECTION_ID" => $result['country_id'],
                    "IBLOCK_ID"         => 32,
                    "NAME"              => $result['capital'],
                    "SORT"              => 500,
                    "PROPERTY_VALUES"   => $PROP,
                );

                $arFields['CODE'] = strtolower(translit($result['capital']));

                if ($cityCapital = $el->Add($arFields)){
                    $result['status'] = 'success';
                    $result['message'] = 'Ваша заявка отправлена';
                    $result['capital_id'] = $cityCapital;
                } else {
                    $result['status'] = 'error';
                    $result['message'] = html_entity_decode("Error: ".$el->LAST_ERROR);
                }
            }
        }

        if($result['country_id']) {
            CIBlockElement::SetPropertyValueCode($result['id'], "COUNTRY", $result['name']);
            CIBlockElement::SetPropertyValueCode($result['id'], "COUNTRY_ID", $result['country_id']);
        }
    } elseif($type == 'city') {

        $result['city_id'] = (int) $input['city_id'];

        if(!$result['city_id']) {
            $result['city_id'] = array_shift(CIBlockElement::GetList(array(), array(
                'IBLOCK_ID'         => 32,
                'IBLOCK_SECTION_ID' => $result['country_id'],
                'PROPERTY_REGION'   => $result['region'],
                'NAME'              => $result['name']
            ), false, array('ID'))->fetch());
        }

        if(!$result['city_id']) {

            $el = new CIBlockElement;

            list($utc, $temp) = explode(':', $result['utc']);
            $digit = (int) preg_replace('/[^\d-]/', '', $utc);
            $msk = (int) $digit - 3;

            if($msk == 0)
                $chp = 'МСК';
            elseif($msk > 0)
                $chp = 'МСК+' . $msk;
            elseif($msk < 0)
                $chp = 'МСК' . $msk;

            $PROP = array();

            $PROP['REGION']     = $result['region'];
            $PROP['NALICHIE']   = 'N';
            $PROP['TOP']        = 'N';
            $PROP['TIP']        = '';
            $PROP['CHP']        = $chp;
            $PROP['UTM']        = $result['utc'];
            $PROP['CAPITAL']    = 'N';
            $PROP['TOPCITY']    = 'N';

            $arFields = array(
                "MODIFIED_BY"       => $USER->GetID(),
                "ACTIVE"            => "Y",
                "IBLOCK_SECTION_ID" => $result['country_id'],
                "IBLOCK_ID"         => 32,
                "NAME"              => $result['name'],
                "SORT"              => 500,
                "PROPERTY_VALUES"   => $PROP,
            );

            $arFields['CODE'] = strtolower(translit($result['name']));

            if ($cityNew = $el->Add($arFields)){
                $result['status'] = 'success';
                $result['message'] = 'Ваша заявка отправлена';
                $result['city_id'] = $cityNew;
            } else {
                $result['status'] = 'error';
                $result['message'] = html_entity_decode("Error: ".$el->LAST_ERROR);
            }
        }

        if($result['city_id']) {
            CIBlockElement::SetPropertyValueCode($result['id'], "CITY", $result['name']);
            CIBlockElement::SetPropertyValueCode($result['id'], "CITY_ID", $result['city_id']);
            CIBlockElement::SetPropertyValueCode($result['id'], "REGION", $result['region']);
            CIBlockElement::SetPropertyValueCode($result['id'], "REGION_ID", $result['city_id']);
        }
    }
}
$data = array('status' => 'success', "res" => $result);
die(json_encode($data));
?>
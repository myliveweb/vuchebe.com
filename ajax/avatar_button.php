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

$id          = (int) $input['id'];
$type        = $input['type'];
$userCurrent = (int) $input['user'];

if($id && $type && $userCurrent && isEdit()) {

    if($type === 'del-avatar') {

        $user = new CUser;
        $fields = Array(
            "PERSONAL_PHOTO" => CFile::MakeFileArray(SITE_TEMPLATE_PATH . "/images/user-1.png"),
        );
        $user->Update($userCurrent, $fields);

        CIBlockElement::SetPropertyValueCode($id, "DEL", 'Y');

        $arFilterDel = Array("IBLOCK_ID" => 27, "ACTIVE" => "Y", "PROPERTY_AUTHOR" => $userCurrent, "PROPERTY_DEL" => "Y");
        $cntDel = CIBlockElement::GetList(array(), $arFilterDel, Array(), false, Array());
        $result["DEL_CNT"] = $cntDel ? $cntDel : 0;

        $result['PIC'] = SITE_TEMPLATE_PATH . "/images/user-1.png";

    } elseif($type === 'reject') {

        CIBlockElement::SetPropertyValueCode($id, "REJECT", 'Y');

        $arFilterRej = Array("IBLOCK_ID" => 27, "ACTIVE" => "Y", "PROPERTY_AUTHOR" => $userCurrent, "PROPERTY_REJECT" => "Y");
        $cntRej = CIBlockElement::GetList(array(), $arFilterRej, Array(), false, Array());
        $result["REJECT_CNT"] = $cntRej ? $cntRej : 0;

    } elseif($type === 'warning') {

        CIBlockElement::SetPropertyValueCode($id, "WARNING", 'Y');

        $arFilterWarning = Array("IBLOCK_ID" => 27, "ACTIVE" => "Y", "PROPERTY_AUTHOR" => $userCurrent, "PROPERTY_WARNING" => "Y");
        $cntWarning = CIBlockElement::GetList(array(), $arFilterWarning, Array(), false, Array());
        $result["WARNING_CNT"] = $cntWarning ? $cntWarning : 0;

    } elseif($type === 'ban') {

    } elseif($type === 'del-user') {

        $arId = array();
        $arSelect = array("ID", "IBLOCK_ID");
        $arFilter = array("IBLOCK_ID" => 27, "PROPERTY_AUTHOR" => $userCurrent);
        $res = CIBlockElement::GetList(array("NAME" => "ASC"), $arFilter, false, false, $arSelect);
        while($row = $res->GetNext()) {
            $arId[] = $row["ID"];
        }

        foreach($arId as $item) {
            CIBlockElement::Delete($item);
        }

        CUser::Delete($userCurrent);

        $result['OUT'] = $arId;

    } elseif($type === 'deactivate') {

        $el = new CIBlockElement;
        $fields = array(
            "ACTIVE" => "N",
        );
        $el->Update($id, $fields);

    }

    /* Кто и когда изменял запись */
    CIBlockElement::SetPropertyValueCode($id, "MODERATOR", $user_id);
    CIBlockElement::SetPropertyValueCode($id, "MODERATE_TIME", date('d.m.Y H:i:s'));

    /* Сбор данных для отрисовки счётчиков */
    $arFilterCntNew = Array("IBLOCK_ID" => 27, "ACTIVE" => "Y", "!PROPERTY_WARNING" => "Y", "!PROPERTY_REJECT" => "Y", "!PROPERTY_DEL" => "Y");
    $resCntNew = CIBlockElement::GetList(array(), $arFilterCntNew, Array(), false, Array());
    $result['NEW'] = $resCntNew ? $resCntNew : 0;

    $arFilterCntRej = Array("IBLOCK_ID" => 27, "ACTIVE" => "Y", "PROPERTY_REJECT" => "Y");
    $resCntRej = CIBlockElement::GetList(array(), $arFilterCntRej, Array(), false, Array());
    $result['REJECT'] = $resCntRej ? $resCntRej : 0;

    $arFilterCntWarning = Array("IBLOCK_ID" => 27, "ACTIVE" => "Y", "PROPERTY_WARNING" => "Y");
    $resCntWarning = CIBlockElement::GetList(array(), $arFilterCntWarning, Array(), false, Array());
    $result['WARNING'] = $resCntWarning ? $resCntWarning : 0;

    $arFilterCntDel = Array("IBLOCK_ID" => 27, "ACTIVE" => "Y", "PROPERTY_DEL" => "Y");
    $resCntDel = CIBlockElement::GetList(array(), $arFilterCntDel, Array(), false, Array());
    $result['DEL'] = $resCntDel ? $resCntDel : 0;

    $arFilterCntAll = Array("IBLOCK_ID" => 27, "ACTIVE" => "Y");
    $resCntAll = CIBlockElement::GetList(array(), $arFilterCntAll, Array(), false, Array());
    $result['ALL'] = $resCntAll ? $resCntAll : 0;

}

$data = $result ? array("status" => "success", "res" => $result ) : array("status" => "error", 'message' => 'Ошибка обработки запроса.');
die(json_encode($data));
?>
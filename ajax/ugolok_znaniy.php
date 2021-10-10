<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
?>
<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){

    if(!empty($_REQUEST['id'])){

		global $USER;
		global $DB;

		CModule::IncludeModule('iblock');

		$id = (int) strip_tags($_REQUEST['id']);

		$arSelect = Array("ID", "NAME", "IBLOCK_ID", "DETAIL_PAGE_URL", "PREVIEW_TEXT");
		$arFilter = Array("IBLOCK_ID"=>5, "ACTIVE"=>"Y", "!=ID"=>$id);
		$res = CIBlockElement::GetList(Array("RAND"=>"ASC"), $arFilter, false, Array("nPageSize"=>1), $arSelect);
		if($ob = $res->GetNextElement())
		{
			$arFields = $ob->GetFields();
			$arProps = $ob->GetProperties();

			$result['status'] = 'success';
			$result['id_ug'] = $arFields['ID'];
			$result['message'] = mb_substr($arFields['PREVIEW_TEXT'], 0, 140) . '..';
			$result['link'] = $arFields['DETAIL_PAGE_URL'];
			$result['sign'] = $arProps["SIGN"]["~VALUE"];
		} else {
			$result['status'] = 'error';
			$result['message'] = html_entity_decode("Error: Записи не найдены");
		}
    }else{
        $result['status'] = 'error';
        $result['message'] = 'Исключения не заданы';
    }

    echo json_encode($result);
}
?>
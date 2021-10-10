<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule('iblock');

$input = filter_input_array(INPUT_POST);

$iblock = $input['iblock'];
if(!$iblock)
	$iblock = 2;

$result = array();

$user_id = 0;
$user_name = 'Аноним';
if($_SESSION['USER_DATA']) {
	$user_id = $_SESSION['USER_DATA']['ID'];
	$user_name = $_SESSION['USER_DATA']['FULL_NAME'];
}

$isAdmin = 0;
$arrAdmins = array();

$resAdmins = CIBlockElement::GetProperty($iblock, $input['id_vuz'], "sort", "asc", array("CODE" => "ADMINS"));
while ($obAdmins = $resAdmins->GetNext()) {
    $arrAdmins[] = $obAdmins['VALUE'];
}

if(in_array($user_id, $arrAdmins) || isEdit())
	$isAdmin = 1;

if($_FILES['my'] && $isAdmin) {
	$file = $_FILES['my'];

	$file_name = $file['name'];
	$arrExt = explode('.', $file_name);
	$ext = strtolower($arrExt[count($arrExt)-1]);
	$filename = mb_substr(md5(microtime() . rand(0, 9999)), 0, 20) . '.' . $ext;

	$search_path = $_SERVER["DOCUMENT_ROOT"] . '/upload/news_img/' . $filename;
	if(move_uploaded_file($file['tmp_name'], $search_path)) {
		if($input['id_block']) {
			global $USER;
			global $DB;

			$arrIbNews = array(1 => 31, 2 => 22, 3 => 28, 4 => 29, 6 => 30);
			$ibNews = $arrIbNews[$iblock];

			if($ext == 'jpeg' || $ext == 'jpg') {
                shell_exec("jpegoptim --max=100 " . $search_path);
            } elseif($ext == 'png') {
                shell_exec("optipng -o7 " . $search_path);
            }

	        $arFile = CFile::MakeFileArray($search_path);

			$el = new CIBlockElement;

			$arLoadProductArray = Array(
				"MODIFIED_BY"    	=> $USER->GetID(),
				"IBLOCK_SECTION" 	=> false,
				"PREVIEW_PICTURE"	=> $arFile
			);

			$el->Update($input['id_block'], $arLoadProductArray);

			$morePhoto = array();

		    $arSelect = array("ID", "NAME", "IBLOCK_ID", "PREVIEW_PICTURE");
		    $arFilter = array("IBLOCK_ID" => $ibNews, "ACTIVE" => "Y", "ID" => $input['id_block']);
		    $res = CIBlockElement::GetList(array("NAME" => "ASC"), $arFilter, false, false, $arSelect);
		    if($row = $res->GetNext()) {
			    $pathMP = CFile::GetPath($row['PREVIEW_PICTURE']);
			    $morePhoto = array('SRC' => $pathMP, 'ID' => $row['PREVIEW_PICTURE']);
			}

	    } else {
			$morePhoto = array('SRC' => '/upload/news_img/' . $filename, 'ID' => 0);
	    }

	    $result["PHOTO"] = $morePhoto;
	}
}

$data = $result ? $result : array('error' => 'Ошибка загрузки файлов.');

die(json_encode($data));
?>
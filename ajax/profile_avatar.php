<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if($_FILES['my']) {
	$file = $_FILES['my'];
	$typeUpload = $_POST['tu'];

	$iblock = $_POST['iblock'];
	if(!$iblock)
		$iblock = 2;

	$file_name = $file['name'];
	$arrExt = explode('.', $file_name);
	$ext = strtolower($arrExt[count($arrExt)-1]);
	$filename = mb_substr(md5(microtime() . rand(0, 9999)), 0, 20) . '.' . $ext;

  $uploadPath = '/upload/' . $typeUpload . '/' . $filename;

	$search_path = $_SERVER["DOCUMENT_ROOT"] . $uploadPath;
	if(move_uploaded_file($file['tmp_name'], $search_path)) {

		global $USER;
		global $DB;

        if($ext == 'jpeg' || $ext == 'jpg') {
            shell_exec("jpegoptim --max=85 " . $search_path);
        } elseif($ext == 'png') {
            shell_exec("optipng -o5 -strip all " . $search_path);
        }

        $arFile = CFile::MakeFileArray($search_path);

		if($typeUpload == 'avatar') {
			$arFile['del'] = "Y";
	        $arFile['old_file'] = $_SESSION['USER_DATA']['PERSONAL_PHOTO'];

			$user = new CUser;
			$fields = Array(
			  	"PERSONAL_PHOTO"    => $arFile,
			);

			if ($user->Update($USER->GetId(), $fields)) {

				$rsUser = CUser::GetByID($USER->GetId());
				$_SESSION['USER_DATA'] = $rsUser->Fetch();

				$result['status'] = 'success';
				$result['file'] = CFile::GetPath($_SESSION['USER_DATA']['PERSONAL_PHOTO']);
			} else {
				$result['status'] = 'error';
				$result['message'] = html_entity_decode("Error: " . $user->LAST_ERROR);
			}
		} elseif($typeUpload == 'logo') {
			CModule::IncludeModule('iblock');

			$id_vuz = $_POST['vuz'];

			$PropFileArr['LOGO'] = array('VALUE' => $arFile, 'DESCRIPTION' => '');
			CIBlockElement::SetPropertyValuesEx($id_vuz, $iblock, $PropFileArr);

		    $resLogo = CIBlockElement::GetProperty($iblock, $id_vuz, "sort", "asc", array("CODE" => "LOGO"));
		    if($obLogo = $resLogo->GetNext())
		    {
		    	if($obLogo['VALUE'])
		    		$result['file'] = CFile::GetPath($obLogo['VALUE']);
					$result['status'] = 'success';
		    }
		} elseif($typeUpload == 'history') {
			CModule::IncludeModule('iblock');

			$id_vuz = $_POST['vuz'];

			$PropFileArr['PHOTO_HISTORY'] = array('VALUE' => $arFile, 'DESCRIPTION' => '');
			CIBlockElement::SetPropertyValuesEx($id_vuz, $iblock, $PropFileArr);

		    $resLogo = CIBlockElement::GetProperty($iblock, $id_vuz, "sort", "asc", array("CODE" => "PHOTO_HISTORY"));
		    if($obLogo = $resLogo->GetNext())
		    {
		    	if($obLogo['VALUE'])
		    		$result['file'] = CFile::GetPath($obLogo['VALUE']);
					$result['status'] = 'success';
		    }
		} elseif($typeUpload == 'banner') {
		  $result['file'] = $uploadPath;
			$result['status'] = 'success';
		}

	}
}

$data = $result ? $result : array('error' => 'Ошибка загрузки файлов.');

die(json_encode($data));
?>
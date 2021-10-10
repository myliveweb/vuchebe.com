<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $USER;

$error = array();
$result = array();

$input = filter_input_array(INPUT_POST);

$data        = $input['image'];
$type_avatar = $input['type_avatar'];
$idChat      = $input['id'];

list($type, $data) = explode(';', $data);
list(, $data)      = explode(',', $data);
$data = base64_decode($data);

if($type_avatar == 'user') {

    CModule::IncludeModule('iblock');

    if ($obLogo['VALUE'])
        $result['file'] = CFile::GetPath($data);

    $imageName = time() . '.png';
    $full_path = $_SERVER["DOCUMENT_ROOT"] . '/upload/crop/' . $imageName;
    file_put_contents($full_path, $data);
    shell_exec("optipng -o4 " . $full_path);

    $arFile['del'] = "Y";
    $arFile['old_file'] = $_SESSION['USER_DATA']['PERSONAL_PHOTO'];

    $arFile = CFile::MakeFileArray($full_path);

    $user = new CUser;
    $fields = array(
        "PERSONAL_PHOTO" => $arFile,
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

} elseif($type_avatar == 'group') {

    require_once('function.php');

    $imageName = time() . '.png';
    $full_path = $_SERVER["DOCUMENT_ROOT"] . '/upload/crop_group_chat/' . $imageName;
    file_put_contents($full_path, $data);
    shell_exec("optipng -o5 " . $full_path);

    $shortAvatar = '/upload/crop_group_chat/' . $imageName;

    if($idChat) {
        $stmt= $dbh->prepare('UPDATE a_group_chat SET avatar = "' . $shortAvatar . '"  WHERE id = ' . $idChat);
        $stmt->execute();
    }

    $result['status'] = 'success';
    $result['file'] = $shortAvatar;

} elseif($type_avatar == 'ugolok') {

    CModule::IncludeModule('iblock');

    $imageName = time() . '.png';
    $full_path = $_SERVER["DOCUMENT_ROOT"] . '/upload/crop/' . $imageName;
    file_put_contents($full_path, $data);
    shell_exec("optipng -o4 " . $full_path);

    $arFile = CFile::MakeFileArray($full_path);

    $el = new CIBlockElement;

    $dataArray = Array(
        "MODIFIED_BY"       => $USER->GetID(),
        "ACTIVE"            => "Y",
        "DETAIL_PICTURE"    => $arFile
    );

    if ($el->Update($idChat, $dataArray)) {

        $arSelectLaw = array("ID", "IBLOCK_ID", "NAME", "DETAIL_PICTURE");
        $arFilterLaw = array("IBLOCK_ID" => 5, "ACTIVE" => "Y", "ID" => $idChat);
        $resLaw = CIBlockElement::GetList(array("SORT" => "ASC", "ID" => "ASC"), $arFilterLaw, false, false, $arSelectLaw);
        if($rowLaw = $resLaw->GetNext()) {
            $result['status'] = 'success';
            $result['file'] = CFile::GetPath($rowLaw['DETAIL_PICTURE']);
        }
    } else {
        $result['status'] = 'error';
        $result['message'] = html_entity_decode("Error: " . $user->LAST_ERROR);
    }
} elseif($type_avatar == 'top-banner' || $type_avatar == 'side-banner') {

    $imageName = time() . '.png';
    $full_path = $_SERVER["DOCUMENT_ROOT"] . '/upload/crop_banner/' . $imageName;
    file_put_contents($full_path, $data);
    shell_exec("optipng -o5 " . $full_path);

    $shortAvatar = '/upload/crop_banner/' . $imageName;

    $result['status'] = 'success';
    $result['file'] = $shortAvatar;

}

$data = $result ? $result : array('error' => 'Ошибка загрузки файлов.');

die(json_encode($data));
?>
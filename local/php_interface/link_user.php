<?php

function getUserIdByUrl($url) {

    $filter = array("ACTIVE" => "Y", "WORK_PHONE" => $url);
    $rsUsers = CUser::GetList($by="ID", $order="ASC", $filter);
    if($curUser = $rsUsers->Fetch()) {
        return (int) $curUser['ID'];
    } else {
        return false;
    }
}

function getUserUrl($arrUser) {

    if ($arrUser['WORK_PHONE'] != '') {
        return $arrUser['WORK_PHONE'];
    } else {
        return $arrUser['ID'];
    }
}
?>
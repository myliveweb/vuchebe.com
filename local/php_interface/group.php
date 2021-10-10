<?php

function isEdit() { // Проверка админских прав

    global $USER;

    $arGroups = CUser::GetUserGroup($USER->GetID());
    if(in_array(8, $arGroups) || $USER->IsAdmin()) {
        return true;
    }

    return false;
}

function isEditPlus() { // Проверка админских прав + Прав администратора учебного заведения

    global $USER;

    $arGroups = CUser::GetUserGroup($USER->GetID());
    if(in_array(8, $arGroups) || in_array(9, $arGroups) || $USER->IsAdmin()) {
        return true;
    }

    return false;
}

function getGroup() { // Получение группы текущего пользователя

    global $USER;

    $arGroups = CUser::GetUserGroup($USER->GetID());

    if(in_array(1, $arGroups)) {
        return 1;
    } elseif(in_array(8, $arGroups)) {
        return 8;
    } elseif(in_array(6, $arGroups)) {
        return 6;
    } elseif(in_array(7, $arGroups)) {
        return 7;
    } else {
        return 2;
    }
}

function getGroupById($id) { // Получение группы по Id

    global $USER;

    $arGroups = CUser::GetUserGroup($id);

    if(in_array(1, $arGroups)) {
        return 1;
    } elseif(in_array(8, $arGroups)) {
        return 8;
    } elseif(in_array(6, $arGroups)) {
        return 6;
    } elseif(in_array(7, $arGroups)) {
        return 7;
    } else {
        return 2;
    }
}

function getGroupName() { // Получение имени группы текущего пользователя

    $groupName = array('',
        'Администратор',
        'Пользователь сайта',
        '',
        '',
        '',
        'Бизнес-аккаунт (Юридическое лицо)',
        'Бизнес-аккаунт (Физическое лицо)',
        'Контент-менеджер');

    $group = getGroup();

    return $groupName[$group];
}

function isSupport($id) { // Проверка прав техподдержки

    global $USER;

    $arGroups = CUser::GetUserGroup($id);
    if(in_array(8, $arGroups)) {
        return true;
    }

    return false;
}

?>
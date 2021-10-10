<?
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2014 Bitrix
 */

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @param array $arParams
 * @param array $arResult
 * @param CBitrixComponentTemplate $this
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?><?$APPLICATION->IncludeComponent(
    "bitrix:main.profile",
    "profile",
    Array(
        "AJAX_MODE" => "Y",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "CHECK_RIGHTS" => "N",
        "COMPONENT_TEMPLATE" => "profile",
        "SEND_INFO" => "N",
        "SET_TITLE" => "Y",
        "USER_PROPERTY" => array(),
        "USER_PROPERTY_NAME" => ""
    )
);?>
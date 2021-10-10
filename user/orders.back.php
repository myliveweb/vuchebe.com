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
?><?
$_REQUEST['show_all']='Y';
$APPLICATION->IncludeComponent(
    "bitrix:sale.personal.order.list",
    ".default",
    array(
        "STATUS_COLOR_N" => "green",
        "STATUS_COLOR_P" => "yellow",
        "STATUS_COLOR_F" => "gray",
        "STATUS_COLOR_PSEUDO_CANCELLED" => "red",
        "PATH_TO_DETAIL" => "order_detail.php?ID=#ID#",
        "PATH_TO_COPY" => "basket.php",
        "PATH_TO_CANCEL" => "order_cancel.php?ID=#ID#",
        "PATH_TO_BASKET" => "/basket/",
        "PATH_TO_PAYMENT" => "/order/make/payment.php",
        "ORDERS_PER_PAGE" => "20",
        "ID" => $ID,
        "SET_TITLE" => "Y",
        "SAVE_IN_SESSION" => "Y",
        "NAV_TEMPLATE" => "",
        "CACHE_TYPE" => "N",
        "CACHE_TIME" => "3600",
        "CACHE_GROUPS" => "Y",
        "HISTORIC_STATUSES" => array(
        ),
        "ACTIVE_DATE_FORMAT" => "d.m.Y",
        "COMPONENT_TEMPLATE" => ".default",
        "PATH_TO_CATALOG" => "/catalog/",
        "RESTRICT_CHANGE_PAYSYSTEM" => array(
            0 => "0",
        ),
        "REFRESH_PRICES" => "N",
        "DEFAULT_SORT" => "DATE_INSERT",
        "ALLOW_INNER" => "N",
        "ONLY_INNER_FULL" => "N"
    ),
    false
); ?>
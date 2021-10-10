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
$APPLICATION->SetTitle("Бонусная программа");
?><?$arFilter = Array(
    "USER_ID" => $USER->GetID());

$db_sales = CSaleOrder::GetList(array("DATE_INSERT" => "ASC"), $arFilter);
$sum=0;
while ($ar_sales = $db_sales->Fetch())
{
    if ($ar_sales["PAYED"]=='Y')
        $sum+=$ar_sales["PRICE"];

}
if ($sum<5000){
    $dis = 0;
} elseif ($sum>=5000&&$sum<8000) {
    $dis = 5;
}elseif ($sum>=8000&&$sum<12000){
    $dis = 8;
}elseif ($sum>=12000&&$sum<16000){
    $dis = 10;
}
elseif ($sum>=16000&&$sum<20000){
    $dis = 13;
}elseif ($sum>=20000){
    $dis = 15;
}?>
<div class="banner banner--dark">
    <div class="banner-block banner-img">
        <img src="/i/personal/bonus.png" alt="Бонусы">
    </div>
    <div class="banner-block banner-content">
        <div class="banner-h">
            Ваша персональная скидка: <?=$dis?>%
        </div>
        <div class="banner-text">
            Общая сумма заказов достигла <?=$sum?> руб.
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-7">
        <div class="WYSIWYG">
            <h4>Условия бонусной программы</h4>
            <p>
                Размер скидки зависит от суммы совершенных покупок. У нас есть отличные подарки для ваших детей! Не забудьте ответить на несколько вопросов в разделе <a href="/profile/children/">«Мой ребенок»</a>. Чем подробнее вы заполните анкету, тем лучше мы сможем подобрать подходящие именно вашему ребенку подарки.
            </p>
            <p>
                Накопительная скидка не суммируется с товарами по акциям!
            </p>
        </div>
    </div>
    <div class="col-md-5">
        <div class="WYSIWYG">
            <table>
                <thead>
                <tr>
                    <th>
                        Накопленная сумма
                    </th>
                    <th>
                        Скидка
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        5 000 - 8 000 руб.
                    </td>
                    <td>
                        5%
                    </td>
                </tr>
                <tr>
                    <td>
                        8 000 - 12 000 руб.
                    </td>
                    <td>
                        8%
                    </td>
                </tr>
                <tr>
                    <td>
                        12 000 - 16 000 руб.
                    </td>
                    <td>
                        10%
                    </td>
                </tr>
                <tr>
                    <td>
                        16 000 - 20 000 руб.
                    </td>
                    <td>
                        13%
                    </td>
                </tr>
                <tr>
                    <td>
                        от 20 000 руб.
                    </td>
                    <td>
                        15%
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

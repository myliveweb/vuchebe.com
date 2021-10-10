<?php
function getRandomBanner($iblock = 34, $width = 428, $height = 60, $load = array()) {

    global $USER;
    global $dbh;

    $useID = array();
    $arrBanner = array();

    $arSelect = array("ID", "NAME", "IBLOCK_ID", "PREVIEW_PICTURE");

    if(isset($_SESSION['PANEL']['CITY']) && $_SESSION['PANEL']['CITY'] && $_SESSION['PANEL']['TOPCITY'])
        $arFilter = array("IBLOCK_ID" => $iblock, "ACTIVE" => "Y", "PROPERTY_MODERATION" => "Y", "PROPERTY_LAUNCHED" => "Y", "PROPERTY_CITY" => $_SESSION['PANEL']['CITY']);
    elseif(isset($_SESSION['PANEL']['REGION']) && $_SESSION['PANEL']['REGION'])
        $arFilter = array("IBLOCK_ID" => $iblock, "ACTIVE" => "Y", "PROPERTY_MODERATION" => "Y", "PROPERTY_LAUNCHED" => "Y", "PROPERTY_REGION" => $_SESSION['PANEL']['REGION']);
    elseif(isset($_SESSION['PANEL']['COUNTRY']) && $_SESSION['PANEL']['COUNTRY'])
        $arFilter = array("IBLOCK_ID" => $iblock, "ACTIVE" => "Y", "PROPERTY_MODERATION" => "Y", "PROPERTY_LAUNCHED" => "Y", "PROPERTY_COUNTRY" => $_SESSION['PANEL']['COUNTRY']);
    else
        $arFilter = array("IBLOCK_ID" => $iblock, "ACTIVE" => "Y", "PROPERTY_MODERATION" => "Y", "PROPERTY_LAUNCHED" => "Y");

    $res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
    while($obRes = $res->GetNextElement()) {

        $row = $obRes->GetFields();
        $props = $obRes->GetProperties();

        $row['PROPERTY_URL_VALUE']          = $props['URL']['VALUE'];
        $row['PROPERTY_COUNTER_VALUE']      = $props['COUNTER']['VALUE'];
        $row['PROPERTY_LIMIT_VALUE']        = $props['LIMIT']['VALUE'];
        $row['PROPERTY_PLAN_TAX_VALUE']     = $props['PLAN_TAX']['VALUE'];
        $row['PROPERTY_BALANCE_VALUE']      = $props['BALANCE']['VALUE'];
        $row['PROPERTY_OWNER_VALUE']        = $props['OWNER']['VALUE'];
        $row['PROPERTY_DISCOUNT_VALUE']     = $props['DISCOUNT']['VALUE'];
        $row['PROPERTY_LIMIT_PROMO_VALUE']  = $props['LIMIT_PROMO']['VALUE'];
        $row['PROPERTY_LIMIT_CURENT_VALUE'] = $props['LIMIT_CURENT']['VALUE'];

        $taxDiscount   = 0;
        $limitDiscount = 0;

        if($row['PROPERTY_DISCOUNT_VALUE']) {
            if ($row['PROPERTY_LIMIT_PROMO_VALUE'] > 0) {
                if($row['PROPERTY_LIMIT_CURENT_VALUE'] > 0) {
                    $sumDiscount = $row['PROPERTY_PLAN_TAX_VALUE'] / 100 * $row['PROPERTY_DISCOUNT_VALUE'];
                    $taxDiscount = $row['PROPERTY_PLAN_TAX_VALUE'] - $sumDiscount;
                    $limitDiscount = $row['PROPERTY_LIMIT_CURENT_VALUE'] - 1;
                } else {
                    $taxDiscount = $row['PROPERTY_PLAN_TAX_VALUE'];
                }
            } else {
                $sumDiscount = $row['PROPERTY_PLAN_TAX_VALUE'] / 100 * $row['PROPERTY_DISCOUNT_VALUE'];
                $taxDiscount = $row['PROPERTY_PLAN_TAX_VALUE'] - $sumDiscount;
            }
        } else {
            $taxDiscount = $row['PROPERTY_PLAN_TAX_VALUE'];
        }

        $taxDiscountRound = round($taxDiscount, 2);

        if((int) $row['PROPERTY_LIMIT_VALUE'] > (int) $row['PROPERTY_COUNTER_VALUE'] && $row['PROPERTY_BALANCE_VALUE'] >= $taxDiscountRound) {
            $useID[] = $row['ID'];
            $arrBanner[] = array($row['ID'], $row['PREVIEW_PICTURE'], $row['PROPERTY_URL_VALUE'], (int) $row['PROPERTY_COUNTER_VALUE'], (int) $row['PROPERTY_OWNER_VALUE'], $taxDiscountRound, $row['NAME'], $row['PROPERTY_LIMIT_VALUE'], $limitDiscount);
        }
    }

    $arFilter = array("IBLOCK_ID" => $iblock, "ACTIVE" => "Y", "PROPERTY_MODERATION" => "Y", "PROPERTY_LAUNCHED" => "Y", "!ID" => $useID, "PROPERTY_COUNTRY" => false);

    $res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
    while($obRes = $res->GetNextElement()) {

        $row = $obRes->GetFields();
        $props = $obRes->GetProperties();

        $row['PROPERTY_URL_VALUE']          = $props['URL']['VALUE'];
        $row['PROPERTY_COUNTER_VALUE']      = $props['COUNTER']['VALUE'];
        $row['PROPERTY_LIMIT_VALUE']        = $props['LIMIT']['VALUE'];
        $row['PROPERTY_PLAN_TAX_VALUE']     = $props['PLAN_TAX']['VALUE'];
        $row['PROPERTY_BALANCE_VALUE']      = $props['BALANCE']['VALUE'];
        $row['PROPERTY_OWNER_VALUE']        = $props['OWNER']['VALUE'];
        $row['PROPERTY_DISCOUNT_VALUE']     = $props['DISCOUNT']['VALUE'];
        $row['PROPERTY_LIMIT_PROMO_VALUE']  = $props['LIMIT_PROMO']['VALUE'];
        $row['PROPERTY_LIMIT_CURENT_VALUE'] = $props['LIMIT_CURENT']['VALUE'];

        $taxDiscount   = 0;
        $limitDiscount = 0;

        if($row['PROPERTY_DISCOUNT_VALUE']) {
            if ($row['PROPERTY_LIMIT_PROMO_VALUE'] > 0) {
                if($row['PROPERTY_LIMIT_CURENT_VALUE'] > 0) {
                    $sumDiscount = $row['PROPERTY_PLAN_TAX_VALUE'] / 100 * $row['PROPERTY_DISCOUNT_VALUE'];
                    $taxDiscount = $row['PROPERTY_PLAN_TAX_VALUE'] - $sumDiscount;
                    $limitDiscount = $row['PROPERTY_LIMIT_CURENT_VALUE'] - 1;
                } else {
                    $taxDiscount = $row['PROPERTY_PLAN_TAX_VALUE'];
                }
            } else {
                $sumDiscount = $row['PROPERTY_PLAN_TAX_VALUE'] / 100 * $row['PROPERTY_DISCOUNT_VALUE'];
                $taxDiscount = $row['PROPERTY_PLAN_TAX_VALUE'] - $sumDiscount;
            }
        } else {
            $taxDiscount = $row['PROPERTY_PLAN_TAX_VALUE'];
        }

        $taxDiscountRound = round($taxDiscount, 2);

        if((int) $row['PROPERTY_LIMIT_VALUE'] > (int) $row['PROPERTY_COUNTER_VALUE'] && $row['PROPERTY_BALANCE_VALUE'] >= $taxDiscountRound) {
            $useID[] = $row['ID'];
            $arrBanner[] = array($row['ID'], $row['PREVIEW_PICTURE'], $row['PROPERTY_URL_VALUE'], (int) $row['PROPERTY_COUNTER_VALUE'], (int) $row['PROPERTY_OWNER_VALUE'], $taxDiscountRound, $row['NAME'], $row['PROPERTY_LIMIT_VALUE'], $limitDiscount);
        }
    }

    if(sizeof($arrBanner) > 0) {
        shuffle($arrBanner);
        $currentBanner = $arrBanner[0];
        $newCounter = $currentBanner[3] + 1;

        $clickBanner = 0;
        if($_SERVER['HTTP_USER_AGENT'] && !preg_match("/Bot|CensysInspect|curl|Embarcadero|Rambler|Yahoo|zgrab|Go-http|Banner|libwww|Go http|python|Research/i", $_SERVER['HTTP_USER_AGENT']) && stripos($_SERVER['PHP_SELF'], 'urlrewrite.php') === false && stripos($_SERVER['PHP_SELF'], '404.php') === false) {
            CIBlockElement::SetPropertyValueCode($currentBanner[0], "COUNTER", $newCounter);
            $clickBanner = 1;

            $rsUser = CUser::GetByID($currentBanner[4]);
            $owner = $rsUser->Fetch();

            $free = $owner['WORK_FAX'] - $owner['WORK_PAGER'] - $currentBanner[5];
            $free = round($free, 2);

            $user = new CUser;

            $many = $owner['WORK_FAX'] - $currentBanner[5];
            $manyRound = round($many, 2);
            $fields = Array(
                "WORK_FAX" => $manyRound,
            );

            $user->Update($currentBanner[4], $fields);

            $arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_OWNER");
            $arFilter = array("IBLOCK_ID" => array(34, 35), "ACTIVE" => "Y", "PROPERTY_OWNER" => $currentBanner[4]);
            $res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
            while($row = $res->GetNext()) {

                CIBlockElement::SetPropertyValueCode($row["ID"], "BALANCE", $free);
            }

            $createTime = time();

            $stmt = $dbh->prepare("INSERT INTO a_balance_history (user_id, tax, free, hold, balance, direction, disc, create_at, date_at) VALUES (:user_id, :tax, :free, :hold, :balance, 0, 'Оплата услуг', :create_at, NOW())");
            $stmt->bindParam(':user_id', $currentBanner[4], PDO::PARAM_INT);
            $stmt->bindParam(':tax', $currentBanner[5]);
            $stmt->bindParam(':free', $free);
            $stmt->bindParam(':hold', $owner['WORK_PAGER']);
            $stmt->bindParam(':balance', $manyRound);
            $stmt->bindParam(':create_at', $createTime, PDO::PARAM_INT);
            $stmt->execute();

            setBannerHistory(7, $currentBanner[0], $currentBanner[4], $currentBanner[5]);

            $arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_OWNER", "PROPERTY_PLAN_TAX");
            $arFilter = array("IBLOCK_ID" => array(34, 35), "ACTIVE" => "Y", "PROPERTY_OWNER" => $currentBanner[4], "PROPERTY_LAUNCHED" => "Y");
            $res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
            while($row = $res->GetNext()) {
                if($row['PROPERTY_PLAN_TAX_VALUE'] > $free) {
                    CIBlockElement::SetPropertyValueCode($row["ID"], "LAUNCHED", "N");
                    setBannerHistory(6, $row["ID"], $row["PROPERTY_OWNER_VALUE"], 0);
                }
            }

            $arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_OWNER", "PROPERTY_COUNTER", "PROPERTY_LIMIT");
            $arFilter = array("IBLOCK_ID" => array(34, 35), "ACTIVE" => "Y", "PROPERTY_OWNER" => $currentBanner[4], "PROPERTY_LAUNCHED" => "Y");
            $res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
            while($row = $res->GetNext()) {
                if($row['PROPERTY_COUNTER_VALUE'] >= $row['PROPERTY_LIMIT_VALUE']) {
                    CIBlockElement::SetPropertyValueCode($row["ID"], "LAUNCHED", "N");
                    setBannerHistory(8, $row["ID"], $row["PROPERTY_OWNER_VALUE"], 0);
                }
            }

            if($currentBanner[8] < 0) {
                $currentLimit = 0;
            } else {
                $currentLimit = $currentBanner[8];
            }
            CIBlockElement::SetPropertyValueCode($currentBanner[0], "LIMIT_CURENT", $currentLimit);
        }

        $fileBanner = CFile::ResizeImageGet($currentBanner[1], array('width' => $width, 'height' => $height));
        return array($currentBanner[0], $fileBanner['src'], $currentBanner[2], ' target="blank"', $clickBanner, $currentBanner[6]);
    } else {
        if($iblock == 35) {
            $iblockZagluchka = 49;
        } else {
            $iblockZagluchka = 48;
        }

        $arSelect = array("ID", "NAME", "IBLOCK_ID", "PREVIEW_PICTURE", "PROPERTY_URL");
        $arFilter = array("IBLOCK_ID" => $iblockZagluchka, "ACTIVE" => "Y");
        $res = CIBlockElement::GetList(array("RAND" => "ASC"), $arFilter, false, false, $arSelect);
        if($row = $res->GetNext()) {

            $fileBanner = CFile::ResizeImageGet($row['PREVIEW_PICTURE'], array('width' => $width, 'height' => $height));
            return array(0, $fileBanner['src'], $row['PROPERTY_URL_VALUE'], ' target="blank"', $clickBanner, $row['NAME']);
        }

        if($iblock == 35) {
            return array(0, '/local/templates/vuchebe/images/empty-img.png', 'https://www.korablik.ru/', ' target="blank"', $clickBanner, 'Кораблик');
        } else {
            return array(0, '/local/templates/vuchebe/img/banner.png', 'https://www.detmir.ru/', ' target="blank"', $clickBanner, 'Детский мир');
        }
    }
}

function setBannerHistory($status, $banner, $user_id = 0, $tax = 0) {

    global $dbh;

    $arrayStatus = array(
            'Заказ оформлен',
            'Баннер прошёл модерацию',
            'Баннер включен',
            'Баннер остановлен',
            'Баннер скрыт',
            'Переход по ссылке ',
            'Баннер остановлен (Недостаточно средств)',
            'Баннер показан',
            'Баннер остановлен (Лимит показов закончился)'
        );

    $disc = $arrayStatus[$status];

    if(!$user_id) {

        $arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_OWNER");
        $arFilter = array("IBLOCK_ID" => array(34, 35), "ACTIVE" => "Y", "ID" => $banner);
        $res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
        if($row = $res->GetNext()) {
            $user_id = (int) $row['PROPERTY_OWNER_VALUE'];
        }
    }

    /*if($status == 7 && !$tax) {

        $arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_PLAN_TAX", "PROPERTY_DISCOUNT", "PROPERTY_LIMIT_PROMO", "PROPERTY_LIMIT_CURENT");
        $arFilter = array("IBLOCK_ID" => array(34, 35), "ACTIVE" => "Y", "ID" => $banner);
        $res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
        if($row = $res->GetNext()) {

            $tax = 0;
            $taxDiscount = 0;

            if($row['PROPERTY_DISCOUNT_VALUE']) {

                if ($row['PROPERTY_LIMIT_PROMO_VALUE'] > 0) {
                    if($row['PROPERTY_LIMIT_CURENT_VALUE'] > 0) {
                        $sumDiscount = $row['PROPERTY_PLAN_TAX_VALUE'] / 100 * $row['PROPERTY_DISCOUNT_VALUE'];
                        $taxDiscount = $row['PROPERTY_PLAN_TAX_VALUE'] - $sumDiscount;
                    } else {
                        $taxDiscount = $row['PROPERTY_PLAN_TAX_VALUE'];
                    }
                } else {
                    $sumDiscount = $row['PROPERTY_PLAN_TAX_VALUE'] / 100 * $row['PROPERTY_DISCOUNT_VALUE'];
                    $taxDiscount = $row['PROPERTY_PLAN_TAX_VALUE'] - $sumDiscount;
                }
            } else {
                $taxDiscount = $row['PROPERTY_PLAN_TAX_VALUE'];
            }
            $tax = round($taxDiscount, 2);
        }
    }*/

    $createTime = time();

    $stmt = $dbh->prepare("INSERT INTO a_banner_history (user_id, banner_id, tax, direction, disc, create_at, date_at) VALUES (:user_id, :banner_id, :tax, :direction, :disc, :create_at, NOW())");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':banner_id', $banner, PDO::PARAM_INT);
    $stmt->bindParam(':tax', $tax);
    $stmt->bindParam(':direction', $status, PDO::PARAM_INT);
    $stmt->bindParam(':disc', $disc);
    $stmt->bindParam(':create_at', $createTime, PDO::PARAM_INT);
    $stmt->execute();
}
?>
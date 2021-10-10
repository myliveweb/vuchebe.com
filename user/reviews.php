<?php
global $dbh;

$user_id = 0;
if($_SESSION['USER_DATA'])
	$user_id = $_SESSION['USER_DATA']['ID'];

$input = filter_input_array(INPUT_POST);
$search = $input['s'];

$cnt = 10;

$outArray = array();

$arrFilter = array('all' => 0, 'start' => 0, 'stop' => 0, 'new' => 0, 'otklon' => 0, 'finich' => 0);

$arSelect = array("ID", "NAME", "IBLOCK_ID", "DATE_CREATE", "DETAIL_TEXT");

$arFilter = array("IBLOCK_ID" => 23);

if($search) {

    $idSearch = array();

    $GLOBALS["FILTER_logic"] = "or";
    $arFilterSearch= array(
        "NAME" => $search . "%",
        "WORK_COMPANY" => $search . "%"
    );

    $dataUserSearch = CUser::GetList($by="ID", $order="ASC", $arFilterSearch);
    while($arUserSearch = $dataUserSearch->Fetch()) {
        $idSearch[] = $arUserSearch['ID'];
    }

    $arFilter['PROPERTY_AUTHOR'] = $idSearch;
}

$res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
while($obRes = $res->GetNextElement()) {

    $row = $obRes->GetFields();
    $props = $obRes->GetProperties();

    $row['WARNING'] = $props['WARNING']['VALUE'];
    $row['REJECT']  = $props['REJECT']['VALUE'];
    $row['DEL']     = $props['DEL']['VALUE'];

    $row['TICKET']  = $props['TICKET']['VALUE'];

    $row['AUTHOR']  = $props['AUTHOR']['VALUE'];
    $row['ABUSE']   = $props['ABUSE']['VALUE'];

    $row['POST']   = $props['POST_ID']['VALUE'];
    $row['URL_POST']   = $props['URL']['VALUE'];

    $uz = $props['VUZ_ID']['VALUE'];

    if($row['TICKET']) {
        $arrChat = $dbh->query('SELECT * from a_chat_support WHERE group_chat = ' . $row['TICKET'] . ' ORDER BY id DESC')->fetch();
        if($arrChat['del_owner']) {
            $row['TICKET_COLOR'] = 'red';
        } else {
            $row['TICKET_COLOR'] = 'green';
        }
    }

    if((sizeof($outArray) < $cnt && $row['WARNING'] != 'Y' && $row['REJECT'] != 'Y' && $row['DEL'] != 'Y') || $search) {

        list($dateFormat, $timeFormat) = explode(' ', $row["DATE_CREATE"]);
        list($hoursFormat, $minFormat) = explode(':', $timeFormat);

        $row["DATE_FORMAT"] = $dateFormat . ' (' . $hoursFormat . '.' . $minFormat .  ')';

        $arrTeacher = $dbh->query('SELECT COUNT(id) as cnt from a_user_uz WHERE teacher = 1 AND user_id = ' . $row['AUTHOR'])->fetch();
        if($arrTeacher['cnt'] > 0) {
            $row['TEACHER'] = 1;
        } else {
            $row['TEACHER'] = 0;
        }

        $rsAuthorData = CUser::GetByID($row['AUTHOR']);
        $authorData = $rsAuthorData->Fetch();

        $row["URL"] = getUserUrl($authorData);

        if($authorData['PERSONAL_PHOTO']) {
            $row["PIC"] = CFile::GetPath($authorData['PERSONAL_PHOTO']);
        } else {
            $row["PIC"] = SITE_TEMPLATE_PATH . "/images/user-1.png";
        }

        if (strlen(trim($authorData['NAME']))) {
            $format_name = '<span>' . strtoupper(mb_substr(trim($authorData['NAME']), 0, 1)) . '</span>' . mb_substr(trim($authorData['NAME']), 1);
            if($authorData['SECOND_NAME']) {
                $format_name .= ' ';
                $format_name .= '<span>' . strtoupper(mb_substr($authorData['SECOND_NAME'], 0, 1)) . '</span>' . mb_substr($authorData['SECOND_NAME'], 1);
            }
            if($authorData['LAST_NAME']) {
                $format_name .= ' ';
                $format_name .= '<span>' . strtoupper(mb_substr(trim($authorData['LAST_NAME']), 0, 1)) . '</span>' . mb_substr(trim($authorData['LAST_NAME']), 1);
            }
        } else {
            $format_name = '<span>' . strtoupper(mb_substr(trim($authorData['LOGIN']), 0, 1)) . '</span>' . mb_substr(trim($authorData['LOGIN']), 1);
        }

        $row['FORMAT_NAME'] = $format_name;

        /* Инфо о том кто подал жалобу */

        $rsAbuseData = CUser::GetByID($row['ABUSE']);
        $abuseData = $rsAbuseData->Fetch();

        if($abuseData['PERSONAL_PHOTO']) {
            $row["PIC_ABUSE"] = CFile::GetPath($abuseData['PERSONAL_PHOTO']);
        } else {
            $row["PIC_ABUSE"] = SITE_TEMPLATE_PATH . "/images/user-1.png";
        }

        $row["URL_ABUSE"] = getUserUrl($abuseData);

        $arrTeacherAbuse = $dbh->query('SELECT COUNT(id) as cnt from a_user_uz WHERE teacher = 1 AND user_id = ' . $row['ABUSE'])->fetch();
        if($arrTeacherAbuse['cnt'] > 0) {
            $row['TEACHER_ABUSE'] = 1;
        } else {
            $row['TEACHER_ABUSE'] = 0;
        }

        if (strlen(trim($abuseData['NAME']))) {
            $format_name_abuse = '<span>' . strtoupper(mb_substr(trim($abuseData['NAME']), 0, 1)) . '</span>' . mb_substr(trim($abuseData['NAME']), 1);
            $title_abuse = $abuseData['NAME'];
            if($abuseData['SECOND_NAME']) {
                $format_name_abuse .= ' ';
                $format_name_abuse .= '<span>' . strtoupper(mb_substr($abuseData['SECOND_NAME'], 0, 1)) . '</span>' . mb_substr($abuseData['SECOND_NAME'], 1);
                $title_abuse .= ' ';
                $title_abuse .= $abuseData['SECOND_NAME'];
            }
            if($abuseData['LAST_NAME']) {
                $format_name_abuse .= ' ';
                $format_name_abuse .= '<span>' . strtoupper(mb_substr(trim($abuseData['LAST_NAME']), 0, 1)) . '</span>' . mb_substr(trim($abuseData['LAST_NAME']), 1);
                $title_abuse .= ' ';
                $title_abuse .= $abuseData['LAST_NAME'];
            }
        } else {
            $format_name_abuse = '<span>' . strtoupper(mb_substr(trim($abuseData['LOGIN']), 0, 1)) . '</span>' . mb_substr(trim($abuseData['LOGIN']), 1);
        }

        $row['FORMAT_NAME_ABUSE'] = $format_name_abuse;
        $row['TITLE_ABUSE'] = $title_abuse;

        $arSelectUz = array("ID", "NAME", "IBLOCK_ID", "PREVIEW_PICTURE", "DETAIL_PAGE_URL");
        $arFilterUz = array("IBLOCK_ID" => array(2, 3, 4, 6), "ACTIVE" => "Y", "ID" => $uz);
        $resUz = CIBlockElement::GetList(array("ID" => "ASC"), $arFilterUz, false, false, $arSelectUz);
        if($obResUz = $resUz->GetNextElement()) {

            $rowUz = $obResUz->GetFields();
            $propsUz = $obResUz->GetProperties();

            $rowUz['LOGO']   = $propsUz['LOGO']['VALUE'];

        }

        if($rowUz['LOGO']) {
            $rowUz["PIC"] = CFile::GetPath($rowUz['LOGO']);
        } elseif($row["PREVIEW_PICTURE"]) {
            $rowUz["PIC"] = CFile::GetPath($rowUz["PREVIEW_PICTURE"]);
        } else {
            $rowUz["PIC"] = '/local/templates/vuchebe/images/noimage-2.png';
        }

        $row['UZ'] = $rowUz;

        $arSelectPost = array("ID", "NAME", "IBLOCK_ID", "DATE_CREATE");
        $arFilterPost = array("IBLOCK_ID" => 21, "ACTIVE" => "Y", "ID" => $row['POST']);
        $resPost = CIBlockElement::GetList(array("ID" => "ASC"), $arFilterPost, false, false, $arSelectPost);
        if($obResPost = $resPost->GetNextElement()) {

            $rowPost = $obResPost->GetFields();

            list($dateFormatPost, $timeFormatPost) = explode(' ', $rowPost["DATE_CREATE"]);
            list($hoursFormatPost, $minFormatPost) = explode(':', $timeFormatPost);

            $row["DATE_FORMAT_POST"] = $dateFormatPost . ' (' . $hoursFormatPost . '.' . $minFormatPost .  ')';

        }

        $row["TOTAL"] = 0;
        $arFilter = Array("IBLOCK_ID"=>23, "ACTIVE"=>"Y", "PROPERTY_AUTHOR" => $row["AUTHOR"]);
        $cntTotal = CIBlockElement::GetList(false, $arFilter, array('IBLOCK_ID'))->Fetch()['CNT'];
        if($cntTotal) {
            $row["TOTAL"] = $cntTotal;
        }

        $row["WARNING_CNT"] = 0;
        $arFilterWarning = Array("IBLOCK_ID"=>23, "ACTIVE"=>"Y", "PROPERTY_AUTHOR" => $row["AUTHOR"], "PROPERTY_WARNING" => "Y");
        $cntWarning = CIBlockElement::GetList(false, $arFilterWarning, array('IBLOCK_ID'))->Fetch()['CNT'];
        if($cntWarning) {
            $row["WARNING_CNT"] = $cntWarning;
        }

        $row["REJECT_CNT"] = 0;
        $arFilterReject = Array("IBLOCK_ID"=>23, "ACTIVE"=>"Y", "PROPERTY_AUTHOR" => $row["AUTHOR"], "PROPERTY_REJECT" => "Y");
        $cntReject = CIBlockElement::GetList(false, $arFilterReject, array('IBLOCK_ID'))->Fetch()['CNT'];
        if($cntReject) {
            $row["REJECT_CNT"] = $cntReject;
        }

        $row["DEL_CNT"] = 0;
        $arFilterDel = Array("IBLOCK_ID"=>23, "ACTIVE"=>"Y", "PROPERTY_AUTHOR" => $row["AUTHOR"], "PROPERTY_DEL" => "Y");
        $cntDel = CIBlockElement::GetList(false, $arFilterDel, array('IBLOCK_ID'))->Fetch()['CNT'];
        if($cntDel) {
            $row["DEL_CNT"] = $cntDel;
        }

        $row["TICKET_CNT"] = 0;
        $arFilterTicket = Array("IBLOCK_ID"=>23, "ACTIVE"=>"Y", "PROPERTY_AUTHOR" => $row["AUTHOR"], "!PROPERTY_TICKET" => false);
        $cntTicket = CIBlockElement::GetList(false, $arFilterTicket, array('IBLOCK_ID'))->Fetch()['CNT'];
        //$cntDel = CIBlockElement::GetList(array(), $arFilterTicket, Array(), false, Array());
        if($cntTicket) {
            $row["TICKET_CNT"] = $cntTicket;
        }

        $outArray[] = $row;
    }

    $arrFilter['all']++;

    if ($row['WARNING'] != 'Y' && $row['REJECT'] != 'Y' && $row['DEL'] != 'Y') {
        $arrFilter['new']++;
    }

    if ($row['REJECT'] == 'Y') {
        $arrFilter['otklon']++;
    }

    if ($row['WARNING'] == 'Y') {
        $arrFilter['warning']++;
    }

    if ($row['DEL'] == 'Y') {
        $arrFilter['del']++;
    }
}
?>
<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/pages.css">
<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/avatar.css">
<script>
var startFromListAdmin = 1;
var cnt = <?php echo $cnt; ?>;
</script>
<style>
.cur {
    margin: 10px 0px 5px 0px;
    font-style: italic; /* Курсивное начертание текста */
    color: navy; /* Синий цвет текста */
}
.cur::before {
    color: navy; /* Цвет маркера */
    content: "«"; /* Сам маркер */
    padding-right: 3px; /* Расстояние от маркера до текста */
    font-size: 15px;
}
.cur::after {
    color: navy; /* Цвет маркера */
    content: "»"; /* Сам маркер */
    padding-left: 3px; /* Расстояние от маркера до текста */
    font-size: 15px;
}
</style>
<?php
$url = getUserUrl($_SESSION['USER_DATA']);
?>
<div class="st-content-right">
	<div class="breadcrumbs">
		<a href="/">Главная</a> <i class="fa fa-angle-double-right color-orange"></i> <a href="/user/<?php echo $url; ?>/">Профиль</a> <i class="fa fa-angle-double-right color-orange"></i> <span>Отзывы</span>
	</div><br>
    <div class="page-content st-content-users" id="page">
        <!-- Поиск -->
        <div class="structure-cat bg-silver text-center" style="margin: 15px 0 20px;">
            <div class="row-line">
                <form id="form-reviews-avatar" method="post" accept-charset="utf-8">
                    <div class="col-10 search-filed" style="padding: 0 0 0 15px;">
                        <input class="js-add-user" type="text" name="s" id="search-avatar" value="<?php echo $search; ?>" placeholder="Введите имя или ссылку на пользователя" />
                        <div class="auto-complit" style="overflow: auto; width: calc(83.3% - 15px); top: 28px; text-align: left;"></div>
                    </div>
                    <div class="col-2 button-filed">
                        <button type="submit" style="line-height: 30px; width: 100%;">
                            <span class="short"><i class="fa fa-search"></i></span>
                            <span class="full">найти</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <!-- End Поиск -->
		<div class="name-block text-center txt-up" style="margin: 0 0 15px;"><span>Отзывы</span></div>
		<div class="st-content-bottom clear">
			<div class="module st-news">
                <?php if(!$search) { ?>
				<div class="m-header">
                    <a href="#" data-filter="new" class="filter js-reviews-list color-silver">новые(<span class="js-new"><?php if($arrFilter['new']) { echo $arrFilter['new']; } else { echo '0'; } ?></span>)</a>
					<a href="#" data-filter="otklon" class="filter js-reviews-list">отклоненные(<span class="js-otklon"><?php if($arrFilter['otklon']) { echo $arrFilter['otklon']; } else { echo '0'; } ?></span>)</a>
					<a href="#" data-filter="warning" class="filter js-reviews-list">предупреждения(<span class="js-warning"><?php if($arrFilter['warning']) { echo $arrFilter['warning']; } else { echo '0'; } ?></span>)</a>
					<a href="#" data-filter="del" class="filter js-reviews-list">удалённые отзывы(<span class="js-del"><?php if($arrFilter['del']) { echo $arrFilter['del']; } else { echo '0'; } ?></span>)</a>
                    <a href="#" data-filter="all" class="filter js-reviews-list">все(<span class="js-all"><?php if($arrFilter['all']) { echo $arrFilter['all']; } else { echo '0'; } ?></span>)</a>
				</div>
                <?php } else { ?>
                    <div class="search-result">
                        <div class="result-left">Найдено: <?php echo sizeof($outArray); ?></div>
                        <div class="result-right"><a href="/user/<?php echo $url; ?>/reviews/">назад к списку отзывов</a></div>
                    </div>
                <?php } ?>
            <div class="line-orders new">
        <?php
        foreach($outArray as $arrData) {
        ?>
        <div class="news-item" data-id="<?php echo $arrData['ID']; ?>">
          <div style="display: flex; width: 100%; justify-content: space-between; margin-bottom: 20px;">
              <div style="width: 25%;">
                  <div class="image brd" style="margin: 0 auto;">
                      <a href="<?=$arrData['UZ']['DETAIL_PAGE_URL']?>" target="blank">
                          <img class="big" src="<?=$arrData["UZ"]["PIC"]?>" alt="<?=$arrData["NAME"]?>" title="<?=$arrData["NAME"]?>" style="width: 105px; padding: 3px;">
                      </a>
                  </div>
              </div>
              <div style="width: 75%; margin-left: 15px;">
                  <a href="<?=$arrData['UZ']['DETAIL_PAGE_URL']?>" target="blank" style="font-size: 20px;">
                    <?=$arrData["UZ"]["NAME"]?>
                  </a>
              </div>
          </div>
          <div class="col-3 width-sm content-left" style="padding: 0;">
              <div class="image brd rad-50" style="text-align: center; width: 100%;">
                  <a href="/user/<?=$arrData['URL']?>/" target="blank">
                    <img class="big" src="<?=$arrData["PIC"]?>" alt="<?=$arrData["NAME"]?>" title="<?=$arrData["NAME"]?>" style="height: 111px; width: 111px;<?php if($arrData['TEACHER']) { echo ' border: 3px solid #ff5b32;'; } ?>">
                  </a>
              </div>
          </div>
          <div class="col-9 width-sm content-right">
            <div class="news-name">
                <span><a href="/user/<?=$arrData['URL']?>/" class="display-name" target="blank"><?=$arrData['FORMAT_NAME']?></a>
                <?php if($arrData["DATE_FORMAT_POST"]) { ?>
                    <span style="color: #9f9f9f; font-size: 13px; margin-left: 10px;"><?php echo $arrData["DATE_FORMAT_POST"]; ?></span></span>
                <?php } else { ?>
                    <span style="color: red; font-size: 13px; margin-left: 10px;">удалён</span></span>
                <?php } ?>
            </div>
            <div style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                <div class="params-banner" style="height: 20px; margin-top: 15px;">Текст отзыва:</div>
                <div class="params-banner cur"><?=$arrData["DETAIL_TEXT"]?></div>
                <div class="params-banner" style="text-align: right; margin-top: 0px;"><a href="<?=$arrData['URL_POST']?>" class="color-silver" target="blank">Перейти к отзыву</a></div>
                <div class="params-banner" style="height: 30px;">Жалоба от:
                    <a class="img-top" style="margin-left: 5px; display: inline-block;" href="/user/<?php echo $arrData["URL_ABUSE"]; ?>/" target="_blank">
                        <img class="ava" src="<?php echo $arrData["PIC_ABUSE"]; ?>" alt="<?=$arrData["TITLE_ABUSE"]?>" title="<?=$arrData["TITLE_ABUSE"]?>" style="border: 1px solid #ff5b32; border-radius: 50%;">
                    </a><a class="name-text" style="margin-left: 7px;" href="/user/<?php echo $arrData["URL_ABUSE"]; ?>/" target="_blank"><?=$arrData["FORMAT_NAME_ABUSE"]?></a>
                </div>
                <div class="params-banner">Время: <?php echo $arrData["DATE_FORMAT"]; ?></div>
                <div class="params-banner">Предыдущие жалобы: <a href="#" class="js-reviews-action abuse" data-id="<?=$arrData['ID']?>" data-user="<?=$arrData['AUTHOR']?>" data-type="abuse"><?php echo $arrData["TOTAL"]; ?></a></div>
                <div class="params-banner">Предупреждений: <a href="#" class="js-reviews-action warning" data-id="<?=$arrData['ID']?>" data-user="<?=$arrData['AUTHOR']?>" data-type="warning"><?php echo $arrData["WARNING_CNT"]; ?></a></div>
                <div class="params-banner">Отклонённые жалобы: <a href="#" class="js-reviews-action reject" data-id="<?=$arrData['ID']?>" data-user="<?=$arrData['AUTHOR']?>" data-type="reject"><?php echo $arrData["REJECT_CNT"]; ?></a></div>
                <div class="params-banner">Удаленные отзывы: <a href="#" class="js-reviews-action del" data-id="<?=$arrData['ID']?>" data-user="<?=$arrData['AUTHOR']?>" data-type="del"><?php echo $arrData["DEL_CNT"]; ?></a></div>
                <div class="params-banner">Созданных тикетов: <a href="#" class="js-reviews-action ticket" data-id="<?=$arrData['ID']?>" data-user="<?=$arrData['AUTHOR']?>" data-type="ticket"><?php echo $arrData["TICKET_CNT"]; ?></a></div>
            </div>
          </div>
        <div class="params-banner-top col-12" style="margin-top: 15px; text-align: right;">
            <a class="color-silver js-reviews-button del-reviews" data-type="del-reviews" data-id="<?php echo $arrData["ID"]; ?>" data-user="<?=$arrData['AUTHOR']?>">Удалить отзыв</a>
            <a class="color-silver js-reviews-button reject" data-type="reject" data-id="<?php echo $arrData["ID"]; ?>" data-user="<?=$arrData['AUTHOR']?>">Отклонить жалобу</a>
            <a class="color-silver js-reviews-button warning" data-type="warning" data-id="<?php echo $arrData["ID"]; ?>" data-user="<?=$arrData['AUTHOR']?>">Предупреждение</a>
            <a class="color-silver js-reviews-button ban" data-type="ban" data-id="<?php echo $arrData["ID"]; ?>" data-user="<?=$arrData['AUTHOR']?>">Бан</a>
            <a class="color-silver js-reviews-button del-user" data-type="del-user" data-id="<?php echo $arrData["ID"]; ?>" data-user="<?=$arrData['AUTHOR']?>">Удалить пользователя</a>
            <a class="color-silver js-reviews-button deactivate" data-type="deactivate" data-id="<?php echo $arrData["ID"]; ?>" data-user="<?=$arrData['AUTHOR']?>">Удалить жалобу</a>
            <?php if($arrData['TICKET']) { ?>
            <a href="/user/support/<?php echo $arrData['TICKET']; ?>/" class="color-silver" target="_blank" style="color: <?php echo $arrData['TICKET_COLOR']; ?>;">Тикет №<?php echo $arrData['TICKET']; ?></a>
            <?php } else { ?>
            <a class="color-silver js-new-chat" data-type="ticket" data-id="<?php echo $arrData["ID"]; ?>" data-user="<?=$arrData['AUTHOR']?>">Новая заявка</a>
            <?php } ?>
        </div>
        </div>
        <?php
        }
        ?>
        </div>
        <div class="line-orders otklon" style="display: none;"></div>
        <div class="line-orders warning" style="display: none;"></div>
        <div class="line-orders del" style="display: none;"></div>
        <div class="line-orders all" style="display: none;"></div>
        </div>
			 <!-- st-news -->
		</div>
	</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
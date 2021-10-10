<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetTitle("В учёбе");
?>
<script>
frontPage = 1;
</script>
<style>
.index-front {
	padding: 20px;
}
.index-front .name-block {
    margin: 10px 0 25px;
    font-size: 16px;
}
.index-front .name-block::before {
    display: block;
    position: absolute;
    left: 0;
    top: 50%;
    width: 100%;
    height: 1px;
    background: #ff471a;
}
.index-front .name-block span {
    display: inline-block;
    padding: 0 20px;
    background: #fff;
    position: relative;
    z-index: 5;
    font-size: 16px;
    text-transform: uppercase;
    font-weight: bold;
}
.display-name {
	color: #000 !important;
	cursor: pointer;
	text-decoration-color: #ff471a;
	text-align: center;
}
.display-name span.front {
	color: #ff471a !important;
}
.crop-height {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.owl-stage {
	display: flex;
	align-items: center;
}
</style>
<?php
CModule::IncludeModule('iblock');

$arrUser = array();
$arrTeacher = array();

$userBy = "id";
$userOrder = "asc";

if(isset($_SESSION['PANEL']['CITY']) && $_SESSION['PANEL']['CITY'] && $_SESSION['PANEL']['TOPCITY'])
	$filter = array("ACTIVE" => "Y", "UF_CITY" => $_SESSION['PANEL']['CITY']);
elseif(isset($_SESSION['PANEL']['REGION']) && $_SESSION['PANEL']['REGION'])
	$filter = array("ACTIVE" => "Y", "UF_REGION" => $_SESSION['PANEL']['REGION']);
elseif(isset($_SESSION['PANEL']['COUNTRY']) && $_SESSION['PANEL']['COUNTRY'])
	$filter = array("ACTIVE" => "Y", "UF_COUNTRY" => $_SESSION['PANEL']['COUNTRY']);
else
	$filter = array("ACTIVE" => "Y");

$rsUsers = CUser::GetList($userBy, $userOrder, $filter);
while($res = $rsUsers->Fetch())
{
    if(isSupport($res['ID']))
        continue;

	$arrUser[] = $res;

	$teacher = $dbh->query('SELECT COUNT(id) as cnt from a_user_uz WHERE teacher = 1 AND user_id = ' . $res['ID'])->fetch();
	if($teacher['cnt'] > 0) {
		$arrTeacher[] = $res;
	} elseif($res["ID"] >= 27 && $res["ID"] <= 38) {
		$arrTeacher[] = $res;
	}
}

shuffle($arrUser);
shuffle($arrTeacher);

$arrType = array(2 => 'universities', 3 => 'colleges', 4 => 'schools', 6 => 'language-class');
?>
<div class="container index-front">
	<div class="name-block"> &nbsp;&nbsp;&nbsp;<span>Случайный пользователь</span></div>
	<div class="st-carousel news-33" style="margin: 0 10px; min-height: 176px;">
		<div class="owl-carousel">
		<?php
		if(sizeof($arrUser) > 0) {
			for ($n = 0; $n < 12; $n++) {
				$user = $arrUser[$n];

				if(!$user['NAME']) {
					continue;
				}

				if($user['PERSONAL_PHOTO']) {
					$avatar_url = CFile::GetPath($user['PERSONAL_PHOTO']);
				} else {
					$avatar_url = SITE_TEMPLATE_PATH . "/images/user-1.png";
				}

				if (strlen(trim($user['NAME'])) && strlen(trim($user['LAST_NAME']))) {
					$format_name = '<span class="front">' . strtoupper(mb_substr(trim($user['NAME']), 0, 1)) . '</span>' . mb_substr(trim($user['NAME']), 1);
					if($user['SECOND_NAME']) {
						$format_name .= ' ';
						$format_name .= '<span class="front">' . strtoupper(mb_substr($user['SECOND_NAME'], 0, 1)) . '</span>' . mb_substr($user['SECOND_NAME'], 1);
					}
					$format_name .= ' ';
					$format_name .= '<span class="front">' . strtoupper(mb_substr(trim($user['LAST_NAME']), 0, 1)) . '</span>' . mb_substr(trim($user['LAST_NAME']), 1);
				} else {
					$format_name = '<span class="front">' . strtoupper(mb_substr(trim($user['LOGIN']), 0, 1)) . '</span>' . mb_substr(trim($user['LOGIN']), 1);
				}

                $url = getUserUrl($user);
				?>
				<div class="st-item" style="text-align: center;">
					<div class="image brd rad-50">
						<a href="/user/<?php echo $url; ?>/" class="<?php if(!$_SESSION['USER_DATA']) { echo 'js-noauth'; } ?>">
							<img src="<?=$avatar_url?>" alt="img" style="height: 111px; width: 111px;">
						</a>
					</div>
					<a href="/user/<?php echo $url; ?>/" class="display-name crop-height<?php if(!$_SESSION['USER_DATA']) { echo ' js-noauth'; } ?>" style="display: inline;"><?php echo $format_name; ?></a>
					<?php if(CUser::IsOnLine($user['ID'], 30) && $user['PERSONAL_PAGER'] != 1 && $_SESSION['USER_DATA']['PERSONAL_PAGER'] != 1) { ?>
					<div style="display: inline-block; position: relative; top: 1px; margin-left: 3px; width: 10px; height: 10px; border-radius: 50%; background-color: #ff471a;" title="В сети"></div>
					<?php } ?>
				</div>
				<?php
			}
		}
		?>
		</div>
	</div>
	<div class="name-block"> &nbsp;&nbsp;&nbsp;<span>Случайный ВУЗ</span></div>
	<div class="st-carousel news-3" style="margin: 0 10px; min-height: 176px;">
		<div class="owl-carousel">
			<?php
			$arrNews = array();
			$arSelect = array("ID", "NAME", "IBLOCK_ID", "DETAIL_PAGE_URL", "CODE", "PREVIEW_PICTURE", "PROPERTY_LOGO");

			if(isset($_SESSION['PANEL']['CITY']) && $_SESSION['PANEL']['CITY'] && $_SESSION['PANEL']['TOPCITY'])
				$arFilter = array("IBLOCK_ID" => 2, "ACTIVE" => "Y", "PROPERTY_CITY" => $_SESSION['PANEL']['CITY']);
			elseif(isset($_SESSION['PANEL']['REGION']) && $_SESSION['PANEL']['REGION'])
				$arFilter = array("IBLOCK_ID" => 2, "ACTIVE" => "Y", "PROPERTY_REGION" => $_SESSION['PANEL']['REGION']);
			elseif(isset($_SESSION['PANEL']['COUNTRY']) && $_SESSION['PANEL']['COUNTRY'])
				$arFilter = array("IBLOCK_ID" => 2, "ACTIVE" => "Y", "PROPERTY_COUNTRY" => $_SESSION['PANEL']['COUNTRY']);
			else
				$arFilter = array("IBLOCK_ID" => 2, "ACTIVE" => "Y");

			$resCarusel = CIBlockElement::GetList(array("RAND" => "ASC"), $arFilter, false, array("nPageSize"=>12), $arSelect);
			while($rowCarusel = $resCarusel->Fetch())
			{
				$arrNews[] = $rowCarusel;
			}
			foreach($arrNews as $val_item) {
				if($val_item["PROPERTY_LOGO_VALUE"]):
					$srcLogo = CFile::GetPath($val_item["PROPERTY_LOGO_VALUE"]);
				elseif($val_item["PREVIEW_PICTURE"]):
					$srcLogo = CFile::GetPath($val_item["PREVIEW_PICTURE"]);
				else:
					$srcLogo = SITE_TEMPLATE_PATH . '/images/noimage-2.png';
				endif;
			?>
			<div class="st-item">
				<div class="image brd" style="width: 100%;">
					<img style="max-height: 111px; max-width: 111px;" src="<?php echo $srcLogo; ?>" alt="<?=$val_item["NAME"]?>" title="<?=$val_item["NAME"]?>" />
				</div>
				<a style="margin: 15px 0 10px;" href="/uchebnye-zavedeniya/universities/<?=$val_item["CODE"]?>/" class="display-name" alt="<?=$val_item["NAME"]?>" title="<?=$val_item["NAME"]?>"><span class="crop-height"><?php echo $val_item['NAME']; ?></span></a>
			</div>
			<?php
			}
			?>
		</div>
	</div><!-- st-carousel -->
	<div class="name-block"> &nbsp;&nbsp;&nbsp;<span>Случайный колледж</span></div>
	<div class="st-carousel news-3" style="margin: 0 10px; min-height: 176px;">
		<div class="owl-carousel">
			<?php
			$arrNews = array();
			$arSelect = array("ID", "NAME", "IBLOCK_ID", "DETAIL_PAGE_URL", "CODE", "PREVIEW_PICTURE", "PROPERTY_LOGO");

			if(isset($_SESSION['PANEL']['CITY']) && $_SESSION['PANEL']['CITY'] && $_SESSION['PANEL']['TOPCITY'])
				$arFilter = array("IBLOCK_ID" => 3, "ACTIVE" => "Y", "PROPERTY_CITY" => $_SESSION['PANEL']['CITY']);
			elseif(isset($_SESSION['PANEL']['REGION']) && $_SESSION['PANEL']['REGION'])
				$arFilter = array("IBLOCK_ID" => 3, "ACTIVE" => "Y", "PROPERTY_REGION" => $_SESSION['PANEL']['REGION']);
			elseif(isset($_SESSION['PANEL']['COUNTRY']) && $_SESSION['PANEL']['COUNTRY'])
				$arFilter = array("IBLOCK_ID" => 3, "ACTIVE" => "Y", "PROPERTY_COUNTRY" => $_SESSION['PANEL']['COUNTRY']);
			else
				$arFilter = array("IBLOCK_ID" => 3, "ACTIVE" => "Y");

			$resCarusel = CIBlockElement::GetList(array("RAND" => "ASC"), $arFilter, false, array("nPageSize"=>12), $arSelect);
			while($rowCarusel = $resCarusel->Fetch())
			{
				$arrNews[] = $rowCarusel;
			}
			foreach($arrNews as $val_item) {
				if($val_item["PROPERTY_LOGO_VALUE"]):
					$srcLogo = CFile::GetPath($val_item["PROPERTY_LOGO_VALUE"]);
				elseif($val_item["PREVIEW_PICTURE"]):
					$srcLogo = CFile::GetPath($val_item["PREVIEW_PICTURE"]);
				else:
					$srcLogo = SITE_TEMPLATE_PATH . '/images/noimage-2.png';
				endif;
			?>
			<div class="st-item">
				<div class="image brd" style="width: 100%;">
					<img style="max-height: 111px; max-width: 111px;" src="<?php echo $srcLogo; ?>" alt="<?=$val_item["NAME"]?>" title="<?=$val_item["NAME"]?>" />
				</div>
				<a style="margin: 15px 0 10px;" href="/uchebnye-zavedeniya/colleges/<?=$val_item["CODE"]?>/" class="display-name" alt="<?=$val_item["NAME"]?>" title="<?=$val_item["NAME"]?>"><span class="crop-height"><?php echo $val_item['NAME']; ?></span></a>
			</div>
			<?php
			}
			?>
		</div>
	</div><!-- st-carousel -->
	<div class="name-block"> &nbsp;&nbsp;&nbsp;<span>Случайная школа</span></div>
	<div class="st-carousel news-3" style="margin: 0 10px; min-height: 176px;">
		<div class="owl-carousel">
			<?php
			$arrNews = array();
			$arSelect = array("ID", "NAME", "IBLOCK_ID", "DETAIL_PAGE_URL", "CODE", "PREVIEW_PICTURE", "PROPERTY_LOGO");

			if(isset($_SESSION['PANEL']['CITY']) && $_SESSION['PANEL']['CITY'] && $_SESSION['PANEL']['TOPCITY'])
				$arFilter = array("IBLOCK_ID" => 4, "ACTIVE" => "Y", "PROPERTY_CITY" => $_SESSION['PANEL']['CITY']);
			elseif(isset($_SESSION['PANEL']['REGION']) && $_SESSION['PANEL']['REGION'])
				$arFilter = array("IBLOCK_ID" => 4, "ACTIVE" => "Y", "PROPERTY_REGION" => $_SESSION['PANEL']['REGION']);
			elseif(isset($_SESSION['PANEL']['COUNTRY']) && $_SESSION['PANEL']['COUNTRY'])
				$arFilter = array("IBLOCK_ID" => 4, "ACTIVE" => "Y", "PROPERTY_COUNTRY" => $_SESSION['PANEL']['COUNTRY']);
			else
				$arFilter = array("IBLOCK_ID" => 4, "ACTIVE" => "Y");

			$resCarusel = CIBlockElement::GetList(array("RAND" => "ASC"), $arFilter, false, array("nPageSize"=>12), $arSelect);
			while($rowCarusel = $resCarusel->Fetch())
			{
				$arrNews[] = $rowCarusel;
			}
			foreach($arrNews as $val_item) {
				if($val_item["PROPERTY_LOGO_VALUE"]):
					$srcLogo = CFile::GetPath($val_item["PROPERTY_LOGO_VALUE"]);
				elseif($val_item["PREVIEW_PICTURE"]):
					$srcLogo = CFile::GetPath($val_item["PREVIEW_PICTURE"]);
				else:
					$srcLogo = SITE_TEMPLATE_PATH . '/images/noimage-2.png';
				endif;
			?>
			<div class="st-item">
				<div class="image brd" style="width: 100%;">
					<img style="max-height: 111px; max-width: 111px;" src="<?php echo $srcLogo; ?>" alt="<?=$val_item["NAME"]?>" title="<?=$val_item["NAME"]?>" />
				</div>
				<a style="margin: 15px 0 10px;" href="/uchebnye-zavedeniya/schools/<?=$val_item["CODE"]?>/" class="display-name" alt="<?=$val_item["NAME"]?>" title="<?=$val_item["NAME"]?>"><span class="crop-height"><?php echo $val_item['NAME']; ?></span></a>
			</div>
			<?php
			}
			?>
		</div>
	</div><!-- st-carousel -->
	<div class="name-block"> &nbsp;&nbsp;&nbsp;<span>Случайные языковые курсы</span></div>
	<div class="st-carousel news-3" style="margin: 0 10px; min-height: 176px;">
		<div class="owl-carousel">
			<?php
			$arrNews = array();
			$arSelect = array("ID", "NAME", "IBLOCK_ID", "DETAIL_PAGE_URL", "CODE", "DETAIL_PICTURE", "PROPERTY_LOGO");

			if(isset($_SESSION['PANEL']['CITY']) && $_SESSION['PANEL']['CITY'] && $_SESSION['PANEL']['TOPCITY'])
				$arFilter = array("IBLOCK_ID" => 6, "ACTIVE" => "Y", "PROPERTY_CITY" => $_SESSION['PANEL']['CITY']);
			elseif(isset($_SESSION['PANEL']['REGION']) && $_SESSION['PANEL']['REGION'])
				$arFilter = array("IBLOCK_ID" => 6, "ACTIVE" => "Y", "PROPERTY_REGION" => $_SESSION['PANEL']['REGION']);
			elseif(isset($_SESSION['PANEL']['COUNTRY']) && $_SESSION['PANEL']['COUNTRY'])
				$arFilter = array("IBLOCK_ID" => 6, "ACTIVE" => "Y", "PROPERTY_COUNTRY" => $_SESSION['PANEL']['COUNTRY']);
			else
				$arFilter = array("IBLOCK_ID" => 6, "ACTIVE" => "Y");

			$resCarusel = CIBlockElement::GetList(array("RAND" => "ASC"), $arFilter, false, array("nPageSize"=>12), $arSelect);
			while($rowCarusel = $resCarusel->Fetch())
			{
				$arrNews[] = $rowCarusel;
			}
			foreach($arrNews as $val_item) {
				if($val_item["PROPERTY_LOGO_VALUE"]):
					$srcLogo = CFile::GetPath($val_item["PROPERTY_LOGO_VALUE"]);
				elseif($val_item["DETAIL_PICTURE"]):
					$srcLogo = CFile::GetPath($val_item["DETAIL_PICTURE"]);
				else:
					$srcLogo = SITE_TEMPLATE_PATH . '/images/noimage-2.png';
				endif;
			?>
			<div class="st-item">
				<div class="image brd" style="width: 100%;">
					<img style="max-height: 111px; max-width: 111px;" src="<?php echo $srcLogo; ?>" alt="<?=$val_item["NAME"]?>" title="<?=$val_item["NAME"]?>" />
				</div>
				<a style="margin: 15px 0 10px;" href="/uchebnye-zavedeniya/language-class/<?=$val_item["CODE"]?>/" class="display-name" alt="<?=$val_item["NAME"]?>" title="<?=$val_item["NAME"]?>"><span class="crop-height"><?php echo $val_item['NAME']; ?></span></a>
			</div>
			<?php
			}
			?>
		</div>
	</div><!-- st-carousel -->
	<div class="name-block"> &nbsp;&nbsp;&nbsp;<span>Случайный преподаватель</span></div>
	<div class="st-carousel news-33" style="margin: 0 10px; min-height: 176px;">
		<div class="owl-carousel">
		<?php
		if(sizeof($arrTeacher) > 0) {
			for ($n = 0; $n < 12; $n++) {
				$user = $arrTeacher[$n];

				if(!$user['NAME']) {
					continue;
				}

				if($user['PERSONAL_PHOTO']) {
					$avatar_url = CFile::GetPath($user['PERSONAL_PHOTO']);
				} else {
					$avatar_url = SITE_TEMPLATE_PATH . "/images/user-1.png";
				}

				if (strlen(trim($user['NAME'])) && strlen(trim($user['LAST_NAME']))) {
					$format_name = '<span class="front">' . strtoupper(mb_substr(trim($user['NAME']), 0, 1)) . '</span>' . mb_substr(trim($user['NAME']), 1);
					if($user['SECOND_NAME']) {
						$format_name .= ' ';
						$format_name .= '<span class="front">' . strtoupper(mb_substr($user['SECOND_NAME'], 0, 1)) . '</span>' . mb_substr($user['SECOND_NAME'], 1);
					}
					$format_name .= ' ';
					$format_name .= '<span class="front">' . strtoupper(mb_substr(trim($user['LAST_NAME']), 0, 1)) . '</span>' . mb_substr(trim($user['LAST_NAME']), 1);
				} else {
					$format_name = '<span class="front">' . strtoupper(mb_substr(trim($user['LOGIN']), 0, 1)) . '</span>' . mb_substr(trim($user['LOGIN']), 1);
				}

                $url = getUserUrl($user);
				?>
				<div class="st-item" style="text-align: center;">
					<div class="image brd rad-50">
						<a href="/user/<?php echo $url; ?>/" class="<?php if(!$_SESSION['USER_DATA']) { echo 'js-noauth'; } ?>">
							<img src="<?=$avatar_url?>" alt="img" style="height: 111px; width: 111px; border: 3px solid #ff5b32;">
						</a>
					</div>
					<a href="/user/<?php echo $url; ?>/" class="display-name crop-height<?php if(!$_SESSION['USER_DATA']) { echo ' js-noauth'; } ?>" style="display: inline;"><?php echo $format_name; ?></a>
					<?php if(CUser::IsOnLine($user['ID'], 30) && $user['PERSONAL_PAGER'] != 1 && $_SESSION['USER_DATA']['PERSONAL_PAGER'] != 1) { ?>
					<div style="display: inline-block; position: relative; top: 1px; margin-left: 3px; width: 10px; height: 10px; border-radius: 50%; background-color: #ff471a;" title="В сети"></div>
					<?php } ?>
				</div>
				<?php
			}
		}
		?>
		</div>
	</div>
	<div class="name-block"> &nbsp;&nbsp;&nbsp;<span>Случайный день открытых дверей</span></div>
	<div class="st-carousel news-3" style="margin: 0 10px; min-height: 138px;">
		<div class="owl-carousel">
			<?php
			$arrNews = array();
			$arSelect = array("ID", "NAME", "IBLOCK_ID", "DETAIL_PAGE_URL", "CODE");

			if(isset($_SESSION['PANEL']['CITY']) && $_SESSION['PANEL']['CITY'] && $_SESSION['PANEL']['TOPCITY'])
				$arFilter = array("IBLOCK_ID" => array(2, 3, 4, 6), "ACTIVE" => "Y", "!PROPERTY_OPENDOOR" => false, "PROPERTY_CITY" => $_SESSION['PANEL']['CITY']);
			elseif(isset($_SESSION['PANEL']['REGION']) && $_SESSION['PANEL']['REGION'])
				$arFilter = array("IBLOCK_ID" => array(2, 3, 4, 6), "ACTIVE" => "Y", "!PROPERTY_OPENDOOR" => false, "PROPERTY_REGION" => $_SESSION['PANEL']['REGION']);
			elseif(isset($_SESSION['PANEL']['COUNTRY']) && $_SESSION['PANEL']['COUNTRY'])
				$arFilter = array("IBLOCK_ID" => array(2, 3, 4, 6), "ACTIVE" => "Y", "!PROPERTY_OPENDOOR" => false, "PROPERTY_COUNTRY" => $_SESSION['PANEL']['COUNTRY']);
			else
				$arFilter = array("IBLOCK_ID" => array(2, 3, 4, 6), "ACTIVE" => "Y", "!PROPERTY_OPENDOOR" => false);

			$resCarusel = CIBlockElement::GetList(array("RAND" => "ASC"), $arFilter, false, false, $arSelect);
			while($rowCarusel = $resCarusel->Fetch())
			{

				$resEvents = CIBlockElement::GetProperty($rowCarusel["IBLOCK_ID"], $rowCarusel["ID"], "sort", "asc", array("CODE" => "OPENDOOR"));
				while ($obEvents = $resEvents->GetNext()) {
					$rowCarusel["TYPE"]  = $arrType[$rowCarusel["IBLOCK_ID"]];

					$tempEvents = explode('#', $obEvents['VALUE']);

					$rowCarusel["NAME_OPENDOOR"]  = $tempEvents[0];

					$fullTime = $tempEvents[1] . ' ' . $tempEvents[2];

					if(!$tempEvents[0] || strtotime($fullTime) > time())
						continue;

					$strDate = get_str_time_post(strtotime($fullTime));
					$curDate = explode(' ', $strDate);
					$rowCarusel["DAY"] = $curDate[0];
					$rowCarusel["MONTH"] = $curDate[1];

					$arrNews[] = $rowCarusel;
				}
			}
			shuffle($arrNews);
			for($n = 0; $n < 12; $n++) {
				$val_item = $arrNews[$n];
			?>
			<div class="st-item">
				<div style="text-align: center;">
				<div class="date-ico" style="margin-bottom: 10px; width: 100%; background-position: center;"><span><?=$val_item['DAY']?></span><?=$val_item['MONTH']?></div>
				<a style="margin: 15px 0 10px;" href="/uchebnye-zavedeniya/<?=$val_item["TYPE"]?>/<?=$val_item["CODE"]?>/?sect=opendoor" class="display-name" alt="<?=$val_item["NAME"]?>" title="<?=$val_item["NAME"]?>"><span class="crop-height"><?php echo $val_item['NAME_OPENDOOR']; ?></span></a>
				</div>
			</div>
			<?php
			}
			?>
		</div>
	</div><!-- st-carousel -->
	<div class="name-block"> &nbsp;&nbsp;&nbsp;<span>Случайная новость учебных заведений</span></div>
	<div class="st-carousel news-3" style="margin: 0 10px;">
		<div class="owl-carousel">
			<?php
			$arrNews = array();
			$arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_VUZ_ID");

			if(isset($_SESSION['PANEL']['CITY']) && $_SESSION['PANEL']['CITY'] && $_SESSION['PANEL']['TOPCITY'])
				$arFilter = array("IBLOCK_ID" => array(22, 28, 29, 30), "ACTIVE" => "Y", "PROPERTY_CITY" => $_SESSION['PANEL']['CITY']);
			elseif(isset($_SESSION['PANEL']['REGION']) && $_SESSION['PANEL']['REGION'])
				$arFilter = array("IBLOCK_ID" => array(22, 28, 29, 30), "ACTIVE" => "Y", "PROPERTY_REGION" => $_SESSION['PANEL']['REGION']);
			elseif(isset($_SESSION['PANEL']['COUNTRY']) && $_SESSION['PANEL']['COUNTRY'])
				$arFilter = array("IBLOCK_ID" => array(22, 28, 29, 30), "ACTIVE" => "Y", "PROPERTY_COUNTRY" => $_SESSION['PANEL']['COUNTRY']);
			else
				$arFilter = array("IBLOCK_ID" => array(22, 28, 29, 30), "ACTIVE" => "Y");

			$resCarusel = CIBlockElement::GetList(array("RAND" => "ASC"), $arFilter, false, array("nPageSize"=>12), $arSelect);
			while($rowCarusel = $resCarusel->Fetch())
			{
				$arSelectNews = array("ID", "NAME", "IBLOCK_ID", "DETAIL_PAGE_URL", "CODE");

				$arFilterNews = array("ACTIVE" => "Y", "ID" => $rowCarusel["PROPERTY_VUZ_ID_VALUE"]);
				$resNews = CIBlockElement::GetList(array("ID" => "ASC"), $arFilterNews, false, false, $arSelectNews);
				if($rowNews = $resNews->Fetch())
				{
					$rowCarusel["TITLE"] = $rowNews["NAME"];
					$rowCarusel["URL"]   = $rowNews["DETAIL_PAGE_URL"];
					$rowCarusel["CODE"]  = $rowNews["CODE"];
					$rowCarusel["TYPE"]  = $arrType[$rowNews["IBLOCK_ID"]];
				}
				$arrNews[] = $rowCarusel;
			}
			foreach($arrNews as $val_item) {
			?>
			<div class="st-item" style="padding: 5px 15px; font-family: Verdana; border-radius: 5px; border: 1px solid #ff471a; margin: 0 0 10px; min-height: 82px;">
				<a style="margin: 15px 0 10px;" href="/uchebnye-zavedeniya/<?=$val_item["TYPE"]?>/<?=$val_item["CODE"]?>/?sect=news&s=<?php echo $val_item['ID']; ?>" class="display-name" alt="<?=$val_item["TITLE"]?>" title="<?=$val_item["TITLE"]?>"><span class="crop-height"><?php echo $val_item['NAME']; ?></span></a>
			</div>
			<?php
			}
			?>
		</div>
	</div><!-- st-carousel -->
	<div class="name-block"> &nbsp;&nbsp;&nbsp;<span>Случайное событие учебных заведений</span></div>
	<div class="st-carousel news-3" style="margin: 0 10px;">
		<div class="owl-carousel">
			<?php
			$arrNews = array();
			$arSelect = array("ID", "NAME", "IBLOCK_ID", "DETAIL_PAGE_URL", "CODE");

			if(isset($_SESSION['PANEL']['CITY']) && $_SESSION['PANEL']['CITY'] && $_SESSION['PANEL']['TOPCITY'])
				$arFilter = array("IBLOCK_ID" => array(2, 3, 4, 6), "ACTIVE" => "Y", "!PROPERTY_ADD_EVENTS" => false, "PROPERTY_CITY" => $_SESSION['PANEL']['CITY']);
			elseif(isset($_SESSION['PANEL']['REGION']) && $_SESSION['PANEL']['REGION'])
				$arFilter = array("IBLOCK_ID" => array(2, 3, 4, 6), "ACTIVE" => "Y", "!PROPERTY_ADD_EVENTS" => false, "PROPERTY_REGION" => $_SESSION['PANEL']['REGION']);
			elseif(isset($_SESSION['PANEL']['COUNTRY']) && $_SESSION['PANEL']['COUNTRY'])
				$arFilter = array("IBLOCK_ID" => array(2, 3, 4, 6), "ACTIVE" => "Y", "!PROPERTY_ADD_EVENTS" => false, "PROPERTY_COUNTRY" => $_SESSION['PANEL']['COUNTRY']);
			else
				$arFilter = array("IBLOCK_ID" => array(2, 3, 4, 6), "ACTIVE" => "Y", "!PROPERTY_ADD_EVENTS" => false);

			$resCarusel = CIBlockElement::GetList(array("RAND" => "ASC"), $arFilter, false, false, $arSelect);
			while($rowCarusel = $resCarusel->Fetch())
			{

				$resEvents = CIBlockElement::GetProperty($rowCarusel["IBLOCK_ID"], $rowCarusel["ID"], "sort", "asc", array("CODE" => "ADD_EVENTS"));
				while ($obEvents = $resEvents->GetNext()) {
					$rowCarusel["TYPE"]  = $arrType[$rowCarusel["IBLOCK_ID"]];

					$tempEvents = explode('#', $obEvents['VALUE']);

					$rowCarusel["NAME_EVENT"]  = $tempEvents[0];

					$fullTime = $tempEvents[1] . ' ' . $tempEvents[2];

					if(!$tempEvents[0] || strtotime($fullTime) > time())
						continue;

					$strDate = get_str_time_post(strtotime($fullTime));
					$curDate = explode(' ', $strDate);
					$rowCarusel["DAY"] = $curDate[0];
					$rowCarusel["MONTH"] = $curDate[1];

					$arrNews[] = $rowCarusel;
				}
			}
			shuffle($arrNews);
			for($n = 0; $n < 12; $n++) {
				$val_item = $arrNews[$n];
			?>
			<div class="st-item">
				<div style="text-align: center;">
				<div class="date-ico" style="margin-bottom: 10px; width: 100%; background-position: center;"><span><?=$val_item['DAY']?></span><?=$val_item['MONTH']?></div>
				<a style="margin: 15px 0 10px;" href="/uchebnye-zavedeniya/<?=$val_item["TYPE"]?>/<?=$val_item["CODE"]?>/?sect=events" class="display-name" alt="<?=$val_item["NAME"]?>" title="<?=$val_item["NAME"]?>"><span class="crop-height"><?php echo $val_item['NAME_EVENT']; ?></span></a>
				</div>
			</div>
			<?php
			}
			?>
		</div>
	</div><!-- st-carousel -->
</div>
<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>
<?php
global $dbh;

CModule::IncludeModule('iblock');

$user_id = 0;
if($_SESSION['USER_DATA'])
	$user_id = $_SESSION['USER_DATA']['ID'];

$arResult["EVENTS"] = array();
if($user_id) {
	$arrEvents = $dbh->query('SELECT * from a_events_go WHERE id_user = ' . $user_id . ' ORDER BY id DESC')->fetchAll();
	foreach($arrEvents as $events) {

		$events['id_event'] = $events['key_event'];
		$n = 0;
		$arSelect = array("ID", "NAME", "IBLOCK_ID", "DETAIL_PAGE_URL", "PROPERTY_ADD_EVENTS");
		$arFilter = array("IBLOCK_ID" => array(2, 3, 4, 6), "ACTIVE" => "Y", "ID" => $events['id_vuz']);
		$res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
		while($row = $res->GetNext())
		{

			$arrTemp = array();
			$arrItem = explode('#', $row["PROPERTY_ADD_EVENTS_VALUE"]);

			if($arrItem[14] == $events['key_event']) {


				$arrTemp['ID'] = $events['key_event'];
				$arrTemp['DATA'] = $arrItem;

				$arrTemp['URL'] = $row["DETAIL_PAGE_URL"];

				$fullTime = $arrItem[1] . ' ' . $arrItem[2];
				$fullTimeSort = $arrItem[12];

				$strDate = get_str_time_post(strtotime($fullTime));
				$arrTemp["FORMAT_DATE"] = $strDate;

				$curDate = explode(' ', $strDate);
				$arrTemp["DAY"] = $curDate[0];
				$arrTemp["MONTH"] = $curDate[1];

				$arrTemp['META'] = $events;
				$arrTemp['USER'] = $dbh->query('SELECT * from a_events_go WHERE id_vuz = ' . $events['id_vuz'] . ' AND key_event = "' . $events['key_event'] . '"')->fetchAll();

				$arrTemp['MAIN_LIKE'] = $dbh->query('SELECT key_event from a_like_events WHERE id_user = ' . $user_id . ' AND id_vuz = ' . $events['id_vuz'] . ' AND key_event = "' . $events['key_event'] . '"')->fetch();
				$arrTemp['MAIN_DESLIKE'] = $dbh->query('SELECT key_event from a_deslike_events WHERE id_user = ' . $user_id . ' AND id_vuz = ' . $events['id_vuz'] . ' AND key_event = "' . $events['key_event'] . '"')->fetch();
				$arrTemp['CNT_LIKE'] = $dbh->query('SELECT * from a_like_events WHERE id_vuz = ' . $events['id_vuz'] . ' AND key_event = "' . $events['key_event'] . '"')->fetchAll();
				$arrTemp['CNT_DESLIKE'] = $dbh->query('SELECT * from a_deslike_events WHERE id_vuz = ' . $events['id_vuz'] . ' AND key_event = "' . $events['key_event'] . '"')->fetchAll();

				$arResult["EVENTS"][strtotime($fullTimeSort)] = $arrTemp;
				$arResult["EVENTS_DATE"][strtotime($fullTime)] = $arrTemp;

			}
			$n++;
		}
	}
}

krsort($arResult["EVENTS"]);
ksort($arResult["EVENTS_DATE"]);

//echo '<pre>';
//print_r($arResult["EVENTS"]);
//echo '</pre>';
?>
<style>
.page-rating .button.active {
    color: #ff471a;
    background: #fff;
    border-color: #ff471a;
}
.page-rating .button.active span::before {
    border-color: #ff471a;
}
.m-header .filter {
	color: #ff471a;
}
.m-header .filter.color-silver {
	color: #9f9f9f;
	text-decoration: none;
	cursor: default;
}
.st-tags-block .tag {
	color: #ff471a;
}
.st-tags-block .tag.active {
	border-color: #ff471a;
	text-decoration: none;
}
.news-item.one p {
	margin-bottom: 0px;
	margin-top: 5px;
}
.button.js-b-left:hover,
.button.js-b-right:hover,
.button.js-news-left:hover,
.button.js-news-right:hover,
.button.js-event-left:hover,
.button.js-event-right:hover,
.button.js-event-go:hover {
	color: #ff471a;
	background: #fff;
	box-shadow: none;
}
.button.js-b-left:active,
.button.js-b-right:active,
.button.js-news-left:active,
.button.js-news-right:active,
.button.js-event-left:active,
.button.js-event-right:active,
.button.js-event-go:active {
	color: #ff471a;
	background: #fff;
	box-shadow: none;
	box-shadow: 0 0 13px #999 inset;
}
.button.js-event-left:hover span::before,
.button.js-event-right:hover span::before,
.button.js-event-go:hover span::before {
    border-color: #ff471a;
}
.button.js-event-left.active,
.button.js-event-right.active,
.button.js-event-go.active {
    color: #ff471a;
    background: #fff;
    border-color: #ff471a;
}
.button.js-event-left.active span::before,
.button.js-event-right.active span::before,
.button.js-event-go.active span::before {
	border-color: #ff471a;
}

.my-baloon {
    padding: 5px 15px 5px 5px;
    border: 1px solid #ff471a;
    box-shadow: 1px 1px 3px #ccc;
    position: absolute;
    //width: 265px;
    background: #fff;
    z-index: 9999;
    bottom: 68px;
    //left: -120px;
    border-radius: 5px;
    display: none;
}
.my-baloon .image {
	width: auto;
	margin: 10px 0 10px 10px;
}
.my-baloon .image:first-child {

}
.my-baloon .image img {
	width: 22px;
    border-radius: 50%;
    border: 1px solid #ff471a;
    padding: 0px;
    cursor: pointer;
}

.news-item .st-baloon {
    padding: 5px 10px 5px 5px;
    border: 1px solid #ff471a;
    box-shadow: 1px 1px 3px #ccc;
    position: absolute;
    background: #fff;
    z-index: 9999;
    width: 155px;
    top: -60px;
    border-radius: 5px;
    display: none;
}
.news-item .st-baloon .image {
	width: auto;
	margin: 10px 0 10px 10px;
}
.news-item .st-baloon .image:first-child {

}
.news-item .st-baloon .image img {
	width: 22px;
    border-radius: 50%;
    border: 1px solid #ff471a;
    padding: 0px;
    cursor: pointer;
}
.st-baloon .more-baloon {
	text-align: center;
}
.st-baloon .more-baloon span {
	color: #9f9f9f;
	cursor: pointer;
	border-bottom: 1px dashed #9f9f9f;
}
.button.js-event-left:hover,
.button.js-event-right:hover,
.button.js-event-go:hover {
	color: #ff471a;
	background: #fff;
}
.button.js-event-left:hover span::before,
.button.js-event-right:hover span::before,
.button.js-event-go:hover span::before {
    border-color: #ff471a;
}
.button.js-event-left.active,
.button.js-event-right.active,
.button.js-event-go.active {
    color: #ff471a;
    background: #fff;
    border-color: #ff471a;
}
.button.js-event-left.active span::before,
.button.js-event-right.active span::before,
.button.js-event-go.active span::before {
	border-color: #ff471a;
}
#box-line .js-bookmark {
	padding: 0;
	width: 100%;
}
.js-bookmark.active,
.js-bookmark:hover {
	color: #ff471a;
    background: #fff;
    border-color: #ff471a;
    box-shadow: none;
}
.js-bookmark.active span,
.js-bookmark:hover span {
	font-family: Verdana;
	text-decoration: none;
    color: #ff471a;
}
.js-bookmark.active span::before,
.js-bookmark:hover span::before {
	border-color: #ff471a;
}
.js-bookmark:active {
    color: #ff471a;
    background: #fff;
    box-shadow: none;
    box-shadow: 0 0 13px #999 inset;
}
.display-name {
	color: #000 !important;
	cursor: pointer;
	text-decoration-color: #ff471a;
}
.display-name span {
	color: #ff471a;
}
.m-header .filter {
	color: #ff471a;
}
.m-header .filter.color-silver {
	color: #9f9f9f;
	text-decoration: none;
	cursor: default;
}
.news-item.event_id {
	display: block;
}
.news-item.event_date {
	display: none;
}
</style>

<?php
$url = getUserUrl($_SESSION['USER_DATA']);
?>

<div class="st-content-right">
	<div class="breadcrumbs">
		<a href="/">Главная</a> <i class="fa fa-angle-double-right color-orange"></i> <a href="/user/<?php echo $url; ?>/">Профиль</a> <i class="fa fa-angle-double-right color-orange"></i> <span>Мои события</span>
	</div><br>
	<div class="page-content" id="page">
		<div class="st-content-bottom clear">
			<div class="module st-news">
				<div class="name-block text-center txt-up"><span>События</span></div>
				<?php if(sizeof($arResult["EVENTS"]) > 1) { ?>
				<div class="m-header" style="padding-bottom: 5px;">
					<a href="#" data-filter="id_event" class="filter color-silver js-events-list">по дате добавления</a> &nbsp;
					<a href="#" data-filter="date_event" class="filter js-events-list">по дате события</a> &nbsp;
				</div>
				<?php } ?>
				<?php $placeholder = array('Название',
				            'Дата',
				            'Время',
				            'Адрес',
				            'Координаты Яндекс',
				            'Телефон',
				            'Контактное лицо',
				            'Ссылка на страницу',
				            'Комментарий',
				            'Текст',
				            'Облако тегов',
				            'Тег',
				            'Запасная строка',
				            'Дополнительная строка',
				            'Внутренний комментарий'); // 15
				    ?>
				<div class="line" id="box-line" data-type="events">
				<?php
					$go = 1;
					foreach($arResult["EVENTS"] as $ts => $itemList) {
						$arrItem = $itemList['DATA'];
						if(!$arrItem[0])
							continue;
					?>
					<div class="news-item event_id">
						<div class="right" data-vuz="<?=$itemList['META']['id_vuz']?>" data-event="<?=$itemList['ID']?>">
						<? if($arrItem[1]) {
							$arrDate = explode(' ', $arrItem[1]);
							$fullTime = $arrDate[0] . ' ' . $arrItem[2];
							$strDate = get_str_time_post(strtotime($fullTime));
							$curDate = explode(' ', $strDate);
						?>
						<div class="date-ico" style="margin-bottom: 10px;"><span><?=$itemList['DAY']?></span><?=$itemList['MONTH']?></div>
						<? } ?>
						<? if($arrItem[4]) { ?>
							<div class="btns text-right" style="text-align: left;"><a href="#" class="button"><span style="font-family: Verdana;">на карте</span></a></div>
						<? } ?>
							<div class="btns text-right" style="margin-top: 15px; text-align: left; position: relative;">
								<?php
								if(sizeof($itemList['CNT_LIKE'])) {
									if(sizeof($itemList['CNT_LIKE']) > 4) {
										$showBaloon = 3;
									} else {
										$showBaloon = 4;
									}
								?>
								<div class="st-baloon" style="height: 52px; right: 0px; top: -60px;">
								<?php
								$en = 0;
								foreach($itemList['CNT_LIKE'] as $events_item) {
									if($en >= $showBaloon) {
										echo '<div class="more-baloon"><span data-id-vuz="' . $itemList['META']['id_vuz'] . '" data-type="events" data-id="' . $itemList['ID'] . '" data-hash="like" style="margin-left: 10px; font-size: 10px; top: 12px; position: relative;">ещё</span></div>';													break;
									} else {
										$en++;
										$rsUserData = CUser::GetByID($events_item["id_user"]);
										$userData = $rsUserData->Fetch();

										if (strlen(trim($userData['NAME'])) && strlen(trim($userData['LAST_NAME']))) {
											$format_name = $userData['NAME'];
											if($userData['SECOND_NAME']) {
												$format_name .= ' ';
												$format_name .= $userData['SECOND_NAME'];
											}
											$format_name .= ' ';
											$format_name .= $userData['LAST_NAME'];
										} else {
											$format_name = $userData['LOGIN'];
										}

										if($userData['PERSONAL_PHOTO']) {
											$avatar_baloon = CFile::GetPath($userData['PERSONAL_PHOTO']);
										} else {
											$avatar_baloon = SITE_TEMPLATE_PATH . "/images/user-1.png";
										}
									}
									?>
									<a href="/user/<?php echo $userData['ID']; ?>/">
										<div class="image">
											<img style="height: 22px;" src="<?php echo $avatar_baloon; ?>" alt="<?php echo $format_name; ?>" title="<?php echo $format_name; ?>">
										</div>
									</a>
								<?php } ?>
								</div>
								<?php } ?>
								<a href="#" data-my="<?php if($itemList['MAIN_LIKE']) { echo "1"; } else { echo "0"; } ?>" data-cnt="<?php if(sizeof($itemList['CNT_LIKE'])) { echo sizeof($itemList['CNT_LIKE']); } else { echo '0'; } ?>" class="button js-event-left b-left<?php if($itemList['MAIN_LIKE']) { echo " active"; } ?>" style="position: relative; left: 0px; top: 0px;"><span style="text-decoration: none;"><i class="fa fa-thumbs-o-up" style="margin-right: 7px;"></i><?php if(sizeof($itemList['CNT_LIKE'])) { echo sizeof($itemList['CNT_LIKE']); } else { echo '0'; } ?></span></a>
							</div>
							<div class="btns text-right" style="margin-top: 9px; text-align: left; position: relative;">
								<?php
								if(sizeof($itemList['CNT_DESLIKE'])) {
									if(sizeof($itemList['CNT_DESLIKE']) > 4) {
										$showBaloon = 3;
									} else {
										$showBaloon = 4;
									}
								?>
								<div class="st-baloon" style="height: 52px; right: 0px; top: -60px;">
								<?php
								$en = 0;
								foreach($itemList['CNT_DESLIKE'] as $events_item) {
									if($en >= $showBaloon) {
										echo '<div class="more-baloon"><span data-id-vuz="' . $itemList['META']['id_vuz'] . '" data-type="events" data-id="' . $itemList['ID'] . '" data-hash="deslike" style="margin-left: 10px; font-size: 10px; top: 12px; position: relative;">ещё</span></div>';													break;
									} else {
										$en++;
										$rsUserData = CUser::GetByID($events_item["id_user"]);
										$userData = $rsUserData->Fetch();

										if (strlen(trim($userData['NAME'])) && strlen(trim($userData['LAST_NAME']))) {
											$format_name = $userData['NAME'];
											if($userData['SECOND_NAME']) {
												$format_name .= ' ';
												$format_name .= $userData['SECOND_NAME'];
											}
											$format_name .= ' ';
											$format_name .= $userData['LAST_NAME'];
										} else {
											$format_name = $userData['LOGIN'];
										}

										if($userData['PERSONAL_PHOTO']) {
											$avatar_baloon = CFile::GetPath($userData['PERSONAL_PHOTO']);
										} else {
											$avatar_baloon = SITE_TEMPLATE_PATH . "/images/user-1.png";
										}
									}
									?>
									<a href="/user/<?php echo $userData['ID']; ?>/">
										<div class="image">
											<img style="height: 22px;" src="<?php echo $avatar_baloon; ?>" alt="<?php echo $format_name; ?>" title="<?php echo $format_name; ?>">
										</div>
									</a>
								<?php } ?>
								</div>
								<?php } ?>
								<a href="#" data-my="<?php if($itemList['MAIN_DESLIKE']) { echo "1"; } else { echo "0"; } ?>" data-cnt="<?php if(sizeof($itemList['CNT_DESLIKE'])) { echo sizeof($itemList['CNT_DESLIKE']); } else { echo '0'; } ?>" class="button js-event-right b-right<?php if($itemList['MAIN_DESLIKE']) { echo " active"; } ?>" style="position: relative; right: 0px; top: 0px;"><span style="text-decoration: none;"><i class="fa fa-thumbs-o-down" style="margin-right: 7px;"></i><?php if(sizeof($itemList['CNT_DESLIKE'])) { echo sizeof($itemList['CNT_DESLIKE']); } else { echo '0'; } ?></span></a>
							</div>
							<div class="btns text-right" style="margin-top: 9px; text-align: left; position: relative;">
								<?php
								if(sizeof($itemList['USER'])) {
									if(sizeof($itemList['USER']) > 4) {
										$showBaloon = 3;
									} else {
										$showBaloon = 4;
									}
								?>
								<div class="st-baloon" style="height: 52px; right: 0px; top: -60px;">
								<?php
								$en = 0;
								foreach($itemList['USER'] as $events_item) {
									if($en >= $showBaloon) {
										echo '<div class="more-baloon"><span data-id-vuz="' . $itemList['META']['id_vuz'] . '" data-type="events" data-id="' . $itemList['ID'] . '" data-hash="go" style="margin-left: 10px; font-size: 10px; top: 12px; position: relative;">ещё</span></div>';													break;
									} else {
										$en++;
										$rsUserData = CUser::GetByID($events_item["id_user"]);
										$userData = $rsUserData->Fetch();

										if (strlen(trim($userData['NAME'])) && strlen(trim($userData['LAST_NAME']))) {
											$format_name = $userData['NAME'];
											if($userData['SECOND_NAME']) {
												$format_name .= ' ';
												$format_name .= $userData['SECOND_NAME'];
											}
											$format_name .= ' ';
											$format_name .= $userData['LAST_NAME'];
										} else {
											$format_name = $userData['LOGIN'];
										}

										if($userData['PERSONAL_PHOTO']) {
											$avatar_baloon = CFile::GetPath($userData['PERSONAL_PHOTO']);
										} else {
											$avatar_baloon = SITE_TEMPLATE_PATH . "/images/user-1.png";
										}
									}
									?>
									<a href="/user/<?php echo $userData['ID']; ?>/">
										<div class="image">
											<img style="height: 22px;" src="<?php echo $avatar_baloon; ?>" alt="<?php echo $format_name; ?>" title="<?php echo $format_name; ?>">
										</div>
									</a>
								<?php } ?>
								</div>
								<?php } ?>
								<a href="#" data-lk="1" class="button js-event-go b-right active" style="position: relative; right: 0px; top: 0px;"><span style="text-decoration: none;">Я пойду (<?php echo sizeof($itemList['USER']); ?>)</span></a>
							</div>
						</div>
						<div class="date" style="margin-bottom: 7px;"><?php echo $strDate; ?></div>
						<div class="news-name">
							<a href="<?=$itemList['URL']?>?sect=events" alt="<?=$arrItem[0]?>" title="<?=$arrItem[0]?>"><span><?=$arrItem[0]?></span></a>
						</div>
						<p>
						<?php
						for($n = 1; $n < sizeof($placeholder); $n++) {
							if($n == 1 || $n == 2 || $n == 4 || $n == 12 || $n == 13 || $n == 14)
								continue;
							if(trim($arrItem[$n])) {
								if($n == 7) {
									echo $placeholder[$n] . ': <a href="' . $arrItem[7] . '" target="blank">' . trim($arrItem[$n]) . '</a><br>';
								} elseif($n == 9) {
									echo trim($arrItem[$n]) . '<br>';
								} else {
									echo $placeholder[$n] . ': ' . trim($arrItem[$n]) . '<br>';
								}
							}
						}
						?>
						</p>
					</div>
					<?
						$go++;
						if($go > 10)
							break;
					}
					?>
				<?php
					$go = 1;
					foreach($arResult["EVENTS_DATE"] as $ts => $itemList) {
						$arrItem = $itemList['DATA'];
						if(!$arrItem[0])
							continue;
					?>
					<div class="news-item event_date">
						<div class="right" data-vuz="<?=$itemList['META']['id_vuz']?>" data-event="<?=$itemList['ID']?>">
						<? if($arrItem[1]) {
							$arrDate = explode(' ', $arrItem[1]);
							$fullTime = $arrDate[0] . ' ' . $arrItem[2];
							$strDate = get_str_time_post(strtotime($fullTime));
							$curDate = explode(' ', $strDate);
						?>
						<div class="date-ico" style="margin-bottom: 10px;"><span><?=$itemList['DAY']?></span><?=$itemList['MONTH']?></div>
						<? } ?>
						<? if($arrItem[4]) { ?>
							<div class="btns text-right" style="text-align: left;"><a href="#" class="button"><span style="font-family: Verdana;">на карте</span></a></div>
						<? } ?>
							<div class="btns text-right" style="margin-top: 15px; text-align: left; position: relative;">
								<?php
								if(sizeof($itemList['CNT_LIKE'])) {
									if(sizeof($itemList['CNT_LIKE']) > 4) {
										$showBaloon = 3;
									} else {
										$showBaloon = 4;
									}
								?>
								<div class="st-baloon" style="height: 52px; right: 0px; top: -60px;">
								<?php
								$en = 0;
								foreach($itemList['CNT_LIKE'] as $events_item) {
									if($en >= $showBaloon) {
										echo '<div class="more-baloon"><span data-id-vuz="' . $itemList['META']['id_vuz'] . '" data-type="events" data-id="' . $itemList['ID'] . '" data-hash="like" style="margin-left: 10px; font-size: 10px; top: 12px; position: relative;">ещё</span></div>';													break;
									} else {
										$en++;
										$rsUserData = CUser::GetByID($events_item["id_user"]);
										$userData = $rsUserData->Fetch();

										if (strlen(trim($userData['NAME'])) && strlen(trim($userData['LAST_NAME']))) {
											$format_name = $userData['NAME'];
											if($userData['SECOND_NAME']) {
												$format_name .= ' ';
												$format_name .= $userData['SECOND_NAME'];
											}
											$format_name .= ' ';
											$format_name .= $userData['LAST_NAME'];
										} else {
											$format_name = $userData['LOGIN'];
										}

										if($userData['PERSONAL_PHOTO']) {
											$avatar_baloon = CFile::GetPath($userData['PERSONAL_PHOTO']);
										} else {
											$avatar_baloon = SITE_TEMPLATE_PATH . "/images/user-1.png";
										}
									}
									?>
									<a href="/user/<?php echo $userData['ID']; ?>/">
										<div class="image">
											<img style="height: 22px;" src="<?php echo $avatar_baloon; ?>" alt="<?php echo $format_name; ?>" title="<?php echo $format_name; ?>">
										</div>
									</a>
								<?php } ?>
								</div>
								<?php } ?>
								<a href="#" data-my="<?php if($itemList['MAIN_LIKE']) { echo "1"; } else { echo "0"; } ?>" data-cnt="<?php if(sizeof($itemList['CNT_LIKE'])) { echo sizeof($itemList['CNT_LIKE']); } else { echo '0'; } ?>" class="button js-event-left b-left<?php if($itemList['MAIN_LIKE']) { echo " active"; } ?>" style="position: relative; left: 0px; top: 0px;"><span style="text-decoration: none;"><i class="fa fa-thumbs-o-up" style="margin-right: 7px;"></i><?php if(sizeof($itemList['CNT_LIKE'])) { echo sizeof($itemList['CNT_LIKE']); } else { echo '0'; } ?></span></a>
							</div>
							<div class="btns text-right" style="margin-top: 9px; text-align: left; position: relative;">
								<?php
								if(sizeof($itemList['CNT_DESLIKE'])) {
									if(sizeof($itemList['CNT_DESLIKE']) > 4) {
										$showBaloon = 3;
									} else {
										$showBaloon = 4;
									}
								?>
								<div class="st-baloon" style="height: 52px; right: 0px; top: -60px;">
								<?php
								$en = 0;
								foreach($itemList['CNT_DESLIKE'] as $events_item) {
									if($en >= $showBaloon) {
										echo '<div class="more-baloon"><span data-id-vuz="' . $itemList['META']['id_vuz'] . '" data-type="events" data-id="' . $itemList['ID'] . '" data-hash="deslike" style="margin-left: 10px; font-size: 10px; top: 12px; position: relative;">ещё</span></div>';													break;
									} else {
										$en++;
										$rsUserData = CUser::GetByID($events_item["id_user"]);
										$userData = $rsUserData->Fetch();

										if (strlen(trim($userData['NAME'])) && strlen(trim($userData['LAST_NAME']))) {
											$format_name = $userData['NAME'];
											if($userData['SECOND_NAME']) {
												$format_name .= ' ';
												$format_name .= $userData['SECOND_NAME'];
											}
											$format_name .= ' ';
											$format_name .= $userData['LAST_NAME'];
										} else {
											$format_name = $userData['LOGIN'];
										}

										if($userData['PERSONAL_PHOTO']) {
											$avatar_baloon = CFile::GetPath($userData['PERSONAL_PHOTO']);
										} else {
											$avatar_baloon = SITE_TEMPLATE_PATH . "/images/user-1.png";
										}
									}
									?>
									<a href="/user/<?php echo $userData['ID']; ?>/">
										<div class="image">
											<img style="height: 22px;" src="<?php echo $avatar_baloon; ?>" alt="<?php echo $format_name; ?>" title="<?php echo $format_name; ?>">
										</div>
									</a>
								<?php } ?>
								</div>
								<?php } ?>
								<a href="#" data-my="<?php if($itemList['MAIN_DESLIKE']) { echo "1"; } else { echo "0"; } ?>" data-cnt="<?php if(sizeof($itemList['CNT_DESLIKE'])) { echo sizeof($itemList['CNT_DESLIKE']); } else { echo '0'; } ?>" class="button js-event-right b-right<?php if($itemList['MAIN_DESLIKE']) { echo " active"; } ?>" style="position: relative; right: 0px; top: 0px;"><span style="text-decoration: none;"><i class="fa fa-thumbs-o-down" style="margin-right: 7px;"></i><?php if(sizeof($itemList['CNT_DESLIKE'])) { echo sizeof($itemList['CNT_DESLIKE']); } else { echo '0'; } ?></span></a>
							</div>
							<div class="btns text-right" style="margin-top: 9px; text-align: left; position: relative;">
								<?php
								if(sizeof($itemList['USER'])) {
									if(sizeof($itemList['USER']) > 4) {
										$showBaloon = 3;
									} else {
										$showBaloon = 4;
									}
								?>
								<div class="st-baloon" style="height: 52px; right: 0px; top: -60px;">
								<?php
								$en = 0;
								foreach($itemList['USER'] as $events_item) {
									if($en >= $showBaloon) {
										echo '<div class="more-baloon"><span data-id-vuz="' . $itemList['META']['id_vuz'] . '" data-type="events" data-id="' . $itemList['ID'] . '" data-hash="go" style="margin-left: 10px; font-size: 10px; top: 12px; position: relative;">ещё</span></div>';													break;
									} else {
										$en++;
										$rsUserData = CUser::GetByID($events_item["id_user"]);
										$userData = $rsUserData->Fetch();

										if (strlen(trim($userData['NAME'])) && strlen(trim($userData['LAST_NAME']))) {
											$format_name = $userData['NAME'];
											if($userData['SECOND_NAME']) {
												$format_name .= ' ';
												$format_name .= $userData['SECOND_NAME'];
											}
											$format_name .= ' ';
											$format_name .= $userData['LAST_NAME'];
										} else {
											$format_name = $userData['LOGIN'];
										}

										if($userData['PERSONAL_PHOTO']) {
											$avatar_baloon = CFile::GetPath($userData['PERSONAL_PHOTO']);
										} else {
											$avatar_baloon = SITE_TEMPLATE_PATH . "/images/user-1.png";
										}
									}
									?>
									<a href="/user/<?php echo $userData['ID']; ?>/">
										<div class="image">
											<img style="height: 22px;" src="<?php echo $avatar_baloon; ?>" alt="<?php echo $format_name; ?>" title="<?php echo $format_name; ?>">
										</div>
									</a>
								<?php } ?>
								</div>
								<?php } ?>
								<a href="#" data-lk="1" class="button js-event-go b-right active" style="position: relative; right: 0px; top: 0px;"><span style="text-decoration: none;">Я пойду (<?php echo sizeof($itemList['USER']); ?>)</span></a>
							</div>
						</div>
						<div class="date" style="margin-bottom: 7px;"><?php echo $strDate; ?></div>
						<div class="news-name">
							<a href="<?=$itemList['URL']?>?sect=events" alt="<?=$arrItem[0]?>" title="<?=$arrItem[0]?>"><span><?=$arrItem[0]?></span></a>
						</div>
						<p>
						<?php
						for($n = 1; $n < sizeof($placeholder); $n++) {
							if($n == 1 || $n == 2 || $n == 4 || $n == 12 || $n == 13 || $n == 14)
								continue;
							if(trim($arrItem[$n])) {
								if($n == 7) {
									echo $placeholder[$n] . ': <a href="' . $arrItem[7] . '" target="blank">' . trim($arrItem[$n]) . '</a><br>';
								} elseif($n == 9) {
									echo trim($arrItem[$n]) . '<br>';
								} else {
									echo $placeholder[$n] . ': ' . trim($arrItem[$n]) . '<br>';
								}
							}
						}
						?>
						</p>
					</div>
					<?
						$go++;
						if($go > 10)
							break;
					}
					?>
				</div>
			</div>
		</div>
	</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
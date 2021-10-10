<?php if($pageAdmin) { ?>
<div style="text-align: center; margin: -5px auto 20px auto;">
	<span class="color-silver js-new-add" data-vuz="<?=$arResult["ID"]?>" data-type="events" data-iblock="6" style="cursor: pointer; border-bottom: 1px dashed #9f9f9f;">Добавить событие</span>
</div>
<?php } ?>
<?php
global $dbh;
CModule::IncludeModule('iblock');

$user_id = 0;
if($_SESSION['USER_DATA']) {
	$user_id = $_SESSION['USER_DATA']['ID'];
	$user_name = $_SESSION['USER_DATA']['FULL_NAME'];
	$user_avatar = $_SESSION['USER_DATA']['AVATAR'];
}
?>
<style>
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
</style>
<div class="page-content" id="page">
	<div class="name-block text-center txt-up"><span>События языкового курса</span></div>
	<div class="st-content-bottom clear">
	<? if($arResult["PROPERTIES"]["ADD_EVENTS"]["VALUE"]) { ?>
		<div class="module st-news">
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
		            'Дата создания',
		            'Дополнительная строка',
		            'Уникальный ключ'); // 15

			$like_events = array();
			$like_events_sql = $dbh->query('SELECT key_event from a_like_events WHERE id_user = ' . $user_id . ' AND id_vuz = ' . $arResult["ID"])->fetchAll();
			foreach($like_events_sql as $like_events_item) {
				$like_events[] = $like_events_item['key_event'];
			}

			$deslike_events = array();
			$deslike_events_sql = $dbh->query('SELECT key_event from a_deslike_events WHERE id_user = ' . $user_id . ' AND id_vuz = ' . $arResult["ID"])->fetchAll();
			foreach($deslike_events_sql as $deslike_events_item) {
				$deslike_events[] = $deslike_events_item['key_event'];
			}

			$like_events_cnt = array();
			$like_events_cnt_sql = $dbh->query('SELECT * from a_like_events WHERE id_vuz = ' . $arResult["ID"])->fetchAll();
			foreach($like_events_cnt_sql as $like_events_cnt_item) {
				$like_events_cnt[$like_events_cnt_item['key_event']][] = $like_events_cnt_item;
			}

			$deslike_events_cnt = array();
			$deslike_events_cnt_sql = $dbh->query('SELECT * from a_deslike_events WHERE id_vuz = ' . $arResult["ID"])->fetchAll();
			foreach($deslike_events_cnt_sql as $deslike_events_cnt_item) {
				$deslike_events_cnt[$deslike_events_cnt_item['key_event']][] = $deslike_events_cnt_item;
			}

			$deslike_events_go = array();
			$deslike_events_go_sql = $dbh->query('SELECT key_event from a_events_go WHERE id_user = ' . $user_id . ' AND id_vuz = ' . $arResult["ID"])->fetchAll();
			foreach($deslike_events_go_sql as $deslike_events_go_item) {
				$deslike_events_go[] = $deslike_events_go_item['key_event'];
			}

			$deslike_events_go_cnt = array();
			$deslike_events_go_cnt_sql = $dbh->query('SELECT * from a_events_go WHERE id_vuz = ' . $arResult["ID"])->fetchAll();
			foreach($deslike_events_go_cnt_sql as $deslike_events_go_cnt_item) {
				$deslike_events_go_cnt[$deslike_events_go_cnt_item['key_event']][] = $deslike_events_go_cnt_item;
			}

			foreach($arResult["PROPERTIES"]["ADD_EVENTS"]["VALUE"] as $idEvent => $item) {
				$arrTemp = array();
				$arrItem = explode('#', $item);
				if(!$arrItem[0])
					continue;

				$arrTemp['ID'] = $arrItem[14];
				$arrTemp['DATA'] = $arrItem;

				$fullTime = $arrItem[1] . ' ' . $arrItem[2];

				$strDate = get_str_time_post(strtotime($fullTime));
				$arrTemp["FORMAT_DATE"] = $strDate;

				$curDate = explode(' ', $strDate);
				$arrTemp["DAY"] = $curDate[0];
				$arrTemp["MONTH"] = $curDate[1];

				$arrTemp['sort'] = strtotime($fullTime);
				$newsList[] = $arrTemp;
			}

			usort($newsList, "cmp_uz");
		?>
			<script>
			<?php
			if(sizeof($newsList) > 10) {
				echo 'var startFrom = 10;' . "\n";
			}

			echo "var id_vuz = " . $arResult["ID"] . ";\n";
			echo "var id_user = " . $user_id . ";\n";
			echo "var user_name = '" . $user_name . "';\n";
			echo "var user_avatar = '" . $user_avatar . "';\n";
			echo "var curPage = 'language-class';\n";

			echo 'var arrLikeEvents = new Array();' . "\n";
			foreach($like_events as $itemLikeEvents) {
				echo 'arrLikeEvents.push("' . $itemLikeEvents . '");' . "\n";
			}
			echo 'var arrDeslikeEvents = new Array();' . "\n";
			foreach($deslike_events as $itemDeslikeEvents) {
				echo 'arrDeslikeEvents.push("' . $itemDeslikeEvents . '");' . "\n";
			}
			echo 'var arrLikeEventsCnt = new Array();' . "\n";
			foreach($like_events_cnt as $idEvent => $arrCnt) {
				echo 'arrLikeEventsCnt["' . $idEvent . '"] = ' . sizeof($arrCnt) . ';' . "\n";
			}
			echo 'var arrDeslikeEventsCnt = new Array();' . "\n";
			foreach($deslike_events_cnt as $idEvent => $arrCnt) {
				echo 'arrDeslikeEventsCnt["' . $idEvent . '"] = ' . sizeof($arrCnt) . ';' . "\n";
			}

			echo 'var arrGoEvents = new Array();' . "\n";
			foreach($deslike_events_go as $itemDeslikeEvents) {
				echo 'arrGoEvents.push("' . $itemDeslikeEvents . '");' . "\n";
			}
			echo 'var arrGoEventsCnt = new Array();' . "\n";
			foreach($deslike_events_go_cnt as $idEvent => $arrCnt) {
				echo 'arrGoEventsCnt["' . $idEvent . '"] = ' . sizeof($arrCnt) . ';' . "\n";
			}

			echo "var detailPageUrl = '" . $arResult["DETAIL_PAGE_URL"] . "';" . "\n";
			?>
			</script>

			<div class="line" id="box-line" data-type="events">
			<?php
				$go = 1;
				$cur_time = time();
				$tuday = 1;
				foreach($newsList as $ts => $itemList) {
					$arrItem = $itemList['DATA'];
					if(!$arrItem[0])
						continue;
				?>
				<?php
				if($cur_time > $itemList['sort'] && $tuday) {
					$tuday = 0;
					if($ts) {
				?>
					<div style="height: 1px; border-top: 1px solid #ff4719; position: relative; top: -21px; text-align: center;">
						<div style="display: inline-block; padding: 5px 15px; background-color: #ffffff; position: relative; top: -14px;">Сегодня</div>
					</div>
				<?php
					}
				}
				?>
				<div class="news-item events" data-id="<?=$arResult['ID']?>" data-ukey="<?=$itemList['ID']?>">
					<div class="right" data-vuz="<?=$arResult['ID']?>" data-event="<?=$itemList['ID']?>">
					<?if($pageAdmin):?>
					<div style="position: relative; top: -10px; right: 5px; text-align: right;">
						<div class="color-silver js-news-edit" data-block="events" data-id="<?php echo $itemList["ID"]; ?>" data-iblock="6" style="cursor: pointer; border-bottom: 1px dashed #9f9f9f; display: inline-block;">изменить</div>
					</div>
					<?endif?>
					<? if($arrItem[1]) {
						$arrDate = explode(' ', $arrItem[1]);
						$fullTime = $arrDate[0] . ' ' . $arrItem[2];
						$strDate = get_str_time_post(strtotime($fullTime));
						$curDate = explode(' ', $strDate);
					?>
					<div class="date-ico" style="margin-bottom: 10px;"><span><?=$itemList['DAY']?></span><?=$itemList['MONTH']?></div>
					<? } ?>
					<? if($arrItem[4]) { ?>
					<div class="btns text-right" style="text-align: left;">
						<a href="/map/?map=<?php echo $arResult["ID"]; ?>&event=<?php echo ($itemList["ID"] + 1); ?>" class="button">
							<span style="font-family: Verdana;">на карте</span>
						</a>
					</div>
					<? } ?>
						<div class="btns text-right" style="margin-top: 15px; text-align: left; position: relative;">
							<?php
							if(sizeof($like_events_cnt[$itemList['ID']])) {
								if(sizeof($like_events_cnt[$itemList['ID']]) > 4) {
									$showBaloon = 3;
								} else {
									$showBaloon = 4;
								}
								?>
								<div class="st-baloon" style="height: 52px; right: 0px; top: -60px;">
									<?php
									$en = 0;
									foreach($like_events_cnt[$itemList['ID']] as $events_item) {
										if($en >= $showBaloon) {
											echo '<div class="more-baloon"><span data-id-vuz="' . $arResult['ID'] . '" data-type="events" data-id="' . $itemList["ID"] . '" data-hash="like" style="margin-left: 10px; font-size: 10px; top: 12px; position: relative;">ещё</span></div>';													break;
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
										<a href="/user/<?php echo $userData['ID']; ?>/"<?php if(!$_SESSION['USER_DATA']) { echo ' class="js-noauth"'; } ?>>
											<div class="image">
												<img style="height: 22px;" src="<?php echo $avatar_baloon; ?>" alt="<?php echo $format_name; ?>" title="<?php echo $format_name; ?>">
											</div>
										</a>
								<?php } ?>
								</div>
							<?php } ?>
							<a href="#" data-my="<?php if(in_array($itemList['ID'], $like_events)) { echo "1"; } else { echo "0"; } ?>" data-cnt="<?php if(sizeof($like_events_cnt[$itemList['ID']])) { echo sizeof($like_events_cnt[$itemList['ID']]); } else { echo '0'; } ?>" class="button <?php if($_SESSION['USER_DATA']) { echo 'js-event-left'; } else { echo 'js-noauth'; } ?> b-left<?php if(in_array($itemList['ID'], $like_events)) { echo " active"; } ?>" style="position: relative; left: 0px; top: 0px;">
								<span style="text-decoration: none;"><i class="fa fa-thumbs-o-up" style="margin-right: 7px;"></i><?php if(sizeof($like_events_cnt[$itemList['ID']])) { echo sizeof($like_events_cnt[$itemList['ID']]); } else { echo '0'; } ?></span>
							</a>
						</div>
						<div class="btns text-right" style="margin-top: 9px; text-align: left; position: relative;">
							<?php
							if(sizeof($deslike_events_cnt[$itemList['ID']])) {
								if(sizeof($deslike_events_cnt[$itemList['ID']]) > 4) {
									$showBaloon = 3;
								} else {
									$showBaloon = 4;
								}
								?>
								<div class="st-baloon" style="height: 52px; right: 0px; top: -60px;">
									<?php
									$en = 0;
									foreach($deslike_events_cnt[$itemList['ID']] as $events_item) {
										if($en >= $showBaloon) {
											echo '<div class="more-baloon"><span data-id-vuz="' . $arResult['ID'] . '" data-type="events" data-id="' . $itemList["ID"] . '" data-hash="deslike" style="margin-left: 10px; font-size: 10px; top: 12px; position: relative;">ещё</span></div>';
											break;
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
										<a href="/user/<?php echo $userData['ID']; ?>/"<?php if(!$_SESSION['USER_DATA']) { echo ' class="js-noauth"'; } ?>>
											<div class="image">
												<img style="height: 22px;" src="<?php echo $avatar_baloon; ?>" alt="<?php echo $format_name; ?>" title="<?php echo $format_name; ?>">
											</div>
										</a>
								<?php } ?>
								</div>
							<?php } ?>
							<a href="#" data-my="<?php if(in_array($itemList['ID'], $deslike_events)) { echo "1"; } else { echo "0"; } ?>" data-cnt="<?php if(sizeof($deslike_events_cnt[$itemList['ID']])) { echo sizeof($deslike_events_cnt[$itemList['ID']]); } else { echo '0'; } ?>" class="button <?php if($_SESSION['USER_DATA']) { echo 'js-event-right'; } else { echo 'js-noauth'; } ?> b-right<?php if(in_array($itemList['ID'], $deslike_events)) { echo " active"; } ?>" style="position: relative; right: 0px; top: 0px;">
								<span style="text-decoration: none;"><i class="fa fa-thumbs-o-down" style="margin-right: 7px;"></i><?php if(sizeof($deslike_events_cnt[$itemList['ID']])) { echo sizeof($deslike_events_cnt[$itemList['ID']]); } else { echo '0'; } ?></span>
							</a>
						</div>
						<div class="btns text-right" style="margin-top: 9px; text-align: left; position: relative;">
							<?php
							if(sizeof($deslike_events_go_cnt[$itemList['ID']])) {
								if(sizeof($deslike_events_go_cnt[$itemList['ID']]) > 4) {
									$showBaloon = 3;
								} else {
									$showBaloon = 4;
								}
								?>
								<div class="st-baloon" style="height: 52px; right: 0px; top: -60px;">
								<?php
								$en = 0;
								foreach($deslike_events_go_cnt[$itemList['ID']] as $events_item) {
										if($en >= $showBaloon) {
											echo '<div class="more-baloon"><span data-id-vuz="' . $arResult['ID'] . '" data-type="events" data-id="' . $itemList["ID"] . '" data-hash="go" style="margin-left: 10px; font-size: 10px; top: 12px; position: relative;">ещё</span></div>';
											break;
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
										<a href="/user/<?php echo $userData['ID']; ?>/"<?php if(!$_SESSION['USER_DATA']) { echo ' class="js-noauth"'; } ?>>
											<div class="image">
												<img style="height: 22px;" src="<?php echo $avatar_baloon; ?>" alt="<?php echo $format_name; ?>" title="<?php echo $format_name; ?>">
											</div>
										</a>
								<?php } ?>
								</div>
							<?php } ?>
							<a href="#" data-lk="0" class="button <?php if($_SESSION['USER_DATA']) { echo 'js-event-go'; } else { echo 'js-noauth'; } ?> b-right<?php if(in_array($itemList['ID'], $deslike_events_go)) { echo " active"; } ?>" style="position: relative; right: 0px; top: 0px;"><span style="text-decoration: none;">Я пойду (<?php echo sizeof($deslike_events_go_cnt[$itemList['ID']]); ?>)</span></a>
						</div>
					</div>
					<div class="date" style="margin-bottom: 7px;"><?php echo $strDate; if($cur_time > $itemList['sort']) { echo ' (событие уже прошло)'; } ?></div>
					<div class="news-name">
						<span><?=$arrItem[0]?></span>
					</div>
					<p style="margin-right: 100px;">
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
		</div><!-- st-news -->
	<? } ?>
	</div><!-- st-content-bottom -->
</div>
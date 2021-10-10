<?php if($pageAdmin) { ?>
<div style="text-align: center; margin: -5px auto 20px auto;">
	<span class="color-silver js-new-add" data-vuz="<?=$arResult["ID"]?>" data-type="news" data-iblock="6" style="cursor: pointer; border-bottom: 1px dashed #9f9f9f;">Добавить новость</span>
</div>
<?php } ?>
<?php
global $dbh;
CModule::IncludeModule('iblock');

$placeholder = array('Название подразделения',
    'ID вуза',
    'ID колледжа',
    'ID школы',
    'Адрес',
    'Координаты Яндекс',
    'Метро',
    'Телефон',
    'Ссылка на страницу',
    'Email',
    'Текст',
    'Облако тегов',
    'Тег',
    'ucheba.ru',
    'Запасная строка',
    'Дополнительная строка',
    'Внутренний комментарий'); // 17

$user_id = 0;
if($_SESSION['USER_DATA']) {
	$user_id = $_SESSION['USER_DATA']['ID'];
	$user_name = $_SESSION['USER_DATA']['FULL_NAME'];
	$user_avatar = $_SESSION['USER_DATA']['AVATAR'];
}

$like = array();
$like_sql = $dbh->query('SELECT id_news from a_like_news WHERE id_user = ' . $user_id . ' AND id_vuz = ' . $arResult["ID"])->fetchAll();
foreach($like_sql as $like_item) {
	$like[] = $like_item['id_news'];
}

$deslike = array();
$deslike_sql = $dbh->query('SELECT id_news from a_deslike_news WHERE id_user = ' . $user_id . ' AND id_vuz = ' . $arResult["ID"])->fetchAll();
foreach($deslike_sql as $deslike_item) {
	$deslike[] = $deslike_item['id_news'];
}

$like_news_cnt = array();
$like_news_cnt_sql = $dbh->query('SELECT * from a_like_news WHERE id_vuz = ' . $arResult["ID"])->fetchAll();
foreach($like_news_cnt_sql as $like_news_cnt_item) {
	$like_news_cnt[$like_news_cnt_item['id_news']][] = $like_news_cnt_item;
}

$deslike_news_cnt = array();
$deslike_news_cnt_sql = $dbh->query('SELECT * from a_deslike_news WHERE id_vuz = ' . $arResult["ID"])->fetchAll();
foreach($deslike_news_cnt_sql as $deslike_news_cnt_item) {
	$deslike_news_cnt[$deslike_news_cnt_item['id_news']][] = $deslike_news_cnt_item;
}
?>
<script>
<?php
echo "var id_vuz = " . $arResult["ID"] . ";\n";
echo "var id_user = " . $user_id . ";\n";
echo "var user_name = '" . $user_name . "';\n";
echo "var user_avatar = '" . $user_avatar . "';\n";

echo 'var arrLikeNewsCnt = new Array();' . "\n";
foreach($like_news_cnt as $idNews => $arrCnt) {
	echo 'arrLikeNewsCnt[' . $idNews . '] = ' . sizeof($arrCnt) . ';' . "\n";
}
echo 'var arrDeslikeNewsCnt = new Array();' . "\n";
foreach($deslike_news_cnt as $idNews => $arrCnt) {
	echo 'arrDeslikeNewsCnt[' . $idNews . '] = ' . sizeof($arrCnt) . ';' . "\n";
}
?>
</script>
<?php
if($_REQUEST['s']) {
	$id_news = (int) $_REQUEST['s'];
	$arSelect = array("ID", "NAME", "IBLOCK_ID", "DATE_CREATE", "PREVIEW_PICTURE", "DETAIL_TEXT", "DETAIL_PICTURE", "PROPERTY_LIKE", "PROPERTY_DESLIKE", "PROPERTY_MORE_PHOTO");
	$arFilter = array("IBLOCK_ID" => 30, "ACTIVE" => "Y", "ID" => $id_news);
	$res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
	$news_item = $res->Fetch();

	$news_item["FORMAT_DATE"] = get_str_time_post(strtotime($news_item['DATE_CREATE']));
?>
<style>
.st-news .news-item .button {
    font-size: 11px;
    padding: 0 10px;
    width: 90px;
    line-height: 30px;
}
</style>
<div class="page-content" id="page">
<div class="name-block text-center txt-up"><span>Новости языкового курса</span></div>
<div class="st-content-bottom clear">
	<div class="module st-news">
		<div class="line" id="box-line">
			<div class="news-item one" style="position: relative;">
				<?if($pageAdmin):?>
				<div class="color-silver js-news-edit" data-block="news" data-id="<?php echo $news_item["ID"]; ?>" data-iblock="6" style="position: absolute; right: 5px; cursor: pointer; border-bottom: 1px dashed #9f9f9f;">изменить</div>
				<?endif?>
				<?php if($news_item["PREVIEW_PICTURE"]) { ?>
				<div class="image brd left">
					<img src="<? echo CFile::GetPath($news_item["PREVIEW_PICTURE"]); ?>" alt="<?=$news_item["NAME"]?>" title="<?=$news_item["NAME"]?>" style="max-width: 230px;">
				</div>
				<? } ?>
				<div class="date" style="margin-bottom: 7px;"><?php echo $news_item["FORMAT_DATE"]; ?></div>
				<div class="news-name" style="margin-bottom: 15px;"><span><?=$news_item["NAME"]?></span></div>
				<?php
				$br = str_replace(array("\r\n", "\r", "\n"), '<br>', $news_item["DETAIL_TEXT"]);
				echo $br;
				?>
				<div class="page-rating" data-news="<?=$news_item['ID']?>" data-vuz="<?=$arResult["ID"]?>" data-name="<?=$news_item["NAME"]?>" style="margin: 30px 0px 5px 0px;">
					<?php if(sizeof($like_news_cnt[$news_item['ID']])) {
							if(sizeof($like_news_cnt[$news_item['ID']]) > 4) {
								$showBaloon = 3;
							} else {
								$showBaloon = 4;
							}
					?>
					<div class="st-baloon" style="left: 200px; height: 52px;">
					<?php
					$en = 0;
					foreach($like_news_cnt[$news_item['ID']] as $news_item_list) {
						if($en >= $showBaloon) {
							echo '<div class="more-baloon"><span data-id-vuz="' . $arResult['ID'] . '" data-type="news" data-id="' . $news_item["ID"] . '" data-hash="like" style="margin-left: 10px; font-size: 10px; top: 12px; position: relative;">ещё</span></div>';													break;
						} else {
							$en++;
							$rsUserData = CUser::GetByID($news_item_list["id_user"]);
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
							<div class="image" style="height: 42px;">
								<img style="height: 22px;" src="<?php echo $avatar_baloon; ?>" alt="<?php echo $format_name; ?>" title="<?php echo $format_name; ?>">
							</div>
						</a>
					<?php } ?>
					</div>
					<?php } ?>
					<a href="#" data-my="<?php if(in_array($news_item['ID'], $like)) { echo "1"; } else { echo "0"; } ?>" data-cnt="<?php if(sizeof($like_news_cnt[$news_item['ID']])) { echo sizeof($like_news_cnt[$news_item['ID']]); } else { echo '0'; } ?>" class="button full <?php if($_SESSION['USER_DATA']) { echo 'js-news-left'; } else { echo 'js-noauth'; } ?> b-left<?php if(in_array($news_item['ID'], $like)) { echo " active"; } ?>" style="position: relative; left: 0px; top: 0px; width: 90px;">
						<span><i class="fa fa-thumbs-o-up" style="margin-right: 7px;"></i><?php if(sizeof($like_news_cnt[$news_item['ID']])) { echo sizeof($like_news_cnt[$news_item['ID']]); } else { echo '0'; } ?></span>
					</a>
					<?php if(sizeof($deslike_news_cnt[$news_item['ID']])) {
							if(sizeof($deslike_news_cnt[$news_item['ID']]) > 4) {
								$showBaloon = 3;
							} else {
								$showBaloon = 4;
							}
					?>
					<div class="st-baloon" style="left: 300px; height: 52px;">
					<?php
					$en = 0;
					foreach($deslike_news_cnt[$news_item['ID']] as $news_item_list) {
						if($en >= $showBaloon) {
							echo '<div class="more-baloon"><span data-id-vuz="' . $arResult['ID'] . '" data-type="news" data-id="' . $news_item["ID"] . '" data-hash="deslike" style="margin-left: 10px; font-size: 10px; top: 12px; position: relative;">ещё</span></div>';													break;
						} else {
							$en++;
							$rsUserData = CUser::GetByID($news_item_list["id_user"]);
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
							<div class="image" style="height: 42px;">
								<img style="height: 22px;" src="<?php echo $avatar_baloon; ?>" alt="<?php echo $format_name; ?>" title="<?php echo $format_name; ?>">
							</div>
						</a>
					<?php } ?>
					</div>
					<?php } ?>
					<a href="#" data-my="<?php if(in_array($news_item['ID'], $deslike)) { echo "1"; } else { echo "0"; } ?>" data-cnt="<?php if(sizeof($deslike_news_cnt[$news_item['ID']])) { echo sizeof($deslike_news_cnt[$news_item['ID']]); } else { echo '0'; } ?>" class="button full <?php if($_SESSION['USER_DATA']) { echo 'js-news-right'; } else { echo 'js-noauth'; } ?> b-right<?php if(in_array($news_item['ID'], $deslike)) { echo " active"; } ?>" style="position: relative; right: 0px; top: 0px; margin-left: 5px; width: 90px;">
						<span><i class="fa fa-thumbs-o-down" style="margin-right: 7px;"></i><?php if(sizeof($deslike_news_cnt[$news_item['ID']])) { echo sizeof($deslike_news_cnt[$news_item['ID']]); } else { echo '0'; } ?></span>
					</a>
					<div class="btns text-right" style="float: right;">
						<a href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=news" class="button" style="font-family: Verdana;"><i class="fa fa-angle-double-left"></i> назад к списку новостей</a>
					</div>
				</div>
			</div>
		</div>
	</div><!-- st-news -->

	<div class="name-block text-center"><span>Другие новости</span></div>
	<div class="st-carousel news-3">
		<div class="owl-carousel">
			<?php
			$arrNews = array();

			$arSelect = array("ID", "NAME", "IBLOCK_ID", "DATE_CREATE", "PREVIEW_PICTURE");
			$arFilter = array("IBLOCK_ID" => 30, "ACTIVE" => "Y", "PROPERTY_VUZ_ID" => $arResult["ID"], "!ID" => $news_item["ID"]);
			$resCarusel = CIBlockElement::GetList(array("RAND" => "ASC"), $arFilter, false, false, $arSelect);
			while($rowCarusel = $resCarusel->Fetch()) {
				$rowCarusel["FORMAT_DATE"] = get_str_time_post(strtotime($rowCarusel['DATE_CREATE']));

				$arrNews[] = $rowCarusel;
			}
			foreach($arrNews as $val_item) {
			?>
			<div class="st-item">
				<?php if($val_item["PREVIEW_PICTURE"]) { ?>
				<div class="image brd"><img src="<? echo CFile::GetPath($val_item["PREVIEW_PICTURE"]); ?>"></div>
				<?php } ?>
				<div class="small color-silver"><?php echo $val_item['FORMAT_DATE']; ?></div>
				<a href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=news&s=<?php echo $val_item['ID']; ?>"><span><?php echo $val_item['NAME']; ?></span></a>
			</div>
			<?php
			}
			?>
		</div>
	</div><!-- st-carousel -->
</div>
</div>
<?php
} else {

	$arrNews = array();
	$perPage = 5;
	$page = 1;

	if($_REQUEST['p'])
		$page = (int) $_REQUEST['p'];

	$arFilter = Array("IBLOCK_ID"=>30, "ACTIVE"=>"Y", "PROPERTY_VUZ_ID" => $arResult["ID"]);
	$cnt = CIBlockElement::GetList(false, $arFilter, array('IBLOCK_ID'))->Fetch()['CNT'];

	$totalPage = ceil($cnt/$perPage);

	$arSelect = array("ID", "NAME", "IBLOCK_ID", "DATE_CREATE", "PREVIEW_PICTURE", "DETAIL_TEXT", "PROPERTY_LIKE", "PROPERTY_DESLIKE");
	$arFilter = array("IBLOCK_ID" => 30, "ACTIVE" => "Y", "PROPERTY_VUZ_ID" => $arResult["ID"]);
	$res = CIBlockElement::GetList(array("ID" => "DESC"), $arFilter, false,  false, $arSelect);
	while($row = $res->Fetch()) {
		$row["FORMAT_DATE"] = get_str_time_post(strtotime($row['DATE_CREATE']));

		$arrNews[] = $row;
	}
	echo '<div class="page-content" id="page">';
	echo '<div class="name-block text-center txt-up"><span>Новости языкового курса</span></div>';
	echo '<div class="module st-news">';
	?>
	<script>
	<?php
	if(sizeof($arrNews) > 10) {
	?>
		var startFrom = 10;
	<?php
	}
	echo "var detailPageUrl = '" . $arResult["DETAIL_PAGE_URL"] . "';" . "\n";

	echo 'var arrLikeNews = new Array();' . "\n";
	foreach($like as $itemLike) {
		echo 'arrLikeNews.push(' . $itemLike . ');' . "\n";
	}
	echo 'var arrDeslikeNews = new Array();' . "\n";
	foreach($deslike as $itemDeslike) {
		echo 'arrDeslikeNews.push(' . $itemDeslike . ');' . "\n";
	}

	?>
	</script>
	<?php

	$nNews = sizeof($arrNews);
	if($nNews > 10)
		$nNews = 10;

	if($nNews) {
		echo '<div class="line" id="box-line" data-vuz="' . $arResult['ID'] . '" data-type="news" data-iblock="6">';
		for($n = 0; $n < $nNews; $n++) {
			$news_item = $arrNews[$n];
		?>
		<div class="news-item news" style="position: relative;">
			<?if($pageAdmin):?>
			<div class="color-silver js-news-edit" data-block="news" data-id="<?php echo $news_item["ID"]; ?>" style="position: absolute; right: 5px; cursor: pointer; border-bottom: 1px dashed #9f9f9f;">изменить</div>
			<?endif?>
			<?php if($news_item["PREVIEW_PICTURE"]) { ?>
			<div class="image brd left"><img src="<? echo CFile::GetPath($news_item["PREVIEW_PICTURE"]); ?>" alt="<?=$news_item["NAME"]?>" title="<?=$news_item["NAME"]?>" style="max-width: 200px;"></div>
			<?php } ?>
			<div class="date" style="margin-bottom: 7px;"><?php echo $news_item["FORMAT_DATE"]; ?></div>
			<div class="news-name" style="margin-bottom: 15px;">
				<a href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=news&s=<?php echo $news_item["ID"]; ?>"><span><?=$news_item["NAME"]?></span></a>
			</div>
			<p>
			<?php
			$br = str_replace(array("\r\n", "\r", "\n"), '<br>', $news_item["DETAIL_TEXT"]);
			$out = mb_substr($br, 0, 148);
			echo $out . '..';
			?>
			</p>
			<div class="page-rating" data-news="<?=$news_item['ID']?>" data-vuz="<?=$arResult["ID"]?>" data-name="<?=$news_item["NAME"]?>" style="margin: 0px 0px 5px 0px; text-align: right;">
			<?php
				if(sizeof($like_news_cnt[$news_item['ID']])) {
					if(sizeof($like_news_cnt[$news_item['ID']]) > 4) {
						$showBaloon = 3;
					} else {
						$showBaloon = 4;
					}
				?>
				<div class="st-baloon" style="right: 100px; height: 52px;">
				<?php
				$en = 0;
				foreach($like_news_cnt[$news_item['ID']] as $news_item_baloon) {
					if($en >= $showBaloon) {
						echo '<div class="more-baloon"><span data-id-vuz="' . $arResult['ID'] . '" data-type="news" data-id="' . $news_item["ID"] . '" data-hash="like" data-iblock="6" style="margin-left: 10px; font-size: 10px; top: 12px; position: relative;">ещё</span></div>';													break;
					} else {
						$en++;
						$rsUserData = CUser::GetByID($news_item_baloon["id_user"]);
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
						<div class="image" style="height: 42px;">
							<img style="height: 22px;" src="<?php echo $avatar_baloon; ?>" alt="<?php echo $format_name; ?>" title="<?php echo $format_name; ?>">
						</div>
					</a>
				<?php } ?>
				</div>
				<?php } ?>
				<a href="#" data-my="<?php if(in_array($news_item['ID'], $like)) { echo "1"; } else { echo "0"; } ?>" data-cnt="<?php if(sizeof($like_news_cnt[$news_item['ID']])) { echo sizeof($like_news_cnt[$news_item['ID']]); } else { echo '0'; } ?>" class="button <?php if($_SESSION['USER_DATA']) { echo 'js-news-left'; } else { echo 'js-noauth'; } ?> b-left<?php if(in_array($news_item['ID'], $like)) { echo " active"; } ?>" style="position: relative; left: 0px; top: 0px;">
					<span><i class="fa fa-thumbs-o-up" style="margin-right: 7px;"></i><?php if(sizeof($like_news_cnt[$news_item['ID']])) { echo sizeof($like_news_cnt[$news_item['ID']]); } else { echo '0'; } ?></span>
				</a>
				<?php
				if(sizeof($deslike_news_cnt[$news_item['ID']])) {
					if(sizeof($deslike_news_cnt[$news_item['ID']]) > 4) {
						$showBaloon = 3;
					} else {
						$showBaloon = 4;
					}
				?>
				<div class="st-baloon" style="right: 0px; height: 52px;">
				<?php
				$en = 0;
				foreach($deslike_news_cnt[$news_item['ID']] as $news_item_baloon) {
					if($en >= $showBaloon) {
						echo '<div class="more-baloon"><span data-id-vuz="' . $arResult['ID'] . '" data-type="news" data-id="' . $news_item["ID"] . '" data-hash="deslike" data-iblock="6" style="margin-left: 10px; font-size: 10px; top: 12px; position: relative;">ещё</span></div>';
						break;
					} else {
						$en++;
						$rsUserData = CUser::GetByID($news_item_baloon["id_user"]);
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
						<div class="image" style="height: 42px;">
							<img style="height: 22px;" src="<?php echo $avatar_baloon; ?>" alt="<?php echo $format_name; ?>" title="<?php echo $format_name; ?>">
						</div>
					</a>
				<?php } ?>
				</div>
				<?php } ?>
				<a href="#" data-my="<?php if(in_array($news_item['ID'], $deslike)) { echo "1"; } else { echo "0"; } ?>" data-cnt="<?php if(sizeof($deslike_news_cnt[$news_item['ID']])) { echo sizeof($deslike_news_cnt[$news_item['ID']]); } else { echo '0'; } ?>" class="button <?php if($_SESSION['USER_DATA']) { echo 'js-news-right'; } else { echo 'js-noauth'; } ?> b-right<?php if(in_array($news_item['ID'], $deslike)) { echo " active"; } ?>" style="position: relative; right: 0px; top: 0px; margin-left: 5px;">
					<span><i class="fa fa-thumbs-o-down" style="margin-right: 7px;"></i><?php if(sizeof($deslike_news_cnt[$news_item['ID']])) { echo sizeof($deslike_news_cnt[$news_item['ID']]); } else { echo '0'; } ?></span>
				</a>
			</div>
		</div>
		<?php
		}

		if($totalPage > 1 && 0) {
		?>
		<br><br>
		<div class="page-nav">
		<?php
		if($page > 1) {
			echo '<a href="' . $arResult["DETAIL_PAGE_URL"] . '?sect=news">«</a>';
		} elseif($n == 0) {
			echo '<span>«</span>';
		}

		for($n = 1; $n <= $totalPage; $n++) {

			if($n == $page) {
				echo '<span style="margin-left: 5px;">' . $n . '</span>';
			} elseif($n > 0) {
				echo '<a href="' . $arResult["DETAIL_PAGE_URL"] . '?sect=news&p=' . $n . '" style="margin-left: 5px;">' . $n . '</a>';
			}

		}

		if($page == $totalPage) {
			echo '<span style="margin-left: 5px;">»</span>';
		} else {
			echo '<a href="' . $arResult["DETAIL_PAGE_URL"] . '?sect=news&p=' . $totalPage . '" style="margin-left: 5px;">»</a>';
		}
		?>
		</div>
		<?php
		}
		echo '</div>';
	}
	echo '</div>';
	echo '</div>';
}
?>
<style>
.m-header .filter {
	color: #ff471a;
}
a.btn-post {
	text-decoration: none;
	border-bottom: 1px dashed;
}
.m-header .filter.color-silver {
	color: #9f9f9f;
	text-decoration: none;
	cursor: default;
	border-bottom: none;
}
.button.js-b-left-2:hover,
.button.js-b-right-2:hover {
	/*color: #ff471a;
	background: #fff;
	box-shadow: none;*/
    color: #fff;
    background: #ff471a;
    border-color: #ff471a;
	box-shadow: none;
}
.button.js-b-left-2:active,
.button.js-b-right-2:active {
	color: #ff471a;
	background: #fff;
	box-shadow: none;
	box-shadow: 0 0 13px #999 inset;
}
</style>
<?php
global $dbh;

$user_id = 0;
if($_SESSION['USER_DATA']) {
	$user_id = $_SESSION['USER_DATA']['ID'];
	$user_name = $_SESSION['USER_DATA']['FULL_NAME'];
	$user_avatar = $_SESSION['USER_DATA']['AVATAR'];
}

$arrPostFirst = array();
$arrPostSecond = array();

$arSelect = array("ID", "NAME", "IBLOCK_ID", "DETAIL_TEXT", "ACTIVE", "DATE_CREATE", "PROPERTY_USER_ID", "PROPERTY_PARENT_ID", "PROPERTY_LIKE", "PROPERTY_DESLIKE");
$arFilter = Array("IBLOCK_ID"=>21, "PROPERTY_VUZ_ID" => $arResult["ID"]);
$res_reviews = CIBlockElement::GetList(array("ID" => "DESC"), $arFilter, false, false, $arSelect);
while($row_reviews = $res_reviews->Fetch()) {

	$rsUser = CUser::GetByID($row_reviews["PROPERTY_USER_ID_VALUE"]);
	$postUser = $rsUser->Fetch();

	$name = trim($postUser['NAME']) . ' ' . trim($postUser['LAST_NAME']);
	if (strlen($name) <= 0)
		$name = $postUser['LOGIN'];

	$row_reviews["NAME_USER"] = $name;

	if($postUser['PERSONAL_PHOTO']) {
		$row_reviews["AVATAR"] = CFile::GetPath($postUser['PERSONAL_PHOTO']);
	} else {
		$row_reviews["AVATAR"] = SITE_TEMPLATE_PATH . "/img/foto-user.png";
	}

	if($postUser['WORK_WWW']) {
		$arrTeacher = $dbh->query('SELECT COUNT(id) as cnt from a_user_uz WHERE teacher = 1 AND user_id = ' . $postUser['ID'])->fetch();
		if($arrTeacher['cnt'] > 0) {
			$row_reviews['TEACHER'] = 1;
		} else {
			$row_reviews['TEACHER'] = 0;
		}
	} else {
		$row_reviews['TEACHER'] = 0;
	}

	$row_reviews["FORMAT_DATE"] = get_str_time_post(strtotime($row_reviews['DATE_CREATE']));

	if(!$row_reviews["PROPERTY_PARENT_ID_VALUE"]) {
 		$arrPostFirst[$row_reviews['ID']] = $row_reviews;
 	} else {
		$arrPostSecond[$row_reviews['PROPERTY_PARENT_ID_VALUE']][] = $row_reviews;
 	}
}
if($arrPostFirst) {

	$arrPostTree = array();

	/*foreach($arrPostSecond as $idSecond => $itemSecond) {
		if(!$arrPostFirst[$idSecond]) {
			$itemFirst['children'] = $arrPostSecond[$idFirst];
		}
		$arrPostTree[] = $itemFirst;
	}*/

	foreach($arrPostFirst as $idFirst => $itemFirst) {
		if($arrPostSecond[$idFirst]) {
			$itemFirst['children'] = $arrPostSecond[$idFirst];
		}
		$arrPostTree[] = $itemFirst;
	}

	$user_id = 0;
	if($_SESSION['USER_DATA'])
		$user_id = $_SESSION['USER_DATA']['ID'];

	//var_dump($user_id);
	$like = array();
	$like_sql = $dbh->query('SELECT id_post from a_like_user WHERE id_user = ' . $user_id . ' AND id_vuz = ' . $arResult["ID"])->fetchAll();
	foreach($like_sql as $like_item) {
		$like[] = $like_item['id_post'];
	}

	$deslike = array();
	$deslike_sql = $dbh->query('SELECT id_post from a_deslike_user WHERE id_user = ' . $user_id . ' AND id_vuz = ' . $arResult["ID"])->fetchAll();
	foreach($deslike_sql as $deslike_item) {
		$deslike[] = $deslike_item['id_post'];
	}

	$like_post_cnt = array();
	$like_post_cnt_sql = $dbh->query('SELECT * from a_like_user WHERE id_vuz = ' . $arResult["ID"])->fetchAll();
	foreach($like_post_cnt_sql as $like_post_cnt_item) {
		$like_post_cnt[$like_post_cnt_item['id_post']][] = $like_post_cnt_item;
	}

	$deslike_post_cnt = array();
	$deslike_post_cnt_sql = $dbh->query('SELECT * from a_deslike_user WHERE id_vuz = ' . $arResult["ID"])->fetchAll();
	foreach($deslike_post_cnt_sql as $deslike_post_cnt_item) {
		$deslike_post_cnt[$deslike_post_cnt_item['id_post']][] = $deslike_post_cnt_item;
	}
}

$arrAbuse = array();

if($user_id) {
	$arSelect = array("ID", "NAME", "IBLOCK_ID", "PROPERTY_POST_ID", "PROPERTY_WARNING", "PROPERTY_REJECT");
	if($pageAdmin || isEdit()){
		$arFilter = Array("IBLOCK_ID"=>23, "PROPERTY_VUZ_ID" => $arResult["ID"]);
	} else {
		$arFilter = Array("IBLOCK_ID"=>23, "PROPERTY_ABUSE" => $arResult["ID"]);
	}
	$res_abuse = CIBlockElement::GetList(array("ID" => "DESC"), $arFilter, false, false, $arSelect);
	while($row_abuse = $res_abuse->Fetch()) {

        if($row_abuse['PROPERTY_WARNING_VALUE'] == 'Y' || $row_abuse['PROPERTY_REJECT_VALUE'] == 'Y') {
            $n = 1;
        } else {
            $arrAbuse[] = $row_abuse['PROPERTY_POST_ID_VALUE'];
        }
	}
}
?>
<script>
<?php
echo "var id_vuz = " . $arResult["ID"] . ";\n";
echo "var id_user = " . $user_id . ";\n";
echo "var user_name = '" . $user_name . "';\n";
echo "var user_avatar = '" . $user_avatar . "';\n";

echo 'var arrLikeNews = new Array();' . "\n";
foreach($like as $itemLike) {
	echo 'arrLikeNews.push(' . $itemLike . ');' . "\n";
}
echo 'var arrDeslikeNews = new Array();' . "\n";
foreach($deslike as $itemDeslike) {
	echo 'arrDeslikeNews.push(' . $itemDeslike . ');' . "\n";
}
echo 'var arrLikePostCnt = new Array();' . "\n";
foreach($like_post_cnt as $idPost => $arrCnt) {
	echo 'arrLikePostCnt[' . $idPost . '] = ' . sizeof($arrCnt) . ';' . "\n";
}
echo 'var arrDeslikePostCnt = new Array();' . "\n";
foreach($deslike_post_cnt as $idPost => $arrCnt) {
	echo 'arrDeslikePostCnt[' . $idPost . '] = ' . sizeof($arrCnt) . ';' . "\n";
}

echo "var detailPageUrl = '" . $arResult["DETAIL_PAGE_URL"] . "';" . "\n";
?>
</script>
<?php
//echo '<pre>';
//print_r($arrPostTree);
//echo '</pre>';
function greate_post($item, $arResult, $like, $deslike, $like_post_cnt, $deslike_post_cnt, $arrAbuse, $recursion = false) {
	//var_dump($item);
	global $USER;
	if($recursion) {
	?>
	<div id="post-<?=$item['ID']?>" data-post="<?=$item['ID']?>" class="news-item" style="margin-left: 30px; margin-top: 15px; margin-bottom: 0px; padding-bottom: 0px; border-bottom: none;">
	<?php } else { ?>
	<div id="post-<?=$item['ID']?>" data-post="<?=$item['ID']?>" class="news-item" style="margin-top: 25px;">
	<?php } ?>
		<a href="/user/<?=$item["PROPERTY_USER_ID_VALUE"]?>/">
			<img src="<?=$item['AVATAR']?>" alt="img" style="<?php if($recursion) { echo 'width: 22px; top: -3px;'; } else { echo 'width: 44px;'; } ?> border-radius: 50%; <?php if($item['TEACHER']) { echo 'border: 2px'; } else { echo 'border: 1px'; } ?> solid #ff471a;">
		</a>
		<div class="news-name" style="display: inline-block; position: relative; top: -12px; width: 80%;">
			<a href="/user/<?=$item["PROPERTY_USER_ID_VALUE"]?>/"<?php if($recursion) { echo ' style="font-size: 14px;"'; } ?>><span><?=$item['NAME_USER']?></span></a><span style="color: #9f9f9f; margin-left: 10px; font-size: 13px;"><?=$item['FORMAT_DATE']?></span>
		</div>

		<?php
		if($_SESSION['USER_DATA']['AVATAR']) {
			$mainAvatar = $_SESSION['USER_DATA']['AVATAR'];
		} else {
			$mainAvatar = SITE_TEMPLATE_PATH . "/img/foto-user.png";
		}
		if(strlen($item['DETAIL_TEXT']) > 200) {
			$textPost = substr($item['DETAIL_TEXT'], 0, 198) . '.. <a href="#" class="js-btn-text-full btn-post">читать весь</a>';
			$br = str_replace(array("\r\n", "\r", "\n"), '<br>', $item['DETAIL_TEXT']);
		?>
		<p class="js-text-full" style="display: none; margin-top: 5px;"><?php echo $br . ' <a href="#" class="js-btn-text-short btn-post">свернуть</a>'; ?></p>
		<?php
		} else {
			$textPost = $item['DETAIL_TEXT'];
		}
		$viewText = str_replace(array("\r\n", "\r", "\n"), '<br>', $textPost);
		?>
		<?php if($item["ACTIVE"] == 'Y') { ?>
		<p class="js-text-short" style="margin-top: 5px;"><?=$viewText?></p>
		<div class="page-rating" data-post="<?=$item['ID']?>" data-vuz="<?=$arResult["ID"]?>" data-name="<?=$arResult["NAME"]?>" style="margin: 0px 0px 5px 0px;">

			<?php
			if(sizeof($like_post_cnt[$item['ID']])) {
				if(sizeof($like_post_cnt[$item['ID']]) > 4) {
					$showBaloon = 3;
				} else {
					$showBaloon = 4;
				}
			?>
			<div class="st-baloon" style="left: 0px; height: 52px;">
			<?php
			$en = 0;
			foreach($like_post_cnt[$item['ID']] as $news_item) {
				if($en >= $showBaloon) {
					echo '<div class="more-baloon"><span data-id-vuz="' . $arResult['ID'] . '" data-type="post" data-id="' . $news_item['id_post'] . '" data-hash="like" style="margin-left: 10px; font-size: 10px; top: 12px; position: relative;">ещё</span></div>';
					break;
				} else {
					$en++;
					$rsUserData = CUser::GetByID($news_item["id_user"]);
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
				<a class="post-like-<?php echo $news_item["id_user"]; ?><?php if(!$_SESSION['USER_DATA']) { echo ' js-noauth'; } ?>" href="/user/<?php echo $userData['ID']; ?>/">
					<div class="image">
						<img style="height: 22px;" src="<?php echo $avatar_baloon; ?>" alt="<?php echo $format_name; ?>" title="<?php echo $format_name; ?>">
					</div>
				</a>
			<?php } ?>
			</div>
			<?php } ?>
			<a href="#" data-my="<?php if(in_array($item['ID'], $like)) { echo "1"; } else { echo "0"; } ?>" data-cnt="<?php if(sizeof($like_post_cnt[$item['ID']])) { echo sizeof($like_post_cnt[$item['ID']]); } else { echo '0'; } ?>" class="button <?php if($_SESSION['USER_DATA']) { echo 'js-b-left-2'; } else { echo 'js-noauth'; } ?> b-left<?php if(in_array($item['ID'], $like)) { echo " active"; } ?>" style="position: relative; left: 0px; top: 0px;"><span><i class="fa fa-thumbs-o-up" style="margin-right: 7px;"></i><?php if(sizeof($like_post_cnt[$item['ID']])) { echo sizeof($like_post_cnt[$item['ID']]); } else { echo '0'; } ?></span></a>

			<?php
			if(sizeof($deslike_post_cnt[$item['ID']])) {
				if(sizeof($deslike_post_cnt[$item['ID']]) > 4) {
					$showBaloon = 3;
				} else {
					$showBaloon = 4;
				}
			?>
			<div class="st-baloon" style="left: 100px; height: 52px;">
			<?php
			$en = 0;
			foreach($deslike_post_cnt[$item['ID']] as $news_item) {
				if($en >= $showBaloon) {
					echo '<div class="more-baloon"><span data-id-vuz="' . $arResult['ID'] . '" data-type="post" data-id="' . $news_item['id_post'] . '" data-hash="deslike" style="margin-left: 10px; font-size: 10px; top: 12px; position: relative;">ещё</span></div>';
					break;
				} else {
					$en++;
					$rsUserData = CUser::GetByID($news_item["id_user"]);
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
				<a class="post-deslike-<?php echo $news_item["id_user"]; ?><?php if(!$_SESSION['USER_DATA']) { echo ' js-noauth'; } ?>" href="/user/<?php echo $userData['ID']; ?>/">
					<div class="image">
						<img style="height: 22px;" src="<?php echo $avatar_baloon; ?>" alt="<?php echo $format_name; ?>" title="<?php echo $format_name; ?>" style="<?php if($item['TEACHER']) { echo 'border: 2px'; } else { echo 'border: 1px'; } ?> solid #ff471a;">
					</div>
				</a>
			<?php } ?>
			</div>
			<?php } ?>
			<a href="#" data-my="<?php if(in_array($item['ID'], $deslike)) { echo "1"; } else { echo "0"; } ?>" data-cnt="<?php if(sizeof($deslike_post_cnt[$item['ID']])) { echo sizeof($deslike_post_cnt[$item['ID']]); } else { echo '0'; } ?>" class="button <?php if($_SESSION['USER_DATA']) { echo 'js-b-right-2'; } else { echo 'js-noauth'; } ?> b-right<?php if(in_array($item['ID'], $deslike)) { echo " active"; } ?>" style="position: relative; right: 0px; top: 0px; margin-left: 5px;"><span><i class="fa fa-thumbs-o-down" style="margin-right: 7px;"></i><?php if(sizeof($deslike_post_cnt[$item['ID']])) { echo sizeof($deslike_post_cnt[$item['ID']]); } else { echo '0'; } ?></span></a>

			<?php if($_SESSION['USER_DATA']) { ?>
				<?php if($item["PROPERTY_USER_ID_VALUE"] == $_SESSION['USER_DATA']['ID']) { ?>
				<a class="js-edit-post" data-text="<?=$item['DETAIL_TEXT']?>" style="text-decoration: none; cursor: pointer; margin-left: 15px;"><span style="border-bottom: 1px dashed; margin-left: 3px;">редактировать</span></a>
				<?php } elseif(!$recursion) { ?>
				<a class="js-comment-post" style="text-decoration: none; cursor: pointer; margin-left: 15px;">
					<img src="<?=$mainAvatar?>" alt="img" style="width: 22px; position: relative; top: 6px; border-radius: 50%; <?php if($_SESSION['USER_DATA']['TEACHER']) { echo 'border: 2px solid #ff5b32;'; } else { echo 'border: 1px solid #ff5b32;'; } ?>">
					<span style="border-bottom: 1px dashed; margin-left: 3px;">комментировать</span>
				</a>
				<?php } ?>
			<?php }	?>
			<?php if($item["PROPERTY_USER_ID_VALUE"] == $_SESSION['USER_DATA']['ID']) { ?>
			<?php if(in_array($item['ID'], $arrAbuse)) { ?>
				<span style="margin-left: 15px; color: red;">подана жалоба</span>
			<?php } ?>
			<a class="js-delete-post" style="text-decoration: none; cursor: pointer; margin-left: 15px;"><span style="border-bottom: 1px dashed; margin-left: 3px; color: #9f9f9f;">удалить</span></a>
			<?php } else { ?>
				<?php if(in_array($item['ID'], $arrAbuse)) { ?>
					<span style="margin-left: 15px; color: red;">подана жалоба</span>
					<a class="js-delete-post" style="text-decoration: none; cursor: pointer; margin-left: 15px;"><span style="border-bottom: 1px dashed; margin-left: 3px; color: #9f9f9f;">удалить</span></a>
				<?php } else { ?>
				<a class="js-abuse-post" data-id="<?=$item['ID']?>" data-user="<?=$item["PROPERTY_USER_ID_VALUE"]?>" data-text="<?=$item['DETAIL_TEXT']?>" data-name="<?=$item['NAME']?>" style="text-decoration: none; cursor: pointer; margin-left: 15px;"><span style="border-bottom: 1px dashed; margin-left: 3px; color: #9f9f9f;">пожаловаться</span></a>
				<?php } ?>
			<?php } ?>
		</div>
		<?php } else { ?>
		<p class="js-text-short" style="margin-top: 5px; color: #9f9f9f;">Сообщение было удалено</p>
		<?php } ?>
		<? if($item['children']) {
			foreach($item['children'] as $item) {
				greate_post($item, $arResult, $like, $deslike, $like_post_cnt, $deslike_post_cnt, $arrAbuse, $recursion = true);
			}
		}
		?>
	</div>
<?php
}
//var_dump($_SESSION['USER_DATA']);
?>
<div class="page-content" id="page">
	<div class="name-block text-center txt-up"><span>Отзывы</span></div>
	<div class="st-content-bottom clear">

		<div class="module st-news">
			<div class="m-header" style="margin: 0 0 25px;">
				<a href="#" data-filter="date" class="filter js-filter-one btn-post color-silver">По дате</a> &nbsp;
				<a href="#" data-filter="pop" class="filter js-filter-one btn-post">По популярности</a> &nbsp;
			</div>
			<?php if($_SESSION['USER_DATA']) { ?>
			<div class="btns" style="text-align: center; margin: 0;"><a href="#" class="button js-new-post" data-vuz="<?=$arResult["ID"]?>" data-name="<?=$arResult["NAME"]?>"><span style="font-family: Verdana; text-decoration: none;">Написать отзыв</span></a></div>
			<?php } else { ?>
			<div class="btns" style="text-align: center; margin: 0;"><span class="button" style="font-family: Verdana; text-decoration: none;">Написать отзыв</span></div>
			<?php } ?>
			<div class="line" id="box-line">
				<? if($arrPostTree) { ?>
					<? foreach($arrPostTree as $item) {
						greate_post($item, $arResult, $like, $deslike, $like_post_cnt, $deslike_post_cnt, $arrAbuse);
					} ?>
				<? } ?>
			</div>
		</div><!-- st-news -->
	</div><!-- st-content-bottom -->
</div>
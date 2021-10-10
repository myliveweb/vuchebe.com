<?php
$user_id = 0;
if($_SESSION['USER_DATA']) {
	$user_id = $_SESSION['USER_DATA']['ID'];
}

$arrFilter = array();
$flagArray = $dbh->query('SELECT * from a_user_uz WHERE uz_id = ' . $arResult['ID'] . ' AND teacher = 0 ORDER BY user_name ASC')->fetchAll();
foreach($flagArray as $flagItem) {
	if($flagItem['end_p']) {
		$arrFilter['end'] = 1;
	} elseif($flagItem['start_p']) {
		$arrFilter['start'] = 1;
	}
}
?>
<style>
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
#box-line .js-bookmark {
    padding: 0;
    width: 100%;
    //font-family: Verdana;
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
.js-bookmark.active:hover {
    color: ffffff;
    box-shadow: none;
}
.js-bookmark.active:hover span {
    text-decoration: none;
    color: #ffffff;
}

/* Поиск */
.st-content-users form .button-filed .full {
    display: block;
}

.st-content-users form .button-filed .short {
    display: none;
}

.st-content-users form .button-filed button {
    padding: 0;
}

@media screen and (max-width: 540px) {
    .st-content-users form .search-filed {
        width: 75%;
    }

    .st-content-users form .button-filed {
        width: 25%;
    }

    .st-content-users form .button-filed .full {
        display: none;
    }

    .st-content-users form .button-filed .short {
        display: block;
    }

    .st-content-users .filter-search .filter {
        display: block;
        margin-right: 0;
    }

    .st-content-users .filter-search .filter .bulet {
        top: 4px;
        right: 4px;
    }
}
</style>
<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/pages.css">
<?php
$input = filter_input_array(INPUT_POST);
$search = $input['s'];
?>
<div class="page-content st-content-users" id="page">
    <!-- Поиск -->
    <div class="structure-cat bg-silver text-center" style="margin: 15px 0 20px;">
        <div class="row-line">
            <form id="user-search" method="post" accept-charset="utf-8">
                <div class="col-10 search-filed" style="padding: 0 0 0 15px;">
                    <input type="text" name="s" id="search-text" value="<?php echo $search; ?>" />
                    <input type="hidden" name="p" value="1" />
                    <input type="hidden" name="filter" id="filterinput" value="<?php echo $filter; ?>" />
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
	<div class="name-block text-center txt-up"><span>Студенты колледжа</span></div>
	<div class="st-content-bottom clear">
	<? if($arResult["STUDENTS"]) { ?>
		<div class="module st-news">
			<div class="m-header" style="padding-bottom: 10px;">
				<a href="#" data-filter="all" class="filter color-silver js-user-list">Все</a> &nbsp;
				<?if($arrFilter['start']):?>
				<a href="#" data-filter="start" class="filter js-user-list">Учатся</a> &nbsp;
				<?endif?>
				<?if($arrFilter['end']):?>
				<a href="#" data-filter="end" class="filter js-user-list">Учились</a> &nbsp;
				<?endif?>
			</div>
			<div class="line" id="box-line" data-type="students">
				<?
				$arrUsers = array();
				$arrId = array();
				foreach($arResult["STUDENTS"] as $item) {
					$rsUser = CUser::GetByID($item['user_id']);
					$arResult['USER'] = $rsUser->Fetch();

					if(!$arResult['USER'])
						continue;

					if($arResult['USER']['PERSONAL_PHOTO']) {
						$avatar_url = CFile::GetPath($arResult['USER']['PERSONAL_PHOTO']);
					} else {
						$avatar_url = SITE_TEMPLATE_PATH . "/images/user-1.png";
					}

					if (strlen(trim($arResult['USER']['NAME'])) && strlen(trim($arResult['USER']['LAST_NAME']))) {
						$format_name = '<span>' . strtoupper(mb_substr(trim($arResult['USER']['NAME']), 0, 1)) . '</span>' . mb_substr(trim($arResult['USER']['NAME']), 1);
						if($arResult['USER']['SECOND_NAME']) {
							$format_name .= ' ';
							$format_name .= '<span>' . strtoupper(mb_substr($arResult['USER']['SECOND_NAME'], 0, 1)) . '</span>' . mb_substr($arResult['USER']['SECOND_NAME'], 1);
						}
						$format_name .= ' ';
						$format_name .= '<span>' . strtoupper(mb_substr(trim($arResult['USER']['LAST_NAME']), 0, 1)) . '</span>' . mb_substr(trim($arResult['USER']['LAST_NAME']), 1);
					} else {
						$format_name = '<span>' . strtoupper(mb_substr(trim($arResult['USER']['LOGIN']), 0, 1)) . '</span>' . mb_substr(trim($arResult['USER']['LOGIN']), 1);
					}

		            if($user_id) {
		                $bookmark = $dbh->query('SELECT * from a_bookmark WHERE type = 5 AND uz_id = ' . $arResult['USER']['ID'] . ' AND user_id = ' . $user_id)->fetch();
		            }

		            if(in_array($arResult['USER']['ID'], $arrUsers)) {
		            	$strIn = implode(',', $arrId);
		            	$flag = $dbh->query('SELECT * from a_user_uz WHERE uz_id = ' . $arResult['ID'] . ' AND user_id = ' . $arResult['USER']['ID'] . ' AND teacher = 0 AND id NOT IN(' . $strIn . ')')->fetch();
		            	$arrId[] = $flag['id'];
		        	} else {
		            	$flag = $dbh->query('SELECT * from a_user_uz WHERE uz_id = ' . $arResult['ID'] . ' AND user_id = ' . $arResult['USER']['ID'] . ' AND teacher = 0')->fetch();
		            	$arrUsers[]	= $arResult['USER']['ID'];
		            	$arrId[] = $flag['id'];
		        	}

                    $url = getUserUrl($arResult['USER']);
				?>
				<div class="news-item<?php if($flag['end_p']) { echo ' end'; } elseif($flag['start_p']) { echo ' start'; } ?>">
					<div class="col-3 width-sm content-left">
						<div class="image brd rad-50"><img src="<?=$avatar_url?>" alt="img" style="height: 111px; width: 111px;"></div>
						<br>
					</div>
	                <div class="col-9 width-sm content-right" style="padding: 0;">
	                    <div class="page-info">
	                        <h1 class="name-user">
	                            <span><a href="/user/<?=$url?>/" class="display-name"><?=$format_name?></a></span>
	                            <?php if(CUser::IsOnLine($arResult['USER']['ID'], 30) && $arResult['USER']['PERSONAL_PAGER'] != 1 && $_SESSION['USER_DATA']['PERSONAL_PAGER'] != 1) { ?>
	                            <div style="display: inline-block; position: relative; top: -1px; margin-left: 5px; width: 10px; height: 10px; border-radius: 50%; background-color: #ff471a;" title="В сети"></div>
	                            <?php } ?>
	                        </h1>
	                        <div class="contact-info">
	                        	<?php
								if($flag['end_p']) {
								?>
								<div class="date-ico theme-1 right" style="position: relative;">
									год<br>выпуска
									<span><?php echo $flag['end_p']; ?></span>
								</div>
								<?php
								} elseif($flag['start_p']) {
								?>
								<div class="date-ico theme-1 right" style="position: relative;">
									год<br>поступления
									<span><?php echo $flag['start_p']; ?></span>
								</div>
								<?php
								}
	                        	?>
	                            <div class="btns" style="margin-top: 25px; width: 145px; display: inline-block;">
	                                <a style="height: 33px;" href="#" class="button js-bookmark<?php if($bookmark) { echo ' active'; } ?>" data-state="0" data-type="<?php echo '5'; ?>" data-id="<?php echo $arResult['USER']['ID']; ?>" data-no-close="1">
	                                    <span style="font-size: 16px; padding-top: 5px;">закладки</span>
	                                </a>
	                            </div>
	                            <div class="btns right" style="cursor: pointer; display: inline-block; float: none; position: relative; top: -1px;">
                              <?php
                                if($_SESSION['USER_DATA']['PRO'] === 'Y') {
                                  $link = '';
                                } else {
                                  $link = 'href="/user/chat/'. $arResult['USER']['ID'] . '/"';
                                }
                              ?>
                                  <a style="height: 31px;" <?php echo $link; ?> class="button small">сообщение</a>
	                            </div>
	                        </div><!-- contact-info -->
	                        <br>
	                    </div>
	                </div><!-- content-right -->
				</div>
				<?
				}
				?>
			</div>
		</div><!-- st-news -->
	<? } else { ?>
		<div class="module st-news">
			<h1>К сожалению ничего не найдено</h1>
		</div><!-- st-news -->
	<? } ?>
	</div><!-- st-content-bottom -->
</div>
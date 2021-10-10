<?php
$user_id = 0;
if($_SESSION['USER_DATA']) {
	$user_id = $_SESSION['USER_DATA']['ID'];
}
?>
<style>

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
</div>
<?php if($pageAdmin) { ?>
<div style="text-align: center; margin: 5px auto 20px auto;">
	<span class="color-silver js-admins-add" data-id="0" data-vuz-id="<?php echo $arResult['ID']; ?>" data-iblock="4" style="cursor: pointer; border-bottom: 1px dashed #9f9f9f;">Добавить администратора</span>
</div>
<?php } ?>
<style>
.display-name {
	color: #000 !important;
	cursor: pointer;
	text-decoration-color: #ff471a;
}
.display-name span {
	color: #ff471a;
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
</style>
<div class="module st-news">
	<div class="line" id="box-line" data-type="students">
		<?
        if($search) {
            $arrOut = array();
            foreach($arResult["PROPERTIES"]["ADMINS"]["VALUE"] as $item) {
                $filter = array("NAME" => $search, "ID" => $item);
                $rsUsers = CUser::GetList($by="NAME", $order="ASC", $filter);
                if($userItem = $rsUsers->Fetch()) {
                    $arrOut[] = $item;
                }
            }
            $arResult["PROPERTIES"]["ADMINS"]["VALUE"] = $arrOut;
        }

		foreach($arResult["PROPERTIES"]["ADMINS"]["VALUE"] as $item) {

			$rsUser = CUser::GetByID($item);
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

			if($arResult['USER']['WORK_WWW']) {
				$arrTeacher = $dbh->query('SELECT COUNT(id) as cnt from a_user_uz WHERE teacher = 1 AND user_id = ' . $arResult['USER']['ID'])->fetch();
				if($arrTeacher['cnt'] > 0) {
					$arResult['USER']['TEACHER'] = 1;
				} else {
					$arResult['USER']['TEACHER'] = 0;
				}
			} else {
				$arResult['USER']['TEACHER'] = 0;
			}

            if($user_id) {
                $bookmark = $dbh->query('SELECT * from a_bookmark WHERE type = 5 AND uz_id = ' . $arResult['USER']['ID'] . ' AND user_id = ' . $user_id)->fetch();
            }
		?>
		<div class="news-item" id="user-<?php echo $arResult['USER']['ID']; ?>">
			<div class="col-3 width-sm content-left">
				<div class="image brd rad-50">
					<img src="<?=$avatar_url?>" alt="img" style="height: 111px; width: 111px;<?php if($arResult['USER']['TEACHER']) { echo ' border: 3px solid #ff5b32;'; } ?>">
				</div>
				<br>
			</div>
			<div class="col-9 width-sm content-right" style="padding: 0;">
				<div class="btns right">
					<?if($pageAdmin):?>
					<div class="adm-btns">
						<div class="color-silver js-admins-edit" data-id="<?php echo $arResult['USER']['ID']; ?>" data-vuz-id="<?php echo $arResult['ID']; ?>" data-iblock="4" style="cursor: pointer; border-bottom: 1px dashed #9f9f9f; display: inline-block;">изменить</div>
					</div>
					<?endif?>
				</div>
				<div class="page-info">
					<h1 class="name-user">
						<span><a href="/user/<?=$arResult['USER']['ID']?>/" class="display-name<?php if(!$_SESSION['USER_DATA']) { echo ' js-noauth'; } ?>"><?=$format_name?></a></span>
						<?php if(CUser::IsOnLine($arResult['USER']['ID'], 30) && $arResult['USER']['PERSONAL_PAGER'] != 1 && $_SESSION['USER_DATA']['PERSONAL_PAGER'] != 1) { ?>
						<div style="display: inline-block; position: relative; top: -1px; margin-left: 5px; width: 10px; height: 10px; border-radius: 50%; background-color: #ff471a;" title="В сети"></div>
						<?php } ?>
					</h1>
					<div class="contact-info">
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
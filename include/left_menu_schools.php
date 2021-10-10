<?php
$structure_menu = 0;
if($_REQUEST['sect'] == 'programs' || $_REQUEST['sect'] == 'fakultets' ||
	$_REQUEST['sect'] == 'corpus' || $_REQUEST['sect'] == 'fillials' ||
	$_REQUEST['sect'] == 'units' || $_REQUEST['sect'] == 'obchegitie' ||
	$_REQUEST['sect'] == 'ring' || $_REQUEST['sect'] == 'sections' ||
	$_REQUEST['sect'] == 'start' || $_REQUEST['sect'] == 'admins' ||
	$_REQUEST['sect'] == 'vacancies') {
	$structure_menu = 1;
}
?>
<div class="left-column left">
	<div class="st-aside static">
		<div class="st-aside-menu">
			<form name="select_all" method="post">
				<div class="title">
				</div>
				<div class="st-select-search">
					<div class="select<?if(!$_REQUEST['sect']):?> st-cheked<?endif?>">
						<div class="style-input">
 							<a href="<?=$arResult["DETAIL_PAGE_URL"]?>" class="a-label">
								<div>Страница школы</div>
 							</a>
						</div>
					</div>
					 <!-- select st-cheked-->
					<div class="select">
						<div class="style-input">
 							<a href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=start" class="a-label">
								<div>
									 Структура школы
								</div>
 							</a>
							<div class="sub-menu"<?if($structure_menu):?> style="display: block;"<?endif?>>
								<?if($arResult["PROPERTIES"]["PROGRAMS"]["VALUE"] || $pageAdmin):?>
								<a<?if($_REQUEST['sect'] == 'programs'):?> class="sub-current"<?endif?> href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=programs"><span>Программы обучения</span></a>
								<?endif?>
								<?if($arResult["PROPERTIES"]["FAKULTETS"]["VALUE"] || $pageAdmin):?>
								<a<?if($_REQUEST['sect'] == 'fakultets'):?> class="sub-current"<?endif?> href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=fakultets"><span>Факультеты и институты</span></a>
								<?endif?>
								<?if($arResult["PROPERTIES"]["DOP_ADRESS"]["VALUE"] || $pageAdmin):?>
								<a<?if($_REQUEST['sect'] == 'corpus'):?> class="sub-current"<?endif?> href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=corpus"><span>Корпуса</span></a>
								<?endif?>
								<?if($arResult["PROPERTIES"]["FILLIALS_VUZ"]["VALUE"] || $pageAdmin):?>
								<a<?if($_REQUEST['sect'] == 'fillials'):?> class="sub-current"<?endif?> href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=fillials"><span>Филиалы</span></a>
								<?endif?>
								<?if($arResult["PROPERTIES"]["MORE_U"]["VALUE"] || $pageAdmin):?>
								<a<?if($_REQUEST['sect'] == 'units'):?> class="sub-current"<?endif?> href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=units"><span>Подразделения</span></a>
								<?endif?>
								<?if($arResult["PROPERTIES"]["OBG"]["VALUE"] || $pageAdmin):?>
								<a<?if($_REQUEST['sect'] == 'obchegitie'):?> class="sub-current"<?endif?> href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=obchegitie"><span>Общежитие</span></a>
								<?endif?>
								<?if($arResult["PROPERTIES"]["TIME_RING"]["VALUE"] || $pageAdmin):?>
								<a<?if($_REQUEST['sect'] == 'ring'):?> class="sub-current"<?endif?> href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=ring"><span>Расписание звонков</span></a>
								<?endif?>
								<?if($arResult["PROPERTIES"]["SECTIONS_VUZ"]["VALUE"] || $pageAdmin):?>
								<a<?if($_REQUEST['sect'] == 'sections'):?> class="sub-current"<?endif?> href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=sections"><span>Секции</span></a>
								<?endif?>
								<?if($arResult["PROPERTIES"]["ADMINS"]["VALUE"] || $pageAdmin):?>
								<a<?if($_REQUEST['sect'] == 'admins'):?> class="sub-current"<?endif?> href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=admins"><span>Администраторы</span></a>
								<?endif?>
								<?if($arResult["VACANCIES"] || $pageAdmin):?>
								<a<?if($_REQUEST['sect'] == 'vacancies'):?> class="sub-current"<?endif?> href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=vacancies"><span>Вакансии</span></a>
								<?endif?>
							</div>
						</div>
					</div>
					 <!-- select -->

					<? if($arResult["MENU_TEACHER"]) { ?>
					<div class="select<?if($_REQUEST['sect'] == 'teacher'):?> st-cheked<?endif?>">
						<div class="style-input">
 							<a href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=teacher" class="a-label">
								<div>Учителя школы</div>
 							</a>
						</div>
					</div>
					 <!-- select -->
					 <?php } ?>

					<? if($arResult["MENU_STUDENTS"]) { ?>
					<div class="select<?if($_REQUEST['sect'] == 'students'):?> st-cheked<?endif?>">
						<div class="style-input">
 							<a href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=students" class="a-label">
								<div>Ученики школы</div>
							</a>
						</div>
					</div>
					 <!-- select -->
					<?php } ?>

					<? if($arResult["PROPERTIES"]["OPENDOOR"]["VALUE"] || $pageAdmin) { ?>
					<div class="select<?if($_REQUEST['sect'] == 'opendoor'):?> st-cheked<?endif?>">
						<div class="style-input">
 							<a href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=opendoor" class="a-label">
								<div>Дни открытых дверей</div>
 							</a>
						</div>
					</div>
					 <!-- select -->
					<?php } ?>
					<? if($arResult["PROPERTIES"]["ADD_EVENTS"]["VALUE"] || $pageAdmin) { ?>
					<div class="select<?if($_REQUEST['sect'] == 'events'):?> st-cheked<?endif?>">
						<div class="style-input">
 							<a href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=events" class="a-label">
								<div>События школы</div>
 							</a>
						</div>
					</div>
					 <!-- select -->
					<?php } ?>
					<? if($arResult["NEWS"] || $pageAdmin) { ?>
					<div class="select<?if($_REQUEST['sect'] == 'news'):?> st-cheked<?endif?>">
						<div class="style-input">
 							<a href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=news" class="a-label">
								<div>Новости школы</div>
 							</a>
						</div>
					</div>
					 <!-- select -->
					<?php } ?>
					<? if($arResult["PROPERTIES"]["HISTORY_VUZ"]["VALUE"] || $pageAdmin) { ?>
					<div class="select<?if($_REQUEST['sect'] == 'history'):?> st-cheked<?endif?>">
						<div class="style-input">
 							<a href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=history" class="a-label">
								<div>История школы</div>
 							</a>
						</div>
					</div>
					 <!-- select -->
					<?php } ?>
				</div>
			</form>
		</div>
	</div>
    <?php
    require($_SERVER["DOCUMENT_ROOT"].'/include/sidebanner.php');
    ?>
</div>
 <!-- left-column -->
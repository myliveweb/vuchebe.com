<style>
.button.reverse {
    text-shadow: none;
}
.button.reverse:hover {
    box-shadow: none;
}
.button.reverse.active {
    color: #fff;
    text-decoration: none;
    background: #ff4719;
}
</style>
<?php
$zagolovok = 'Структура';
?>
<div class="page-content">
	<div class="name-block text-center txt-up"><span>Структура</span></div>
	<div class="structure clear">
		<div class="structure-cat bg-silver text-center">
			<div class="row-line">
				<?if($arResult["PROPERTIES"]["PROGRAMS"]["VALUE"] || $pageAdmin):?>
				<div class="col-5"><a href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=programs" class="button reverse<?if($_REQUEST['sect'] == 'programs'): $zagolovok = 'Программы обучения'; ?> active<?endif?>">Программы обучения</a></div>
				<?endif?>
				<?if($arResult["PROPERTIES"]["FAKULTETS"]["VALUE"] || $pageAdmin):?>
				<div class="col-6"><a href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=fakultets" class="button reverse<?if($_REQUEST['sect'] == 'fakultets'): $zagolovok = 'Факультеты и институты'; ?> active<?endif?>">Факультеты и институты</a></div>
				<?endif?>
				<?if($arResult["PROPERTIES"]["DOP_ADRESS"]["VALUE"] || $pageAdmin):?>
				<div class="col-3"><a href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=corpus" class="button reverse<?if($_REQUEST['sect'] == 'corpus'): $zagolovok = 'Корпуса'; ?> active<?endif?>">Корпуса</a></div>
				<?endif?>
				<?if($arResult["PROPERTIES"]["FILLIALS_VUZ"]["VALUE"] || $pageAdmin):?>
				<div class="col-3"><a href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=fillials" class="button reverse<?if($_REQUEST['sect'] == 'fillials'): $zagolovok = 'Филиалы'; ?> active<?endif?>">Филиалы</a></div>
				<?endif?>
				<?if($arResult["PROPERTIES"]["MORE_U"]["VALUE"] || $pageAdmin):?>
				<div class="col-4"><a href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=units" class="button reverse<?if($_REQUEST['sect'] == 'units'): $zagolovok = 'Подразделения'; ?> active<?endif?>">Подразделения</a></div>
				<?endif?>
				<?if($arResult["PROPERTIES"]["OBG"]["VALUE"] || $pageAdmin):?>
				<div class="col-4"><a href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=obchegitie" class="button reverse<?if($_REQUEST['sect'] == 'obchegitie'): $zagolovok = 'Общежитие'; ?> active<?endif?>">Общежитие</a></div>
				<?endif?>
				<?if($arResult["PROPERTIES"]["TIME_RING"]["VALUE"] || $pageAdmin):?>
				<div class="col-5"><a href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=ring" class="button reverse<?if($_REQUEST['sect'] == 'ring'): $zagolovok = 'Расписание звонков'; ?> active<?endif?>">Расписание звонков</a></div>
				<?endif?>
				<?if($arResult["PROPERTIES"]["SECTIONS_VUZ"]["VALUE"] || $pageAdmin):?>
				<div class="col-3"><a href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=sections" class="button reverse<?if($_REQUEST['sect'] == 'sections'): $zagolovok = 'Секции'; ?> active<?endif?>">Секции</a></div>
				<?endif?>
				<?if($arResult["PROPERTIES"]["ADMINS"]["VALUE"] || $pageAdmin):?>
				<div class="col-4"><a href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=admins" class="button reverse<?if($_REQUEST['sect'] == 'admins'): $zagolovok = 'Администраторы'; ?> active<?endif?>">Администраторы</a></div>
				<?endif?>
				<?if($arResult["VACANCIES"] || $pageAdmin):?>
				<div class="col-3"><a href="<?=$arResult["DETAIL_PAGE_URL"]?>?sect=vacancies" class="button reverse<?if($_REQUEST['sect'] == 'vacancies'): $zagolovok = 'Вакансии'; ?> active<?endif?>">Вакансии</a></div>
				<?endif?>
			</div>
		</div>

		<h3 class="structure-name"><?=$zagolovok ?></h3>
		<?
		if($_REQUEST['sect'] == 'programs'):
			require($_SERVER["DOCUMENT_ROOT"].'/uchebnye-zavedeniya/schools/programs.php');
		endif;
		if($_REQUEST['sect'] == 'corpus'):
			require($_SERVER["DOCUMENT_ROOT"].'/uchebnye-zavedeniya/schools/corpus.php');
		endif;
		if($_REQUEST['sect'] == 'fakultets'):
			require($_SERVER["DOCUMENT_ROOT"].'/uchebnye-zavedeniya/schools/fakultets.php');
		endif;
		if($_REQUEST['sect'] == 'fillials'):
			require($_SERVER["DOCUMENT_ROOT"].'/uchebnye-zavedeniya/schools/fillials.php');
		endif;
		if($_REQUEST['sect'] == 'units'):
			require($_SERVER["DOCUMENT_ROOT"].'/uchebnye-zavedeniya/schools/units.php');
		endif;
		if($_REQUEST['sect'] == 'obchegitie'):
			require($_SERVER["DOCUMENT_ROOT"].'/uchebnye-zavedeniya/schools/obchegitie.php');
		endif;
		if($_REQUEST['sect'] == 'ring'):
			require($_SERVER["DOCUMENT_ROOT"].'/uchebnye-zavedeniya/schools/ring.php');
		endif;
		if($_REQUEST['sect'] == 'sections'):
			require($_SERVER["DOCUMENT_ROOT"].'/uchebnye-zavedeniya/schools/sections.php');
		endif;
		if($_REQUEST['sect'] == 'admins'):
			require($_SERVER["DOCUMENT_ROOT"].'/uchebnye-zavedeniya/schools/admins.php');
		endif;
		if($_REQUEST['sect'] == 'vacancies'):
			require($_SERVER["DOCUMENT_ROOT"].'/uchebnye-zavedeniya/schools/vacancies.php');
		endif;
		?>
	</div><!-- structure -->
</div>
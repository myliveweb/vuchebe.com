<?php
global $dbh;

$user_id = 0;
if($_SESSION['USER_DATA'])
	$user_id = $_SESSION['USER_DATA']['ID'];

$arResult["BOOKMARK"] = array();
if($user_id) {
	$arrBaookmark = $dbh->query('SELECT * from a_bookmark WHERE user_id = ' . $user_id . ' ORDER BY date_create DESC')->fetchAll();
}

function cmp($a, $b) {
    if ($a['sort'] == $b['sort']) {
        return 0;
    }
    return ($a['sort'] > $b['sort']) ? -1 : 1;
}

$uzArr = $dbh->query('SELECT * from a_user_uz WHERE user_id = ' . $_SESSION['USER_DATA']['ID'] . ' ORDER BY id DESC')->fetchAll();

$arrFilter = array();
$uzArraySort = array();
foreach($uzArr as $uzf) {
	if($uzf['end_p'])
		$uzf['sort'] = $uzf['end_p'];
	elseif($uzf['start_p'])
		$uzf['sort'] = $uzf['start_p'];
	else
		$uzf['sort'] = 0;
	$uzArraySort[] = $uzf;

	if($uzf['type'] == 1 && !$uzf['teacher'])
		$arrFilter['vuz'] = 1;
	elseif($uzf['type'] == 2 && !$uzf['teacher'])
		$arrFilter['suz'] = 1;
	elseif($uzf['type'] == 3 && !$uzf['teacher'])
		$arrFilter['nuz'] = 1;

	if($uzf['teacher'])
		$arrFilter['teacher'] = 1;
}

usort($uzArraySort, "cmp");
?>
<style>
#box-line .js-bookmark {
	padding: 0;
	width: 100%;
}
.js-bookmark.active {
	color: #ff471a;
	background: #fff;
	border: 1px solid #ff471a;
}
.js-bookmark.active span {
	font-family: Verdana;
	text-decoration: none;
    color: #ff471a;
}
.js-bookmark.active span::before {
    border: 1px solid #ff471a;
}
.m-header .filter {
	color: #ff471a;
}
.m-header .filter.color-silver {
	color: #9f9f9f;
	text-decoration: none;
	cursor: default;
}
</style>

<?php
$url = getUserUrl($_SESSION['USER_DATA']);
?>

<div class="st-content-right">
	<div class="breadcrumbs">
		<a href="/">Главная</a> <i class="fa fa-angle-double-right color-orange"></i> <a href="/user/<?php echo $url; ?>/">Профиль</a> <i class="fa fa-angle-double-right color-orange"></i> <span>Мои учебные заведения</span>
	</div><br>
	<div class="page-content">
		<div class="name-block text-center txt-up"><span>Образование</span></div>
		<div class="st-content-bottom clear">
			<div class="module st-news">
				<div class="m-header" style="padding-bottom: 10px;">
					<a href="#" data-filter="all" class="filter color-silver js-educations-list">Все</a> &nbsp;
					<?if($arrFilter['nuz']):?>
					<a href="#" data-filter="nuz" class="filter js-educations-list">Начальное</a> &nbsp;
					<?endif?>
					<?if($arrFilter['suz']):?>
					<a href="#" data-filter="suz" class="filter js-educations-list">Среднее</a> &nbsp;
					<?endif?>
					<?if($arrFilter['vuz']):?>
					<a href="#" data-filter="vuz" class="filter js-educations-list">Высшее</a> &nbsp;
					<?endif?>
					<?if($arrFilter['teacher']):?>
					<a href="#" data-filter="teacher" class="filter js-educations-list">Карьера</a> &nbsp;
					<?endif?>
				</div>
				<div style="text-align: center;">
					<span class="color-silver js-uz-add" style="cursor: pointer; border-bottom: 1px dashed #9f9f9f; position: relative; top: -14px;">Добавить учебное заведение</span>
					<span class="color-silver js-uz-add teacher" style="margin-left: 15px; cursor: pointer; border-bottom: 1px dashed #9f9f9f; position: relative; top: -14px;<?php if(!$_SESSION['USER_DATA']['WORK_WWW']) { echo ' display: none;'; } ?>">Я преподавал(а)</span>
				</div>
				<?php
				if(sizeof($uzArr) > 0) {
					CModule::IncludeModule('iblock');
				?>
				<div class="line" id="box-line">
					<?php
					foreach($uzArraySort as $uz) {
						$name = '';
						$img = '';
						$url = '';
						$startEnd = '';
						$fack = '';
						$dataBlock = '';
						$filterType = '';
						if($uz['type'] == 1) {
							$arSelect = array("ID", "NAME", "IBLOCK_ID", "PREVIEW_PICTURE", "DETAIL_PAGE_URL", "PROPERTY_LOGO");
							$arFilter = array("IBLOCK_ID" => 2, "ACTIVE" => "Y", "ID" => $uz['uz_id']);
							$res = CIBlockElement::GetList(array("NAME" => "ASC"), $arFilter, false, false, $arSelect);
							if($row = $res->GetNext())
							{
								$name = $row['NAME'];

								if($row['PROPERTY_LOGO_VALUE']) {
									$img = CFile::GetPath($row['PROPERTY_LOGO_VALUE']);
								} elseif($row['PREVIEW_PICTURE']) {
									$img = CFile::GetPath($row['PREVIEW_PICTURE']);
								} else {
                                    $img = '/local/templates/vuchebe/images/noimage-2.png';
                                }

								$url = $row['DETAIL_PAGE_URL'];
							}

							if($uz['start_p']) {
								$startEnd .= $uz['start_p'];
							}
							if($uz['start_p'] && $uz['end_p']) {
								$startEnd .= ' - ';
							}
							if($uz['end_p']) {
								$startEnd .= $uz['end_p'];
							}
							if($uz['grupe']) {
								$startEnd .= ' ' . $uz['grupe'];
							}

							if($uz['fack'] >= 0) {
								$res = CIBlockElement::GetProperty(2, $uz['uz_id'], array("sort" => "asc"), array("CODE"=>"FAKULTETS"));
								while($ob = $res->GetNext()) {
									$arrFackEx = explode('#', $ob['VALUE']);
									$f[] = $arrFackEx[0];
								}
								$fack = $f[$uz['fack']];
							}
							$dataBlock = 'vuz-block';

							if($uz['teacher'])
								$filterType = 'teacher';
							else
								$filterType = 'vuz';

						} elseif($uz['type'] == 2) {
							$arSelect = array("ID", "NAME", "IBLOCK_ID", "PREVIEW_PICTURE", "DETAIL_PAGE_URL", "PROPERTY_LOGO");
							$arFilter = array("IBLOCK_ID" => 3, "ACTIVE" => "Y", "ID" => $uz['uz_id']);
							$res = CIBlockElement::GetList(array("NAME" => "ASC"), $arFilter, false, false, $arSelect);
							if($row = $res->GetNext())
							{
								$name = $row['NAME'];

								if($row['PROPERTY_LOGO_VALUE']) {
									$img = CFile::GetPath($row['PROPERTY_LOGO_VALUE']);
								} elseif($row['PREVIEW_PICTURE']) {
									$img = CFile::GetPath($row['PREVIEW_PICTURE']);
								} else {
                                    $img = '/local/templates/vuchebe/images/noimage-2.png';
                                }

								$url = $row['DETAIL_PAGE_URL'];
							}

							if($uz['start_p']) {
								$startEnd .= $uz['start_p'];
							}
							if($uz['start_p'] && $uz['end_p']) {
								$startEnd .= ' - ';
							}
							if($uz['end_p']) {
								$startEnd .= $uz['end_p'];
							}
							if($uz['grupe']) {
								$startEnd .= ' ' . $uz['grupe'];
							}
							$dataBlock = 'coll-block';

							if($uz['teacher'])
								$filterType = 'teacher';
							else
								$filterType = 'suz';

						} elseif($uz['type'] == 3) {
							$arSelect = array("ID", "NAME", "IBLOCK_ID", "DETAIL_PAGE_URL", "PROPERTY_LOGO");
							$arFilter = array("IBLOCK_ID" => 4, "ACTIVE" => "Y", "ID" => $uz['uz_id']);
							$res = CIBlockElement::GetList(array("NAME" => "ASC"), $arFilter, false, false, $arSelect);
							if($row = $res->GetNext())
							{
								$name = $row['NAME'];

								if($row['PROPERTY_LOGO_VALUE']) {
									$img = CFile::GetPath($row['PROPERTY_LOGO_VALUE']);
								} else {
                                    $img = '/local/templates/vuchebe/images/noimage-2.png';
                                }

								$url = $row['DETAIL_PAGE_URL'];
							}

							if($uz['start_p']) {
								$startEnd .= $uz['start_p'];
							}
							if($uz['start_p'] && $uz['end_p']) {
								$startEnd .= ' - ';
							}
							if($uz['end_p']) {
								$startEnd .= $uz['end_p'];
							}
							if($uz['grupe']) {
								$startEnd .= ' ' . $uz['grupe'];
							}
							$dataBlock = 'shool-block';

							if($uz['teacher'])
								$filterType = 'teacher';
							else
								$filterType = 'nuz';

						} elseif($uz['type'] == 4) {
							$arSelect = array("ID", "NAME", "IBLOCK_ID", "DETAIL_PICTURE", "DETAIL_PAGE_URL", "PROPERTY_LOGO");
							$arFilter = array("IBLOCK_ID" => 6, "ACTIVE" => "Y", "ID" => $uz['uz_id']);
							$res = CIBlockElement::GetList(array("NAME" => "ASC"), $arFilter, false, false, $arSelect);
							if($row = $res->GetNext())
							{
								$name = $row['NAME'];

								if($row['DETAIL_PICTURE']) {
									$img = CFile::GetPath($row['DETAIL_PICTURE']);
								} else {
                                    $img = '/local/templates/vuchebe/images/noimage-2.png';
                                }

								$url = $row['DETAIL_PAGE_URL'];
							}

							if($uz['start_p']) {
								$startEnd .= $uz['start_p'];
							}
							if($uz['start_p'] && $uz['end_p']) {
								$startEnd .= ' - ';
							}
							if($uz['end_p']) {
								$startEnd .= $uz['end_p'];
							}
							if($uz['grupe']) {
								$startEnd .= ' ' . $uz['grupe'];
							}
							$dataBlock = 'lang-block';
						}
					?>
					<div class="news-item <?php echo $filterType; ?>">
						<div style="position: relative; top: -10px; right: 5px; text-align: right;">
							<div class="color-silver js-uz-edit" data-block="<?php echo $dataBlock; ?>" data-id="<?php echo $uz['id']; ?>" style="cursor: pointer; border-bottom: 1px dashed #9f9f9f; display: inline-block;">изменить</div>
						</div>
						<?php
						if($uz['start_p'] || $uz['end_p']) {
							if($uz['teacher']) {
								if($uz['end_p']) {
								?>
								<div class="date-ico theme-1 right" style="background: url(/local/templates/vuchebe/images/stick-year-3.png) no-repeat;">
									преподавал<br>до
									<span><?php echo $uz['end_p']; ?></span>
								</div>
								<?php
								} elseif($uz['start_p']) {
								?>
								<div class="date-ico theme-1 right" style="background: url(/local/templates/vuchebe/images/stick-year-3.png) no-repeat;">
									преподаёт<br>с
									<span><?php echo $uz['start_p']; ?></span>
								</div>
								<?php
								}
							} else {
								if($uz['end_p']) {
								?>
								<div class="date-ico theme-1 right">
									год<br>выпуска
									<span><?php echo $uz['end_p']; ?></span>
								</div>
								<?php
								} elseif($uz['start_p']) {
								?>
								<div class="date-ico theme-1 right">
									год<br>поступления
									<span><?php echo $uz['start_p']; ?></span>
								</div>
								<?php
								}
							}
						}
						if($img) {
						?>
						<div class="image left brd"><img style="width: 144px;" src="<?php echo $img; ?>" alt="<?php echo $name; ?>" title="<?php echo $name; ?>"></div>
						<?php
						}
						?>
						<div class="news-name"><a href="<?php echo $url; ?>"><span><?php echo $name; ?></span></a></div>
						<div style="overflow: hidden;">
						<?php
						if($startEnd) {
						?>
						<p style="margin-bottom: 3px;"><?php echo $startEnd; ?></p>
						<?php
						}
						if($fack) {
						?>
						<p style="margin: 0px 0px 4px 0px;">Факультет: <?php echo $fack; ?></p>
						<?php
						}
						if($uz['forma']) {
						?>
						<p style="margin: 0px 0px 4px 0px;">Форма обучения: <?php echo $uz['forma']; ?></p>
						<?php
						}
						if($uz['status']) {
						?>
						<p style="margin: 0px 0px 4px 0px;">Статус: <?php echo $uz['status']; ?></p>
						<?php
						}
						if($uz['spec']) {
						?>
						<p style="margin: 0px 0px 4px 0px;">Специализация: <?php echo $uz['spec']; ?></p>
						<?php
						}
						?>
						</div>
					</div>
					<?php
					}
					?>
				</div>
				<?php
				}
				?>
			</div><!-- st-news -->

		</div><!-- st-content-bottom -->
	</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
<style>
[type="file"] {
	border: 0;
	clip: rect(0, 0, 0, 0);
	height: 1px;
	overflow: hidden;
	padding: 0;
	position: absolute !important;
	white-space: nowrap;
	width: 1px;
}

[type="file"] + label {
	border: none;
	color: #fff;
	cursor: pointer;
	display: inline-block;
	font-family: 'Poppins', sans-serif;
	font-size: 1.2rem;
	font-weight: 600;
	margin-bottom: 1rem;
	outline: none;
	padding: 1rem 0rem;
	position: relative;
	transition: all 0.3s;
	vertical-align: middle;
	border-radius: 50px;
	overflow: hidden;
	width: 100%;
    max-width: 738px;
}

[type="file"]:focus + label,
[type="file"] + label:hover {
    outline: none;
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

<script>
var fname = '';
var lname = '';
var sname = '';

var bday = '';
var day = 0;
var month = 0;
var year = 0;

var icq = '';
var r_city = '';
var phone = '';
var email = '';

<?php
$crop = 1;
if($_SESSION['USER_DATA']['NAME']) { ?>
fname = '<?php echo $_SESSION['USER_DATA']['NAME']; ?>';
<?php } ?>
<?php if($_SESSION['USER_DATA']['LAST_NAME']) { ?>
lname = '<?php echo $_SESSION['USER_DATA']['LAST_NAME']; ?>';
<?php } ?>
<?php if($_SESSION['USER_DATA']['SECOND_NAME']) { ?>
sname = '<?php echo $_SESSION['USER_DATA']['SECOND_NAME']; ?>';
<?php } ?>

<?php if($_SESSION['USER_DATA']['PERSONAL_BIRTHDAY']) { ?>
bday = '<?php echo $_SESSION['USER_DATA']['PERSONAL_BIRTHDAY']; ?>';
<?php
list($day, $month, $year) = explode('.', $_SESSION['USER_DATA']['PERSONAL_BIRTHDAY']);
?>
day = <?php echo (int) $day; ?>;
month = <?php echo (int) $month; ?>;
year = <?php echo (int) $year; ?>;
<?php } ?>
<?php if($_SESSION['USER_DATA']['PERSONAL_ICQ']) { ?>
icq = '<?php echo $_SESSION['USER_DATA']['PERSONAL_ICQ']; ?>';
<?php } ?>
<?php if($_SESSION['USER_DATA']['WORK_CITY']) { ?>
r_city = '<?php echo $_SESSION['USER_DATA']['WORK_CITY']; ?>';
<?php } ?>
<?php if($_SESSION['USER_DATA']['PERSONAL_PHONE']) { ?>
phone = '<?php echo $_SESSION['USER_DATA']['PERSONAL_PHONE']; ?>';
<?php } ?>
<?php if($_SESSION['USER_DATA']['EMAIL']) { ?>
email = '<?php echo $_SESSION['USER_DATA']['EMAIL']; ?>';
<?php } ?>
</script>
<?php
$month_1 = array('', 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
if($_SESSION['USER_DATA']['PERSONAL_BIRTHDAY']) {
	list($dayShow, $monthShow, $yearShow) = explode('.', $_SESSION['USER_DATA']['PERSONAL_BIRTHDAY']);
	$showBd = (int) $dayShow . ' ' . $month_1[(int) $monthShow] . ' ' . (int) $yearShow . ' г.';
}

function cmp($a, $b) {
    if ($a['sort'] == $b['sort']) {
        return 0;
    }
    return ($a['sort'] > $b['sort']) ? -1 : 1;
}

$hide = $dbh->query('SELECT * from a_user_hide WHERE user_id = ' . $_SESSION['USER_DATA']['ID'] . ' ORDER BY id DESC')->fetch();

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
<div class="st-content-right">

	<div class="page-content">

		<div class="page-item clearfix">

				<div class="col-3 content-left">
					<div class="image brd rad-50">
						<?
						if($_SESSION['USER_DATA']['PERSONAL_PHOTO']) {
							$avatar_url = CFile::GetPath($_SESSION['USER_DATA']['PERSONAL_PHOTO']);
						} else {
							$avatar_url = SITE_TEMPLATE_PATH . "/images/user-1.png";
						}
						?>
						<input type="file" id="avatar" data-type="avatar" accept="image/*">
						<label for="avatar">
							<img id="main-profile" class="profile-avatar" style="cursor: pointer; height: 122px; width: 122px;<?php if($_SESSION['USER_DATA']['TEACHER']) { echo ' border: 3px solid #ff5b32;'; } ?>" src="<?=$avatar_url?>" alt="img">
						</label>
					</div>
				</div>

				<?
					if (strlen($_SESSION['USER_DATA']['NAME']) && strlen($_SESSION['USER_DATA']['LAST_NAME'])) {
						$format_name = '<span>' . strtoupper(mb_substr($_SESSION['USER_DATA']['NAME'], 0, 1)) . '</span>' . mb_substr($_SESSION['USER_DATA']['NAME'], 1);
						if($_SESSION['USER_DATA']['SECOND_NAME']) {
							$format_name .= ' ';
							$format_name .= '<span>' . strtoupper(mb_substr($_SESSION['USER_DATA']['SECOND_NAME'], 0, 1)) . '</span>' . mb_substr($_SESSION['USER_DATA']['SECOND_NAME'], 1);
						}
						$format_name .= ' ';
						$format_name .= '<span>' . strtoupper(mb_substr($_SESSION['USER_DATA']['LAST_NAME'], 0, 1)) . '</span>' . mb_substr($_SESSION['USER_DATA']['LAST_NAME'], 1);
					} else {
						$format_name = '<span>' . strtoupper(mb_substr(trim($USER->GetLogin()), 0, 1)) . '</span>' . mb_substr(trim($USER->GetLogin()), 1);
					}
				?>

				<div class="col-9 content-right" style="padding: 0 0 0 15px;">
					<div class="page-info" style="padding: 0 80px 0 0;">
						<div class="color-silver js-profile-edit" style="position: absolute; top: 0px; right: 0px; cursor: pointer; border-bottom: 1px dashed #9f9f9f;">изменить</div>
						<h1 class="name-user">
							<span class="js-name-user" data-fname="<? echo trim($USER->GetFirstName()); ?>" data-lname="<? echo trim($USER->GetLastName()); ?>"><?=$format_name?></span>
							<?php if($_SESSION['USER_DATA']['PERSONAL_PAGER'] != 1) { ?>
							<div style="display: inline-block; position: relative; top: -1px; margin-left: 5px; width: 10px; height: 10px; border-radius: 50%; background-color: #ff471a;" title="В сети"></div>
							<?php } ?>
						</h1>
						<?php if($_SESSION['USER_DATA']['PERSONAL_ICQ']) { ?>
						<small class="color-silver">Статус: <span class="js-icq" data-hide="<?if($hide['status']) { echo '1'; } else { echo '0'; }?>" style="display: inline-block; margin-bottom: 0px; position: relative;"><?if($_SESSION['USER_DATA']['PERSONAL_ICQ']) { echo $_SESSION['USER_DATA']['PERSONAL_ICQ']; } else { echo 'не установлен'; }?></span></small>
						<?php } ?>
						<div class="contact-info">
							<?php if($_SESSION['USER_DATA']['PERSONAL_CITY']) { ?>
								<?php if($_SESSION['USER_DATA']['UF_CITY']) { ?>
									<span>Город: <a href="/users/"><span class="js-city" style="display: inline-block; margin-bottom: 0px; text-decoration: underline; text-transform: capitalize;"><?if($_SESSION['USER_DATA']['PERSONAL_CITY']) { echo ucfirst($_SESSION['USER_DATA']['PERSONAL_CITY']); } else { echo 'не установлен'; }?></span></a></span>
								<?php } else { ?>
								<span>Город: <span class="js-city" style="display: inline-block; margin-bottom: 0px; text-transform: capitalize;"><?if($_SESSION['USER_DATA']['PERSONAL_CITY']) { echo ucfirst($_SESSION['USER_DATA']['PERSONAL_CITY']); } else { echo 'не установлен'; }?></span></span>
								<?php } ?>
							<?php } ?>
							<?php if($_SESSION['USER_DATA']['WORK_CITY']) { ?>
							<span>Родной город: <span class="js-r-city" data-hide="<?if($hide['r_city']) { echo '1'; } else { echo '0'; }?>" style="display: inline-block; margin-bottom: 0px;"><?if($_SESSION['USER_DATA']['WORK_CITY']) { echo $_SESSION['USER_DATA']['WORK_CITY']; } else { echo 'не установлен'; }?></span></span>
							<?php } ?>
							<?php if($_SESSION['USER_DATA']['PERSONAL_BIRTHDAY']) { ?>
							<span class="js-birthday-show">Дата рождения: <span class="js-birthday" data-hide="<?if($hide['day']) { echo '1'; } else { echo '0'; }?>" style="display: inline-block; margin-bottom: 0px;"><?if($_SESSION['USER_DATA']['PERSONAL_BIRTHDAY']) { echo $showBd; } else { echo 'не установлен'; }?></span></span>
							<?php } ?>
                            <?php if($_SESSION['USER_DATA']['PERSONAL_GENDER']) { ?>
                                <span>Пол: <span class="js-pol" data-val="<?php echo $_SESSION['USER_DATA']['PERSONAL_GENDER']; ?>" data-hide="<?if($hide['pol']) { echo '1'; } else { echo '0'; }?>" style="display: inline-block; margin-bottom: 0px;"><?if($_SESSION['USER_DATA']['PERSONAL_GENDER'] == 'M') { echo 'Мужской'; } elseif($_SESSION['USER_DATA']['PERSONAL_GENDER'] == 'F') { echo 'Женский'; } else { echo 'Неустановлен'; }?></span></span>
                            <?php } ?>
							<?php if($_SESSION['USER_DATA']['PERSONAL_PHONE']) { ?>
							<span>Телефон: <span class="js-phone" data-hide="<?if($hide['phone']) { echo '1'; } else { echo '0'; }?>" style="display: inline-block; margin-bottom: 0px;"><?if($_SESSION['USER_DATA']['PERSONAL_PHONE']) { echo $_SESSION['USER_DATA']['PERSONAL_PHONE']; } else { echo 'не установлен'; }?></span></span>
							<?php } ?>
							<?php if($_SESSION['USER_DATA']['EMAIL']) { ?>
							<span class="js-email-parent" data-hide="<?if($hide['email']) { echo '1'; } else { echo '0'; }?>">Email: <?if($_SESSION['USER_DATA']['EMAIL']) { echo '<a class="js-email" href="mailto:' . $_SESSION['USER_DATA']['EMAIL'] . '" target="_blank">' . $_SESSION['USER_DATA']['EMAIL'] . '</a>'; } else { echo '<span class="js-email" style="display: inline-block; margin-bottom: 0px;">не установлен</span>'; }?></span>
							<?php } ?>
							<?php if($_SESSION['USER_DATA']['UF_VK'] ||
									 $_SESSION['USER_DATA']['UF_FB'] ||
									 $_SESSION['USER_DATA']['UF_OK'] ||
									 $_SESSION['USER_DATA']['UF_TW'] ||
									 $_SESSION['USER_DATA']['UF_INST'] ||
									 $_SESSION['USER_DATA']['UF_YOU'] ||
									 $_SESSION['USER_DATA']['UF_LJ']) { ?>
							<span class="links js-links" data-hide="<?if($hide['soc']) { echo '1'; } else { echo '0'; }?>">Соц. сети:
								<?php if($_SESSION['USER_DATA']['UF_VK']) { ?>
								<a class="js-vk" href="<?php echo $_SESSION['USER_DATA']['UF_VK']; ?>"><i class="ico vk"></i></a>
								<?php } ?>
								<?php if($_SESSION['USER_DATA']['UF_FB']) { ?>
								<a class="js-fb" href="<?php echo $_SESSION['USER_DATA']['UF_FB']; ?>"><i class="ico fc"></i></a>
								<?php } ?>
								<?php if($_SESSION['USER_DATA']['UF_OK']) { ?>
								<a class="js-ok" href="<?php echo $_SESSION['USER_DATA']['UF_OK']; ?>"><i class="ico ok"></i></a>
								<?php } ?>
								<?php if($_SESSION['USER_DATA']['UF_TW']) { ?>
								<a class="js-tw" href="<?php echo $_SESSION['USER_DATA']['UF_TW']; ?>"><i class="ico tw"></i></a>
								<?php } ?>
								<?php if($_SESSION['USER_DATA']['UF_INST']) { ?>
								<a class="js-inst" href="<?php echo $_SESSION['USER_DATA']['UF_INST']; ?>"><i class="ico inst"></i></a>
								<?php } ?>
								<?php if($_SESSION['USER_DATA']['UF_YOU']) { ?>
								<a class="js-you" href="<?php echo $_SESSION['USER_DATA']['UF_YOU']; ?>"><i class="ico you"></i></a>
								<?php } ?>
								<?php if($_SESSION['USER_DATA']['UF_LJ']) { ?>
								<a class="js-lj" href="<?php echo $_SESSION['USER_DATA']['UF_LJ']; ?>"><i class="ico live"></i></a>
								<?php } ?>
							</span>
							<?php } ?>
						</div><!-- contact-info -->
					</div>
				</div><!-- content-right -->

				<?php if($_SESSION['USER_DATA']['PERSONAL_NOTES']) { ?>
				<div style="padding-top: 20px; display: block; clear: both;">
					<strong>О себе:</strong>
					<div class="js-notes" data-hide="<?if($hide['note']) { echo '1'; } else { echo '0'; }?>" style="margin: 10px 0 15px 0;">
						<?if($_SESSION['USER_DATA']['PERSONAL_NOTES']) { echo $_SESSION['USER_DATA']['PERSONAL_NOTES']; } else { echo 'не установлено'; }?>
					</div>
				</div>
				<?php } ?>

				<div class="st-content-bottom clear">

					<div class="module st-news">
						<div class="name-block"> &nbsp;&nbsp;&nbsp;<span>Образование</span></div>
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
									<div class="color-silver js-uz-edit<?php if($uz['teacher']) { echo ' teacher'; } ?>" data-block="<?php echo $dataBlock; ?>" data-id="<?php echo $uz['id']; ?>" style="cursor: pointer; border-bottom: 1px dashed #9f9f9f; display: inline-block;">изменить</div>
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
								<div class="image left brd"><img style="width: 111px; width: 111px;" src="<?php echo $img; ?>" alt="<?php echo $name; ?>" title="<?php echo $name; ?>"></div>
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

		</div><!-- page-item -->

	</div><!-- page-content -->

</div><!-- st-content-right -->
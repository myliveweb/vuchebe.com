<?
$rsUserData = CUser::GetByID($user_id);
$userData = $rsUserData->Fetch();
if(!$userData) {
    LocalRedirect('/users/');
}

$user_id = 0;

if($_SESSION['USER_DATA']) {
	$user_id = $_SESSION['USER_DATA']['ID'];
}

$bookmark = array();
if($user_id) {
	$bookmark = $dbh->query('SELECT * from a_bookmark WHERE type = 5 AND uz_id = ' . $userData['ID'] . ' AND user_id = ' . $user_id)->fetch();
}

function cmp($a, $b) {
    if ($a['sort'] == $b['sort']) {
        return 0;
    }
    return ($a['sort'] > $b['sort']) ? -1 : 1;
}

$uzArr = $dbh->query('SELECT * from a_user_uz WHERE user_id = ' . $userData['ID'] . ' ORDER BY id DESC')->fetchAll();

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

$arrBlock = array();
if($user_id) {
	$arrBlock = $dbh->query('SELECT id from a_block_user WHERE id_user = ' . $userData['ID'] . ' AND block_user = ' . $user_id)->fetch();
}

$hide = $dbh->query('SELECT * from a_user_hide WHERE user_id = ' . $userData['ID'] . ' ORDER BY id DESC')->fetch();

?>
<style>
#box-line .js-bookmark {
    padding: 0;
    width: 100%;
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

.m-header .filter {
	color: #ff471a;
}
.m-header .filter.color-silver {
	color: #9f9f9f;
	text-decoration: none;
	cursor: default;
}
</style>
<div class="st-content-right">

	<div class="page-content" id="page-content">

		<div class="page-item clearfix">

				<div class="col-3 content-left">
					<div class="image brd rad-50">
						<?
						if($userData['PERSONAL_PHOTO']) {
							$avatar_url = CFile::GetPath($userData['PERSONAL_PHOTO']);
							$avatarPopUp = 1;
						} else {
							$avatar_url = SITE_TEMPLATE_PATH . "/images/user-1.png";
							$avatarPopUp = 0;
						}

						if($userData['WORK_WWW']) {
							$arrTeacher = $dbh->query('SELECT COUNT(id) as cnt from a_user_uz WHERE teacher = 1 AND user_id = ' . $userData['ID'])->fetch();
							if($arrTeacher['cnt'] > 0) {
								$userData['TEACHER'] = 1;
							} else {
								$userData['TEACHER'] = 0;
							}
						} else {
							$userData['TEACHER'] = 0;
						}

						?>
						<img data-user="<? echo $userData['ID']; ?>" data-fname="<? echo trim($userData['NAME']); ?>" data-sname="<? echo trim($userData['SECOND_NAME']); ?>" data-lname="<? echo trim($userData['LAST_NAME']); ?>" class="profile-avatar<?php if($avatarPopUp) { echo ' js-avatar'; } ?>" style="height: 122px; width: 122px;<?php if($avatarPopUp) { echo ' cursor: pointer;'; } ?><?php if($userData['TEACHER']) { echo ' border: 3px solid #ff5b32;'; } ?>" src="<?=$avatar_url?>">
					</div>
					<div class="btns text-center" style="margin-top: 15px;">
            <?php
              if($_SESSION['USER_DATA']['PRO'] === 'Y') {
                $link = '';
              } else {
                $link = 'href="/user/chat/'. $userData['ID'] . '/"';
              }
            ?>
						<a <?php echo $link; ?> class="button" style="padding: 0; width: 100%;">
						<span>сообщение</span>
						</a>
					</div>
					<div class="btns" style="margin-top: 10px;">
						<?php if($bookmark) { ?>
						<a href="#" class="button js-bookmark active" data-state="1" data-type="5" data-id="<?php echo $userData['ID']; ?>">
							<span>закладки</span>
						</a>
						<?php } else { ?>
						<a href="#" class="button js-bookmark" data-state="0" data-type="5" data-id="<?php echo $userData['ID']; ?>">
							<span>закладки</span>
						</a>
						<?php } ?>
					</div>
				</div>
				<?
					if (strlen(trim($userData['NAME'])) && strlen(trim($userData['LAST_NAME']))) {
						$format_name = '<span>' . strtoupper(mb_substr(trim($userData['NAME']), 0, 1)) . '</span>' . mb_substr(trim($userData['NAME']), 1);
						if($userData['SECOND_NAME']) {
							$format_name .= ' ';
							$format_name .= '<span>' . strtoupper(mb_substr($userData['SECOND_NAME'], 0, 1)) . '</span>' . mb_substr($userData['SECOND_NAME'], 1);
						}
						$format_name .= ' ';
						$format_name .= '<span>' . strtoupper(mb_substr(trim($userData['LAST_NAME']), 0, 1)) . '</span>' . mb_substr(trim($userData['LAST_NAME']), 1);
					} else {
						$format_name = '<span>' . strtoupper(mb_substr(trim($userData['LOGIN']), 0, 1)) . '</span>' . mb_substr(trim($userData['LOGIN']), 1);
					}

					$month_1 = array('', 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
					if($userData['PERSONAL_BIRTHDAY']) {
						list($dayShow, $monthShow, $yearShow) = explode('.', $userData['PERSONAL_BIRTHDAY']);
						$showBd = (int) $dayShow . ' ' . $month_1[(int) $monthShow] . ' ' . (int) $yearShow . ' г.';
					}

				?>

				<div class="col-9 content-right">
					<div class="page-info">
						<h1 class="name-user">
							<span class="js-name-user" data-fname="<? echo trim($userData['NAME']); ?>" data-lname="<? echo trim($userData['LAST_NAME']); ?>"><?=$format_name?></span>
							<?php if(CUser::IsOnLine($userData['ID'], 30) && $userData['PERSONAL_PAGER'] != 1 && $_SESSION['USER_DATA']['PERSONAL_PAGER'] != 1) { ?>
							<div style="display: inline-block; position: relative; top: -1px; margin-left: 5px; width: 10px; height: 10px; border-radius: 50%; background-color: #ff471a;" title="В сети"></div>
							<?php } ?>
						</h1>

						<?php if($userData['PERSONAL_ICQ'] && !$arrBlock['id'] && !$hide['status']) { ?>
						<small class="color-silver">Статус: <span class="js-icq" style="display: inline-block; margin-bottom: 0px;"><?if($userData['PERSONAL_ICQ']) { echo $userData['PERSONAL_ICQ']; } else { echo 'не установлен'; }?></span></small>
						<?php } ?>

						<div class="contact-info">
							<?php if($userData['PERSONAL_CITY']) { ?>
								<?php if($userData['UF_CITY']) { ?>
									<span>Город: <a href="/users/"><span class="js-city" style="display: inline-block; margin-bottom: 0px; text-decoration: underline; text-transform: capitalize;"><?if($userData['PERSONAL_CITY']) { echo ucfirst($userData['PERSONAL_CITY']); } else { echo 'не установлен'; }?></span></a></span>
								<?php } else { ?>
								<span>Город: <span class="js-city" style="display: inline-block; margin-bottom: 0px; text-transform: capitalize;"><?if($userData['PERSONAL_CITY']) { echo ucfirst($userData['PERSONAL_CITY']); } else { echo 'не установлен'; }?></span></span>
								<?php } ?>
							<?php } ?>
							<?php if($userData['WORK_CITY'] && !$arrBlock['id'] && !$hide['r_city']) { ?>
							<span>Родной город: <span class="js-r-city" style="display: inline-block; margin-bottom: 0px;"><?if($userData['WORK_CITY']) { echo $userData['WORK_CITY']; } else { echo 'не установлен'; }?></span></span>
							<?php } ?>
							<?php if($userData['PERSONAL_BIRTHDAY'] && !$arrBlock['id'] && !$hide['day']) { ?>
							<span>Дата рождения: <span class="js-r-city" style="display: inline-block; margin-bottom: 0px;"><?if($userData['PERSONAL_BIRTHDAY']) { echo $showBd; } else { echo 'не установлен'; }?></span></span>
							<?php } ?>
							<?php if($userData['PERSONAL_PHONE'] && !$arrBlock['id'] && !$hide['phone']) { ?>
							<span>Телефон: <span class="js-phone" style="display: inline-block; margin-bottom: 0px;"><?if($userData['PERSONAL_PHONE']) { echo $userData['PERSONAL_PHONE']; } else { echo 'не установлен'; }?></span></span>
							<?php } ?>
							<?php if($userData['EMAIL'] && !$arrBlock['id'] && !$hide['email']) { ?>
							<span class="js-email-parent">Email: <?if($userData['EMAIL']) { echo '<a class="js-email" href="mailto:' . $userData['EMAIL'] . '" target="_blank">' . $userData['EMAIL'] . '</a>'; } else { echo '<span class="js-email" style="display: inline-block; margin-bottom: 0px;">не установлен</span>'; }?></span>
							<?php } ?>
							<?php if(($userData['UF_VK'] ||
									 $userData['UF_FB'] ||
									 $userData['UF_OK'] ||
									 $userData['UF_TW'] ||
									 $userData['UF_INST'] ||
									 $userData['UF_YOU'] ||
									 $userData['UF_LJ']) && !$arrBlock['id'] && !$hide['soc']) { ?>
							<span class="links">Соц. сети:
								<?php if($userData['UF_VK']) { ?>
								<a class="js-vk" href="<?php echo $userData['UF_VK']; ?>"><i class="ico vk"></i></a>
								<?php } ?>
								<?php if($userData['UF_FB']) { ?>
								<a class="js-fb" href="<?php echo $userData['UF_FB']; ?>"><i class="ico fc"></i></a>
								<?php } ?>
								<?php if($userData['UF_OK']) { ?>
								<a class="js-ok" href="<?php echo $userData['UF_OK']; ?>"><i class="ico ok"></i></a>
								<?php } ?>
								<?php if($userData['UF_TW']) { ?>
								<a class="js-tw" href="<?php echo $userData['UF_TW']; ?>"><i class="ico tw"></i></a>
								<?php } ?>
								<?php if($userData['UF_INST']) { ?>
								<a class="js-inst" href="<?php echo $userData['UF_INST']; ?>"><i class="ico inst"></i></a>
								<?php } ?>
								<?php if($userData['UF_YOU']) { ?>
								<a class="js-you" href="<?php echo $userData['UF_YOU']; ?>"><i class="ico you"></i></a>
								<?php } ?>
								<?php if($userData['UF_LJ']) { ?>
								<a class="js-lj" href="<?php echo $userData['UF_LJ']; ?>"><i class="ico live"></i></a>
								<?php } ?>
							</span>
							<?php } ?>
						</div><!-- contact-info -->
					</div>
				</div><!-- content-right -->

				<?php if($userData['PERSONAL_NOTES'] && !$arrBlock['id'] && !$hide['note']) { ?>
				<div style="padding-top: 20px; display: block; clear: both;">
					<strong>О себе:</strong>
					<div class="js-notes" style="margin: 10px 0 15px 0;">
						<?if($userData['PERSONAL_NOTES']) { echo $userData['PERSONAL_NOTES']; } else { echo 'не установлено'; }?>
					</div>
				</div>
				<?php } ?>

				<div class="st-content-bottom clear">

					<div class="module st-news">
						<div class="name-block"> &nbsp;&nbsp;&nbsp;<span>Образование</span></div>
						<div class="m-header" style="padding-bottom: 5px;">
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

								}
							?>
							<div class="news-item <?php echo $filterType; ?>">
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
								<div class="image left brd"><img style="width: 111px; height: 111px;" src="<?php echo $img; ?>" alt="<?php echo $name; ?>" title="<?php echo $name; ?>"></div>
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
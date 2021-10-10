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
						<img class="profile-avatar" style="cursor: pointer; height: 122px; width: 122px;<?php if($_SESSION['USER_DATA']['TEACHER']) { echo ' border: 3px solid #ff5b32;'; } ?>" src="<?=$avatar_url?>" alt="img">
					</div>
				</div>

				<?
				if($_SESSION['USER_DATA']['PRO'] == 'Y' && $_SESSION['USER_DATA']['PRO_TYPE'] == 'U') {
					$format_name = '<span style="text-transform: capitalize;">' . strtoupper(mb_substr($_SESSION['USER_DATA']['WORK_COMPANY'], 0, 1)) . '</span>' . mb_substr($_SESSION['USER_DATA']['WORK_COMPANY'], 1);
					$contact = $_SESSION['USER_DATA']['LAST_NAME'] . ' ' . $_SESSION['USER_DATA']['NAME'] . ' '	. $_SESSION['USER_DATA']['SECOND_NAME'];
				} else {
					if (strlen($_SESSION['USER_DATA']['NAME']) && strlen($_SESSION['USER_DATA']['LAST_NAME'])) {
						$format_name = '<span style="text-transform: capitalize;">' . strtoupper(mb_substr($_SESSION['USER_DATA']['NAME'], 0, 1)) . '</span>' . mb_substr($_SESSION['USER_DATA']['NAME'], 1);
						if($_SESSION['USER_DATA']['SECOND_NAME']) {
							$format_name .= ' ';
							$format_name .= '<span style="text-transform: capitalize;">' . strtoupper(mb_substr($_SESSION['USER_DATA']['SECOND_NAME'], 0, 1)) . '</span>' . mb_substr($_SESSION['USER_DATA']['SECOND_NAME'], 1);
						}
						$format_name .= ' ';
						$format_name .= '<span style="text-transform: capitalize;">' . strtoupper(mb_substr($_SESSION['USER_DATA']['LAST_NAME'], 0, 1)) . '</span>' . mb_substr($_SESSION['USER_DATA']['LAST_NAME'], 1);
					} else {
						$format_name = '<span>' . strtoupper(mb_substr(trim($USER->GetLogin()), 0, 1)) . '</span>' . mb_substr(trim($USER->GetLogin()), 1);
					}
					$contact = '';
				}
				?>

				<div class="col-9 content-right" style="padding: 0 0 0 15px;">
					<div class="page-info" style="padding: 0 80px 0 0;">
						<!--<div class="color-silver js-profile-edit" style="position: absolute; top: 0px; right: 0px; cursor: pointer; border-bottom: 1px dashed #9f9f9f;">изменить</div>-->
						<h1 class="name-user">
							<span class="js-name-user" data-fname="<? echo trim($USER->GetFirstName()); ?>" data-lname="<? echo trim($USER->GetLastName()); ?>"><?=$format_name?></span>
							<?php if($_SESSION['USER_DATA']['PERSONAL_PAGER'] != 1) { ?>
							<div style="display: inline-block; position: relative; top: -1px; margin-left: 5px; width: 10px; height: 10px; border-radius: 50%; background-color: #ff471a;" title="В сети"></div>
							<?php } ?>
						</h1>
						<div class="contact-info" style="margin-top: 10px;">
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
							<?php if($_SESSION['USER_DATA']['WORK_STREET']) { ?>
							<span>Юридический адрес: <span style="display: inline-block; margin-bottom: 0px;"><?if($_SESSION['USER_DATA']['WORK_STREET']) { echo $_SESSION['USER_DATA']['WORK_STREET']; } else { echo 'не установлен'; }?></span></span>
							<?php } ?>
							<?php if($_SESSION['USER_DATA']['PERSONAL_BIRTHDAY']) { ?>
							<span class="js-birthday-show">Дата рождения: <span class="js-birthday" data-hide="<?if($hide['day']) { echo '1'; } else { echo '0'; }?>" style="display: inline-block; margin-bottom: 0px;"><?if($_SESSION['USER_DATA']['PERSONAL_BIRTHDAY']) { echo $showBd; } else { echo 'не установлен'; }?></span></span>
							<?php } ?>
							<?php if($_SESSION['USER_DATA']['PERSONAL_PHONE']) { ?>
							<span>Телефон: <span class="js-phone" data-hide="<?if($hide['phone']) { echo '1'; } else { echo '0'; }?>" style="display: inline-block; margin-bottom: 0px;"><?if($_SESSION['USER_DATA']['PERSONAL_PHONE']) { echo $_SESSION['USER_DATA']['PERSONAL_PHONE']; } else { echo 'не установлен'; }?></span></span>
							<?php } ?>
							<?php if($_SESSION['USER_DATA']['EMAIL']) { ?>
							<span class="js-email-parent" data-hide="<?if($hide['email']) { echo '1'; } else { echo '0'; }?>">Email: <?if($_SESSION['USER_DATA']['EMAIL']) { echo '<a class="js-email" href="mailto:' . $_SESSION['USER_DATA']['EMAIL'] . '" target="_blank">' . $_SESSION['USER_DATA']['EMAIL'] . '</a>'; } else { echo '<span class="js-email" style="display: inline-block; margin-bottom: 0px;">не установлен</span>'; }?></span>
							<?php } ?>
							<?php if($contact) { ?>
							<span>Контактное лицо: <span style="display: inline-block; margin-bottom: 0px;"><?if($contact) { echo $contact; } else { echo 'не установлен'; }?></span></span>
							<?php } ?>
							<?php if($_SESSION['USER_DATA']['UF_OGRN']) { ?>
							<span>ОГРН/ОГРНИП: <span style="display: inline-block; margin-bottom: 0px;"><?if($_SESSION['USER_DATA']['UF_OGRN']) { echo $_SESSION['USER_DATA']['UF_OGRN']; } else { echo 'не установлен'; }?></span></span>
							<?php } ?>
							<?php if($_SESSION['USER_DATA']['UF_INN']) { ?>
							<span>ИНН: <span style="display: inline-block; margin-bottom: 0px;"><?if($_SESSION['USER_DATA']['UF_INN']) { echo $_SESSION['USER_DATA']['UF_INN']; } else { echo 'не установлен'; }?></span></span>
							<?php } ?>
							<?php if($_SESSION['USER_DATA']['UF_KPP']) { ?>
							<span>КПП: <span style="display: inline-block; margin-bottom: 0px;"><?if($_SESSION['USER_DATA']['UF_KPP']) { echo $_SESSION['USER_DATA']['UF_KPP']; } else { echo 'не установлен'; }?></span></span>
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

		</div><!-- page-item -->

	</div><!-- page-content -->

</div><!-- st-content-right -->
<?php if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<p> </p><br>
			</div>
			<div class="st-page-footer">
				<!-- Уголок знаний -->
				<div class="random-text col-4">
					<?
					CModule::IncludeModule('iblock');

					$arSelect = Array("ID", "NAME", "IBLOCK_ID", "DETAIL_PAGE_URL", "PREVIEW_TEXT");
					$arFilter = Array("IBLOCK_ID"=>5, "ACTIVE"=>"Y");
					$res = CIBlockElement::GetList(Array("RAND"=>"ASC"), $arFilter, false, Array("nPageSize"=>1), $arSelect);
					if($ob = $res->GetNextElement())
					{
						$arFields = $ob->GetFields();
						$arProps = $ob->GetProperties();
					}
					?>
					<div class="title"><a href="/ugolok-znaniy/">Уголок знаний</a></div>
					<a class="js-link-ug" data-res="<? echo $arFields["ID"]; ?>" href="<?=$arFields['DETAIL_PAGE_URL']?>" style="text-decoration: none; cursor: pointer;">
						<p class="js-text-ug" style="min-height: 150px;"><? echo mb_substr($arFields['PREVIEW_TEXT'], 0, 140) . '..'; ?></p>
					</a>
					<div class="author js-author-ug" style="margin-bottom: 15px; height: 14px;">
						<?if($arProps["SIGN"]["~VALUE"]):?>
						<? echo $arProps["SIGN"]["~VALUE"]; ?>
						<?endif;?>
					</div>
					<div class="link text-right js-reload-ug reload-ug"><a href="#">обновить</a></div>
				</div>
				<!-- st Уголок знаний -->
				<div class="st-page-menu col-4">
					<ul class="st-page-menu-ul left" style="width: 49%">
						<li><a href="/abitur"><span>Абитуриентам</span></a></li>
						<li><a href="/forstudents"><span>Учащимся</span></a></li>
						<li><a href="/additional-education"><span>Дополнительное образование</span></a></li>
						<li><a href="/forprofessor"><span>Преподавателям</span></a></li>
						<li><a href="/forinstitution"><span>Учебным заведениям</span></a></li>
						<li><a href="/users"><span>Пользователи</span></a></li>
					</ul>
					<ul class="st-page-menu-ul left" style="width: 49%">
						<li><a href="/company/feedback/"><span>Нашли ошибку?</span></a></li>
						<li><a href="#" class="js-uz-add-main"><span>Добавить учебное заведение</span></a></li>
						<li><a href="/tests"><span>Тесты</span></a></li>
						<li><a href="/games"><span>Игры</span></a></li>
					</ul>
				</div>
				<div class="f-social col-4">
					<div class="f-title">Мы в социальных сетях:</div>
					<div class="widget">
						<a href="https://vk.com/vuchebecom" target="_blank"><span class="ico"><img src="<?=SITE_TEMPLATE_PATH?>/images/s-ico-1.png" alt="ico"></span></a>
						<a href="https://www.facebook.com/groups/352370299051365/" target="_blank"><span class="ico"><img src="<?=SITE_TEMPLATE_PATH?>/images/s-ico-2.png" alt="ico"></span></a>
						<a href="https://ok.ru/group/55402854678671" target="_blank"><span class="ico"><img src="<?=SITE_TEMPLATE_PATH?>/images/s-ico-3.png" alt="ico"></span></a>
						<a href="#" target="_blank"><span class="ico"><img src="<?=SITE_TEMPLATE_PATH?>/images/s-ico-4.png" alt="ico"></span></a>
						<a href="https://www.instagram.com/vuchebe" target="_blank"><span class="ico"><img src="<?=SITE_TEMPLATE_PATH?>/images/s-ico-6.png" alt="ico"></span></a>
						<a href="#" target="_blank"><span class="ico"><img src="<?=SITE_TEMPLATE_PATH?>/images/s-ico-8.png" alt="ico"></span></a>
						<a href="https://ru.wikipedia.org/wiki" target="_blank"><span class="ico"><img src="<?=SITE_TEMPLATE_PATH?>/images/s-ico-5.png" alt="ico"></span></a>

					</div>
					<div class="f-social-menu">
						<ul class="f-social-ul col-6 left">
							<li><a href="/company"><span>Компания</span></a></li>
							<li><a href="/company/law"><span>Правовая информация</span></a></li>
						</ul>
						<ul class="f-social-ul col-6 left">
							<li><a href="/company/about"><span>О проекте</span></a></li>
							<li><a href="/company/feedback"><span>Обратная связь</span></a></li>
							<li><a href="/sitemap"><span>Карта сайта</span></a></li>
						</ul>
					</div>
				</div>
				<img src="<?=SITE_TEMPLATE_PATH?>/images/logo-footer.png" class="f-logo" alt="logo">
			</div><!-- st-page-footer -->
		</div><!-- st-content -->
	</div>
</div>

<footer>
	<div class="container">
  	<div class="st-copyright text-center">
  		<span class="color-orange">© В учёбе</span> 2021
  	</div>
  </div>
</footer>

<span id="toTop"><i class="fa fa-chevron-up"></i></span>

<!-- wrapper -->

<?php
// PopUp Block
require($_SERVER["DOCUMENT_ROOT"].'/include/popup.php');
?>

<script  src="<?=SITE_TEMPLATE_PATH?>/js/jquery.js"></script>
<script  src="<?=SITE_TEMPLATE_PATH?>/js/jquery.form.validation.js"></script>
<script  src="<?=SITE_TEMPLATE_PATH?>/js/jquery.fancybox.pack.js"></script>
<script  src="<?=SITE_TEMPLATE_PATH?>/js/owl.carousel.min.js"></script>
<script  src="<?=SITE_TEMPLATE_PATH?>/js/mask.js"></script>
<script  src="<?=SITE_TEMPLATE_PATH?>/js/style-form.js"></script>
<script  src="<?=SITE_TEMPLATE_PATH?>/js/main_script.js"></script>
<script  src="<?=SITE_TEMPLATE_PATH?>/js/function.js"></script>
<script  src="<?=SITE_TEMPLATE_PATH?>/js/active-menu.js"></script>
<script  src="<?=SITE_TEMPLATE_PATH?>/js/active--menu.js"></script>
<script  src="<?=SITE_TEMPLATE_PATH?>/js/autoresize.jquery.js"></script>
<script  src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.js"></script>
<script  src="<?=SITE_TEMPLATE_PATH?>/js/law_popup.js"></script>
<script  src="<?=SITE_TEMPLATE_PATH?>/js/add_uz.js"></script>

<?php
if(defined("ERROR_404") && ERROR_404 == "Y" && $APPLICATION->GetCurPage(true) !='/404.php') LocalRedirect('/404.php');

if($_REQUEST['adress'] == 'show') {
?>
<script>
$(document).ready(function() {
	$(".st-aside-menu .select").removeClass('st-cheked');
	$(".st-aside-menu .select.adress").addClass('st-cheked');
	$("#js-advantages").hide();
	$("#js-detail-text").hide();
	$("#js-filial-adress").show();
});// document ready
</script>
<?
}
if ($from > 0) {
?>
<script>
$(document).ready(function() {
	$("#chat").animate({ scrollTop: $("#chat").prop("scrollHeight") }, "slow");
	var timerId = setInterval(chat_loop, 4000, <?=$userChat['ID']?>, <?=$_SESSION['USER_DATA']['ID']?>);
});// document ready
</script>
<?
}
if($groupObserver > 0) {
?>
<script>
$(document).ready(function() {
    $("#chat").animate({ scrollTop: $("#chat").prop("scrollHeight") }, "slow");
    var timerId = setInterval(chat_loop_group, 4000);
});// document ready
</script>
<?
}
if ($_SESSION['USER_DATA']['ID']) {
?>
<script>
$(document).ready(function() {
	var timerIdNew = setInterval(chat_loop_new, 5000);
    const curtime = Math.round(Date.now() / 1000);
    //$("#curtime").text(curtime);
});// document ready
</script>
<?
}
?>
<script>
$(document).ready(function() {
	$(".st-aside-menu .select.main").on('click', function() {
		$(".st-aside-menu .select").removeClass('st-cheked');
		$(".st-aside-menu .select.main").addClass('st-cheked');
		$("#js-filial-adress").hide();
		$("#js-advantages").show();
		$("#js-detail-text").show();
		return false;
	});

	$("#show-filials").on('click', function() {
		let name = $(this).text();
		if(name == 'показать') {
			$("#js-filial-adress").slideDown();
			$(this).text('скрыть');
		} else {
			$("#js-filial-adress").slideUp();
			$(this).text('показать');
		}
		return false;
	});

	let cityNameTop = $('#name-city.name-city a').text();
	$('#name-city-top.name-city a').text(cityNameTop);

	var $owl = $('#panel-carousel.owl-carousel').owlCarousel({
			loop:false,
			mouseDrag: false,
			margin: 0,
			navText:["",""],
			nav:true,
			startPosition: 70,
			responsive:{
					0:{
							items:1,
					},
					600:{
							items:1,
					},
					1000:{
							items:1,
							mouseDrag: false,
					}
			}
  });
  
	function getOrderById(id) {

		if(!pro)
      return false;

		dataform = { id: id};

		$.ajax({
			type: 'POST',
			url: '/ajax/get_order_by_id.php',
			data: dataform,
			dataType: 'json',
			success: function(result){
				if(result.status=='success') {

          let text = bannerPrice['BASE']['description'];

          const data = result.res;
          $('.page-content.form-banner.start-banner img.profile-banner').attr('src', data["PIC"]);
          $('.page-content.form-banner.start-banner input.js-banner-name').val(data["NAME"]);
          $('.page-content.form-banner.start-banner input.js-banner-link').val(data["PROPERTY_URL_VALUE"]);

          if(data["PROPERTY_COUNTRY_VALUE"]) {

            $('.form-banner .js-banner-country option[value=' + data["PROPERTY_COUNTRY_VALUE"] + ']').prop('selected', true);

            text = bannerPrice['COUNTRY']['description'];

            let html = `<option value="0">Выберите</option>`;

            if(result.region.length > 0) {
              $.each(result.region, function(i, val){
                html += `<option value="${val}">${val}</option>`;
              });
            }

            $('.form-banner .js-banner-region').empty();
            $('.form-banner .js-banner-region').append(html);

            if(data["PROPERTY_REGION_VALUE"] != '0') {
              $('.form-banner .js-banner-region option[value="' + data["PROPERTY_REGION_VALUE"] + '"]').prop('selected', true);

              text = bannerPrice['REGION']['description'];
            }

            if(result.city) {
              $('.page-content.form-banner.start-banner input.js-banner-city').val(result.city["NAME"]);
              $('.page-content.form-banner.start-banner input.js-banner-city-current').val(result.city["ID"]);
              $('.page-content.form-banner.start-banner input.js-banner-city-main-city').val(result.city["PROPERTY_TOPCITY_VALUE"]);
              $('.page-content.form-banner.start-banner input.js-banner-city-capital').val(result.city["PROPERTY_CAPITAL_VALUE"]);

              text = bannerPrice['CITY']['description'];
            }

          } else {
            $('.form-banner .js-banner-country option[value=0]').prop('selected', true);
            $('.form-banner .js-banner-region option[value=0]').prop('selected', true);
          }

          $('.page-content.form-banner.start-banner input.js-banner-price').val(data["PROPERTY_LIMIT_VALUE"]);

          $('.form-banner .js-banner-title').text(text);

          calculatePrice();
				}
			}
		});

		return false;
  }
  
  <?php
  if($bannerId) {
  ?>
  getOrderById(<?php echo $bannerId; ?>);
  <?php
  }
  ?>

}); // document ready
</script>
<script src="<?=SITE_TEMPLATE_PATH?>/js/pro_jobs.js"></script>
<?php
if(CSite::InDir('/company/admin/')) {
?>
    <script src="<?=SITE_TEMPLATE_PATH?>/js/register_admin.js"></script>
<?php
}

if($crop == 1) {
?>
<script>
var $uploadCrop;
$(document).ready(function() {
    $uploadCrop = $('#upload-input').croppie({
        enableExif: true,
        viewport: {
            width: 500,
            height: 500,
            type: 'square'
        },
        boundary: {
            width: 500,
            height: 500
        }
    });

    $('.hideForm-avatar.avatar form').css('width', '600px')
    $('#upload-input').data('avatar', 'user')
});
</script>
<?php
}
if($crop == 2) {
?>
<script>
var $uploadCrop;
$(document).ready(function() {
$uploadCrop = $('#upload-input').croppie({
    enableExif: true,
    viewport: {
        width: 200,
        height: 200,
        type: 'square'
    },
    boundary: {
        width: 300,
        height: 300
    }
});

$('.hideForm-avatar.avatar form').css('width', '510px')
    $('#upload-input').data('avatar', 'group')
});
</script>
<?php
}
if($crop == 3) {
?>
<script>
    var $uploadCrop;
    $(document).ready(function() {
        $uploadCrop = $('#upload-input').croppie({
            enableExif: true,
            viewport: {
                width: 200,
                height: 200,
                type: 'square'
            },
            boundary: {
                width: 300,
                height: 300
            },
            enableResize: true
        });
    });
</script>
<?php
}

if($crop == 4) {
    ?>
    <script>
        var $uploadCrop;
        $(document).ready(function() {
            $uploadCrop = $('#upload-input').croppie({
                enableExif: true,
                viewport: {
                    width: 428,
                    height: 60,
                    type: 'square'
                },
                boundary: {
                    width: 528,
                    height: 200
                }
            });
        });

        calculatePrice()
    </script>
    <?php
}

if($crop == 5) {
    ?>
    <script>
        var $uploadCrop;
        $(document).ready(function() {
            $uploadCrop = $('#upload-input').croppie({
                enableExif: true,
                viewport: {
                    width: 222,
                    height: 222,
                    type: 'square'
                },
                boundary: {
                    width: 322,
                    height: 322
                }
            });
        });

        calculatePrice()
    </script>
    <?php
}

if(CSite::InDir('/user/') || CSite::InDir('/unpack/')) {
    ?>
    <script src="<?=SITE_TEMPLATE_PATH?>/js/balance_user.js"></script>
    <script src="<?=SITE_TEMPLATE_PATH?>/js/avatar_user.js"></script>
    <script src="<?=SITE_TEMPLATE_PATH?>/js/support_chat.js"></script>
    <script src="<?=SITE_TEMPLATE_PATH?>/js/cropp_banner.js"></script>
    <?php
    if($section == 'control') {
    ?>
    <script src="<?=SITE_TEMPLATE_PATH?>/js/order_admin.js"></script>
    <?php
    }
    if($section == 'avatar') {
        ?>
        <script src="<?=SITE_TEMPLATE_PATH?>/js/avatar_admin.js"></script>
        <?php
    }
    if($section == 'reviews') {
        ?>
        <script src="<?=SITE_TEMPLATE_PATH?>/js/reviews_admin.js"></script>
        <?php
    }
    if($section == 'check') {
        ?>
        <script src="<?=SITE_TEMPLATE_PATH?>/js/check_admin.js"></script>
        <?php
    }
    if($section == 'spam') {
        ?>
        <script src="<?=SITE_TEMPLATE_PATH?>/js/spam_admin.js"></script>
        <?php
    }
}

if(CSite::InDir('/ugolok-znaniy/')) {
    ?>
    <script src="<?=SITE_TEMPLATE_PATH?>/js/ugolok_znaniy.js"></script>
    <?php
}
?>
<script src="<?=SITE_TEMPLATE_PATH?>/js/banner.js"></script>
<style>
.cookies__banner {
    position: fixed;
    bottom: 0;
    left: 0;
    display: none;
    width: 100%;
    min-height: 70px;
    padding: 0 14px;
    background-color: #3a3a3a;
    opacity: .97;
    font-size: 0;
    line-height: 20px;
    text-align: center;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    z-index: 999;
}
.cookies__banner-wrapper {
    display: inline-block;
    margin: 0 auto;
    vertical-align: middle;
    margin-top: 7px;
}
.cookies__banner-text {
    display: inline-block;
    margin-right: 20px;
    text-align: left;
    vertical-align: middle;
    font-size: 18px;
    font-style: normal;
    color: #d1d1d1;
}
.cookies__banner-button {
    display: inline-block;
    padding: 8px 15px;
    border: 1px solid #d1d1d1;
    font-size: 18px;
    font-style: normal;
    color: #ff4719;
    text-align: center;
    vertical-align: middle;
    text-decoration: none;
    user-select: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    cursor: pointer;
}
.cookies-banner-show {
    display: inline-block;
}
.cookies__banner-text a.lav-href {
    cursor: pointer;
    border-bottom: 1px dashed #9f9f9f;
    position: relative;
    color: #9f9f9f;
    text-decoration: none;
    line-height: 1.4;
}
</style>
<?php
$lavStrCookies = '';
if(!$_SESSION['PANEL']['HIDE_COOKIES']) {

    $politic = 'PROPERTY_COOKIES';
    $lavStrCookies = getLawPopUpStr($politic);
}
?>
<div class="cookies__banner js-cookies-banner<?php if($_SESSION['PANEL']['HIDE_COOKIES'] == 0) { echo ' cookies-banner-show'; } ?>">
	<div class="cookies__banner-wrapper">
		<p class="cookies__banner-text">Данный сайт использует файлы cookies. <?php echo $lavStrCookies; ?></p>
		<a class="cookies__banner-button js-cookies-button">Соглашаюсь</a>
	</div>
</div>
</body>
</html>

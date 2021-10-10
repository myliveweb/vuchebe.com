<div class="page-content">
	<style>
	.image .profile-avatar {
		margin-bottom: 15px;
		max-width: 230px;
	}
	.image .profile-avatar:last-child {
		margin-bottom: 0px;
	}
	</style>
	<div class="name-block text-center txt-up"><span>История языкового курса</span></div>
	<div class="st-content-bottom clear">
	<? if($arResult["PROPERTIES"]["HISTORY_VUZ"]["VALUE"]) { ?>
		<div class="module st-news">

			<div class="line">

				<div class="news-item one" style="position: relative;">
					<?if($pageAdmin):?>
					<div class="color-silver js-vuz-edit" data-block="history"  data-iblock="6" style="position: absolute; top: -25px; right: 5px; cursor: pointer; border-bottom: 1px dashed #9f9f9f;">изменить</div>
					<?endif?>
					<?php
					if($pageAdmin) {
						?>
						<div class="image brd left">
							<input type="file" id="file" data-type="history" data-id="<?php echo $arResult["ID"]; ?>" data-iblock="6" accept="image/*">
							<label for="file">
							<?php
							if($arResult["PROPERTIES"]["PHOTO_HISTORY"]["VALUE"]) {
							?>
								<img class="profile-avatar" src="<?=CFile::GetPath($arResult["PROPERTIES"]["PHOTO_HISTORY"]["VALUE"]); ?>" alt="<?=$arResult["PROPERTIES"]["PHOTO_HISTORY"]["DESCRIPTION"]; ?>" title="<?=$arResult["PROPERTIES"]["PHOTO_HISTORY"]["DESCRIPTION"]; ?>">
							<?php
							} else {
							?>
								<img class="profile-avatar" src="<?=SITE_TEMPLATE_PATH ?>/images/noimage-2.png" alt="Фото ВУЗа" title="Фото ВУЗа">
							<?php
							}
							?>
							</label>
						</div>
						<?php
					} elseif($arResult["PROPERTIES"]["PHOTO_HISTORY"]["VALUE"]) {
					?>
					<div class="image brd left">
						<img class="profile-avatar" src="<?=CFile::GetPath($arResult["PROPERTIES"]["PHOTO_HISTORY"]["VALUE"]); ?>" alt="<?=$arResult["PROPERTIES"]["PHOTO_HISTORY"]["DESCRIPTION"]; ?>" title="<?=$arResult["PROPERTIES"]["PHOTO_HISTORY"]["DESCRIPTION"]; ?>">
					</div>
					<?php
					}
					$br = str_replace(array("\r\n", "\r", "\n"), '<br>', $arResult["PROPERTIES"]["HISTORY_VUZ"]["~VALUE"]["TEXT"]);
					echo $br;
					?>
					<div class="btns text-right">
						<a style="font-family: Verdana; margin-top: 15px;" href="<?=$arResult["DETAIL_PAGE_URL"]?>" class="button"><i class="fa fa-angle-double-left"></i> назад</a>
					</div>
				</div>

			</div>

		</div><!-- st-news -->
	<? } else { ?>
		<div class="module st-news">
			<div class="line">
				<div class="news-item one" style="position: relative;">
					<?if($pageAdmin):?>
					<div class="color-silver js-vuz-edit" data-block="history" data-iblock="6" style="position: absolute; top: -15px; right: 5px; cursor: pointer; border-bottom: 1px dashed #9f9f9f;">изменить</div>
					<?endif?>
				</div>
			</div>
		</div><!-- st-news -->
	<? } ?>
	</div><!-- st-content-bottom -->
</div>
<div id="banner-list">
    <div class="modules-left clear">
        <div class="st-banner" style="position: relative;">
            <div class="hide-banner js-hide-banner">реклама</div>
            <div class="image brd">
                <?php list($idBanner, $srcBanner, $hrefBanner, $targetBanner, $clickBanner, $nameBanner) = getRandomBanner(35, 222, 222); ?>
                <?php if($idBanner) { ?>
                <a class="js-click-banner side-banner" href="#" data-id="<?php echo $idBanner; ?>"<?php echo $targetBanner; ?>><img src="<?php echo $srcBanner; ?>" title="<?php echo $nameBanner; ?>" alt="<?php echo $nameBanner; ?>"></a>
                <?php } else { ?>
                <a class="default-banner" href="<?php echo $hrefBanner; ?>"<?php echo $targetBanner; ?>><img src="<?php echo $srcBanner; ?>" title="<?php echo $nameBanner; ?>" alt="<?php echo $nameBanner; ?>"></a>
                <?php }  ?>
            </div>
        </div>
    </div>
</div>
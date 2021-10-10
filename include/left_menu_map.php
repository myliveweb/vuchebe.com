<div class="st-aside absolute">
	<div class="st-aside-menu">
		<form name="select_all" method="post">
			<div class="title" style="height: 20px;"></div>
			<div class="st-select-search" id="map-menu">
				<div data-id="2" class="js-map select<?if('universities' == $current_razdel || 'all' == $current_razdel) { ?> st-cheked<? } ?>">
					<div class="style-input">
 						<a href="/map/universities/" class="a-label">
							<div>
								<span class="ico">
									<img src="<?=SITE_TEMPLATE_PATH?>/images/ico-3.png" alt="ico">
								</span>
								<span>ВУЗы</span>
							</div>
						</a>
					</div>
				</div><!-- select -->
				<div data-id="3" class="js-map select<?if('colleges' == $current_razdel || 'all' == $current_razdel) { ?> st-cheked<? } ?>">
					<div class="style-input">
 						<a href="/map/colleges/" class="a-label">
							<div>
								<span class="ico">
									<img src="<?=SITE_TEMPLATE_PATH?>/images/ico-2.png" alt="ico">
								</span>
								<span>Колледжи</span>
							</div>
						</a>
					</div>
				</div><!-- select -->
				<div data-id="4" class="js-map select<?if('schools' == $current_razdel || 'all' == $current_razdel) { ?> st-cheked<? } ?>">
					<div class="style-input">
 						<a href="/map/schools/" class="a-label">
							<div>
								<span class="ico">
									<img src="<?=SITE_TEMPLATE_PATH?>/images/ico-1.png" alt="ico">
								</span>
								<span>Школы</span>
							</div>
						</a>
					</div>
				</div><!-- select -->
				<div data-id="6" class="js-map select<?if('language-class' == $current_razdel || 'all' == $current_razdel) { ?> st-cheked<? } ?>">
					<div class="style-input">
 						<a href="/map/language-class/" class="a-label">
							<div>
								<span class="ico">
									<img src="<?=SITE_TEMPLATE_PATH?>/images/ico-5.png" alt="ico">
								</span>
								<span>Языковые курсы</span>
							</div>
						</a>
					</div>
				</div><!-- select -->
			</div>
		</form>
	</div>
</div>
 <!-- left-column -->
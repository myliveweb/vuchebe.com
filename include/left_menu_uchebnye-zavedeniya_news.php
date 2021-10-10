<div class="left-column left">
	<div class="st-aside static">
		<div class="st-aside-menu">
			<form name="select_all" method="post">
			<div class="title"></div>
				<div class="st-select-search">
					<?php if($obrMenu) { ?>
					<div class="select<? if($current_razdel == 'news-education') { ?> st-cheked<? } ?>">
						<div class="style-input">
							<a href="/news/education/" class="a-label">
								<div>
									<span>Новости образования</span>
								</div>
							</a>
						</div>
					</div><!-- select -->
					<?php } ?>
					<div class="select<? if($current_razdel == 'news-universities') { ?> st-cheked<? } ?>">
						<div class="style-input">
							<a href="/news/universities/" class="a-label">
								<div>
									<span>Новости ВУЗов</span>
								</div>
							</a>
						</div>
					</div><!-- select -->
					<div class="select<? if($current_razdel == 'news-colleges') { ?> st-cheked<? } ?>">
						<div class="style-input">
							<a href="/news/colleges/" class="a-label">
								<div>
									<span>Новости колледжей</span>
								</div>
							</a>
						</div>
					</div><!-- select -->
					<div class="select<? if($current_razdel == 'news-schools') { ?> st-cheked<? } ?>">
						<div class="style-input">
							<a href="/news/schools/" class="a-label">
								<div>
									<span>Новости школ</span>
								</div>
							</a>
						</div>
					</div><!-- select -->
					<div class="select<? if($current_razdel == 'news-language-class') { ?> st-cheked<? } ?>">
						<div class="style-input">
							<a href="/news/language-class/" class="a-label">
								<div>
									<span>Новости языковых курсов</span>
								</div>
							</a>
						</div>
					</div><!-- select -->
					<div class="select<? if($current_razdel == 'news-open-days') { ?> st-cheked<? } ?>">
						<div class="style-input">
							<a href="/uchebnye-zavedeniya/open-days/" class="a-label">
								<div>
									<span>Дни открытых дверей</span>
								</div>
							</a>
						</div>
					</div><!-- select -->
				</div>
			</form>
		</div>
	</div>
    <?php
    require($_SERVER["DOCUMENT_ROOT"].'/include/sidebanner.php');
    ?>
</div><!-- left-column -->
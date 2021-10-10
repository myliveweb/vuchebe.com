<div class="left-column left">
	<div class="st-aside static">
		<div class="st-aside-menu">
			<form name="select_all" method="post">
			<div class="title"></div>
				<div class="st-select-search">
					<div class="select<? if($current_razdel == 'universities') { ?> st-cheked<? } ?>">
						<div class="style-input">
							<a href="/uchebnye-zavedeniya/universities/" class="a-label">
								<div>
									<span>ВУЗы</span>
								</div>
							</a>
						</div>
					</div><!-- select -->
					<div class="select<? if($current_razdel == 'colleges') { ?> st-cheked<? } ?>">
						<div class="style-input">
							<a href="/uchebnye-zavedeniya/colleges/" class="a-label">
								<div>
									<span>Колледжи</span>
								</div>
							</a>
						</div>
					</div><!-- select -->
					<div class="select<? if($current_razdel == 'schools') { ?> st-cheked<? } ?>">
						<div class="style-input">
							<a href="/uchebnye-zavedeniya/schools/" class="a-label">
								<div>
									<span>Школы</span>
								</div>
							</a>
						</div>
					</div><!-- select -->
					<div class="select<? if($current_razdel == 'language-class') { ?> st-cheked<? } ?>">
						<div class="style-input">
							<a href="/uchebnye-zavedeniya/language-class/" class="a-label">
								<div>
									<span>Языковые курсы</span>
								</div>
							</a>
						</div>
					</div><!-- select -->
					<div class="select<? if($current_razdel == 'open-days') { ?> st-cheked<? } ?>">
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
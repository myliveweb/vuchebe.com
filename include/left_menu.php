<div class="left-column left">
	<div class="st-aside static">
		<div class="st-aside-menu">
			<form name="select_all" method="post">
				<div class="title">
				</div>
				<div class="st-select-search">
					<div class="select<?if('schools' == $current_razdel) { ?> st-cheked<? } ?>">
						<div class="style-input">
 <a href="/uchebnye-zavedeniya/schools/" class="a-label">
							<div>
								 Школы
							</div>
 </a>
						</div>
					</div>
					 <!-- select -->
					<div class="select<?if('colleges' == $current_razdel) { ?> st-cheked<? } ?>">
						<div class="style-input">
 <a href="/uchebnye-zavedeniya/colleges/" class="a-label">
							<div>
								 Колледжи
							</div>
 </a>
						</div>
					</div>
					 <!-- select -->
					<div class="select<?if('universities' == $current_razdel) { ?> st-cheked<? } ?>">
						<div class="style-input">
 <a href="/uchebnye-zavedeniya/universities/" class="a-label">
							<div>
								 ВУЗы
							</div>
 </a>
						</div>
					</div>
					 <!-- select -->
					<div class="select<?if('language-class' == $current_razdel) { ?> st-cheked<? } ?>">
						<div class="style-input">
 <a href="/uchebnye-zavedeniya/language-class/" class="a-label">
							<div>
								 Языковые курсы
							</div>
 </a>
						</div>
					</div>
					 <!-- select -->
					<div class="select">
						<div class="style-input">
 <a href="/uchebnye-zavedeniya/add-education/" class="a-label">
							<div>
								 Дополнительное образование
							</div>
 </a>
						</div>
					</div>
					 <!-- select -->
					<div class="select<?if('open-days' == $current_razdel) { ?> st-cheked<? } ?>">
						<div class="style-input">
 <a href="/uchebnye-zavedeniya/open-days/" class="a-label">
							<div>
								 Дни открытых дверей
							</div>
 </a>
						</div>
					</div>
					 <!-- select -->
				</div>
			</form>
		</div>
	</div>
    <?php
    require($_SERVER["DOCUMENT_ROOT"].'/include/sidebanner.php');
    ?>
</div>
 <!-- left-column -->
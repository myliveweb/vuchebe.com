<style>
.auto-complit {
	position: absolute;
	top: 47px;
	left: 15px;
	max-height: 200px;
	width: 466px;
	background-color: #fff;
	border-right: 1px solid #ff471a;
	border-bottom: 1px solid #ff471a;
	border-left: 1px solid #ff471a;
	border-radius: 0px 0px 4px 4px;
	z-index: 100;
	padding: 4px 0px;
	display: none;
}
.auto-complit .item {
	width: 100%;
	color: #000;
	padding: 8px 15px;
	height: 30px;
	cursor: pointer;
	white-space: nowrap;
}
.auto-complit .item div {
	overflow: hidden;
}
.auto-complit .item:hover {
	background-color: #e0e0e0;
}
.js-block {
	display: none;
}
</style>
<div class="hideForm-news-edit uz">
	<div class="foneBg" onClick="close_form();"></div>
  	<div class="form-open-block">
	  	<form id="form-news-uz" method="post" action="">
			<div>
				<div class="name_form text-center"><span>Добавление учебного заведения</span></div>
				<div id="error-message-vuz-edit" style="color: red; font-weight: bold; font-size: 20px; height: 24px; margin-bottom: 15px; display: none;"></div>
				<div class="row-line">
					<div class="col-12">
						<div class="label">Образование</div>
						<select class="obr js-news-edit-form" style="color: black;">
							<option value="0">Неустановлено</option>
							<option value="1">Высшее</option>
							<option value="2">Среднее</option>
							<option value="3">Начальное</option>
							<option value="4">Языковые курсы</option>
						</select>
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-12">
						<div class="label">Страна</div>
						<select class="country js-news-edit-form" disabled="true" style="color: black;">
							<option value="0">Выберите</option>
							<?php
								$arrCountry = array();
							    $arSelectCountry = array("ID", "NAME", "IBLOCK_ID");
							    $arFilterCountry = array("IBLOCK_ID" => 32, "ACTIVE" => "Y");
							    $resCountry = CIBlockSection::GetList(array("SORT" => "ASC"), $arFilterCountry, false, $arSelectCountry);
							    while($rowCountry = $resCountry->GetNext()) {
							    ?>
								<option value="<?php echo $rowCountry['ID']; ?>"><?php echo $rowCountry['NAME']; ?></option>
							    <?php
								}
							?>
						</select>
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-12">
						<div class="label">Регион</div>
						<select class="region js-news-edit-form" style="color: black;" disabled = true >
							<option value="0">Выберите</option>
						</select>
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-12">
						<div class="label">Город</div>
						<input class="city_id js-news-edit-form" type="hidden">
						<input style="color: black;" class="city js-news-edit-form" type="text">
						<div class="auto-complit-city" style="overflow: auto;"></div>
					</div>
				</div>
				<div class="row-line mt-10">
					<div class="col-12" style="position: relative;">
						<div class="label" id="naz">Название учебного заведения</div>
						<input class="name js-news-edit-form" type="text" disabled="true" style="color: black;">
						<div class="auto-complit-name" style="overflow: auto;"></div>
					</div>
				</div>
				<div class="vuz-block js-block">
					<div class="row-line mt-10" style="display: none;">
						<div class="col-12">
							<div class="label">Факультет</div>
							<select class="fack js-news-edit-form" disabled="true" style="color: black;">
								<option value="-1">Неустановлено</option>
							</select>
						</div>
					</div>
					<div class="row-line mt-10">
						<div class="col-12">
							<div class="label">Форма обучения</div>
							<select class="forma js-news-edit-form" disabled="true" style="color: black;">
								<option value="">Неустановлено</option>
								<option value="Очная">Очная</option>
								<option value="Очно-заочная">Очно-заочная</option>
								<option value="Заочная">Заочная</option>
								<option value="Группа выходного дня">Группа выходного дня</option>
								<option value="Дистанционная">Дистанционная</option>
							</select>
						</div>
					</div>
					<div class="row-line mt-10">
						<div class="col-12">
							<div class="label">Статус</div>
							<select class="status js-news-edit-form" disabled="true" style="color: black;">
								<option value="">Неустановлено</option>
								<option value="Абитуриент">Абитуриент</option>
								<option value="Студент (специалист)">Студент (специалист)</option>
								<option value="Студент (бакалавр)">Студент (бакалавр)</option>
								<option value="Студент (магистр)">Студент (магистр)</option>
								<option value="Выпускник (специалист)">Выпускник (специалист)</option>
								<option value="Выпускник (бакалавр)">Выпускник (бакалавр)</option>
								<option value="Выпускник (магистр)">Выпускник (магистр)</option>
								<option value="Аспирант">Аспирант</option>
								<option value="Кандидат наук">Кандидат наук</option>
								<option value="Доктор наук">Доктор наук</option>
								<option value="Интерн">Интерн</option>
								<option value="Клинический ординатор">Клинический ординатор</option>
								<option value="Соискатель">Соискатель</option>
								<option value="Ассистент-стажёр">Ассистент-стажёр</option>
								<option value="Докторант">Докторант</option>
								<option value="Адъюнкт">Адъюнкт</option>
							</select>
						</div>
					</div>
					<div class="row-line mt-10">
						<div class="col-12">
							<div class="label">Группа</div>
							<input class="group js-news-edit-form" type="text" style="color: black;">
						</div>
					</div>
					<div class="row-line mt-10">
						<div class="col-12">
							<div class="label start-text">Дата начала обучения</div>
							<select class="start js-news-edit-form" style="color: black;">
								<option value="0">Неустановлено</option>
								<?php
								for($n = 2027; $n > 1939; $n--) {
								?>
									<option value="<?php echo $n; ?>"><?php echo $n; ?></option>
								<?php
								}
								?>
							</select>
						</div>
					</div>
					<div class="row-line mt-10">
						<div class="col-12">
							<div class="label end-text">Дата выпуска</div>
							<select class="end js-news-edit-form" style="color: black;">
								<option value="0">Неустановлено</option>
								<?php
								for($n = 2027; $n > 1939; $n--) {
								?>
									<option value="<?php echo $n; ?>"><?php echo $n; ?></option>
								<?php
								}
								?>
							</select>
						</div>
					</div>
				</div>
				<div class="coll-block js-block">
					<div class="row-line mt-10">
						<div class="col-12">
							<div class="label">Специализация</div>
							<input class="spec js-news-edit-form" type="text" style="color: black;">
						</div>
					</div>
					<div class="row-line mt-10">
						<div class="col-12">
							<div class="label">Группа</div>
							<input class="group js-news-edit-form" type="text" style="color: black;">
						</div>
					</div>
					<div class="row-line mt-10">
						<div class="col-12">
							<div class="label start-text">Дата начала обучения</div>
							<select class="start js-news-edit-form" style="color: black;">
								<option value="0">Неустановлено</option>
								<?php
								for($n = 2027; $n > 1939; $n--) {
								?>
									<option value="<?php echo $n; ?>"><?php echo $n; ?></option>
								<?php
								}
								?>
							</select>
						</div>
					</div>
					<div class="row-line mt-10">
						<div class="col-12">
							<div class="label end-text">Дата выпуска</div>
							<select class="end js-news-edit-form" style="color: black;">
								<option value="0">Неустановлено</option>
								<?php
								for($n = 2027; $n > 1939; $n--) {
								?>
									<option value="<?php echo $n; ?>"><?php echo $n; ?></option>
								<?php
								}
								?>
							</select>
						</div>
					</div>
				</div>
				<div class="shool-block js-block">
					<div class="row-line mt-10">
						<div class="col-12">
							<div class="label">Класс</div>
							<input class="klass js-news-edit-form" type="text" style="color: black;">
						</div>
					</div>
					<div class="row-line mt-10">
						<div class="col-12">
							<div class="label start-text">Дата начала обучения</div>
							<select class="start js-news-edit-form" style="color: black;">
								<option value="0">Неустановлено</option>
								<?php
								for($n = 2027; $n > 1939; $n--) {
								?>
									<option value="<?php echo $n; ?>"><?php echo $n; ?></option>
								<?php
								}
								?>
							</select>
						</div>
					</div>
					<div class="row-line mt-10">
						<div class="col-12">
							<div class="label end-text">Дата выпуска</div>
							<select class="end js-news-edit-form" style="color: black;">
								<option value="0">Неустановлено</option>
								<?php
								for($n = 2027; $n > 1939; $n--) {
								?>
									<option value="<?php echo $n; ?>"><?php echo $n; ?></option>
								<?php
								}
								?>
							</select>
						</div>
					</div>
				</div>
				<div class="lang-block js-block">
					<div class="row-line mt-10">
						<div class="col-12">
							<div class="label start-text">Дата начала обучения</div>
							<select class="start js-news-edit-form" style="color: black;">
								<option value="0">Неустановлено</option>
								<?php
								for($n = 2027; $n > 1939; $n--) {
								?>
									<option value="<?php echo $n; ?>"><?php echo $n; ?></option>
								<?php
								}
								?>
							</select>
						</div>
					</div>
					<div class="row-line mt-10">
						<div class="col-12">
							<div class="label end-text">Дата выпуска</div>
							<select class="end js-news-edit-form" style="color: black;">
								<option value="0">Неустановлено</option>
								<?php
								for($n = 2027; $n > 1939; $n--) {
								?>
									<option value="<?php echo $n; ?>"><?php echo $n; ?></option>
								<?php
								}
								?>
							</select>
						</div>
					</div>
				</div>
				<div class="row-line mb-10 mt-15 css-btn">
					<div class="col-4">
						<button type="submit" class="js-submit-uz-edit" data-form="uz"><span>Сохранить</span></button>
					</div>
					<div class="col-4">
						<button type="button" style="background-color: #a7a7a7; display: none;" class="js-del-uz gray"><span>Удалить</span></button>
					</div>
				</div>
				<a href="javascript:void(0);" class="close" onclick="close_form();"></a>
			</div>
	  	</form>
  	</div>
</div><!-- hideForm-news-edit uz -->
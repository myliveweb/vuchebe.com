<div class="row-line">
	<div class="col-4 mob-line">
		<span class="label">Имя</span>
		<input class="w-80 left f_first_name" type="text" name="f_first_name" value="<?=$_POST['f_first_name']?>">
	</div>
	<div class="col-4 mob-line">
		<span class="label">Фамилия</span>
		<input class="w-80 left f_last_name" type="text" name="f_last_name" value="<?=$_POST['f_last_name']?>">
	</div>
	<div class="col-4 mob-line">
		<span class="label">Отчество</span>
		<input class="w-80 left f_second_name" type="text" name="f_second_name" value="<?=$_POST['f_second_name']?>">
	</div>
</div>
<div class="row-line">
	<div class="col-4 mob-line">
		<div class="label">День рождения</div>
		<select name="f_day" class="f_day left-5" style="color: black;">
			<option value="0">Число</option>
			<?php
			for($n = 1; $n < 32; $n++) {
			?>
				<option value="<?php echo $n; ?>"><?php echo $n; ?></option>
			<?php
			}
			?>
		</select>
	</div>
	<div class="col-4 mob-line">
		<div class="label">&nbsp;</div>
		<select name="f_month" class="f_month left-5" style="color: black;">
			<option value="0">Месяц</option>
			<option value="1">Январь</option>
			<option value="2">Февраль</option>
			<option value="3">Март</option>
			<option value="4">Апрель</option>
			<option value="5">Май</option>
			<option value="6">Июнь</option>
			<option value="7">Июль</option>
			<option value="8">Август</option>
			<option value="9">Сентябрь</option>
			<option value="10">Октябрь</option>
			<option value="11">Ноябрь</option>
			<option value="12">Декабрь</option>
		</select>
	</div>
	<div class="col-4 mob-line">
		<div class="label">&nbsp;</div>
		<select name="f_year" class="f_year left-5" style="color: black;">
			<option value="0">Год</option>
			<?php
			for($n = 2012; $n > 1939; $n--) {
			?>
				<option value="<?php echo $n; ?>"><?php echo $n; ?></option>
			<?php
			}
			?>
		</select>
	</div>
</div>
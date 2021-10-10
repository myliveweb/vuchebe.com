$(document).ready(function() {
	$('#form-jobs').on('click', '.add-link', function(e) {
		e.preventDefault();

		let parentBox = $(this).parent().prev();
		let html = `<input class="w-80 left links add" style="display: none;" type="text" name="links[]" value="">`;

		$(parentBox).append(html);
		$('#form-jobs .links:last').slideDown();

		return false;
	});

	$('#pro').on('click', '.pro-switch', function(e) {
		e.preventDefault();

		$('#pro .error-message').text('');
		$('#pro .error-message').hide();

		let tab = $(this).data('tab');

		$('#pro .pro-tab').hide();
		$('#pro .' + tab).fadeIn();

		return false;
	});

	$('#pro-reg').on('click', '.js-pro', function() {

		$('#pro .error-message').text('');
		$('#pro .error-message').hide();

		let tab = $(this).data('tab');

		$('#pro-reg .reg-tab').hide();
		$('#pro-reg .reg-' + tab).fadeIn();
	});

	$('#pro-reg').on('blur', '.email', function() {

		let email = $('#pro-reg .email').val();

		if(email.length < 4)
			return false;

		$.ajax({
			type: 'POST',
			url: '/ajax/check_email.php',
			data: {'email': email},
			dataType: 'json',
			success: function(result) {
				if (result.status == 'success') {
					if(result.res.length > 0) {
						badEmailCheck = 1;
						$('#pro-reg .email').css('color', 'red');
						$('#pro-reg .color-orange').css('color', 'red');
						$('#pro-reg .color-orange').text('Такой E-mail уже используется');
						$('#pro-reg .color-orange').show();

						$('#form-reg .success-duble-email-hide').hide()
						$('#form-reg .duble-email a').data('email', email);
						$('#form-reg .duble-email a span').text(email);
						$('#form-reg .duble-email span').text(email);
						$('#form-reg .duble-email-hide').show();
					} else {
						badEmailCheck = 0;
						$('#pro-reg .color-orange').css('color', 'green');
						$('#pro-reg .color-orange').text('OK');
						$('#pro-reg .color-orange').show();
					}
				}
			}
		});
		return false;
	});

    $('#pro-reg').on('change', '.js-law', function(e){
    	e.preventDefault();

    	if($("#pro-reg .js-law").is(':checked')) {
			$("#pro-reg .law-text").slideUp();
    	} else {
    		$("#pro-reg .law-text").slideDown();
    	}

        return false;
    });

    $('#pro-reg').on('submit', function(e){
    	e.preventDefault();

		$('#pro .error-message').text('');
		$('#pro .error-message').hide();

		$('#pro-reg input').css('color', '#a7a7a7');
		$('#pro-reg .u_password').attr('type', 'password');
		$('#pro-reg .u_password_confirm').attr('type', 'password');
		$('#pro-reg .f_password').attr('type', 'password');
		$('#pro-reg .f_password_confirm').attr('type', 'password');

		let typePro = parseInt($('#pro-reg .js-pro:checked').val());

		if(typePro === 6) {
			let name 			 = $('#pro-reg .u_name').val().trim();
			let ogrn 			 = $('#pro-reg .u_ogrn').val().trim();
			let inn  			 = $('#pro-reg .u_inn').val().trim();
			let address  		 = $('#pro-reg .u_address').val().trim();

			if(name.length === 0) {
				$('#pro-reg .u_name').css('color', 'red');
				$('#pro-reg .u_name').val('Заполните');
				return false;
			}

			if(ogrn.length === 0) {
				$('#pro-reg .u_ogrn').css('color', 'red');
				$('#pro-reg .u_ogrn').val('Заполните');
				return false;
			}

			if(inn.length === 0) {
				$('#pro-reg .u_inn').css('color', 'red');
				$('#pro-reg .u_inn').val('Заполните');
				return false;
			}

			if(address.length === 0) {
				$('#pro-reg .u_address').css('color', 'red');
				$('#pro-reg .u_address').val('Заполните');
				return false;
			}

			let firstName  		 = $('#pro-reg .u_first_name').val().trim();
			let lastName  		 = $('#pro-reg .u_last_name').val().trim();
			let secondName  	 = $('#pro-reg .u_second_name').val().trim();

			if(firstName.length === 0) {
				$('#pro-reg .u_first_name').css('color', 'red');
				$('#pro-reg .u_first_name').val('Заполните');
				return false;
			}

			if(lastName.length === 0) {
				$('#pro-reg .u_last_name').css('color', 'red');
				$('#pro-reg .u_last_name').val('Заполните');
				return false;
			}

			if(secondName.length === 0) {
				$('#pro-reg .u_second_name').css('color', 'red');
				$('#pro-reg .u_second_name').val('Заполните');
				return false;
			}

		} else {
			let day  			 = parseInt($('#pro-reg .f_day').val());
			let month  			 = parseInt($('#pro-reg .f_month').val());
			let year  			 = parseInt($('#pro-reg .f_year').val());

			let firstName  		 = $('#pro-reg .f_first_name').val().trim();
			let lastName  		 = $('#pro-reg .f_last_name').val().trim();
			let secondName  	 = $('#pro-reg .f_second_name').val().trim();

			if(firstName.length === 0) {
				$('#pro-reg .f_first_name').css('color', 'red');
				$('#pro-reg .f_first_name').val('Заполните');
				return false;
			}

			if(lastName.length === 0) {
				$('#pro-reg .f_last_name').css('color', 'red');
				$('#pro-reg .f_last_name').val('Заполните');
				return false;
			}

			if(secondName.length === 0) {
				$('#pro-reg .f_second_name').css('color', 'red');
				$('#pro-reg .f_second_name').val('Заполните');
				return false;
			}

			if(day === 0 || month === 0 || year === 0) {
				$('#pro .error-message').css('color', 'red');
				$('#pro .error-message').text('Заполните дату вашего дня рождения');
				$('#pro .error-message').show();
				return false;
			}

		}

		let email  			 = $('#pro-reg .email').val();
		let phone  			 = $('#pro-reg .phone').val();
		let password  		 = $('#pro-reg .password').val().trim();
		let passwordConfirm  = $('#pro-reg .password_confirm').val().trim();

		if(!validateEmail(email)){
			$('#pro-reg .email').css('color', 'red');
			$('#pro-reg .email').val('Вы не правельно ввели Email');
			return false;
		}

		if(phone.length === 0){
			$('#pro-reg .phone').css('color', 'red');
			$('#pro-reg .phone').val('Заполните');
			return false;
		}

		if(password.length < 6){
			$('#pro-reg .password').attr('type', 'text');
			$('#pro-reg .password').css('color', 'red');
			$('#pro-reg .password').val('Не менее 6 символов');
			return false;
		}

		if(password !== passwordConfirm){
			$('#pro-reg .password_confirm').attr('type', 'text');
			$('#pro-reg .password_confirm').css('color', 'red');
			$('#pro-reg .password_confirm').val('Не совпадает с паролём');
			return false;
		}

		let captchaWord = $('#pro-reg .captcha_word').val();
		let captchaSid  = $('#pro-reg .captcha_sid').val();

		if(captchaWord.length === 0){
			$('#pro-reg .captcha_word').css('color', 'red');
			$('#pro-reg .captcha_word').val('Заполните');
			return false;
		}

		let data_captcha = {};
		data_captcha['captcha_word'] = captchaWord;
		data_captcha['captcha_sid']  = captchaSid;

		$.ajax({
			type: 'POST',
			url: '/ajax/captcha_check.php',
			data: data_captcha,
			dataType: 'json',
			success: function(result){
				if (result.status) {
					if (result.status=='success'){
						captha = 1;
					} else {
						captha = 0;

						$.getJSON('/ajax/captcha.php', function(data) {
				            $('#pro-reg .capcha_img img').attr('src','/bitrix/tools/captcha.php?captcha_sid='+data);
				            $('#pro-reg .captcha_sid').val(data);
				        });

						$('#pro-reg .captcha_word').css('color', 'red');
						$('#pro-reg .captcha_word').val('Неверный код');
						return false;
					}
				}
			}
		});

    	if(!$("#pro-reg .js-law").is(':checked')) {
			$("#pro-reg .law-text").slideDown();
			return false;
    	} else {
    		$("#pro-reg .law-text").hide();
    	}

		$.ajax({
			type: 'POST',
			url: '/ajax/register_pro.php',
			data: $("#pro-reg").serialize(),
			dataType: 'json',
			success: function(result){
				if (result.status) {
					if (result.status=='success'){
						$('#pro .error-message').text('');
						$('#pro .error-message').hide();

						$('#pro .pro-tab').hide();
						$('#pro .pro-f').fadeIn();
					} else {
						$("#error-message").text(result.message);
						$("#error-message").show();
					}
				}
			}
		});

    	return false;
    });

	$('#pro-reg input').on('focus', function(){
		$(this).css('color', '#a7a7a7');
		if($(this).hasClass('pass'))
			$(this).attr('type', 'password');
		if($(this).val() == 'Не менее 6 символов' || $(this).val() == 'Не совпадает с паролём' || $(this).val() == 'Заполните' || $(this).val() == 'Неверный код')
			$(this).val('');
		return false;
	});

	$('#pro-reg select').on('change', function(){
		$('#pro .error-message').slideUp();
		return false;
	});

}); // document ready
$(document).ready(function() {
    $('#form-moderate').on('click', '.js-submit-moderate', function(e){
        e.preventDefault();

        $('#form-moderate span.error-text').text('')
        $('#form-moderate span.error-email').css('color', 'red')
        $('#form-moderate .error-reset').css('color', '#000000')

        // Validation

        const name            = $('#form-moderate input.name').val().trim();
        const lname           = $('#form-moderate input.lname').val().trim();
        const sname           = $('#form-moderate input.sname').val().trim();
        const email           = $('#form-moderate input.email').val().trim();
        const password        = $('#form-moderate input.password').val().trim();
        const confirmPassword = $('#form-moderate input.confirm-password').val().trim();
        const phone           = $('#form-moderate input.phone').val().trim();
        const day             = parseInt($('#form-moderate select.day').val());
        const month           = parseInt($('#form-moderate select.month').val());
        const year            = parseInt($('#form-moderate select.year').val());
        const country         = parseInt($('#form-moderate select.country').val());
        const region          = $('#form-moderate select.region').val().trim();
        const cityId          = parseInt($('#form-moderate input.city-id').val());
        const city            = $('#form-moderate input.city').val().trim();

        const captcha_word    = $('#form-moderate .captcha_word').val();
        const captcha_sid     = $('#form-moderate .captcha_sid').val();

        if(name.length < 2){
            $('#form-moderate span.error-name').text('Введите ваше имя');
            return false;
        }

        if(lname.length < 2){
            $('#form-moderate span.error-lname').text('Введите вашу фамилию');
            return false;
        }

        if(sname.length < 2){
            $('#form-moderate span.error-sname').text('Введите ваше отчество');
            return false;
        }

        if(!validateEmailVuchebe(email)){
            $('#form-moderate span.error-email').text('Адрес электронной почты должен заканчиваться на @vuchebe.com');
            return false;
        }

        if(!validateEmail(email)){
            $('#form-moderate span.error-email').text('Адрес электронной почты введён некорректно');
            return false;
        }

        if(password.length < 6 || password.length > 10){
            $('#form-moderate span.error-password').text('Пароль должен иметь длину от 6 до 10 символов');
            return false;
        }

        const rePassword = /^[_a-zA-Z\d]+$/;
        if(!rePassword.test(password)){
            $('#form-moderate span.error-password').text('Допустимые символы для пароля _ a-z A-Z 0-9');
            return false;
        }

        if(password !== confirmPassword){
            $('#form-moderate span.error-confirm-password').text('Пароль и повтор пароля не совпадают');
            return false;
        }

        if(phone.length < 2){
            $('#form-moderate span.error-phone').text('Введите ваш телефон');
            return false;
        }

        if(!day){
            $('#form-moderate span.error-day').text('Выберите день');
            return false;
        }

        if(!month){
            $('#form-moderate span.error-month').text('Выберите месяц');
            return false;
        }

        if(!year){
            $('#form-moderate span.error-year').text('Выберите год');
            return false;
        }

        if(!country){
            $('#form-moderate span.error-country').text('Выберите страну');
            return false;
        }

        if(!region){
            $('#form-moderate span.error-region').text('Выберите регион');
            return false;
        }

        if(!cityId){
            $('#form-moderate span.error-city').text('Введите город');
            return false;
        }

        if(captcha_word.length === 0) {
            $('#form-moderate .st-captcha-input .label').hide();
            $('#form-moderate .st-captcha-input .label').text('Вы не ввели код');
            $('#form-moderate .st-captcha-input .label').css('color', 'red');
            $('#form-moderate .st-captcha-input .label').fadeIn();
            return false;
        }

        if(!$("#form-moderate .js-law").is(':checked')) {
            $("#form-moderate .law-text").slideDown();
            return false;
        } else {
            $("#form-moderate .law-text").hide();
        }

        const dataCaptcha = {
            captcha_word: captcha_word,
            captcha_sid: captcha_sid
        }

        $.ajax({
            type: 'POST',
            url: '/ajax/check_admin.php',
            data: {email, type: 'all'},
            dataType: 'json',
            success: function(result){
                if(result.status == 'success'){
                    $.ajax({
                        type: 'POST',
                        url: '/ajax/captcha_check.php',
                        data: dataCaptcha,
                        dataType: 'json',
                        success: function(resultCaptcha) {
                            if(resultCaptcha.status == 'success') {
                                $('#form-moderate').submit();
                            } else {
                                $('#form-moderate .st-captcha-input .label').hide();
                                $('#form-moderate .st-captcha-input .label').text('Неправильный код');
                                $('#form-moderate .st-captcha-input .label').css('color', 'red');
                                $('#form-moderate .st-captcha-input .label').fadeIn();
                                return false;
                            }
                        }
                    });
                } else {
                    if(result.error.email){
                        $('#form-moderate span.error-email').text(result.error.email);
                    }
                    return false;
                }
            }
        });

        return false;
    });

    $('#form-moderate').on('blur', 'input.email', function(e){
        e.preventDefault();

        const email = $('#form-moderate input.email').val().trim();

        if(!validateEmailVuchebe(email)){
            $('#form-moderate span.error-email').text('Адрес электронной почты должен заканчиваться на @vuchebe.com');
            return false;
        }

        if(!validateEmail(email)){
            $('#form-moderate span.error-email').text('Адрес электронной почты введён некорректно');
            return false;
        }

        $.ajax({
            type: 'POST',
            url: '/ajax/check_admin.php',
            data: {email, type: 'email'},
            dataType: 'json',
            success: function(result){
                if (result.status == 'success'){

                    $('#form-moderate span.error-email').css('color', 'green')
                    $('#form-moderate span.error-email').text('Ok');
                } else {
                    if(result.error.email){

                        $('#form-moderate span.error-email').css('color', 'red')
                        $('#form-moderate span.error-email').text(result.error.email);
                    }

                    return false;
                }
            }
        });

        return false;
    });

    $('#form-moderate').on('change', '.js-law', function(e){
        e.preventDefault();

        if($("#form-moderate .js-law").is(':checked')) {
            $("#form-moderate .law-text").slideUp();
        } else {
            $("#form-moderate .law-text").slideDown();
        }

        return false;
    });

    $('#form-moderate').on('change', '.country', function(e){
        e.preventDefault();

        const $this = $(this);

        const countryId = $this.val();

        if(!countryId){
            $('#form-moderate span.error-country').text('Выберите страну');
            return false;
        }

        $.ajax({
            type: 'POST',
            url: '/ajax/profile_region.php',
            data: {'country_id': countryId},
            dataType: 'json',
            success: function(result){
                if (result.status && result.status=='success') {
                    if(result.res.REGION.length > 0) {
                        let html = `<option value="0">Выберите</option>`;
                        $.each(result.res.REGION, function(i, val){
                            html += `<option value="${val}">${val}</option>`;
                        });
                        $('#form-moderate .region').empty();
                        $('#form-moderate .region').append(html);
                        $('#form-moderate .region').attr('disabled', false);
                        $('#form-moderate span.error-country').text('');
                    } else {
                        $('#form-moderate .region').empty();
                        $('#form-moderate .region').attr('disabled', true);
                        $('#form-moderate span.error-country').text('Выберите страну');

                    }
                    $('#form-moderate .city').val('');
                    $('#form-moderate .city-id').val(0);
                    $('#form-moderate .auto-complit').hide();
                    $('#form-moderate .auto-complit').empty();
                }
            }
        });

        return false;
    });

    $('#form-moderate').on('change', '.region', function(e){
        e.preventDefault();

        const $this = $(this);

        const region  = $this.val();

        if(!region){
            $('#form-moderate span.error-country').text('Выберите страну');
            return false;
        }

        $('#form-moderate .city').val('');
        $('#form-moderate .city-id').val(0);
        $('#form-moderate .auto-complit').hide();
        $('#form-moderate .auto-complit').empty();
        $('#form-moderate span.error-region').text('');

        return false;
    });

    $('#form-moderate').on('keyup', '.city', function(e){
        e.preventDefault();

        const $this = $(this);

        const country = parseInt($('#form-moderate .country').val());
        const region  = $('#form-moderate .region').val();
        const strCity = $this.val();

        if(!strCity) {
            $('#form-moderate .auto-complit').hide();
            $('#form-moderate .auto-complit').empty();
            return false;
        }

        $.ajax({
            type: 'POST',
            url: '/ajax/profile_city.php',
            data: {'country_id': country, 'region_name': region, 'str_city': strCity},
            dataType: 'json',
            success: function(result){
                if (result.status && result.status=='success') {
                    let html = '';
                    if(result.res.CITY.length > 0) {
                        let re;
                        let podstr = '';
                        let html = '';
                        $.each(result.res.CITY, function(){
                            re = new RegExp(strCity, 'i');
                            podstr = this.NAME.replace(re, '<b>' + strCity.replace(/(^|\s)\S/g, function(a) {return a.toUpperCase()}) + '</b>');
                            let original = this.NAME;
                            html += `
								<div class="item" data-id="${this.ID}" data-main-city="${this.PROPERTY_TOPCITY_VALUE}" data-capital="${this.PROPERTY_CAPITAL_VALUE}" data-original="${original}">${podstr}</div>`;
                        });
                        $('#form-moderate .auto-complit').empty();
                        $('#form-moderate .auto-complit').append(html);
                        $('#form-moderate .auto-complit').show();
                    } else {
                        $('#form-moderate .auto-complit').hide();
                        $('#form-moderate .auto-complit').empty();
                    }
                }
            }
        });

        return false;
    });

    $('#form-moderate').on('click', '.auto-complit .item', function(e){
        e.preventDefault();

        const $this = $(this);

        const cityId   = $this.data('id');
        const cityName = $this.data('original');

        $('#form-moderate .city').val(cityName);
        $('#form-moderate .auto-complit').hide();
        $('#form-moderate .auto-complit').empty();

        $('#form-moderate .city-id').val(cityId);

        $('#form-moderate span.error-city').text('');

        return false;
    });

    $('#form-moderate').on('click', '.js-submit-moderate-end', function(e){
        e.preventDefault();

        console.log('Submit')

        $('#form-moderate span.error-text').text('')
        $('#form-moderate .error-reset').css('color', '#000000')

        // Validation

        const lname   = $('#form-moderate input.lname').val().trim();
        const sname   = $('#form-moderate input.sname').val().trim();
        const phone   = $('#form-moderate input.phone').val().trim();
        const day     = parseInt($('#form-moderate select.day').val());
        const month   = parseInt($('#form-moderate select.month').val());
        const year    = parseInt($('#form-moderate select.year').val());
        const country = parseInt($('#form-moderate select.country').val());
        const region  = $('#form-moderate select.region').val().trim();
        const cityId  = parseInt($('#form-moderate input.city-id').val());
        const city    = $('#form-moderate input.city').val().trim();

        const captcha_word    = $('#form-moderate .captcha_word').val();
        const captcha_sid     = $('#form-moderate .captcha_sid').val();

        if(lname.length < 2){
            $('#form-moderate span.error-lname').text('Введите вашу фамилию');
            return false;
        }

        if(sname.length < 2){
            $('#form-moderate span.error-sname').text('Введите ваше отчество');
            return false;
        }

        if(phone.length < 2){
            $('#form-moderate span.error-phone').text('Введите ваш телефон');
            return false;
        }

        if(!day){
            $('#form-moderate span.error-day').text('Выберите день');
            return false;
        }

        if(!month){
            $('#form-moderate span.error-month').text('Выберите месяц');
            return false;
        }

        if(!year){
            $('#form-moderate span.error-year').text('Выберите год');
            return false;
        }

        if(!country){
            $('#form-moderate span.error-country').text('Выберите страну');
            return false;
        }

        if(!region){
            $('#form-moderate span.error-region').text('Выберите регион');
            return false;
        }

        if(!cityId){
            $('#form-moderate span.error-city').text('Введите город');
            return false;
        }

        if(captcha_word.length === 0) {
            $('#form-moderate .st-captcha-input .label').hide();
            $('#form-moderate .st-captcha-input .label').text('Вы не ввели код');
            $('#form-moderate .st-captcha-input .label').css('color', 'red');
            $('#form-moderate .st-captcha-input .label').fadeIn();
            return false;
        }

        if(!$("#form-moderate .js-law").is(':checked')) {
            $("#form-moderate .law-text").slideDown();
            return false;
        } else {
            $("#form-moderate .law-text").hide();
        }

        const dataCaptcha = {
            captcha_word: captcha_word,
            captcha_sid: captcha_sid
        }

        $.ajax({
            type: 'POST',
            url: '/ajax/captcha_check.php',
            data: dataCaptcha,
            dataType: 'json',
            success: function(resultCaptcha) {
                if(resultCaptcha.status == 'success') {
                    $('#form-moderate').submit();
                } else {
                    $('#form-moderate .st-captcha-input .label').hide();
                    $('#form-moderate .st-captcha-input .label').text('Неправильный код');
                    $('#form-moderate .st-captcha-input .label').css('color', 'red');
                    $('#form-moderate .st-captcha-input .label').fadeIn();
                    return false;
                }
            }
        });

        return false;
    });
});
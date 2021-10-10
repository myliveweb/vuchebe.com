$(document).ready(function() {

    let inProgress = false;

    $('.js-adduz-list').on('click', function () {

        if ($(this).hasClass('color-silver')) {
            return false;
        }

        $(".js-adduz-list").removeClass('color-silver');
        $(this).addClass('color-silver');
        filter = $(this).data('filter');

        inProgress = false;
        inProgressGetBanner = false;
        offset = 0;

        $('#banner-list .remove-banner').remove()

        lazyListUzAdmin();

        $("#page .line-adduz").hide();
        $(".line-adduz." + filter).fadeIn(1000);

        return false;
    });

    $(".js-uz-add-main").on('click', function (e) {
        e.preventDefault();

        $('.hideForm-news-edit.uz-add .name_form span').text('Добавление учебного заведения');

        var top_form = $(window).scrollTop();

        $('.hideForm-news-edit.uz-add .form-open-block').css({
            'height': $(window).height(),
            'position': 'absolute',
            'top': top_form,
        });
        $('.hideForm-news-edit.uz-add').css({ 'height': $(document).height(), });

        $('.hideForm-news-edit.uz-add select').find('option[value=0]').prop('selected', true);
        $('.hideForm-news-edit.uz-add select').find('option[value=""]').prop('selected', true);

        $('.hideForm-news-edit.uz-add input').val('');
        $('.hideForm-news-edit.uz-add input').css('color', 'black');

        $('.foneBg').css({ 'display': 'block' });

        $('#error-message-vuz-add').hide();
        $('#error-message-vuz-add').text('');

        $.getJSON('/ajax/captcha.php', function (data) {
            $('.capcha_img img').attr('src', '/bitrix/tools/captcha.php?captcha_sid=' + data);
            $('.captcha_sid').val(data);
        });

        $('.hideForm-news-edit.uz-add').fadeIn(250);
        return false;
    });

    $("#form-news-uz-add").on('click', '.js-submit-uz-add-edit', function (e) {
        e.preventDefault();

        dataform = {};

        dataform['obr'] = $('.hideForm-news-edit.uz-add .obr').val();
        dataform['country'] = $('.hideForm-news-edit.uz-add .country').val().trim();
        dataform['region'] = $('.hideForm-news-edit.uz-add .region').val().trim();
        dataform['city'] = $('.hideForm-news-edit.uz-add .city').val().trim();
        dataform['name'] = $('.hideForm-news-edit.uz-add .name').val().trim();
        dataform['adress'] = $('.hideForm-news-edit.uz-add .adress').val().trim();
        dataform['tel'] = $('.hideForm-news-edit.uz-add .tel').val().trim();
        dataform['site'] = $('.hideForm-news-edit.uz-add .site').val().trim();
        dataform['email'] = $('.hideForm-news-edit.uz-add .email').val().trim();

        let captcha_word = $('#form-news-uz-add .captcha_word').val();
        let captcha_sid = $('#form-news-uz-add .captcha_sid').val();

        if (!dataform['obr'] || !dataform['country'] || !dataform['region'] || !dataform['city'] || !dataform['name'] || !dataform['adress'] || !dataform['tel'] || !dataform['site'] || !dataform['email'] || !captcha_word) {
            $('#error-message-vuz-add').text('Все поля обязательны для заполнения');
            $('#error-message-vuz-add').show();
            return false;
        }

        let data_captcha = {};
        data_captcha['captcha_word'] = captcha_word;
        data_captcha['captcha_sid'] = captcha_sid;

        $.ajax({
            type: 'POST',
            url: '/ajax/captcha_check.php',
            data: data_captcha,
            dataType: 'json',
            success: function (result) {
                if (result.status) {
                    if (result.status == 'success') {
                        $.ajax({
                            type: 'POST',
                            url: '/ajax/add_uz_user.php',
                            data: dataform,
                            dataType: 'json',
                            success: function (result) {
                                if (result.status == 'success') {
                                    document.location.reload(true);
                                }
                            }
                        });
                    } else {
                        $('#form-news-uz-add .captcha_word').css('color', 'red');
                        $('#form-news-uz-add .captcha_word').val('Неверный код');
                        return false;
                    }
                }
            }
        });
        return false;
    });

    $(window).scroll(function () {
        /* Если высота окна + высота прокрутки больше или равны высоте всего документа и ajax-запрос в настоящий момент не выполняется, то запускаем ajax-запрос */
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 500 && !inProgress) {
            lazyListUzAdmin();
        }
    });

    function lazyListUzAdmin() {

        const type = $('#page .m-header .color-silver').data('filter');
        let load = [];
        let id = 0;
        let html = '';

        $('.line-adduz.' + type + ' .news-item').each(function() {
            id = $(this).data('id');
            load.push(id);
        });

        $.ajax({
            url: '/ajax/lazy_load_uz_admin.php',
            method: 'POST',
            data: { "type": type, "cnt": cnt, "load": load },
            beforeSend: function () {
                inProgress = true;
                inProgressGetBanner = true;
            }
        }).done(function (data) {
            data = jQuery.parseJSON(data);

            if (data.res && data.res.length > 0) {

                $.each(data.res, function () {
                    html += `
                  <div class="news-item" data-id="${this['ID']}">
                  <div class="col-12 width-sm content-right">
                    <div style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                        <div class="params-banner">Время: ${this['DATE_FORMAT']}</div>
                    `;
                    if(this['COUNTRY_ID']) {
                        html += `<div class="params-banner">Страна: ${this['COUNTRY']}</div>
                                <input type="hidden" value="${this['COUNTRY_ID']}" class="js-country-id" />
                        `;
                    } else {
                        html += `
                            <div class="params-banner input-block">Страна: 
                                <input type="text" value="${this['COUNTRY']}" class="input-add js-country" placeholder="Страна">
                                <input type="text" value="" class="input-add js-capital" placeholder="Столица">
                                <input type="text" value="" class="input-add js-region-country" placeholder="Регион">
                                <input type="text" value="" class="input-add js-utc-country" style="width: 110px;" placeholder="UTC+0:00">
                                <a class="color-silver js-uz-add-button" data-id="${this['ID']}" data-type="country" data-user="${this['AUTHOR']}" style="text-decoration: none;">добавить</a>                            
                            </div>
                        `;
                    }
                    if(this['REGION_ID']) {
                        html += `<div class="params-banner">Регион: ${this['REGION']}</div>
                                <input type="hidden" value="${this['REGION_ID']}" class="js-region-id" />
                        `;
                    } else {
                        html += `
                            <div class="params-banner">Регион:
                                <input type="text" value="${this['REGION']}" class="input-add js-region" style="width: 342px;" placeholder="Регион"> 
                            </div>
                        `;
                    }
                    if(this['CITY_ID']) {
                        html += `<div class="params-banner">Город: ${this['CITY']}</div>
                                <input type="hidden" value="${this['CITY_ID']}" class="js-city-id" />
                        `;
                    } else {
                        html += `
                            <div class="params-banner">Город:
                                <input type="text" value="${this['CITY']}" class="input-add js-city" style="width: 342px; margin-left: 7px;" placeholder="Город"> 
                                <input type="text" value="" class="input-add js-utc-city" style="width: 110px;" placeholder="UTC+0:00">
                                <a class="color-silver js-uz-add-button" data-id="${this['ID']}" data-type="city" data-user="${this['AUTHOR']}" style="text-decoration: none;${!this['COUNTRY_ID'] ? ` display: none;` : ``}">добавить</a>
                            </div>
                        `;
                    }
                    html += `
                        <div class="params-banner">Тип учебного заведения: ${this['TYPE']}</div>
                        <div class="params-banner">Название: ${this['NAME']}</div>
                        <div class="params-banner">Адрес: ${this['ADRESS']}</div>
                        <div class="params-banner">Телефон: ${this['PHONE']}</div>
                        <div class="params-banner">Сайт: <a href="${this['SITE']}" target="_blank">${this['SITE']}</a></div>
                        <div class="params-banner">E-mail: <a href="mailto:${this['EMAIL']}" target="_blank">${this['EMAIL']}</a></div>
                    </div>
                  </div>
                  <div class="params-banner-top col-12" style="margin-top: 15px; text-align: right;">
                        ${this['ADD'] !== 'Y' && this['COUNTRY_ID'] && this['REGION_ID'] && this['CITY_ID'] && this['DEL'] !== 'Y' ? `<a class="color-silver js-edit-button add" data-type="add" data-id="${this['ID']}" data-user="${this['AUTHOR']}">Добавить заведение</a>` : ``}
                        ${this['PENDING'] !== 'Y' && this['DEL'] !== 'Y' ? `<a class="color-silver js-edit-button pending" data-type="pending" data-id="${this['ID']}" data-user="${this['AUTHOR']}" data-uz-id="${this['UZ_ID']}">Ожидание</a>` : ``}
                        ${this['DEL'] !== 'Y' ? `<a class="color-silver js-edit-button del" data-type="del" data-id="${this['ID']}" data-user="${this['AUTHOR']}" data-uz-id="${this['UZ_ID']}">Удалить заявку</a>` : ``}
                  </div>           
                  </div>
                  `;
                });

                $('.line-adduz.' + type).append(html);

                inProgress = false;
                inProgressGetBanner = false;
            }
        });
    }

    $('#form-news-uz-add').on('keyup', '.country', function(e){
        e.preventDefault();

        const $this = $(this);

        $('#form-news-uz-add .country-id').val(0);
        const strCountry = $this.val();

        if(!strCountry) {
            $('#form-news-uz-add .auto-complit-country').hide();
            $('#form-news-uz-add .auto-complit-country').empty();
            return false;
        }

        $.ajax({
            type: 'POST',
            url: '/ajax/uz_get_location.php',
            data: {'str_country': strCountry, 'type': 'country'},
            dataType: 'json',
            success: function(result){
                if (result.status && result.status=='success') {
                    let html = '';
                    if(result.res.length > 0) {
                        let re;
                        let podstr = '';
                        let html = '';
                        $.each(result.res, function(){
                            re = new RegExp(strCountry, 'i');
                            podstr = this.NAME.replace(re, '<b>' + strCountry.replace(/(^|\s)\S/g, function(a) {return a.toUpperCase()}) + '</b>');
                            let original = this.NAME;
                            html += `
								<div class="item" data-id="${this.ID}" data-original="${original}">${podstr}</div>`;
                        });
                        $('#form-news-uz-add .auto-complit-country').empty();
                        $('#form-news-uz-add .auto-complit-country').append(html);
                        $('#form-news-uz-add .auto-complit-country').show();
                    } else {
                        $('#form-news-uz-add .auto-complit-country').hide();
                        $('#form-news-uz-add .auto-complit-country').empty();
                    }
                }
            }
        });

        return false;
    });

    $('#form-news-uz-add').on('click', '.auto-complit-country .item', function (e) {
        e.preventDefault();

        const $this = $(this);

        const countryId = $this.data('id');
        const countryName = $this.data('original');

        $('#form-news-uz-add .country').val(countryName);
        $('#form-news-uz-add .country-id').val(countryId);
        $('#form-news-uz-add .auto-complit-country').hide();
        $('#form-news-uz-add .auto-complit-country').empty();

        return false;
    });

    $('#form-news-uz-add').on('keyup', '.region', function(e){
        e.preventDefault();

        const $this = $(this);

        const idCountry = $('#form-news-uz-add .country-id').val();
        const strRegion = $this.val();

        if(!idCountry || !strRegion) {
            $('#form-news-uz-add .auto-complit-region').hide();
            $('#form-news-uz-add .auto-complit-region').empty();
            return false;
        }

        $.ajax({
            type: 'POST',
            url: '/ajax/uz_get_location.php',
            data: {'id_country': idCountry, 'str_region': strRegion, 'type': 'region'},
            dataType: 'json',
            success: function(result){
                if (result.status && result.status=='success') {
                    let html = '';
                    if(result.res.length > 0) {
                        let re;
                        let podstr = '';
                        let html = '';
                        $.each(result.res, function(){
                            re = new RegExp(strRegion, 'i');
                            podstr = this.PROPERTY_REGION_VALUE.replace(re, '<b>' + strRegion.replace(/(^|\s)\S/g, function(a) {return a.toUpperCase()}) + '</b>');
                            let original = this.PROPERTY_REGION_VALUE;
                            html += `
								<div class="item" data-id="${this.ID}" data-original="${original}">${podstr}</div>`;
                        });
                        $('#form-news-uz-add .auto-complit-region').empty();
                        $('#form-news-uz-add .auto-complit-region').append(html);
                        $('#form-news-uz-add .auto-complit-region').show();
                    } else {
                        $('#form-news-uz-add .auto-complit-region').hide();
                        $('#form-news-uz-add .auto-complit-region').empty();
                    }
                }
            }
        });

        return false;
    });

    $('#form-news-uz-add').on('click', '.auto-complit-region .item', function (e) {
        e.preventDefault();

        const $this = $(this);

        const regionId = $this.data('id');
        const regionName = $this.data('original');

        $('#form-news-uz-add .region').val(regionName);
        $('#form-news-uz-add .region-id').val(regionId);
        $('#form-news-uz-add .auto-complit-region').hide();
        $('#form-news-uz-add .auto-complit-region').empty();

        return false;
    });

    $('#form-news-uz-add').on('keyup', '.city', function(e){
        e.preventDefault();

        const $this = $(this);

        const idCountry = $('#form-news-uz-add .country-id').val();
        const strRegion = $('#form-news-uz-add .region').val();
        const strCity = $this.val();

        if(!idCountry || !strRegion || !strCity) {
            $('#form-news-uz-add .auto-complit-city').hide();
            $('#form-news-uz-add .auto-complit-city').empty();
            return false;
        }

        $.ajax({
            type: 'POST',
            url: '/ajax/uz_get_location.php',
            data: {'id_country': idCountry, 'str_region': strRegion, 'str_city': strCity, 'type': 'city'},
            dataType: 'json',
            success: function(result){
                if (result.status && result.status=='success') {
                    let html = '';
                    if(result.res.length > 0) {
                        let re;
                        let podstr = '';
                        let html = '';
                        $.each(result.res, function(){
                            re = new RegExp(strCity, 'i');
                            podstr = this.NAME.replace(re, '<b>' + strCity.replace(/(^|\s)\S/g, function(a) {return a.toUpperCase()}) + '</b>');
                            let original = this.NAME;
                            html += `
								<div class="item" data-id="${this.ID}" data-original="${original}">${podstr}</div>`;
                        });
                        $('#form-news-uz-add .auto-complit-city').empty();
                        $('#form-news-uz-add .auto-complit-city').append(html);
                        $('#form-news-uz-add .auto-complit-city').show();
                    } else {
                        $('#form-news-uz-add .auto-complit-city').hide();
                        $('#form-news-uz-add .auto-complit-city').empty();
                    }
                }
            }
        });

        return false;
    });

    $('#form-news-uz-add').on('click', '.auto-complit-city .item', function (e) {
        e.preventDefault();

        const $this = $(this);

        const cityId = $this.data('id');
        const cityName = $this.data('original');

        $('#form-news-uz-add .city').val(cityName);
        $('#form-news-uz-add .rcity-id').val(cityId);
        $('#form-news-uz-add .auto-complit-city').hide();
        $('#form-news-uz-add .auto-complit-city').empty();

        return false;
    });

    $('#page').on('click', '.js-uz-add-button', function (e) {
        e.preventDefault();

        const $this = $(this);

        const type = $this.data('type');
        const id = $this.data('id');
        const root = $this.closest('.news-item');

        let error = 0;

        let dataform = {};

        dataform['id'] = id;
        dataform['type'] = type;

        if(type == 'country') {
            dataform['name'] = root.find('.js-country').val();
            dataform['country_id'] = root.find('.js-country-id').val();
            if(!dataform['name'] && !dataform['country_id']) {
                root.find('.js-country').css('color', 'red');
                root.find('.js-country').val('Заполните');
                error = 1;
            }
            dataform['capital'] = root.find('.js-capital').val();
            if(!dataform['capital']) {
                root.find('.js-capital').css('color', 'red');
                root.find('.js-capital').val('Заполните');
                error = 1;
            }
            dataform['region'] = root.find('.js-region-country').val();
            if(!dataform['region']) {
                root.find('.js-region-country').css('color', 'red');
                root.find('.js-region-country').val('Заполните');
                error = 1;
            }
            dataform['utc'] = root.find('.js-utc-country').val();
            if(!dataform['utc']) {
                root.find('.js-utc-country').css('color', 'red');
                root.find('.js-utc-country').val('Заполните');
                error = 1;
            }
        } else if(type == 'city') {
            dataform['name'] = root.find('.js-city').val();
            dataform['city_id'] = root.find('.js-city-id').val();
            if(!dataform['name'] && !dataform['city_id']) {
                root.find('.js-city').css('color', 'red');
                root.find('.js-city').val('Заполните');
                error = 1;
            }
            dataform['region'] = root.find('.js-region').val();
            dataform['region_id'] = root.find('.js-region-id').val();
            if(!dataform['region'] && !dataform['region_id']) {
                root.find('.js-region').css('color', 'red');
                root.find('.js-region').val('Заполните');
                error = 1;
            }
            dataform['utc'] = root.find('.js-utc-city').val();
            if(!dataform['utc']) {
                root.find('.js-utc-city').css('color', 'red');
                root.find('.js-utc-city').val('Заполните');
                error = 1;
            }
            dataform['country_id'] = root.find('.js-country-id').val();
        }

        if(error)
            return false;

        $.ajax({
            type: 'POST',
            url: '/ajax/set_location.php',
            data: dataform,
            dataType: 'json',
            success: function(result){
                if(result.status == 'success') {
                    document.location.reload(true);
                } else {
                    console.log('error: ', result.message)
                }
            }
        });

        return false;
    });

    $('#page').on('focus', '.input-add', function (e) {
        e.preventDefault();

        const $this = $(this);

        $this.css('color', 'black');

        const str = $this.val();

        if(str == 'Заполните')
            $this.val('');

    });

    $('#page').on('click', '.js-edit-button', function (e) {
        e.preventDefault();

        const $this = $(this);

        const type = $this.data('type');
        const id = $this.data('id');
        const user = $this.data('user');

        let dataform = {};

        dataform['id'] = id;
        dataform['type'] = type;
        dataform['user'] = user;

        if(type == 'pending' || type == 'del') {
            const uzId = $this.data('uz-id');
            dataform['uz_id'] = uzId;
        }

        $.ajax({
            type: 'POST',
            url: '/ajax/uz_button.php',
            data: dataform,
            dataType: 'json',
            success: function(result){
                if(result.status == 'success') {

                    const data = result.res;
                    const root = $this.closest('.news-item');

                    root.slideUp();
                    setTimeout(function () {
                        root.remove();
                    }, 1500);

                    $('#page .m-header .js-new').text(data.NEW);
                    $('#page .m-header .js-add').text(data.ADD);
                    $('#page .m-header .js-pending').text(data.PENDING);
                    $('#page .m-header .js-del-info').text(data.DEL);
                    $('#page .m-header .js-all').text(data.ALL);

                    if(type == 'add')
                        window.open(data.url);

                } else {
                    console.log('error: ', result.message)
                }
            }
        });

        return false;
    });

    /* Форма поиска счёта у модераторов */

    $('#form-check').on('input', '.js-add-check', function (e) {
        e.preventDefault();

        const $this = $(this);

        let strCheck = $this.val().trim();

        if (strCheck.length <= 0) {
            $('#form-check .auto-complit').hide();
            $('#form-check .auto-complit').empty();
            return false;
        }

        $.ajax({
            type: 'POST',
            url: '/ajax/search_uz_check.php',
            data: { 'str_check': strCheck },
            dataType: 'json',
            success: function (result) {
                if (result.status && result.status == 'success') {
                    let html = ``;
                    if (result.data.length > 0) {
                        $.each(result.data, function () {
                            console.log(this.NAME_DISPLAY)
                            let re = new RegExp(strCheck, 'i');
                            podstr = this.NAME_DISPLAY.replace(re, '<b>$&</b>');
                            html += `
								<div style="height: 28px; padding: 10px 15px;" class="item" data-id="${this.ID}" data-origin="${this.NAME_DISPLAY}" data-type="${this.TYPE}" data-type-id="${this.TYPE_ID}">
									<div class="name">${podstr}</div>
									<div class="check">${this.TYPE}</div>
								</div>`;
                        });
                        $('#form-check .auto-complit').empty();
                        $('#form-check .auto-complit').append(html);
                        $('#form-check .auto-complit').show();
                    } else {
                        $('#form-check .auto-complit').hide();
                        $('#form-check .auto-complit').empty();
                    }
                }
            }
        });

        return false;
    });

    $('#form-check').on('click', '.auto-complit .item', function (e) {
        //e.preventDefault();

        const $this = $(this);

        const id = $this.data('id');
        const origin = $this.data('origin');
        const type = $this.data('type');
        const typeID = $this.data('type-id');

        $('#form-check .js-add-check').val(origin);
        $('#form-check #searchId').val(typeID);
        $('#form-check .auto-complit').hide();
        $('#form-check .auto-complit').empty();
        $('#form-check .js-add-check').focus();

        return false;
    });
});
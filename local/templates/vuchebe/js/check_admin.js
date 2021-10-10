$(document).ready(function() {

    let inProgress = false;
    let sortType = 'DESC';

    $('.js-check-list').on('click', function () {

        if ($(this).hasClass('color-silver')) {
            return false;
        }

        $(".js-check-list").removeClass('color-silver');
        $(this).addClass('color-silver');
        filter = $(this).data('filter');

        inProgress = false;
        inProgressGetBanner = false;
        offset = 0;

        $('#banner-list .remove-banner').remove()

        lazyListCheckAdmin();

        $("#page .line-orders").hide();
        $(".line-orders." + filter).fadeIn(1000);

        return false;
    });

    $('#page').on('click', '.js-check-sort', function () {

        sortType = $(this).data('sort');

        if(sortType === 'ASC') {
            sortType = 'DESC'
            $(this).data('sort', 'DESC');
        } else {
            sortType = 'ASC'
            $(this).data('sort', 'ASC');
        }

        filter = $('#page .m-header .color-silver').data('filter');

        inProgress = false;
        inProgressGetBanner = false;
        offset = 0;

        $('#banner-list .remove-banner').remove()

        lazyListCheckAdmin(true);

        $("#page .line-orders").hide();
        $(".line-orders." + filter).fadeIn(1000);

        return false;
    });

    if (startFromListAdmin > 0) {
        $(window).scroll(function () {
            /* Если высота окна + высота прокрутки больше или равны высоте всего документа и ajax-запрос в настоящий момент не выполняется, то запускаем ajax-запрос */
            if ($(window).scrollTop() + $(window).height() >= $(document).height() - 500 && !inProgress) {
                lazyListCheckAdmin();
            }
        });
    }

    function lazyListCheckAdmin(loadClean = false) {

        const type = $('#page .m-header .color-silver').data('filter');
        const pro = $('#page').data('type');
        let load = [];
        let id = 0;
        let html = '';

        if(loadClean) {
            $('.line-orders.' + type).empty()
        }

        $('.line-orders.' + type + ' .news-item').each(function () {
            id = $(this).data('id');
            load.push(id);
        });

        $.ajax({
            url: '/ajax/lazy_load_check_admin.php',
            method: 'POST',
            data: { type, cnt, load , 'sort': sortType, pro },
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
                    <div class="news-name">
                        Счёт №${this['ID']} от ${this['DATE_FORMAT']}
                    </div>
                    <div style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis; margin-top: 10px;">
                        <div class="params-banner">Сумма: ${ parseFloat(this['SUM']).toFixed( 2 ) } руб.</div>
                        <div class="params-banner">Статус: <span style="color: ${this['STATE_COLOR']}">${this['STATE']}</span></div>
                        <div class="params-banner">Наименование организации: <a class="name-text" style="margin-left: 7px;" href="/user/${this['URL']}/" target="_blank">${this['FORMAT_NAME']}</a></div>
                        <div class="params-banner">ОГРН: ${this['OGRN']}</div>
                        <div class="params-banner">ИНН: ${this['INN']}</div>
                        <div class="params-banner">КПП: ${this['KPP']}</div>
                        <div class="params-banner">Юр. Адрес: ${this['ADRESS']}</div>
                        <div class="params-banner">Телефон: ${this['PHONE']}</div>
                        <div class="params-banner">E-mail: ${this['EMAIL']}</div>
                    </div>
                  </div>
                  `;
                  if(this['PRO'] === 'moderate') {
                      html += `  
                          <div class="params-banner-top col-12" style="margin-top: 15px; text-align: right;">
                                ${this['PENDING'] !== 'Y' && this['PAID'] !== 'Y' ? `<a class="color-silver js-check-button add" data-type="add" data-id="${this['ID']}" data-user="${this['USER']}" data-sum="${this['SUM']}">Выставить счёт</a>` : ``}
                                ${this['PAID'] !== 'Y' ? `<a class="color-silver js-check-button pay" data-type="pay" data-id="${this['ID']}" data-user="${this['USER']}" data-sum="${this['SUM']}">Оплачен</a>` : ``}
                                <a href="/tcpdf/work/check.php?id=${this['ID']}" download="true" class="color-silver" data-type="pdf" data-id="${this['ID']}" data-user="${this['USER']}" data-sum="${this['SUM']}">Скачать PDF</a>
                                ${this['CANCEL'] !== 'Y' && this['PAID'] !== 'Y' ? `<a class="color-silver js-check-button del" data-type="del" data-id="${this['ID']}" data-user="${this['USER']}" data-sum="${this['SUM']}">Отменить счёт</a>` : ``}
                                ${this['TICKET'] ? `
                                <a href="/user/support/${this['TICKET']}/" class="color-silver" target="_blank" style="color: ${this['TICKET_COLOR']};">Тикет №${this['TICKET']}</a>
                                ` : `
                                <a class="color-silver js-new-chat" data-type="ticket" data-id="${this['ID']}" data-user="${this['USER']}">Новая заявка</a>
                                `}
                          </div>           
                          </div>
                      `;
                    } else {
                      html += `  
                          <div class="params-banner-top col-12" style="margin-top: 15px; text-align: right;">
                                <a href="/tcpdf/work/check.php?id=${this['ID']}" download="true" class="color-silver" data-type="pdf" data-id="${this['ID']}" data-user="${this['USER']}" data-sum="${this['SUM']}">Скачать PDF</a>
                                ${this['CANCEL'] !== 'Y' && this['PAID'] !== 'Y' ? `<a class="color-silver js-check-button del" data-type="del" data-id="${this['ID']}" data-user="${this['USER']}" data-sum="${this['SUM']}">Отменить счёт</a>` : ``}
                                ${this['TICKET'] ? `
                                <a href="/user/support/${this['TICKET']}/" class="color-silver" target="_blank" style="color: ${this['TICKET_COLOR']};">Тикет №${this['TICKET']}</a>
                                ` : `
                                <a class="color-silver js-new-chat" data-type="ticket" data-id="${this['ID']}" data-user="${this['USER']}">Новая заявка</a>
                                `}
                          </div>           
                          </div>
                      `;
                    }
                });

                $('.line-orders.' + type).append(html);

                inProgress = false;
                inProgressGetBanner = false;
            }
        });
    }

    $("#page").on('click', '.js-check-button', function (e) {
        e.preventDefault();

        const $this = $(this);

        const id   = $this.data('id')
        const type = $this.data('type')
        const user = $this.data('user')
        const sum  = $this.data('sum')

        if(type === 'pdf')
            return false;

        $.ajax({
            url: '/ajax/check_button.php',
            method: 'POST',
            data: { id, type, user, sum }
        }).done(function (data) {
            data = jQuery.parseJSON(data);
            if (data.status === 'success') {
                const res = data.res
                const root = $this.closest('.news-item')

                if(type === 'del') {
                    root.slideUp()
                    setTimeout(() => {
                        $this.remove()
                        root.remove()
                    }, 600)
                } else if(type === 'add') {
                    root.slideUp()
                    setTimeout(() => {
                        $this.remove()
                        root.remove()
                    }, 600)
                } else if(type === 'pay') {
                    root.slideUp()
                    setTimeout(() => {
                        $this.remove()
                        root.remove()
                    }, 600)
                } else if(type === 'pdf') {

                }

                $('#page .m-header .js-new').text(res.NEW)
                $('#page .m-header .js-pending').text(res.PENDING)
                $('#page .m-header .js-pay').text(res.PAID)
                $('#page .m-header .js-del').text(res.CANCEL)
                $('#page .m-header .js-all').text(res.ALL)

            }
        });

        return false;
    });

    $("#page").on('click', '.js-new-chat', function (e) {
        e.preventDefault();

        const $this = $(this);
        const root = $this.closest('.news-item')

        const id = $this.data('id')

        $.ajax({
            url: '/ajax/chat_check_admin.php',
            method: 'POST',
            data: { avatar: id }
        }).done(function (data) {
            data = jQuery.parseJSON(data);

            if (data.add) {
                $this.attr('href', `/user/support/${data.add.chat}/`)
                $this.attr('target', '_blank')
                $this.css('color', 'green')
                $this.text(`Тикет №${data.add.chat}`)
                $this.removeClass('js-new-chat')
                window.open(`/user/support/${data.add.chat}/`);
            }
        });

        return false;
    });

    $("#page").on('click', '.js-check-action', function (e) {
        e.preventDefault();

        const $this = $(this);

        const id   = parseInt($this.data('id'))
        const type = $this.data('type')
        const user = parseInt($this.data('user'))
        const num  = parseInt($this.text())

        if(num <= 0)
            return false;

        const title = {
            abuse:   'Предыдущие жалобы',
            warning: 'Предупреждения',
            reject:  'Отклонённые жалобы',
            del:     'Удаленные отзывы',
            ticket:  'Созданные тикеты'
        }

        let html = ``;

        /* Создание формы */
        const top_form = $(window).scrollTop();
        $('.hideForm.avatar .form-open-block').css({
            'height': $(window).height(),
            'position': 'absolute',
            'top': top_form,
        });
        $('.hideForm.avatar').css({ 'height': $(document).height(), });

        $.ajax({
            url: '/ajax/reviews_data.php',
            method: 'POST',
            data: { id, type, user }
        }).done(function (data) {
            data = jQuery.parseJSON(data);
            if (data.status === 'success') {
                const res = data.res

                $('.hideForm.avatar .name_form span').text(title[type])

                $.each(res, function () {
                    html += `
                        <div class="news-item">
                        ${this.TEACHER ? `
                            <img src="${this.PIC}" alt="img" style="border: 2px solid #ff471a;">
                            ` : `
                            <img src="${this.PIC}" alt="img">
                            `}
                            <div class="name-user" style="top: -7px;">
                                <div style="margin-bottom: 5px; font-size: 13px;">${this.DATE_FORMAT}${this.TICKET ? `<a href="/user/support/${this.TICKET}/" style="margin-left: 15px; font-size: 14px; color: ${this.TICKET_COLOR};" target="_blank">Тикет №${this.TICKET}</a>`: ``}</div>
                                <a href="/user/${this.URL}/">${this.FORMAT_NAME}</a>
                            </div>
                        </div>`
                });

                $('.hideForm.avatar #avatar-box').empty();
                $('.hideForm.avatar #avatar-box').append(html);

                /* Вывод формы на экран */
                $('.foneBg').css({ 'display': 'block' });
                $('.hideForm.avatar').fadeIn(250);
            }
        });
        return false;
    });

    /* Форма поиска счёта у модераторов */

    $('#form-check').on('input', '.js-add-check', function (e) {
        e.preventDefault();

        let $this = $(this);

        const pro = $('#page').data('type');
        let strCheck = $this.val().trim();

        if (strCheck.length <= 0) {
            $('#form-check .auto-complit').hide();
            $('#form-check .auto-complit').empty();
            return false;
        }

        $.ajax({
            type: 'POST',
            url: '/ajax/add_check.php',
            data: { 'str_check': strCheck, pro },
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
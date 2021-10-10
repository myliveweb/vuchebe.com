$(document).ready(function() {

    let inProgress = false;

    $('.js-orders-list-admin').on('click', function () {

        if ($(this).hasClass('color-silver')) {
            return false;
        }

        $(".js-orders-list-admin").removeClass('color-silver');
        $(this).addClass('color-silver');
        filter = $(this).data('filter');

        inProgress = false;
        inProgressGetBanner = false;
        offset = 0;

        $('#banner-list .remove-banner').remove()

        lazyListOrdersAdmin();

        $("#page .line-orders").hide();
        $(".line-orders." + filter).fadeIn(1000);

        return false;
    });

    if (startFromListOrdersAdmin > 0) {
        $(window).scroll(function () {
            /* Если высота окна + высота прокрутки больше или равны высоте всего документа и ajax-запрос в настоящий момент не выполняется, то запускаем ajax-запрос */
            if ($(window).scrollTop() + $(window).height() >= $(document).height() - 500 && !inProgress) {
                lazyListOrdersAdmin();
            }
        });
    }

    function lazyListOrdersAdmin() {

        const type = $('#page .m-header .color-silver').data('filter');
        let load = [];
        let id = 0;
        let html = '';

        $('.line-orders.' + type + ' .news-item').each(function (index, value) {
            id = $(this).data('id');
            load.push(id);
        });

        $.ajax({
            url: '/ajax/lazy_load_orders_admin.php',
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
                <div class="col-3 width-sm content-left" style="padding: 0;">
                  <div class="image left brd" style="width: 100%;">
                    <img style="width: 100%;" src="${this['PIC']}" alt="${this['NAME']}" title="${this['NAME']}" />
                  </div>
                  <div class="btns" style="margin-top: 10px;">
                    <a href="#" class="button js-order-start ${this['PROPERTY_LAUNCHED_VALUE'] == 'Y' ? ' active' : ''}" data-id="${this['ID']}" data-status="${this['PROPERTY_LAUNCHED_VALUE'] == 'Y' ? '1' : '0'}">
                      <span style="font-size: 18px; text-decoration-color: #ff4719;">Пуск / Стоп</span>
                    </a>
                  </div>
                </div>
                <div class="col-9 width-sm content-right">
                  <div class="news-name">
                    ${this['ARTICLE']}
                  </div>
                  <div style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                  ${ this['PROPERTY_REJECTED_VALUE'] === 'Y' ? `<div class="params-banner-top" style="white-space: normal; margin: 15px 0px 15px 0px;">Причина отказа:<div style="margin-top: 10px;">${this['PROPERTY_REASON_VALUE']}</div></div>` : ``}
                    <div class="params-banner-top">Статус заказа: <span style="${this['STATUS_STYLE']}">${this['STATUS_NAME']}</span></div>
                    <div class="params-banner">Название баннера: ${this['NAME']}</div>
                    <div class="params-banner">Ссылка: <a href="${this['PROPERTY_URL_VALUE']}" target="blank">${this['PROPERTY_URL_VALUE']}</a></div>
                    <div class="params-banner">Количество показов: ${this['PROPERTY_COUNTER_VALUE'] ? this['PROPERTY_COUNTER_VALUE'] : '0'} (из ${this['PROPERTY_LIMIT_VALUE'] ? this['PROPERTY_LIMIT_VALUE'] : '0'})</div>
                    <div class="params-banner">Количество переходов: ${this['PROPERTY_CLICK_VALUE'] ? this['PROPERTY_CLICK_VALUE'] : '0'}</div>
                    <div class="params-banner">Баннер скрыли: ${this['PROPERTY_HIDE_VALUE'] ? this['PROPERTY_HIDE_VALUE'] : '0'}</div>
                    <div class="params-banner">Тариф: <a href="#" data-tarif="${this['PLAN_CODE']}" class="color-silver js-tarif">${this['PLAN']}</a></div>
                    <div class="params-banner">Стоимость показа: ${this['PLAN_TAX']} руб.</div>
                    ${ this['PROMOCODE'] ? `<div class="params-banner">${this['STRPROMOCODE']}</div>` : ``}
                    <div class="more-info" style="display: none;"></div>
                    <div class="params-banner col-12" style="margin-top: 5px; text-align: right;">
                        <a class="color-silver js-more-info" data-id="${this['ID']}">подробнее</a>
                    </div>
                  </div>
                </div>
                <div class="params-banner-top col-12" style="margin-top: 15px; text-align: right;">                  
                ${ type === 'new' && this['DELETE'] != 'Y' ? `<a class="color-silver js-push-order" data-id="${this['ID']}">Подтвердить заказ</a>` : type === 'otklon' && this['DELETE'] != 'Y' ? `<a class="color-silver js-push-order" data-id="${this['ID']}">Разблокировать</a>` : ``}
                ${ type !== 'otklon' && this['DELETE'] != 'Y' ? `<a class="color-silver js-reject-order" data-id="${this['ID']}">Отклонить заказ</a>` : ``}
                <a class="color-silver js-info-order" data-id="${this['ID']}">Детализация заказа</a>
                ${ this['TICKET'] ? `
                    <a href="/user/support/${this['TICKET']}/" class="color-silver" target="_blank" style="color: ${this['TICKET_COLOR']};">Тикет №${this['TICKET']}</a>
                ` : this['DELETE'] != 'Y' ? `
                    <a class="color-silver js-new-chat" data-id="${this['ID']}">Новая заявка</a>
                ` : ``
                }
                 </div> 
                 <div class="params-banner-top col-12 textarea" style="margin-top: 15px; padding: 0; display: none;">
                    <div>Причина отакза:</div>
                    <textarea style="width: 100%; height: 100px; margin-top: 7px;"></textarea>
                    <div class="col-12" style="text-align: left; padding: 0; margin-top: 10px;">
                 `;
                rejected.forEach( function(currentValue) {
                    html += `
                        <a class="fast-touch" data-id="${currentValue.id}">${currentValue.name}</a>
                    `;
                });
                html += `
                    </div>
                    <div class="col-12" style="text-align: right; padding: 0; margin-top: 10px;">
                        <button type="submit" class="add-block js-moderate-bad" data-id="${this['ID']}"><span>Сохранить</span></button>
                        <a style="margin-left: 15px; text-decoration: none;" class="color-silver cancel-block">Отмена</a>
                    </div>        
                 </div>               
              </div>
              `;
            });
                $('.line-orders.' + type).append(html);

                inProgress = false;
                inProgressGetBanner = false;
            }
        });
    }

    $("#page").on('click', '.js-more-info', function (e) {
        e.preventDefault();

        const $this = $(this);
        const root = $this.parent().prev()

        if($this.hasClass('open')) {
            $this.removeClass('open')
            $this.text('подробнее')
            root.slideUp()
            return false;
        }

        if(root.find('.params-banner').length) {
            $this.addClass('open')
            $this.text('скрыть')
            root.slideDown()
            return false;
        }

        const id = $this.data('id')
        let html = '';

        $.ajax({
            url: '/ajax/orders_admin_detail.php',
            method: 'POST',
            data: { id }
        }).done(function (data) {
            data = jQuery.parseJSON(data);
            if (data.res) {
                html += `
                    <div class="params-banner" style="height: 30px;">Пользователь:
                        <a class="img-top" style="margin-left: 5px; display: inline-block;" href="/user/${data.res.URL}/" target="_blank">
                            <img class="ava" src="${data.res.AVATAR}" alt="${data.res.FULL_NAME}" style="border: 1px solid #ff5b32; border-radius: 50%;">
                        </a><a class="name-text" style="margin-left: 7px;" href="/user/${data.res.URL}/" target="_blank">${data.res.FULL_NAME}</a>
                    </div>
                    <div class="params-banner">Баланс: ${data.res.BALANCE} руб.${data.res.HOLD}</div>
                    <div class="params-banner">Дата регистрации пользователя: ${data.res.REGISTER}</div>
                    <div class="params-banner">Количество заказов: ${data.res.ACT} (${data.res.COUNT})</div>                
                `;
                root.append(html)
                $this.addClass('open')
                $this.text('скрыть')
                root.slideDown()
            }
        });

        return false;
    });

    $("#page").on('click', '.js-push-order', function (e) {
        e.preventDefault();

        const $this = $(this);
        const root = $this.parent().parent()

        const id = $this.data('id')

        $.ajax({
            url: '/ajax/moderate_ok.php',
            method: 'POST',
            data: { id }
        }).done(function (data) {
            data = jQuery.parseJSON(data);
            if (data.status === 'success') {
                $('#page span.js-new').text(data.res.NEW)
                $('#page span.js-stop').text(data.res.STOP)
                $('#page span.js-rej').text(data.res.REJ)
                root.slideUp()
            }
        });

        return false;
    });

    $("#page").on('click', '.js-reject-order', function (e) {
        e.preventDefault();

        const $this = $(this);
        const root = $this.parent().next()

        const id = $this.data('id')
        root.slideDown()
        return false;
    });

    $("#page").on('click', '.cancel-block', function (e) {
        e.preventDefault();

        const $this = $(this);

        const root = $this.parent().parent()
        const textarea = $this.parent().prev().prev()

        root.slideUp()
        textarea.val('')

        return false;
    });

    $("#page").on('click', '.js-new-chat', function (e) {
        e.preventDefault();

        const $this = $(this);

        const id = $this.data('id')

        $.ajax({
            url: '/ajax/support_chat_admin.php',
            method: 'POST',
            data: { banner: id }
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

    $("#page").on('click', '.fast-touch', function (e) {
        e.preventDefault();

        const $this = $(this);

        const id = $this.data('id')
        const textarea = $this.parent().prev()

        const prev = textarea.val()
        const next = rejected[id].title
        textarea.val(`${prev}${next}`)

        return false;
    });

    $("#page").on('click', '.js-moderate-bad', function (e) {
        e.preventDefault();

        const $this = $(this);

        const root = $this.parent().parent().parent()
        const rootTextarea = $this.parent().parent()

        const id = $this.data('id')
        const textarea = $this.parent().prev().prev()
        const text = textarea.val()

        $.ajax({
            url: '/ajax/moderate_bad.php',
            method: 'POST',
            data: { id, text }
        }).done(function (data) {
            data = jQuery.parseJSON(data);
            if (data.status === 'success') {
                $('#page span.js-new').text(data.res.NEW)
                $('#page span.js-stop').text(data.res.STOP)
                $('#page span.js-rej').text(data.res.REJ)

                rootTextarea.slideUp()
                root.slideUp()
                textarea.val('')
            }
        });

        return false;
    });

    $('#search-order').on('keyup', function(e) {
        e.preventDefault()

        const $this = $(this)

        const strValue = $this.val()
        const intValue = strValue.replace(/[\D]+/g, '')

        $this.val(intValue)

        return false
    });

    $('#form-search').on('submit', function () {

        let searchText = $('#search-order').val();
        if (searchText.length < 1) {
            $('#search-order').css('color', 'red');
            $('#search-order').val('Введите № заказа');
            return false;
        }
        $('#form-search').submit();
        return false;
    });

    $('#search-order').on('focus', function (e) {
        e.preventDefault();

        let searchText = $('#search-order').val();
        $('#search-order').css('color', '#a7a7a7');
        if (searchText == 'Введите № заказа')
            $('#search-order').val('');
        return false;
    });
});
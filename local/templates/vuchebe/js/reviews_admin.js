$(document).ready(function() {

    let inProgress = false;

    $('.js-reviews-list').on('click', function () {

        if ($(this).hasClass('color-silver')) {
            return false;
        }

        $(".js-reviews-list").removeClass('color-silver');
        $(this).addClass('color-silver');
        filter = $(this).data('filter');

        inProgress = false;
        inProgressGetBanner = false;
        offset = 0;

        $('#banner-list .remove-banner').remove()

        lazyListReviewsAdmin();

        $("#page .line-orders").hide();
        $(".line-orders." + filter).fadeIn(1000);

        return false;
    });

    if (startFromListAdmin > 0) {
        $(window).scroll(function () {
            /* Если высота окна + высота прокрутки больше или равны высоте всего документа и ajax-запрос в настоящий момент не выполняется, то запускаем ajax-запрос */
            if ($(window).scrollTop() + $(window).height() >= $(document).height() - 500 && !inProgress) {
                lazyListReviewsAdmin();
            }
        });
    }

    function lazyListReviewsAdmin() {

        const type = $('#page .m-header .color-silver').data('filter');
        let load = [];
        let id = 0;
        let html = '';

        $('.line-orders.' + type + ' .news-item').each(function() {
            id = $(this).data('id');
            load.push(id);
        });

        $.ajax({
            url: '/ajax/lazy_load_reviews_admin.php',
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
                      <div style="display: flex; width: 100%; justify-content: space-between; margin-bottom: 20px;">
                          <div style="width: 25%;">
                              <div class="image brd" style="margin: 0 auto;">
                                  <a href="${this['UZ']['DETAIL_PAGE_URL']}" target="blank">
                                      <img class="big" src="${this['UZ']['PIC']}" alt="${this['UZ']['NAME']}" title="${this['UZ']['NAME']}" style="width: 105px; padding: 3px;">
                                  </a>
                              </div>
                          </div>
                          <div style="width: 75%; margin-left: 15px;">
                              <a href="${this['UZ']['DETAIL_PAGE_URL']}" target="blank" style="font-size: 20px;">
                                ${this['UZ']['NAME']}
                              </a>
                          </div>
                      </div>                  
                    <div class="col-3 width-sm content-left" style="padding: 0;">
                      <div class="image brd rad-50" style="text-align: center; width: 100%;">
                          <a href="/user/${this['URL']}/" target="blank">
                            <img class="big" src="${this['PIC']}" alt="${this['NAME']}" title="${this['NAME']}" style="height: 111px; width: 111px;${this['TEACHER'] ? ` border: 3px solid #ff5b32;` : ``}">
                          </a>
                      </div>
                  </div>
                  <div class="col-9 width-sm content-right">
                    <div class="news-name">
                        <span><a href="/user/${this['URL']}/" class="display-name" target="blank">${this['FORMAT_NAME']}</a>
                        ${this['DATE_FORMAT_POST'] ? `
                        <span style="color: #9f9f9f; font-size: 13px; margin-left: 10px;">${this['DATE_FORMAT_POST']}</span>
                        ` : `
                        <span style="color: red; font-size: 13px; margin-left: 10px;">удалён</span></span>
                        `}
                        </span>
                    </div>
                    <div style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                        <div class="params-banner" style="height: 20px; margin-top: 15px;">Текст отзыва:</div>
                        <div class="params-banner cur">${this['DETAIL_TEXT']}</div> 
                        <div class="params-banner" style="text-align: right; margin-top: 0px;"><a href="${this['URL_POST']}" class="color-silver" target="blank">Перейти к отзыву</a></div>         
                        <div class="params-banner" style="height: 30px;">Жалоба от:
                            <a class="img-top" style="margin-left: 5px; display: inline-block;" href="/user/${this['URL_ABUSE']}/" target="_blank">
                                <img class="ava" src="${this['PIC_ABUSE']}" alt="${this['TITLE_ABUSE']}" title="${this['TITLE_ABUSE']}" style="border: 1px solid #ff5b32; border-radius: 50%;">
                            </a>
                            <a class="name-text" style="margin-left: 7px;" href="/user/${this['URL_ABUSE']}/" target="_blank">${this['FORMAT_NAME_ABUSE']}</a>
                        </div>
                        <div class="params-banner">Время: ${this['DATE_FORMAT']}</div>
                        <div class="params-banner">Предыдущие жалобы: <a href="#" class="js-reviews-action abuse" data-id="${this['ID']}" data-user="${this['AUTHOR']}" data-type="abuse">${this['TOTAL']}</a></div>
                        <div class="params-banner">Предупреждений: <a href="#" class="js-reviews-action warning" data-id="${this['ID']}" data-user="${this['AUTHOR']}" data-type="warning">${this['WARNING_CNT']}</a></div>
                        <div class="params-banner">Отклонённые жалобы: <a href="#" class="js-reviews-action reject" data-id="${this['ID']}" data-user="${this['AUTHOR']}" data-type="reject">${this['REJECT_CNT']}</a></div>
                        <div class="params-banner">Удаленные отзывы: <a href="#" class="js-reviews-action del" data-id="${this['ID']}" data-user="${this['AUTHOR']}" data-type="del">${this['DEL_CNT']}</a></div>
                        <div class="params-banner">Созданных тикетов: <a href="#" class="js-reviews-action ticket" data-id="${this['ID']}" data-user="${this['AUTHOR']}" data-type="ticket">${this['TICKET_CNT']}</a></div>
                    </div>
                  </div>
                  <div class="params-banner-top col-12" style="margin-top: 15px; text-align: right;">
                        ${this['DEL'] !== 'Y' ? `<a class="color-silver js-reviews-button del-reviews" data-type="del-reviews" data-id="${this['ID']}" data-user="${this['AUTHOR']}">Удалить отзыв</a>` : ``}
                        ${this['REJECT'] !== 'Y' ? `<a class="color-silver js-reviews-button reject" data-type="reject" data-id="${this['ID']}" data-user="${this['AUTHOR']}">Отклонить жалобу</a>` : ``}
                        ${this['WARNING'] !== 'Y' ? `<a class="color-silver js-reviews-button warning" data-type="warning" data-id="${this['ID']}" data-user="${this['AUTHOR']}">Предупреждение</a>` : ``}
                        <a class="color-silver js-reviews-button ban" data-type="ban" data-id="${this['ID']}" data-user="${this['AUTHOR']}">Бан</a>
                        <a class="color-silver js-reviews-button del-user" data-type="del-user" data-id="${this['ID']}" data-user="${this['AUTHOR']}">Удалить пользователя</a>
                        <a class="color-silver js-reviews-button deactivate" data-type="deactivate" data-id="${this['ID']}" data-user="${this['AUTHOR']}">Удалить жалобу</a>
                        ${this['TICKET'] ? `
                        <a href="/user/support/${this['TICKET']}/" class="color-silver" target="_blank" style="color: ${this['TICKET_COLOR']};">Тикет №${this['TICKET']}</a>
                        ` : `
                        <a class="color-silver js-new-chat" data-type="ticket" data-id="${this['ID']}" data-user="${this['AUTHOR']}">Новая заявка</a>
                        `}
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

    $("#page").on('click', '.js-reviews-button', function (e) {
        e.preventDefault();

        const $this = $(this);

        const id = $this.data('id')
        const type = $this.data('type')
        const user = $this.data('user')

        if(type === 'ban')
            return false;

        $.ajax({
            url: '/ajax/reviews_button.php',
            method: 'POST',
            data: { id, type, user }
        }).done(function (data) {
            data = jQuery.parseJSON(data);
            if (data.status === 'success') {
                const res = data.res
                const root = $this.closest('.news-item')

                console.log(res)
                if(type === 'del-reviews') {
                    root.find('.js-reviews-action.del').text(res.DEL_CNT)
                    $this.remove()
                } else if(type === 'reject') {
                    root.find('.js-reviews-action.reject').text(res.REJECT_CNT)
                    $this.remove()
                } else if(type === 'warning') {
                    root.find('.js-reviews-action.warning').text(res.WARNING_CNT)
                    $this.remove()
                } else if(type === 'ban') {

                } else if(type === 'del-user') {
                    $.each(res.OUT, function() {
                        $('#page .news-item[data-id=' + this + ']').slideUp()
                    });
                } else if(type === 'deactivate') {
                    root.slideUp()
                }

                $('#page .m-header .js-new').text(res.NEW)
                $('#page .m-header .js-otklon').text(res.REJECT)
                $('#page .m-header .js-warning').text(res.WARNING)
                $('#page .m-header .js-del').text(res.DEL)
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
            url: '/ajax/chat_reviews_admin.php',
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
                root.find('.js-reviews-action.ticket').text(data.add.TICKET_CNT)
                window.open(`/user/support/${data.add.chat}/`);
            }
        });

        return false;
    });

    $("#page").on('click', '.js-reviews-action', function (e) {
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
                console.log(res)
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

    /* Форма поиска аватара у модераторов */

    $('#form-reviews-avatar').on('input', '.js-add-user', function (e) {
        e.preventDefault();
        console.log('js-add-user')
        let $this = $(this);

        let strUser = $this.val().trim();

        if (strUser.length <= 0) {
            $('#form-reviews-avatar .auto-complit').hide();
            $('#form-reviews-avatar .auto-complit').empty();
            return false;
        }

        $.ajax({
            type: 'POST',
            url: '/ajax/add_user_avatar.php',
            data: { 'str_user': strUser },
            dataType: 'json',
            success: function (result) {
                if (result.status && result.status == 'success') {
                    let html = ``;
                    if (result.user.length > 0) {
                        $.each(result.user, function () {
                            let re = new RegExp(strUser, 'i');
                            podstr = this.NAME_DISPLAY.replace(re, '<b>$&</b>');
                            html += `
								<div class="item" data-id="${this.ID}" data-origin="${this.NAME_DISPLAY}" data-avatar="${this.AVATAR}" data-type="${this.TYPE}" data-online="${this.ONLINE}">
									<div class="${this.TYPE == 'teacher' ? 'teacher' : 'user'}"><img src="${this.AVATAR}" /></div>
									<div class="name">${podstr}</div>
								</div>`;
                        });
                        $('#form-reviews-avatar .auto-complit').empty();
                        $('#form-reviews-avatar .auto-complit').append(html);
                        $('#form-reviews-avatar .auto-complit').show();
                    } else {
                        $('#form-reviews-avatar .auto-complit').hide();
                        $('#form-reviews-avatar .auto-complit').empty();
                    }
                }
            }
        });

        return false;
    });

    $('#form-reviews-avatar').on('click', '.auto-complit .item', function (e) {
        //e.preventDefault();

        const $this = $(this);

        const id = $this.data('id');
        const origin = $this.data('origin');
        const avatar = $this.data('avatar');
        const type = $this.data('type');
        const online = $this.data('online');

        let load = [];
        let loadId = 0;

        $('#form-reviews-avatar .js-add-user').val(origin);
        $('#form-reviews-avatar .auto-complit').hide();
        $('#form-reviews-avatar .auto-complit').empty();
        $('#form-reviews-avatar .js-add-user').focus();

        console.log(id, origin)
        return false;

        let avatarClass = 'user-name-st';
        if (type == 'teacher')
            avatarClass = 'user-name-te';

        let html = `
      <div class="row-line mt-10 user-group-chat new" data-id="${id}" style="display: none;">
        <div class="col-12">
          <div class="${avatarClass}">
            <a href="/user/${id}/" target="_blank"><img src="${avatar}"/></a>
          </div>
          <div class="user-name-st name">
            <a href="/user/${id}/" target="_blank">${origin}</a>
          </div>
        </div>
      </div>
    `;

        $('#form-support-chat #group-user').empty();
        $('#form-support-chat #group-user').append(html);

        $('#form-support-chat .user-group-chat').each(function (index, value) {
            loadId = $(this).data('id');
            load.push(loadId);
        });

        if (load.length > 1) {
            $('#form-support-chat .js-del-user-chat').show()
            $('#form-support-chat .js-admin').attr('disabled', false)
        } else {
            $('#form-support-chat .js-del-user-chat').hide()
            $('#form-support-chat .js-admin').attr('disabled', true)
        }

        $('#form-support-chat #group-user .user-group-chat.new:last').slideDown();


        //return false;
    });
});
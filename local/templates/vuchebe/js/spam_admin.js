$(document).ready(function() {

    let inProgress = false;

    $('.js-spam-list').on('click', function () {

        if ($(this).hasClass('color-silver')) {
            return false;
        }

        $(".js-spam-list").removeClass('color-silver');
        $(this).addClass('color-silver');
        filter = $(this).data('filter');

        inProgress = false;
        inProgressGetBanner = false;
        offset = 0;

        $('#banner-list .remove-banner').remove()

        lazyListSpamAdmin();

        $("#page .line-orders").hide();
        $(".line-orders." + filter).fadeIn(1000);

        return false;
    });

    if (startFromListAdmin > 0) {
        $(window).scroll(function () {
            /* Если высота окна + высота прокрутки больше или равны высоте всего документа и ajax-запрос в настоящий момент не выполняется, то запускаем ajax-запрос */
            if ($(window).scrollTop() + $(window).height() >= $(document).height() - 500 && !inProgress) {
                lazyListSpamAdmin();
            }
        });
    }

    function lazyListSpamAdmin() {

        const type = $('#page .m-header .color-silver').data('filter');
        let load = [];
        let id = 0;
        let html = '';

        $('.line-orders.' + type + ' .news-item').each(function() {
            id = $(this).data('id');
            load.push(id);
        });

        $.ajax({
            url: '/ajax/lazy_load_spam_admin.php',
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
                    <div data-res="${this['POST']}" class="chat-left">
                        <div class="message_chat_wrapper">
                            <div class="message_chat_user">
                                <a href="/user/${this['URL']}/" class="display-name" target="blank">${this['FORMAT_NAME']}</a> <span style="color: gray; font-size: 11px;">${this['POST_TIME']}</span>
                                ${this['ONLINE'] ? `<div style="display: inline-block; position: relative; top: -1px; margin-left: 2px; width: 8px; height: 8px; border-radius: 50%; background-color: #ff471a;" title="В сети"></div>` : ``}
                            </div>
                            <img class="avatar_chat" src="${this['PIC']}" ${this['TEACHER'] ? `style="border: 2px solid #ff5b32;"` : `style="border: 1px solid #ff5b32;"`}>
                            <img style="right: -2px; z-index: 1;" class="avatar_duz" src="/upload/main/ug_left_3.png">
                            <div class="message_chat" style="margin-left: -4px; position: relative;"><div class="del-mes-left js-spam-button del-post" style="bottom: -1px; right: -137px;" data-type="del-post" data-id="${this['ID']}" data-user="${this['AUTHOR']}">удалить сообщение</div>${this['MESSAGE']}</div>
                        </div>
                    </div>                  
                    <div class="col-3 width-sm content-left" style="padding: 0;">
                      <div class="image brd rad-50" style="text-align: center; width: 100%;"></div>
                  </div>
                  <div class="col-9 width-sm content-right">
                    <div style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                        <div class="params-banner" style="height: 30px;">Жалоба от:
                            <a class="img-top" style="margin-left: 5px; display: inline-block;" href="/user/${this['URL_ABUSE']}/" target="_blank">
                                <img class="ava" src="${this['PIC_ABUSE']}" alt="${this['TITLE_ABUSE']}" title="${this['TITLE_ABUSE']}" style="border: 1px solid #ff5b32; border-radius: 50%;">
                            </a>
                            <a class="name-text" style="margin-left: 7px;" href="/user/${this['URL_ABUSE']}/" target="_blank">${this['FORMAT_NAME_ABUSE']}</a>
                        </div>
                        <div class="params-banner">Время: ${this['DATE_FORMAT']}</div>
                        <div class="params-banner">Предыдущие жалобы: <a href="#" class="js-spam-action abuse" data-id="${this['ID']}" data-user="${this['AUTHOR']}" data-type="abuse">${this['TOTAL']}</a></div>
                        <div class="params-banner">Предупреждений: <a href="#" class="js-spam-action warning" data-id="${this['ID']}" data-user="${this['AUTHOR']}" data-type="warning">${this['WARNING_CNT']}</a></div>
                        <div class="params-banner">Отклонённые жалобы: <a href="#" class="js-spam-action reject" data-id="${this['ID']}" data-user="${this['AUTHOR']}" data-type="reject">${this['REJECT_CNT']}</a></div>
                        <div class="params-banner">Удаление аватара: <a href="#" class="js-spam-action del" data-id="${this['ID']}" data-user="${this['AUTHOR']}" data-type="del">${this['DEL_AVATAR_CNT']}</a></div>
                        <div class="params-banner">Созданных тикетов: <a href="#" class="js-spam-action ticket" data-id="${this['ID']}" data-user="${this['AUTHOR']}" data-type="ticket">${this['TICKET_CNT']}</a></div>
                    </div>
                  </div>
                  <div class="params-banner-top col-12" style="margin-top: 15px; text-align: right;">
                        ${this['AVATAR'] === 'Y' ? `<a class="color-silver js-spam-button del-avatar" data-type="del-avatar" data-id="${this['ID']}" data-user="${this['AUTHOR']}">Удалить аватар</a>` : ``}
                        ${this['REJECT'] !== 'Y' ? `<a class="color-silver js-spam-button reject" data-type="reject" data-id="${this['ID']}" data-user="${this['AUTHOR']}">Отклонить жалобу</a>` : ``}
                        ${this['WARNING'] !== 'Y' ? `<a class="color-silver js-spam-button warning" data-type="warning" data-id="${this['ID']}" data-user="${this['AUTHOR']}">Предупреждение</a>` : ``}
                        <a class="color-silver js-spam-button ban" data-type="ban" data-id="${this['ID']}" data-user="${this['AUTHOR']}">Бан</a>
                        <a class="color-silver js-spam-button del-user" data-type="del-user" data-id="${this['ID']}" data-user="${this['AUTHOR']}">Удалить пользователя</a>
                        ${this['CHAT'] !== 'Y' ? `<a class="color-silver js-chat-button show-chat" data-type="show-chat" data-id="${this['ID']}" data-user="${this['AUTHOR']}" data-post="${this['POST']}" data-ticket="${this['TICKET']}" data-color="${this['TICKET_COLOR']}">Открыть чат</a>` : ``}
                        ${this['DEL'] !== 'Y' ? `<a class="color-silver js-spam-button del-post" data-type="del-post" data-id="${this['ID']}" data-user="${this['AUTHOR']}" data-post="${this['POST']}">Удалить сообщение</a>` : ``}
                        ${this['CHAT'] !== 'Y' ? `<a class="color-silver js-spam-button del-chat" data-type="del-chat" data-id="${this['ID']}" data-user="${this['AUTHOR']}" data-post="${this['POST']}">Удалить чат</a>` : ``}
                        <a class="color-silver js-spam-button deactivate" data-type="deactivate" data-id="${this['ID']}" data-user="${this['AUTHOR']}">Удалить жалобу</a>
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

    $("#page").on('click', '.js-spam-button', function (e) {
        e.preventDefault();

        const $this = $(this);

        const id = $this.data('id')
        const type = $this.data('type')
        const user = $this.data('user')
        const post = $this.data('post')

        if(type === 'ban')
            return false;

        $.ajax({
            url: '/ajax/spam_button.php',
            method: 'POST',
            data: { id, type, user, post }
        }).done(function (data) {
            data = jQuery.parseJSON(data);
            if (data.status === 'success') {
                const res = data.res
                const root = $this.closest('.news-item')

                console.log(res)
                if(type === 'del-avatar') {
                    root.find('.js-spam-action.del').text(res.DEL_AVATAR_CNT)
                    $this.remove()
                    root.find('img.avatar_chat').attr('src', res.PIC)
                } else if(type === 'reject') {
                    root.find('.js-spam-action.reject').text(res.REJECT_CNT)
                    $this.remove()
                    root.slideUp()
                } else if(type === 'warning') {
                    root.find('.js-spam-action.warning').text(res.WARNING_CNT)
                    $this.remove()
                    root.slideUp()
                } else if(type === 'ban') {

                } else if(type === 'del-user') {
                    $.each(res.OUT, function() {
                        $('#page .news-item[data-id=' + this + ']').slideUp()
                    });
                } else if(type === 'del-post') {
                    $this.remove()
                    root.slideUp()
                } else if(type === 'del-chat') {
                    $this.remove()
                    root.slideUp()
                    $('.hideForm.spam, .foneBg').fadeOut(250);
                }  else if(type === 'deactivate') {
                    root.slideUp()
                }

                $('#page .m-header .js-new').text(res.NEW)
                $('#page .m-header .js-otklon').text(res.REJECT)
                $('#page .m-header .js-warning').text(res.WARNING)
                $('#page .m-header .js-del-post').text(res.DEL)
                $('#page .m-header .js-all').text(res.ALL)

            }
        });

        return false;
    });

    $("#page, #button-box").on('click', '.js-new-chat', function (e) {
        e.preventDefault();

        const $this = $(this);
        const root = $this.closest('.news-item')

        const id = $this.data('id')

        $.ajax({
            url: '/ajax/chat_spam_admin.php',
            method: 'POST',
            data: { id }
        }).done(function (data) {
            data = jQuery.parseJSON(data);

            if (data.add) {
                $this.attr('href', `/user/support/${data.add.chat}/`)
                $this.attr('target', '_blank')
                $this.css('color', 'green')
                $this.text(`Тикет №${data.add.chat}`)
                $this.removeClass('js-new-chat')
                root.find('.js-avatar-action.ticket').text(data.add.TICKET_CNT)
                root.find('.js-chat-button').data('ticket', data.add.chat)
                root.find('.js-chat-button').data('color', 'green')
                window.open(`/user/support/${data.add.chat}/`);
            }
        });

        return false;
    });

    $("#page").on('click', '.js-spam-action', function (e) {
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
            del:     'Удаленые аватары',
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
            url: '/ajax/spam_data.php',
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

    $('#form-search-avatar').on('input', '.js-add-user', function (e) {
        e.preventDefault();

        let $this = $(this);

        let strUser = $this.val().trim();

        if (strUser.length <= 0) {
            $('#form-search-avatar .auto-complit').hide();
            $('#form-search-avatar .auto-complit').empty();
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
                        $('#form-search-avatar .auto-complit').empty();
                        $('#form-search-avatar .auto-complit').append(html);
                        $('#form-search-avatar .auto-complit').show();
                    } else {
                        $('#form-search-avatar .auto-complit').hide();
                        $('#form-search-avatar .auto-complit').empty();
                    }
                }
            }
        });

        return false;
    });

    $('#form-search-avatar').on('click', '.auto-complit .item', function (e) {
        //e.preventDefault();

        const $this = $(this);

        const id = $this.data('id');
        const origin = $this.data('origin');
        const avatar = $this.data('avatar');
        const type = $this.data('type');
        const online = $this.data('online');

        let load = [];
        let loadId = 0;

        $('#form-search-avatar .js-add-user').val(origin);
        $('#form-search-avatar .auto-complit').hide();
        $('#form-search-avatar .auto-complit').empty();
        $('#form-search-avatar .js-add-user').focus();


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

    $("#page").on('click', '.js-chat-button', function (e) {
        e.preventDefault();

        const $this = $(this);

        const id     = parseInt($this.data('id'))
        const type   = $this.data('type')
        const user   = parseInt($this.data('user'))
        const post   = parseInt($this.data('post'))
        const ticket = parseInt($this.data('ticket'))
        const color  = $this.data('color')

        let html = ``;

        /* Создание формы */
        const top_form = $(window).scrollTop();
        $('.hideForm.spam .form-open-block').css({
            'height': $(window).height(),
            'position': 'absolute',
            'top': top_form,
        });
        $('.hideForm.spam').css({ 'height': $(document).height(), });

        $.ajax({
            url: '/ajax/spam_chat.php',
            method: 'POST',
            data: { id, type, user, post }
        }).done(function (data) {
            data = jQuery.parseJSON(data);
            if (data.status === 'success') {
                const res = data.res

                let timeLine = false
                $.each(res, function () {

                    if (data.line < this.date && !timeLine) {
                        html += `
                            <div class="line-today" id="time-line" style="height: 1px; border-top: 1px solid #ff4719; position: relative; top: 0px; text-align: center; margin-top: 35px;">
                                <div style="display: inline-block; padding: 5px 15px; background-color: #ffffff; position: relative; top: -14px;">Сегодня</div>
                            </div>`
                        timeLine = true
                    }
                    if(this.side == 'left') {
                        html += `
                            <div data-res="${this.id}" class="chat-left online-user-${this.owner} all">
                                <div class="message_chat_wrapper">
                                    <div class="message_chat_user">
                                        <a href="/user/${this.url}/">${this.formatName}</a> <span style="color: gray;">${this.dateFormat}</span>
                                        ${this.online ? '<div style="display: inline-block; position: relative; top: -1px; margin-left: 2px; width: 8px; height: 8px; border-radius: 50%; background-color: #ff471a;" title="В сети"></div>' : ''}
                                    </div>
                                    <img class="avatar_chat" src="${this.pic}" ${this.teacher ? 'style="border: 2px solid #ff5b32;"' : 'style="border: 1px solid #ff5b32;"'} />
                                    <img style="right: -6px; z-index: 1;" class="avatar_duz" src="/upload/main/ug_left_3.png">
                                    <div class="message_chat" style="margin-left: 1px; position: relative;">${this.spam ? `<div style="bottom: 9px; right: -48px; color: red; position: absolute; font-size: 14px; cursor: default;">СПАМ</div>` : ``}${this.message}</div>
                                </div>
                            </div>`
                    } else if(this.side == 'sys') {
                        html += `
                            <div data-res="${this.id}" class="chat-left all system">
                                <div class="message_chat_wrapper">
                                    <div class="message-chat-system"><a href="/user/${this.urlSys}/" target="_blank">${this.fullNameSys}</a> ${this.message}</div>
                                </div>
                            </div>                        
                        `
                    } else {
                        html += `
                            <div data-res="${this.id}" class="chat-right online-user-${this.owner} all">
                                <div class="message_chat_wrapper" style="position: relative;">
                                    <div class="message_chat_user">
                                        <a href="/user/${this.url}/">Я</a> <span style="color: gray;">${this.dateFormat}</span>
                                    </div>
                                    <div class="message_chat" style="margin-right: 2px; position: relative;">${this.spam ? `<div style="bottom: 9px; left: -48px; color: red; position: absolute; font-size: 14px; cursor: default;">СПАМ</div>` : ``}${this.message}</div>
                                    <img style="right: 7px;" class="avatar_duz" src="/upload/main/ug_right_3.png">
                                    <img class="avatar_chat" src="${this.pic}" ${this.teacher ? 'style="border: 2px solid #ff5b32;"' : 'style="border: 1px solid #ff5b32;"'} />
                                </div>
                            </div>`
                    }
                });
                $('#chat').empty();
                $('#chat').append(html);

                $('#button-box .js-spam-button').data('id', id)
                $('#button-box .js-spam-button').data('user', user)
                $('#button-box .js-spam-button').data('post', post)

                const ticketBlock = $('#button-box .js-new-support')
                if(ticket) {
                    ticketBlock.attr('href', `/user/support/${ticket}/`)
                    ticketBlock.attr('target', '_blank')
                    ticketBlock.css('color', color)
                    ticketBlock.text(`Тикет №${ticket}`)
                    ticketBlock.removeClass('js-new-chat')
                } else {
                    ticketBlock.addClass('js-new-chat')
                    ticketBlock.data('id', id)
                    ticketBlock.data('user', user)
                    ticketBlock.text('Новая заявка')
                    ticketBlock.css('color', '#9f9f9f')
                }

                /* Вывод формы на экран */
                $('.foneBg').css({ 'display': 'block' });
                $('.hideForm.spam').fadeIn(250);

                $("#chat").animate({ scrollTop: $("#chat").prop("scrollHeight") }, "slow");
            }
        });
        return false;
    });

    $("#button-box").on('click', '.js-spam-button', function (e) {
        e.preventDefault();

        const $this = $(this);

        const id = $this.data('id')
        const type = $this.data('type')
        const user = $this.data('user')
        const post = $this.data('post')

        if(type === 'ban')
            return false;

        $.ajax({
            url: '/ajax/spam_button.php',
            method: 'POST',
            data: { id, type, user, post }
        }).done(function (data) {
            data = jQuery.parseJSON(data);
            if (data.status === 'success') {
                const res = data.res
                const root = $('#page .news-item[data-id=' + id + ']')

                if(type === 'del-avatar') {
                    root.find('.js-spam-action.del').text(res.DEL_AVATAR_CNT)
                    $this.remove()
                    $('#chat .all[data-res=' + post + ']').find('img.avatar_chat').attr('src', res.PIC)
                    root.find('img.avatar_chat').attr('src', res.PIC)
                } else if(type === 'reject') {
                    root.find('.js-spam-action.reject').text(res.REJECT_CNT)
                    $this.remove()
                    $('.hideForm.spam, .foneBg').fadeOut(250);
                } else if(type === 'warning') {
                    root.find('.js-spam-action.warning').text(res.WARNING_CNT)
                    $this.remove()
                    $('.hideForm.spam, .foneBg').fadeOut(250);
                } else if(type === 'ban') {

                } else if(type === 'del-user') {
                    $.each(res.OUT, function() {
                        $('#page .news-item[data-id=' + this + ']').slideUp()
                    });
                    $('.hideForm.spam, .foneBg').fadeOut(250);
                } else if(type === 'del-post') {
                    $this.remove()
                    $('.hideForm.spam, .foneBg').fadeOut(250);
                } else if(type === 'del-chat') {
                    $('#page .news-item[data-id=' + id + ']').slideUp()
                    $this.remove()
                    root.slideUp()
                    $('.hideForm.spam, .foneBg').fadeOut(250);
                    close_form();
                } else if(type === 'deactivate') {
                    root.slideUp()
                    $('.hideForm.spam, .foneBg').fadeOut(250);
                }

                $('#page .m-header .js-new').text(res.NEW)
                $('#page .m-header .js-otklon').text(res.REJECT)
                $('#page .m-header .js-warning').text(res.WARNING)
                $('#page .m-header .js-del-post').text(res.DEL)
                $('#page .m-header .js-all').text(res.ALL)
            }
        });

        return false;
    });
});
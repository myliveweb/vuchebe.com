$(document).ready(function() {
    $("#chat").animate({ scrollTop: $("#chat").prop("scrollHeight") }, "slow");
    var timerId = setInterval(chat_loop_support, 4000);

    $('#form-support-chat-post').on('submit', function(e){
        e.preventDefault();

        $("#error-message").hide();

        const chat    = parseInt($('#form-support-chat-post .chat-id').val(), 10);
        const owner   = parseInt($('#form-support-chat-post .owner-id').val(), 10);
        const user    = parseInt($('#form-support-chat-post .user-id').val(), 10);
        const message = $('#form-support-chat-post .message').val().trim();

        if(owner <= 0){
            $("#error-message").text('Ошибка чата. Перезагрузите страницу.');
            $("#error-message").show();
            return false;
        }

        if(message.length == 0){
            $('#form-support-chat-post .message').css('color', 'red');
            $('#form-support-chat-post .message').val('Введите текст сообщения');
            return false;
        }

        $.ajax({
            type: 'POST',
            url: '/ajax/chat_support_post.php',
            data: $("#form-support-chat-post").serialize(),
            dataType: 'json',
            success: function(result){
                if (result.status) {
                    if (result.status == 'success') {
                        if(!chat) {
                            document.location = `/user/support/${result.add.chat}/`
                        } else {
                            $('#form-support-chat-post .message').val('');
                            $('#form-support-chat-post .message').focus();

                            let html = ''
                            const timeLine = $('div').is('#time-line')

                            if (!timeLine) {
                                html += `
								<div class="line-today" id="time-line" style="height: 1px; border-top: 1px solid #ff4719; position: relative; top: 0px; text-align: center; margin-top: 35px;">
									<div style="display: inline-block; padding: 5px 15px; background-color: #ffffff; position: relative; top: -14px;">Сегодня</div>
								</div>
							`;
                            }

                            let displayNone = '';

                            html += '<div data-res="' + result.add.id + '" class="chat-right all' + result.add.class + '"' + displayNone + '>';
                            html += '<div class="message_chat_wrapper" style="position: relative;">';
                            html += '<div class="message_chat_user">';
                            html += '<a href="/user/' + user + '/">Я</a> <span>' + result.add.time + '</span>';
                            html += '</div>';
                            if (result.add.color) {
                                //html += '<div class="message_chat" style="margin-right: 6px; position: relative;"><div class="del-mes-right js-del" style="bottom: 12px; left: -72px;" data-type="bookmark" data-id="' + result.add.id + '" data-pos="right">в закадки</div><div class="del-mes-right js-del-support-post" style="bottom: -1px; left: -60px;" data-type="post" data-id-post="' + result.add.id + '" data-owner="' + result.add.user_id + '" data-chat="' + result.add.chat + '">удалить</div>' + result.add.message + '</div>';
                                html += '<div class="message_chat" style="margin-right: 6px; position: relative;"><div class="del-mes-right js-del-support-post" style="bottom: -1px; left: -60px;" data-type="post" data-id-post="' + result.add.id + '" data-owner="' + result.add.user_id + '" data-chat="' + result.add.chat + '">удалить</div>' + result.add.message + '</div>';
                                html += '<img style="right: 7px;" class="avatar_duz" src="/upload/main/ug_right_3.png">';
                            } else {
                                //html += '<div id="chat-id-' + result.add.id + '" class="message_chat no_show_ajax no_show" data-id="' + result.add.id + '"><div class="del-mes-right js-del" style="bottom: 12px; left: -72px;" data-type="bookmark" data-id="' + result.add.id + '" data-pos="right">в закадки</div><div class="del-mes-right js-del-support-post" style="bottom: -1px; left: -60px;" data-type="post" data-id-post="' + result.add.id + '" data-owner="' + result.add.user_id + '" data-chat="' + result.add.chat + '">удалить</div>' + result.add.message + '</div>';
                                html += '<div id="chat-id-' + result.add.id + '" class="message_chat no_show_ajax no_show" data-id="' + result.add.id + '"><div class="del-mes-right js-del-support-post" style="bottom: -1px; left: -60px;" data-type="post" data-id-post="' + result.add.id + '" data-owner="' + result.add.user_id + '" data-chat="' + result.add.chat + '">удалить</div>' + result.add.message + '</div>';
                                html += '<img style="right: 7px;" class="avatar_duz" src="/upload/main/ug_right_3_no.png">';
                            }
                            if (result.add.teacher) {
                                html += '<img class="avatar_chat" style="border: 2px solid #ff5b32;" src="' + result.add.avatar + '" />';
                            } else {
                                html += '<img class="avatar_chat" src="' + result.add.avatar + '" />';
                            }
                            html += '</div>';
                            html += '</div>';
                            $('#chat').append(html);
                            calculateGroupUser()
                            $("#chat").animate({scrollTop: $("#chat").prop("scrollHeight")}, "slow");
                        }
                    } else {
                        $("#error-message").text(result.message);
                        $("#error-message").show();
                    }
                }
            }
        });

        return false;
    });

    $('#form-support-chat-post .message').on('focus', function(){
        $(this).css('color', '#000');
        if($(this).val() == 'Введите текст сообщения')
            $(this).val('');
        return false;
    });

    $('#chat, #form-support-chat-post, .line').on('click', '.js-support-chat-close', function(e) {
        e.preventDefault();

        const $this = $(this);

        const id   = parseInt($this.data('id'))
        const chat = parseInt($this.data('chat-id'))

        $.ajax({
            type: 'POST',
            url: '/ajax/close_support_chat.php',
            data: {id, chat, type: 'close'},
            dataType: 'json',
            success: function(result){
                if(result.status == 'success') {
                    document.location = `/user/${ result.return }/service/`;
                }
            }
        });

        return false;
    });

    $('#chat, #form-support-chat-post, .line').on('click', '.js-support-chat-delete', function(e) {
        e.preventDefault();

        const $this = $(this);

        const id   = parseInt($this.data('id'))
        const chat = parseInt($this.data('chat-id'))

        $.ajax({
            type: 'POST',
            url: '/ajax/close_support_chat.php',
            data: {id, chat, type: 'delete'},
            dataType: 'json',
            success: function(result){
                if(result.status == 'success') {
                    document.location = `/user/${ result.return }/service/`;
                }
            }
        });

        return false;
    });

    $('#chat, #form-group-chat').on('click', '.js-del-support-post', function(e) {
        e.preventDefault();

        const $this = $(this);

        const id    = parseInt($this.data('id-post'))
        const chat  = parseInt($this.data('chat'))
        const owner = parseInt($this.data('owner'))

        $.ajax({
            type: 'POST',
            url: '/ajax/del_user_support_post.php',
            data: {id, chat, owner},
            dataType: 'json',
            success: function(result){
                if(result.status == 'success') {
                    if(id) {
                        const el = $this.closest('.all')
                        el.removeClass('all admin user teacher')
                        el.slideUp()
                        //calculateGroupUser()
                    } else {
                        document.location.reload(true);
                    }
                }
            }
        });

        return false;
    });

    /*** Создание нового тикета административной группой ***/

    $('.add-support-chat').click(function (e) {
        e.preventDefault();

        const $this = $(this);

        $('.hideForm-support-chat .form-open-block form .warning-text').hide();

        const top_form = $(window).scrollTop();

        $('.hideForm-support-chat .form-open-block').css({
            'height': $(window).height(),
            'position': 'absolute',
            'top': top_form,
        });
        $('.hideForm-support-chat').css({ 'height': $(document).height(), });

        $('#form-support-chat .js-name').val('')
        $('#form-support-chat .js-add-user').val('')
        $('#form-support-chat .auto-complit').hide()
        $('#form-support-chat .auto-complit').empty()

        $('#form-support-chat .js-error-block').hide()

        $('#form-support-chat .js-del-user-chat').hide()
        $('#form-support-chat .js-admin').attr('disabled', true)

        $('#form-support-chat .owner').removeClass('new')
        $('#form-support-chat .owner').addClass('user-group-chat')

        $('#form-support-chat .user-group-chat.new').remove()

        $('#form-support-chat .user-group-chat.owner').show()


        $('.foneBg').css({ 'display': 'block' });

        $('.hideForm-support-chat').fadeIn(250);

        return false;
    });

    $('#form-support-chat').on('input change', '.js-add-user', function (e) {
        e.preventDefault();

        let $this = $(this);

        let strUser = $this.val();

        if (!strUser) {
            $('#form-support-chat .auto-complit').hide();
            $('#form-support-chat .auto-complit').empty();
            return false;
        }

        let book = 0;

        if ($('#form-support-chat .js-in-book').is(':checked'))
            book = 1;

        let load = [];

        $('#form-support-chat .user-group-chat').each(function (index, value) {
            id = $(this).data('id');
            load.push(id);
        });

        $.ajax({
            type: 'POST',
            url: '/ajax/add_user.php',
            data: { 'str_user': strUser, 'load': load, 'book': book },
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
									<div class="book${this.BOOK ? '' : ' hide'}">в закладках</div>
								</div>`;
                        });
                        $('#form-support-chat .auto-complit').empty();
                        $('#form-support-chat .auto-complit').append(html);
                        $('#form-support-chat .auto-complit').show();
                    } else {
                        $('#form-support-chat .auto-complit').hide();
                        $('#form-support-chat .auto-complit').empty();
                    }
                }
            }
        });

        return false;
    });

    $('#form-support-chat').on('change', '.js-in-book', function () {
        $('#form-support-chat .js-add-user').change()
    });

    $('#form-support-chat').on('click', '.auto-complit .item', function (e) {
        //e.preventDefault();

        const $this = $(this);

        const id = $this.data('id');
        const origin = $this.data('origin');
        const avatar = $this.data('avatar');
        const type = $this.data('type');
        const online = $this.data('online');

        let load = [];
        let loadId = 0;

        $('#form-support-chat .js-add-user').val('');
        $('#form-support-chat .auto-complit').hide();
        $('#form-support-chat .auto-complit').empty();
        $('#form-support-chat .js-add-user').focus();

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

    $('#form-support-chat').on('click', '.js-submit-group-chat', function (e) {
        e.preventDefault();

        const $this = $(this);

        $('#form-support-chat .js-error-block').hide()

        const id = parseInt($this.data('id'))
        const owner = parseInt($this.data('owner'))

        let users = []
        let userId = 0

        $('#form-support-chat .user-group-chat').each(function (index, value) {
            userId = $(this).data('id')
            users.push(userId)
        });

        if (users.length <= 0) {
            $('#form-support-chat .js-error-group-user').text('Необходим хотя бы один член группы')
            $('#form-support-chat .js-error-group-user').show()
            return false
        }


        $.ajax({
            type: 'POST',
            url: '/ajax/support_chat_admin.php',
            data: { id, owner, users },
            dataType: 'json',
            success: function (result) {
                if (result.status == 'success') {

                    const message = `Тикет №${ result.add.chat } успешно создан.`

                    const html = `
					<div class="row-line" style="margin: 50px 0;">
					  <div class="col-12">
						<div style="font-size: 24px; line-height: 1.3; color: green; text-align: center;">
						  ${message}<br/>
						</div>
					  </div>
					</div>
				  `;

                    $('#form-support-chat .row-line').remove();
                    $('#form-support-chat #error-message-setting').after(html);

                    setTimeout(function() {
                        document.location = `https://vuchebe.com/user/support/${ result.add.chat }/`;
                    }, 2000);

                } else if (result.status == 'error') {
                    $('#form-support-chat .js-error-name').text(result.message)
                    $('#form-support-chat .js-error-name').show()
                }
            }
        });

        return false;
    });
});

function chat_loop_support() {

    const group = parseInt($('#form-support-chat-post .chat-id').val());

    let load = [];
    let id = 0;

    let html = ``;
    let displayNone = ``;

    $('#chat .all').each(function (index, value) {
        id = $(this).data('res');
        load.push(id);
    });

    $.ajax({
        type: 'POST',
        url: '/ajax/chat_update_support.php',
        data: { load, group },
        dataType: 'json',
        success: function(result){
            if (result.status) {
                if (result.status=='success'){
                    if(result.update.length > 0) {
                        console.log(result.update)
                        $.each(result.update, function() {

                            const timeLine = $('div').is('#time-line')

                            if(!timeLine) {
                                html += `
								<div class="line-today" id="time-line" style="height: 1px; border-top: 1px solid #ff4719; position: relative; top: 0px; text-align: center; margin-top: 35px;">
									<div style="display: inline-block; padding: 5px 15px; background-color: #ffffff; position: relative; top: -14px;">Сегодня</div>
								</div>
							`;
                            }

                            html += `
								<div data-res="${ this.id }" class="chat-left all${ this.class }"${ displayNone }>
									<div class="message_chat_wrapper">
										<div class="message_chat_user">
											<a href="/user/${ this.userid }/">${ this.displayname }</a> <span style="color: gray;">${ this.time }</span>
											${ this.online ? '<div style="display: inline-block; position: relative; top: -1px; margin-left: 2px; width: 8px; height: 8px; border-radius: 50%; background-color: #ff471a;" title="В сети"></div>' : '' }
										</div>
										<img class="avatar_chat" src="${ this.avatar }" ${ this.teacher ? 'style="border: 2px solid #ff5b32;"' : 'style="border: 1px solid #ff5b32;"' } />
										<img style="right: -7px; z-index: 1;" class="avatar_duz" src="/upload/main/ug_left_3.png">
										<div class="message_chat" style="margin-left: 1px; position: relative;"><div class="del-mes-left js-del-support-post" style="bottom: -1px; right: -60px;" data-type="post" data-id-post="${ this.id }" data-owner="${ this.userid }" data-chat="${ group }">удалить</div>${ this.message }</div>
									</div>
								</div>`;
                            $('#chat').append(html);
                        });
                        calculateGroupUser()
                        $("#chat").animate({ scrollTop: $("#chat").prop("scrollHeight") }, "slow");
                    }
                } else {
                    $("#error-message").text(result.message);
                    $("#error-message").show();
                }
            }
        }
    });
    return false;
}
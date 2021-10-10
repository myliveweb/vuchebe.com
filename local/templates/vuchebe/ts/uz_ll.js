// -------------------------------------- Lazy Load -------------------------------------------
// 
/* Переменная-флаг для отслеживания того, происходит ли в данный момент ajax-запрос. В самом начале даем ей значение false, т.е. запрос не в процессе выполнения */
var inProgress = false;
if (startFrom > 0) {
    $(window).scroll(function () {
        /* Если высота окна + высота прокрутки больше или равны высоте всего документа и ajax-запрос в настоящий момент не выполняется, то запускаем ajax-запрос */
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 500 && !inProgress) {
            if (!id_vuz) {
                var id_vuz = $("#box-line").data('vuz');
            }
            var type_1 = $(".filter.color-silver").data('filter');
            if (!type_1)
                type_1 = $("#box-line").data('type');
            var lb_1 = $(".news-item." + type_1).length;
            var html_1 = '';
            var myLike_1 = 0;
            var activeLike_1 = '';
            var myDeslyke_1 = 0;
            var activeDeslyke_1 = '';
            var myGo_1 = 0;
            var activeGo_1 = '';
            $.ajax({
                url: '/ajax/lazy_load.php',
                method: 'POST',
                data: { "cur": curPage, "startFrom": lb_1, "id_vuz": id_vuz, "type": type_1 },
                beforeSend: function () {
                    inProgress = true;
                }
            }).done(function (data) {
                data = jQuery.parseJSON(data);
                if (data.res.length > 0) {
                    var placeholderEvent_1 = new Array('Название', 'Дата', 'Время', 'Адрес', 'Координаты Яндекс', 'Телефон', 'Контактное лицо', 'Ссылка на страницу', 'Комментарий', 'Текст', 'Облако тегов', 'Тег', 'Запасная строка', 'Дополнительная строка', 'Внутренний комментарий'); // 15
                    var placeholderOpendoor_1 = new Array('Название', 'Дата', 'Время', 'Адрес', 'Координаты Яндекс', 'Телефон', 'Ссылка на страницу', 'Комментарий', 'Текст', 'ucheba.ru', 'Запасная ссылка', 'Дополнительная строка', 'Внутренний комментарий');
                    var total_1 = 0;
                    var curTime_1 = Date.now() / 1000;
                    var n_1 = 0;
                    var today_1 = 1;
                    $.each(data.res, function () {
                        html_1 = '';
                        myLike_1 = 0;
                        activeLike_1 = '';
                        myDeslyke_1 = 0;
                        activeDeslyke_1 = '';
                        myGo_1 = 0;
                        activeGo_1 = '';
                        if (this['sort'] < curTime_1 && today_1 > 0) {
                            today_1 = 0;
                            if (n_1 > 0) {
                                html_1 += '<div class="line-today today-' + this['TYPE'] + '" style="height: 1px; border-top: 1px solid #ff4719; position: relative; top: -21px; text-align: center;">';
                                html_1 += '<div style="display: inline-block; padding: 5px 15px; background-color: #ffffff; position: relative; top: -14px;">Сегодня</div>';
                                html_1 += '</div>';
                            }
                        }
                        if (this['TYPE'] == 'news') {
                            var n_2 = parseInt(this['ID'], 10);
                            if (arrLikeNews.includes(n_2)) {
                                myLike_1 = 1;
                                activeLike_1 = ' active';
                            }
                            if (arrDeslikeNews.includes(n_2)) {
                                myDeslyke_1 = 1;
                                activeDeslyke_1 = ' active';
                            }
                            if (arrLikeNewsCnt[n_2])
                                likeCnt = arrLikeNewsCnt[n_2];
                            else
                                likeCnt = 0;
                            if (arrDeslikeNewsCnt[n_2])
                                deslikeCnt = arrDeslikeNewsCnt[n_2];
                            else
                                deslikeCnt = 0;
                            html_1 += '<div class="news-item news" style="position: relative;">';
                            if (this['ADMINS']) {
                                html_1 += '<div class="color-silver js-news-edit" data-block="news" data-id="' + this['ID'] + '" style="position: absolute; top: -6px; right: 5px; cursor: pointer; border-bottom: 1px dashed #9f9f9f;">изменить</div>';
                            }
                            if (this['PICTURE']) {
                                html_1 += '<div class="image brd left"><img src="' + this['PICTURE'] + '" alt="' + this['NAME'] + '" title="' + this['NAME'] + '" style="max-width: 200px;"></div>';
                            }
                            html_1 += '<div class="date" style="margin-bottom: 7px;">' + this['FORMAT_DATE'] + '</div>';
                            html_1 += '<div class="news-name">';
                            html_1 += '<a href="' + detailPageUrl + '?sect=news&s=' + this['ID'] + '"><span>' + this['NAME'] + '</span></a>';
                            html_1 += '</div>';
                            html_1 += '<p>' + this['FULL_TEXT'] + '</p>';
                            html_1 += '<div class="page-rating" data-news="' + this['ID'] + '" data-vuz="' + id_vuz + '" data-name="' + this['NAME'] + '" style="margin: 0px 0px 5px 0px; text-align: right;">';
                            if (this['LIKE']) {
                                html_1 += '<div class="st-baloon" style="right: 100px; height: 52px;">';
                                $.each(this['LIKE'], function (i, baloon) {
                                    html_1 += '<a href="/user/' + baloon['id_user'] + '/">';
                                    html_1 += '<div class="image">';
                                    html_1 += '<img src="' + baloon['user_avatar'] + '" alt="' + baloon['user_name'] + '" title="' + baloon['user_name'] + '">';
                                    html_1 += '</div>';
                                    html_1 += '</a>';
                                });
                                html_1 += '</div>';
                            }
                            html_1 += '<a href="#" data-my="' + myLike_1 + '" data-cnt="' + likeCnt + '" class="button js-news-left b-left' + activeLike_1 + '" style="position: relative; left: 0px; top: 0px;"><span><i class="fa fa-thumbs-o-up" style="margin-right: 7px;"></i>' + likeCnt + '</span></a>';
                            if (this['DESLIKE']) {
                                html_1 += '<div class="st-baloon" style="right: 0px; height: 52px;">';
                                $.each(this['DESLIKE'], function (i, baloon) {
                                    html_1 += '<a href="/user/' + baloon['id_user'] + '/">';
                                    html_1 += '<div class="image">';
                                    html_1 += '<img src="' + baloon['user_avatar'] + '" alt="' + baloon['user_name'] + '" title="' + baloon['user_name'] + '">';
                                    html_1 += '</div>';
                                    html_1 += '</a>';
                                });
                                html_1 += '</div>';
                            }
                            html_1 += '<a href="#" data-my="' + myDeslyke_1 + '" data-cnt="' + deslikeCnt + '" class="button js-news-right b-right' + activeDeslyke_1 + '" style="position: relative; right: 0px; top: 0px; margin-left: 5px;"><span><i class="fa fa-thumbs-o-down" style="margin-right: 7px;"></i>' + deslikeCnt + '</span></a>';
                            html_1 += '</div>';
                            html_1 += '</div>';
                        }
                        if (this['TYPE'] == 'events') {
                            var n_3 = parseInt(this['ID'], 10);
                            if (arrLikeEvents.includes(n_3)) {
                                myLike_1 = 1;
                                activeLike_1 = ' active';
                            }
                            if (arrDeslikeEvents.includes(n_3)) {
                                myDeslyke_1 = 1;
                                activeDeslyke_1 = ' active';
                            }
                            if (arrLikeEventsCnt[n_3])
                                likeCnt = arrLikeEventsCnt[n_3];
                            else
                                likeCnt = 0;
                            if (arrDeslikeEventsCnt[n_3])
                                deslikeCnt = arrDeslikeEventsCnt[n_3];
                            else
                                deslikeCnt = 0;
                            if (arrGoEvents.includes(n_3)) {
                                myGo_1 = 1;
                                activeGo_1 = ' active';
                            }
                            if (arrGoEventsCnt[n_3])
                                goCnt = arrGoEventsCnt[n_3];
                            else
                                goCnt = 0;
                            html_1 += '<div class="news-item events">';
                            html_1 += '<div class="right" data-vuz="' + id_vuz + '" data-event="' + this['ID'] + '" style="position: relative;">';
                            if (this['ADMINS']) {
                                html_1 += '<div style="position: relative; top: -10px; right: 5px; text-align: right;">';
                                html_1 += '<div class="color-silver js-news-edit" data-block="events" data-id="' + this['ID'] + '" style="cursor: pointer; border-bottom: 1px dashed #9f9f9f; display: inline-block;">изменить</div>';
                                html_1 += '</div>';
                            }
                            if (this.DATA[1]) {
                                html_1 += '<div class="date-ico" style="margin-bottom: 10px;"><span>' + this['DAY'] + '</span>' + this['MONTH'] + '</div>';
                            }
                            if (this.DATA[4]) {
                                html_1 += '<div class="btns text-right" style="text-align: left;"><a href="#" class="button"><span style="font-family: Verdana;">на карте</span></a></div>';
                            }
                            html_1 += '<div class="btns text-right" style="margin-top: 15px; text-align: left; position: relative;">';
                            if (this['LIKE']) {
                                total_1 = 3;
                                if (this['LIKE'].length > 4) {
                                    total_1 = 2;
                                }
                                html_1 += '<div class="st-baloon" style="height: 52px; right: 0px;">';
                                $.each(this['LIKE'], function (i, baloon) {
                                    html_1 += '<a href="/user/' + baloon['id_user'] + '/">';
                                    html_1 += '<div class="image">';
                                    html_1 += '<img src="' + baloon['user_avatar'] + '" alt="' + baloon['user_name'] + '" title="' + baloon['user_name'] + '">';
                                    html_1 += '</div>';
                                    html_1 += '</a>';
                                });
                                html_1 += '</div>';
                            }
                            html_1 += '<a href="#" data-my="' + myLike_1 + '" data-cnt="' + likeCnt + '" class="button js-event-left b-left' + activeLike_1 + '" style="position: relative; left: 0px; top: 0px;"><span style="text-decoration: none;"><i class="fa fa-thumbs-o-up" style="margin-right: 7px;"></i>' + likeCnt + '</span></a>';
                            html_1 += '</div>';
                            html_1 += '<div class="btns text-right" style="margin-top: 9px; text-align: left; position: relative;">';
                            if (this['DESLIKE']) {
                                total_1 = 3;
                                if (this['DESLIKE'].length > 4) {
                                    total_1 = 2;
                                }
                                html_1 += '<div class="st-baloon" style="height: 52px; right: 0px;">';
                                $.each(this['DESLIKE'], function (i, baloon) {
                                    html_1 += '<a href="/user/' + baloon['id_user'] + '/">';
                                    html_1 += '<div class="image">';
                                    html_1 += '<img src="' + baloon['user_avatar'] + '" alt="' + baloon['user_name'] + '" title="' + baloon['user_name'] + '">';
                                    html_1 += '</div>';
                                    html_1 += '</a>';
                                });
                                html_1 += '</div>';
                            }
                            html_1 += '<a href="#" data-my="' + myDeslyke_1 + '" data-cnt="' + deslikeCnt + '" class="button js-event-right b-right' + activeDeslyke_1 + '" style="position: relative; right: 0px; top: 0px;"><span style="text-decoration: none;"><i class="fa fa-thumbs-o-down" style="margin-right: 7px;"></i>' + deslikeCnt + '</span></a>';
                            html_1 += '</div>';
                            html_1 += '<div class="btns text-right" style="margin-top: 9px; text-align: left; position: relative;">';
                            if (this['GO']) {
                                total_1 = 3;
                                if (this['GO'].length > 4) {
                                    total_1 = 2;
                                }
                                html_1 += '<div class="st-baloon" style="height: 52px; right: 0px;">';
                                $.each(this['GO'], function (i, baloon) {
                                    html_1 += '<a href="/user/' + baloon['id_user'] + '/">';
                                    html_1 += '<div class="image">';
                                    html_1 += '<img src="' + baloon['user_avatar'] + '" alt="' + baloon['user_name'] + '" title="' + baloon['user_name'] + '">';
                                    html_1 += '</div>';
                                    html_1 += '</a>';
                                });
                                html_1 += '</div>';
                            }
                            html_1 += '<a href="#" data-lk="0" data-my="' + myGo_1 + '" data-cnt="' + goCnt + '" class="button js-event-go b-right' + activeGo_1 + '" style="position: relative; right: 0px; top: 0px;">';
                            html_1 += '<span style="text-decoration: none;">Я пойду (' + goCnt + ')</span>';
                            html_1 += '</a>';
                            html_1 += '</div>';
                            html_1 += '</div>';
                            if (this['sort'] < curTime_1) {
                                html_1 += '<div class="date" style="margin-bottom: 7px;">' + this['FORMAT_DATE'] + ' (событие уже прошло)</div>';
                            }
                            else {
                                html_1 += '<div class="date" style="margin-bottom: 7px;">' + this['FORMAT_DATE'] + '</div>';
                            }
                            html_1 += '<div class="news-name">';
                            html_1 += '<span>' + this.DATA[0] + '</span>';
                            html_1 += '</div>';
                            html_1 += '<p>';
                            for (var n_4 = 1; n_4 < placeholderEvent_1.length; n_4++) {
                                if (n_4 == 1 || n_4 == 2 || n_4 == 4 || n_4 == 10 || n_4 == 11 || n_4 == 12 || n_4 == 13 || n_4 == 14)
                                    continue;
                                if (this.DATA[n_4].trim()) {
                                    if (n_4 == 7) {
                                        html_1 += placeholderEvent_1[n_4] + ': <a href="' + this.DATA[n_4] + '" target="blank">' + this.DATA[n_4].trim() + '</a><br>';
                                    }
                                    else if (n_4 == 8 || n_4 == 9) {
                                        html_1 += this.DATA[n_4].trim() + '<br>';
                                    }
                                    else {
                                        html_1 += placeholderEvent_1[n_4] + ': ' + this.DATA[n_4].trim() + '<br>';
                                    }
                                }
                            }
                            html_1 += '</p>';
                            html_1 += '</div>';
                        }
                        if (this['TYPE'] == 'opendoor') {
                            html_1 += '<div class="news-item opendoor">';
                            html_1 += '<div class="right" style="position: relative;">';
                            if (this['ADMINS']) {
                                html_1 += '<div style="position: relative; top: -10px; right: 5px; text-align: right;">';
                                html_1 += '<div class="color-silver js-news-edit" data-block="opendoor" data-id="' + this['ID'] + '" style="cursor: pointer; border-bottom: 1px dashed #9f9f9f; display: inline-block;">изменить</div>';
                                html_1 += '</div>';
                            }
                            if (this.DATA[1]) {
                                html_1 += '<div class="date-ico" style="margin-bottom: 10px;"><span>' + this['DAY'] + '</span>' + this['MONTH'] + '</div>';
                            }
                            if (this.DATA[4]) {
                                html_1 += '<div class="btns text-right" style="text-align: left;"><a href="#" class="button"><span style="font-family: Verdana;">на карте</span></a></div>';
                            }
                            html_1 += '</div>';
                            if (this['sort'] < curTime_1) {
                                html_1 += '<div class="date" style="margin-bottom: 7px;">' + this['FORMAT_DATE'] + ' (событие уже прошло)</div>';
                            }
                            else {
                                html_1 += '<div class="date" style="margin-bottom: 7px;">' + this['FORMAT_DATE'] + '</div>';
                            }
                            html_1 += '<div class="news-name">';
                            if (this.DATA[6]) {
                                html_1 += '<a href="' + this.DATA[6] + '"><span>' + this.DATA[0] + '</span></a>';
                            }
                            else {
                                html_1 += '<span>' + this.DATA[0] + '</span>';
                            }
                            html_1 += '</div>';
                            html_1 += '<p>';
                            for (var n_5 = 1; n_5 < placeholderOpendoor_1.length; n_5++) {
                                if (n_5 == 1 || n_5 == 2 || n_5 == 4 || n_5 == 9 || n_5 == 10 || n_5 == 11 || n_5 == 12)
                                    continue;
                                if (this.DATA[n_5].trim()) {
                                    if (n_5 == 6) {
                                        html_1 += placeholderOpendoor_1[n_5] + ': <a href="' + this.DATA[n_5] + '" target="blank">' + this.DATA[n_5].trim() + '</a><br>';
                                    }
                                    else if (n_5 == 8) {
                                        html_1 += this.DATA[n_5].trim() + '<br>';
                                    }
                                    else {
                                        html_1 += placeholderOpendoor_1[n_5] + ': ' + this.DATA[n_5].trim() + '<br>';
                                    }
                                }
                            }
                            html_1 += '</p>';
                            html_1 += '</div>';
                        }
                        $("#box-line").append(html_1);
                        n_1 = n_1 + 1;
                    });
                    if (!lb_1) {
                        $("#box-line .news-item." + type_1).fadeIn(1000);
                        $(".today-" + type_1).fadeIn(1000);
                    }
                    inProgress = false;
                    startFrom += 10;
                }
            });
        }
    });
}

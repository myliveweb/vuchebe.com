// -------------------------------------- Lazy Load -------------------------------------------
// 
/* Переменная-флаг для отслеживания того, происходит ли в данный момент ajax-запрос. В самом начале даем ей значение false, т.е. запрос не в процессе выполнения */
var inProgress = false;

if(startFrom > 0) {
    $(window).scroll(function() {
        /* Если высота окна + высота прокрутки больше или равны высоте всего документа и ajax-запрос в настоящий момент не выполняется, то запускаем ajax-запрос */
        if($(window).scrollTop() + $(window).height() >= $(document).height() - 500 && !inProgress) {

            if(!id_vuz) {
                let id_vuz = $("#box-line").data('vuz');
            }
            let type = $(".filter.color-silver").data('filter');
            if(!type)
                type = $("#box-line").data('type');

            let lb = $(".news-item." + type).length;

            let html = '';

            let myLike = 0;
            let activeLike = '';
            let myDeslyke = 0;
            let activeDeslyke = '';
            let myGo = 0;
            let activeGo = '';

            $.ajax({
                url: '/ajax/lazy_load.php',
                method: 'POST',
                data: {"cur":curPage, "startFrom":lb, "id_vuz":id_vuz, "type":type},
                beforeSend: function() {
                inProgress = true;}
                }).done(function(data){
                data = jQuery.parseJSON(data);
                if (data.res.length > 0) {

                    let placeholderEvent = new Array('Название','Дата','Время','Адрес','Координаты Яндекс','Телефон','Контактное лицо','Ссылка на страницу','Комментарий','Текст','Облако тегов','Тег','Запасная строка','Дополнительная строка','Внутренний комментарий'); // 15
                    let placeholderOpendoor = new Array('Название','Дата','Время','Адрес','Координаты Яндекс','Телефон','Ссылка на страницу','Комментарий','Текст','ucheba.ru','Запасная ссылка','Дополнительная строка','Внутренний комментарий');
                    let total = 0;
                    let curTime = Date.now() / 1000;
                    let n = 0;
                    let today = 1;
                    $.each(data.res, function(){
                        html = '';

                        myLike = 0;
                        activeLike = '';
                        myDeslyke = 0;
                        activeDeslyke = '';
                        myGo = 0;
                        activeGo = '';

                        if(this['sort'] < curTime && today > 0) {
                            today = 0;
                            if(n > 0) {
                                html += '<div class="line-today today-' + this['TYPE'] +'" style="height: 1px; border-top: 1px solid #ff4719; position: relative; top: -21px; text-align: center;">';
                                html += '<div style="display: inline-block; padding: 5px 15px; background-color: #ffffff; position: relative; top: -14px;">Сегодня</div>';
                                html += '</div>';
                            }
                        }

                        if(this['TYPE'] == 'news') {

                            let n = parseInt(this['ID'], 10);

                            if(arrLikeNews.includes(n)) {
                                myLike = 1;
                                activeLike = ' active';
                            }

                            if(arrDeslikeNews.includes(n)) {
                                myDeslyke = 1;
                                activeDeslyke = ' active';
                            }

                            if(arrLikeNewsCnt[n])
                                likeCnt = arrLikeNewsCnt[n];
                            else
                                likeCnt = 0;

                            if(arrDeslikeNewsCnt[n])
                                deslikeCnt = arrDeslikeNewsCnt[n];
                            else
                                deslikeCnt = 0;

                            html += '<div class="news-item news" style="position: relative;">';
                            if(this['ADMINS']) {
                                html += '<div class="color-silver js-news-edit" data-block="news" data-id="' + this['ID'] + '" style="position: absolute; top: -6px; right: 5px; cursor: pointer; border-bottom: 1px dashed #9f9f9f;">изменить</div>';
                            }
                            if(this['PICTURE']) {
                                html += '<div class="image brd left"><img src="' + this['PICTURE'] + '" alt="' + this['NAME'] + '" title="' + this['NAME'] + '" style="max-width: 200px;"></div>';
                            }
                            html += '<div class="date" style="margin-bottom: 7px;">' + this['FORMAT_DATE'] + '</div>';
                            html += '<div class="news-name">';
                            html += '<a href="' + detailPageUrl + '?sect=news&s=' + this['ID'] + '"><span>' + this['NAME'] + '</span></a>';
                            html += '</div>';
                            html += '<p>' + this['FULL_TEXT'] + '</p>';
                            html += '<div class="page-rating" data-news="' + this['ID'] + '" data-vuz="' + id_vuz + '" data-name="' + this['NAME'] + '" style="margin: 0px 0px 5px 0px; text-align: right;">';
                            if(this['LIKE']) {
                                html += '<div class="st-baloon" style="right: 100px; height: 52px;">';
                                $.each(this['LIKE'], function(i, baloon){
                                    html += '<a href="/user/' + baloon['id_user'] + '/">';
                                    html += '<div class="image">';
                                    html += '<img src="' + baloon['user_avatar'] + '" alt="' + baloon['user_name'] + '" title="' + baloon['user_name'] + '">';
                                    html += '</div>';
                                    html += '</a>';
                                });
                                html += '</div>';
                            }
                            html += '<a href="#" data-my="' + myLike + '" data-cnt="' + likeCnt + '" class="button js-news-left b-left' + activeLike + '" style="position: relative; left: 0px; top: 0px;"><span><i class="fa fa-thumbs-o-up" style="margin-right: 7px;"></i>' + likeCnt + '</span></a>';
                            if(this['DESLIKE']) {
                                html += '<div class="st-baloon" style="right: 0px; height: 52px;">';
                                $.each(this['DESLIKE'], function(i, baloon){
                                    html += '<a href="/user/' + baloon['id_user'] + '/">';
                                    html += '<div class="image">';
                                    html += '<img src="' + baloon['user_avatar'] + '" alt="' + baloon['user_name'] + '" title="' + baloon['user_name'] + '">';
                                    html += '</div>';
                                    html += '</a>';
                                });
                                html += '</div>';
                            }
                            html += '<a href="#" data-my="' + myDeslyke + '" data-cnt="' + deslikeCnt + '" class="button js-news-right b-right' + activeDeslyke + '" style="position: relative; right: 0px; top: 0px; margin-left: 5px;"><span><i class="fa fa-thumbs-o-down" style="margin-right: 7px;"></i>' + deslikeCnt + '</span></a>';
                            html += '</div>';
                            html += '</div>';
                        }

                        if(this['TYPE'] == 'events') {

                            let n = parseInt(this['ID'], 10);

                            if(arrLikeEvents.includes(n)) {
                                myLike = 1;
                                activeLike = ' active';
                            }

                            if(arrDeslikeEvents.includes(n)) {
                                myDeslyke = 1;
                                activeDeslyke = ' active';
                            }

                            if(arrLikeEventsCnt[n])
                                likeCnt = arrLikeEventsCnt[n];
                            else
                                likeCnt = 0;

                            if(arrDeslikeEventsCnt[n])
                                deslikeCnt = arrDeslikeEventsCnt[n];
                            else
                                deslikeCnt = 0;

                            if(arrGoEvents.includes(n)) {
                                myGo =1;
                                activeGo = ' active';
                            }

                            if(arrGoEventsCnt[n])
                                goCnt = arrGoEventsCnt[n];
                            else
                                goCnt = 0;

                            html += '<div class="news-item events">';
                            html += '<div class="right" data-vuz="' + id_vuz + '" data-event="' + this['ID'] + '" style="position: relative;">';
                            if(this['ADMINS']) {
                                html += '<div style="position: relative; top: -10px; right: 5px; text-align: right;">';
                                html += '<div class="color-silver js-news-edit" data-block="events" data-id="' + this['ID'] + '" style="cursor: pointer; border-bottom: 1px dashed #9f9f9f; display: inline-block;">изменить</div>';
                                html += '</div>';
                            }
                            if(this.DATA[1]) {
                                html += '<div class="date-ico" style="margin-bottom: 10px;"><span>' + this['DAY'] + '</span>' + this['MONTH'] + '</div>';
                            }
                            if(this.DATA[4]) {
                                html += '<div class="btns text-right" style="text-align: left;"><a href="#" class="button"><span style="font-family: Verdana;">на карте</span></a></div>';
                            }
                            html += '<div class="btns text-right" style="margin-top: 15px; text-align: left; position: relative;">';
                            if(this['LIKE']) {
                                total = 3;
                                if(this['LIKE'].length > 4) {
                                    total = 2;
                                }
                                html += '<div class="st-baloon" style="height: 52px; right: 0px;">';
                                $.each(this['LIKE'], function(i, baloon){
                                    html += '<a href="/user/' + baloon['id_user'] + '/">';
                                    html += '<div class="image">';
                                    html += '<img src="' + baloon['user_avatar'] + '" alt="' + baloon['user_name'] + '" title="' + baloon['user_name'] + '">';
                                    html += '</div>';
                                    html += '</a>';
                                });
                                html += '</div>';
                            }
                            html += '<a href="#" data-my="' + myLike + '" data-cnt="' + likeCnt + '" class="button js-event-left b-left' + activeLike + '" style="position: relative; left: 0px; top: 0px;"><span style="text-decoration: none;"><i class="fa fa-thumbs-o-up" style="margin-right: 7px;"></i>' + likeCnt + '</span></a>';
                            html += '</div>';
                            html += '<div class="btns text-right" style="margin-top: 9px; text-align: left; position: relative;">';
                            if(this['DESLIKE']) {
                                total = 3;
                                if(this['DESLIKE'].length > 4) {
                                    total = 2;
                                }
                                html += '<div class="st-baloon" style="height: 52px; right: 0px;">';
                                $.each(this['DESLIKE'], function(i, baloon){
                                    html += '<a href="/user/' + baloon['id_user'] + '/">';
                                    html += '<div class="image">';
                                    html += '<img src="' + baloon['user_avatar'] + '" alt="' + baloon['user_name'] + '" title="' + baloon['user_name'] + '">';
                                    html += '</div>';
                                    html += '</a>';
                                });
                                html += '</div>';
                            }
                            html += '<a href="#" data-my="' + myDeslyke + '" data-cnt="' + deslikeCnt + '" class="button js-event-right b-right' + activeDeslyke + '" style="position: relative; right: 0px; top: 0px;"><span style="text-decoration: none;"><i class="fa fa-thumbs-o-down" style="margin-right: 7px;"></i>' + deslikeCnt + '</span></a>';
                            html += '</div>';
                            html += '<div class="btns text-right" style="margin-top: 9px; text-align: left; position: relative;">';
                            if(this['GO']) {
                                total = 3;
                                if(this['GO'].length > 4) {
                                    total = 2;
                                }
                                html += '<div class="st-baloon" style="height: 52px; right: 0px;">';
                                $.each(this['GO'], function(i, baloon){
                                    html += '<a href="/user/' + baloon['id_user'] + '/">';
                                    html += '<div class="image">';
                                    html += '<img src="' + baloon['user_avatar'] + '" alt="' + baloon['user_name'] + '" title="' + baloon['user_name'] + '">';
                                    html += '</div>';
                                    html += '</a>';
                                });
                                html += '</div>';
                            }
                            html += '<a href="#" data-lk="0" data-my="' + myGo + '" data-cnt="' + goCnt + '" class="button js-event-go b-right' + activeGo + '" style="position: relative; right: 0px; top: 0px;">';
                            html += '<span style="text-decoration: none;">Я пойду (' + goCnt + ')</span>';
                            html += '</a>';
                            html += '</div>';
                            html += '</div>';
                            if(this['sort'] < curTime) {
                                html += '<div class="date" style="margin-bottom: 7px;">' + this['FORMAT_DATE'] + ' (событие уже прошло)</div>';
                            } else {
                                html += '<div class="date" style="margin-bottom: 7px;">' + this['FORMAT_DATE'] + '</div>';
                            }
                            html += '<div class="news-name">';
                            html += '<span>' + this.DATA[0] + '</span>';
                            html += '</div>';
                            html += '<p>';
                            for(let n = 1; n < placeholderEvent.length; n++) {
                                if(n == 1 || n == 2 || n == 4 || n == 10 || n == 11 || n == 12 || n == 13 || n == 14)
                                    continue;
                                if(this.DATA[n].trim()) {
                                    if(n == 7) {
                                        html += placeholderEvent[n] + ': <a href="' + this.DATA[n] + '" target="blank">' + this.DATA[n].trim() + '</a><br>';
                                    } else if(n == 8 || n == 9) {
                                        html += this.DATA[n].trim() + '<br>';
                                    } else {
                                        html += placeholderEvent[n] + ': ' + this.DATA[n].trim() + '<br>';
                                    }
                                }
                            }
                            html += '</p>';
                            html += '</div>';
                        }

                        if(this['TYPE'] == 'opendoor') {

                            html += '<div class="news-item opendoor">';
                            html += '<div class="right" style="position: relative;">';
                            if(this['ADMINS']) {
                                html += '<div style="position: relative; top: -10px; right: 5px; text-align: right;">';
                                html += '<div class="color-silver js-news-edit" data-block="opendoor" data-id="' + this['ID'] + '" style="cursor: pointer; border-bottom: 1px dashed #9f9f9f; display: inline-block;">изменить</div>';
                                html += '</div>';
                            }
                            if(this.DATA[1]) {
                                html += '<div class="date-ico" style="margin-bottom: 10px;"><span>' + this['DAY'] + '</span>' + this['MONTH'] + '</div>';
                            }
                            if(this.DATA[4]) {
                                html += '<div class="btns text-right" style="text-align: left;"><a href="#" class="button"><span style="font-family: Verdana;">на карте</span></a></div>';
                            }
                            html += '</div>';
                            if(this['sort'] < curTime) {
                                html += '<div class="date" style="margin-bottom: 7px;">' + this['FORMAT_DATE'] + ' (событие уже прошло)</div>';
                            } else {
                                html += '<div class="date" style="margin-bottom: 7px;">' + this['FORMAT_DATE'] + '</div>';
                            }
                            html += '<div class="news-name">';
                            if(this.DATA[6]) {
                                html += '<a href="' + this.DATA[6] + '"><span>' + this.DATA[0] + '</span></a>';
                            } else {
                                html += '<span>' + this.DATA[0] + '</span>';
                            }
                            html += '</div>';
                            html += '<p>';
                            for(let n = 1; n < placeholderOpendoor.length; n++) {
                                if(n == 1 || n == 2 || n == 4 || n == 9 || n == 10 || n == 11 || n == 12)
                                    continue;
                                if(this.DATA[n].trim()) {
                                    if(n == 6) {
                                        html += placeholderOpendoor[n] + ': <a href="' + this.DATA[n] + '" target="blank">' + this.DATA[n].trim() + '</a><br>';
                                    } else if(n == 8) {
                                        html += this.DATA[n].trim() + '<br>';
                                    } else {
                                        html += placeholderOpendoor[n] + ': ' + this.DATA[n].trim() + '<br>';
                                    }
                                }
                            }
                            html += '</p>';
                            html += '</div>';
                        }

                        $("#box-line").append(html);
                        n = n + 1;
                    });
                    if(!lb) {
                        $("#box-line .news-item." + type).fadeIn(1000);
                        $(".today-" + type).fadeIn(1000);
                    }
                    inProgress = false;
                    startFrom += 10;
                }
            });
        }
    });
}
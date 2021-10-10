const isMobile = () => {
    const curentWidth = document.documentElement.clientWidth;
    if(curentWidth <= 980) {
        return true;
    }
    return false;
}

$(document).ready(function () {

    let distance = 1000 // Расстояние в пикселях (шаг через который появляются новые баннеры) 1000
    offset = distance

    let heightDiv = 0

    let defaultBanner = false;
    if($('#banner-list .default-banner').length > 0) {
        //defaultBanner = true;
    }


    $(window).scroll(function () {
        if($('.st-content-right').length > 0) {
            heightDiv = $('.st-content-right').outerHeight()
        } else if($('.line:visible').length > 0) {
            heightDiv = $('.line:visible').outerHeight()
        } else if($('.line-orders:visible').length > 0) {
            heightDiv = $('.line-orders:visible').outerHeight()
        } else {
            heightDiv = $('#page').outerHeight()
        }

        if (heightDiv >= $(window).scrollTop() && (heightDiv - distance) > offset && !inProgressGetBanner && !defaultBanner && !isMobile()) {
                getSideBanner();
        }
    });

    function getSideBanner() {

        let load = [];
        let id = 0;
        let html = '';

        html += `
            <div class="modules-left clear new-banner remove-banner" style="margin-top: ${distance - 240}px;">
                <div class="section__item" style="position: relative; display: block; margin-top: 135px;">
                    <div class="loader01"></div>
                </div>
            </div>
        `;

        $('#banner-list').append(html)

        $('.js-click-banner.side-banner').each(function (index, value) {
            id = $(this).data('id');
            load.push(id);
        });
        $.ajax({
            url: '/ajax/get_dinamic_banner.php',
            method: 'POST',
            data: { "load": load },
            beforeSend: function () {
                inProgressGetBanner = true;
            }
        }).done(function (data) {
            data = jQuery.parseJSON(data);

            if(data.status == 'success' && data.banner) {
                html = '';
                if(data.banner.id) {
                    html += `
                        <div class="st-banner" style="position: relative; display: none;">
                            <div class="hide-banner js-hide-banner">реклама</div>
                            <div class="image brd">
                                <a href="#" data-id="${ data.banner.id }" class="js-click-banner side-banner" ${ data.banner.target }><img src="${ data.banner.src }" title="${ data.banner.name }" alt="${ data.banner.name }"></a>
                            </div>
                        </div>    
                    `;
                } else {
                    //if(!defaultBanner) {
                        html += `
                            <div class="st-banner" style="position: relative; display: none;">
                                <div class="hide-banner js-hide-banner">реклама</div>
                                <div class="image brd">
                                    <a class="default-banner" href="${data.banner.href}" ${data.banner.target}><img src="${data.banner.src}" title="${data.banner.name}" alt="${data.banner.name}"></a>
                                </div>
                            </div>    
                        `;
                        //defaultBanner = true;
                    //}
                }

                if(html.length > 0) {
                    $('#banner-list .new-banner').empty()
                    $('#banner-list .new-banner').append(html)
                    $('#banner-list .new-banner .st-banner').fadeIn()
                    $('#banner-list .new-banner').removeClass('new-banner')
                    offset = offset + distance
                }

                inProgressGetBanner = false;

            }
        });

        return false;
    }
});
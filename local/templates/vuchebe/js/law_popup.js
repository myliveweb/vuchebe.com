$(document).ready(function() {

    $('.js-lav-popup').on('click', function (e) {
        e.preventDefault();

        const $this = $(this)

        const id = $this.data('popup')

        const top_form = $(window).scrollTop();
        const height_form = $('.hideForm-law .form-open-block form').height();
        const marg_top = $(window).height() / 2;

        $('.hideForm-law .form-open-block').css({
            'height': $(window).height(),
            'position': 'absolute',
            'top': top_form,
        });
        $('.hideForm-law').css({ 'height': $(document).height(), });

        $.ajax({
            type: 'POST',
            url: '/ajax/get_law.php',
            data: { id },
            dataType: 'json',
            success: function (result) {
                if (result.status == 'success') {
                    console.log(result.res)

                    $('.hideForm-law .name_form span').text(result.res.NAME)
                    $('.hideForm-law .text-content').html(result.res.PREVIEW_TEXT)

                    if(result.res.pdf)
                        $('.hideForm-law .law-download').attr('href', result.res.pdf)
                    else
                        $('.hideForm-law .law-download').hide()

                    $('.foneBg').css({ 'display': 'block' })
                    $('.hideForm-law').fadeIn(250)
                }
            }
        });

        return false;
    });

    $('#page, #banner-info').on('click', '.js-tarif', function (e) {
        e.preventDefault();

        const $this = $(this)

        const type = $this.data('type')
        const tarif = $this.data('tarif')

        $('.hideForm-banner-info').hide()

        const top_form = $(window).scrollTop();
        const height_form = $('.hideForm-tarif .form-open-block form').height();
        const marg_top = $(window).height() / 2;

        $('.hideForm-tarif .form-open-block').css({
            'height': $(window).height(),
            'position': 'absolute',
            'top': top_form,
        });
        $('.hideForm-tarif').css({ 'height': $(document).height(), });

        $('.one-line').find('img.up-tarif').hide()
        $('.one-line').find('img.down-tarif').show()
        $('.one-line').find('span.open-text').text('Развернуть')
        $('.one-line').find('div.js-desc').hide()
        $('.one-line').data('open', 0)

        const root = $('#' + type +' .' + tarif)

        root.find('img.down-tarif').hide()
        root.find('img.up-tarif').show()
        root.find('span.open-text').text('Свернуть')
        root.find('div.js-desc').show()
        root.data('open', 1)

        $('.foneBg').css({ 'display': 'block' })
        $('.hideForm-tarif').fadeIn(250)

        return false;
    });

    $('#form-tarif').on('click', '.js-open-tarif', function (e) {
        e.preventDefault();

        const $this = $(this)

        const root = $this.parent().parent()
        const tarif = root.data('tarif')
        const open = root.data('open')

        if(open) {
            root.find('img.up-tarif').hide()
            root.find('img.down-tarif').show()
            root.find('span.open-text').text('Развернуть')
            root.find('div.js-desc').hide()
            root.data('open', 0)
        } else {
            root.find('img.down-tarif').hide()
            root.find('img.up-tarif').show()
            root.find('span.open-text').text('Свернуть')
            root.find('div.js-desc').show()
            root.data('open', 1)
        }

        console.log(tarif)

        return false;
    });

    $('#box-line').on('click', '.js-refund', function (e) {
        e.preventDefault();

        const $this = $(this)

        const id = $this.data('id')

        $.ajax({
            type: 'POST',
            url: '/ajax/balance_refund.php',
            data: { id },
            dataType: 'json',
            success: function (result) {
                if (result.status == 'success') {
                    $this.closest('.line-refund').slideUp()
                }
            }
        });

        return false;
    });

});

function close_form_law() {
    $('.hideForm-law, .foneBg-law').fadeOut(250);
    return false;
}
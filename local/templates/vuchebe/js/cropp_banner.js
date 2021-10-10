$(document).ready(function() {

    $('input[type=file]#top-banner').on('change', function (ev) {

        const top_form = $(window).scrollTop()

        $('.hideForm-top-banner.top-banner .form-open-block').css({
            'height': $(window).height(),
            'position': 'absolute',
            'top': top_form,
        });
        $('.hideForm-top-banner.top-banner').css({ 'height': $(document).height() });

        $('.hideForm-top-banner.top-banner .js-max-sum-div').css('color', '#000000')
        $('.hideForm-top-banner.top-banner span.js-error-sum').css('color', '#9f9f9f')

        var reader = new FileReader();
        reader.onload = function (e) {
            $uploadCrop.croppie('bind', {
                url: e.target.result
            }).then(function () {
                console.log('jQuery bind complete main');
            });

        }
        reader.readAsDataURL(this.files[0]);

        $('.foneBg').css({ 'display': 'block' })

        $('.hideForm-top-banner.top-banner').fadeIn(250);

        $('.hideForm-top-banner.top-banner .cr-slider').focus()
        $('.hideForm-top-banner.top-banner .cr-slider').css('width', '100%')
    });

    $('input[type=file]#side-banner').on('change', function (ev) {

        const top_form = $(window).scrollTop()

        $('.hideForm-side-banner.side-banner .form-open-block').css({
            'height': $(window).height(),
            'position': 'absolute',
            'top': top_form,
        });
        $('.hideForm-side-banner.side-banner').css({ 'height': $(document).height() });

        $('.hideForm-side-banner.side-banner .js-max-sum-div').css('color', '#000000')
        $('.hideForm-side-banner.side-banner span.js-error-sum').css('color', '#9f9f9f')

        var reader = new FileReader();
        reader.onload = function (e) {
            $uploadCrop.croppie('bind', {
                url: e.target.result
            }).then(function () {
                console.log('jQuery bind complete main');
            });

        }
        reader.readAsDataURL(this.files[0]);

        $('.foneBg').css({ 'display': 'block' })

        $('.hideForm-side-banner.side-banner').fadeIn(250);

        $('.hideForm-side-banner.side-banner .cr-slider').focus()
        $('.hideForm-side-banner.side-banner .cr-slider').css('width', '100%')
    });

});
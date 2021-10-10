$(document).ready(function () {

  $('input[type=file]#avatar').on('change', function (ev) {

    console.log('input[type=file]#avatar')

    const top_form = $(window).scrollTop()

    $('.hideForm-avatar.avatar .form-open-block').css({
      'height': $(window).height(),
      'position': 'absolute',
      'top': top_form,
    });
    $('.hideForm-avatar.avatar').css({ 'height': $(document).height() });

    $('.hideForm-avatar.avatar .js-max-sum-div').css('color', '#000000')
    $('.hideForm-avatar.avatar span.js-error-sum').css('color', '#9f9f9f')

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

    $('.hideForm-avatar.avatar').fadeIn(250);

    $('.hideForm-avatar.avatar .cr-slider').focus()
    $('.hideForm-avatar.avatar .cr-slider').css('width', '100%')
  });

  $('.js-avatar-submit').on('click', function (ev) {
    ev.preventDefault()

    const type = $('#upload-input').data('avatar')

    let id = 0
    if(type == 'group') {
      id = parseInt($('#form-group-chat .js-submit-group-chat').data('id'))
    }

    $('.hideForm-avatar.avatar .section__item, .hideForm-top-banner.top-banner .section__item, .hideForm-side-banner.side-banner .section__item').show()

    $uploadCrop.croppie('result', {
      type: 'canvas',
      size: 'viewport'
    }).then(function (resp) {
      $.ajax({
        url: "/ajax/store_img_ajax.php",
        type: "POST",
        data: { "image": resp, "type_avatar": type, "id": id },
        dataType: 'json',
        success: function (data) {

          $('.hideForm-avatar.avatar .section__item, .hideForm-top-banner.top-banner .section__item, .hideForm-side-banner.side-banner .section__item').hide()

          if(type == 'user') {
            $('#main-profile.profile-avatar').attr('src', resp)
            close_form();
          } else if(type == 'group') {
            $('#group-profile-avatar').attr('src', data.file)
            $('.hideForm-avatar.avatar').fadeOut(250);
          } else if(type == 'top-banner') {
            $('.page-content.form-banner img.top-banner').attr('src', data.file)
            close_form();
          } else if(type == 'side-banner') {
            $('.page-content.form-banner img.side-banner').attr('src', data.file)
            close_form();
          }

        }
      });
    });
    return false;
  });

  $('input[type=file]#avatarGroup').on('change', function (ev) {

    console.log('input[type=file]#avatarGroup')

    const top_form = $(window).scrollTop()

    $('.hideForm-avatar.avatar .form-open-block').css({
      'height': $(window).height(),
      'position': 'absolute',
      'top': top_form,
    });
    $('.hideForm-avatar.avatar').css({ 'height': $(document).height() });

    $('.hideForm-avatar.avatar .js-max-sum-div').css('color', '#000000')
    $('.hideForm-avatar.avatar span.js-error-sum').css('color', '#9f9f9f')

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

    $('.hideForm-avatar.avatar').fadeIn(250);

    $('.hideForm-avatar.avatar .cr-slider').focus()
    $('.hideForm-avatar.avatar .cr-slider').css('width', '100%')
  });

}); // document ready
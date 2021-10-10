$(document).ready(function () {

  console.log('Ugolok Znaniy')

  $('#page').on('click', '.js-ugolok-edit', function (e) {
    e.preventDefault();

    $this = $(this);

    const id = $this.data('id');

    const top_form = $(window).scrollTop();

    $('.hideForm-ugolok.ugolok .form-open-block').css({
      'height': $(window).height(),
      'position': 'absolute',
      'top': top_form,
    });
    $('.hideForm-ugolok.ugolok').css({ 'height': $(document).height() });

    $('.hideForm-ugolok.ugolok #form-ugolok .js-name').css('color', '#303030')

    $.ajax({
      type: 'POST',
      url: '/ajax/ugolok_get.php',
      data: { id },
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {

          $('.hideForm-ugolok.ugolok #form-ugolok .js-element').val(result.res.id)
          $('.hideForm-ugolok.ugolok #form-ugolok .name_form span').text(`Редактирование ${result.res.name}`)
          $('.hideForm-ugolok.ugolok #form-ugolok .js-name').val(result.res.name)
          $('.hideForm-ugolok.ugolok #form-ugolok .js-anonnce').val(result.res.preview)
          $('.hideForm-ugolok.ugolok #form-ugolok .js-anonnce-sign').val(result.res.sign)
          $('.hideForm-ugolok.ugolok #form-ugolok .js-wiki').val(result.res.wiki)
          $('.hideForm-ugolok.ugolok #form-ugolok .js-text').val(result.res.detail)

          console.log(result.res.src)
          if(result.res.src) {
            $('.hideForm-ugolok.ugolok #form-ugolok .js-img').attr('src', result.res.src)
          }

          if(result.res.sections.length) {
            $.each(result.res.sections, function () {
              $('.st-tags-block [data-tag="' + this + '"] ').addClass('active')
            });
          }

          $('.foneBg').css({ 'display': 'block' })
          $('.hideForm-ugolok.ugolok').fadeIn(250);
        }
      }
    });

    return false;
  });

  $(".st-tags-block").on('click', '.tag-ugolok', function (e) {
    e.preventDefault();

    $this = $(this);

    if ($this.hasClass('active'))
      $this.removeClass('active')
    else
      $this.addClass('active');

    return false;
  });

  $("#form-ugolok").on('click', '.js-submit-ugolok', function (e) {
    e.preventDefault();

    $this = $(this);

    const name = $('.hideForm-ugolok.ugolok #form-ugolok .js-name').val().trim()

    if (name == '' || name == 'Поле обязательно для заполнения') {
      $('.hideForm-ugolok.ugolok #form-ugolok .js-name').css('color', 'red');
      $('.hideForm-ugolok.ugolok #form-ugolok .js-name').val('Поле обязательно для заполнения');
      return false;
    }

    const id      = $('.hideForm-ugolok.ugolok #form-ugolok .js-element').val()
    const preview = $('.hideForm-ugolok.ugolok #form-ugolok .js-anonnce').val().trim()
    const sign    = $('.hideForm-ugolok.ugolok #form-ugolok .js-anonnce-sign').val().trim()
    const wiki    = $('.hideForm-ugolok.ugolok #form-ugolok .js-wiki').val().trim()
    const detail  = $('.hideForm-ugolok.ugolok #form-ugolok .js-text').val().trim()

    let sections = [];
    let section = 0;

    $('.st-tags-block .tag-ugolok.active').each(function () {
      section = $(this).data('tag');
      sections.push(section);
    });

    console.log('Submit', sections)

    $.ajax({
      type: 'POST',
      url: '/ajax/ugolok_set.php',
      data: { id, name, preview, sign, wiki, detail, sections },
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          document.location.reload(true);
        }
      }
    });

    return false;
  });

  $('.hideForm-ugolok.ugolok #form-ugolok .js-name').on('focus', function () {
    $(this).css('color', '#303030');
    if ($(this).val() == 'Поле обязательно для заполнения')
      $(this).val('');
    return false;
  });

  $('input[type=file]#avatarUgolok').on('change', function (ev) {

    const top_form = $(window).scrollTop()

    $('.hideForm-avatar.ugolok .form-open-block').css({
      'height': $(window).height(),
      'position': 'absolute',
      'top': top_form,
    });
    $('.hideForm-avatar.ugolok').css({ 'height': $(document).height() });

    $('.hideForm-avatar.ugolok .js-max-sum-div').css('color', '#000000')
    $('.hideForm-avatar.ugolok span.js-error-sum').css('color', '#9f9f9f')

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

    $('.hideForm-avatar.ugolok').fadeIn(250);

    console.log('Crop Show');

    $('.hideForm-avatar.ugolok .cr-slider').focus()
    $('.hideForm-avatar.ugolok .cr-slider').css('width', '100%')
  });

  $('.js-avatar-submit').on('click', function (ev) {
    ev.preventDefault()

    const type = $('#upload-input').data('avatar')

    let id = 0
    if(type == 'group') {
      id = parseInt($('#form-group-chat .js-submit-group-chat').data('id'))
    } else if(type == 'ugolok') {
      id = parseInt($('.hideForm-ugolok.ugolok #form-ugolok .js-element').val())
    }

    $('.hideForm-avatar.ugolok .section__item').show()

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

          $('.hideForm-avatar.ugolok .section__item').hide()

          if(type == 'user') {
            $('#main-profile.profile-avatar').attr('src', resp)
            //setTimeout(function() {
            close_form();
            //}, 1500);
          } else if(type == 'group') {
            console.log(data)
            $('#group-profile-avatar').attr('src', data.file)
            $('.hideForm-avatar.ugolok').fadeOut(250);
          } else if(type == 'ugolok') {
            console.log(data)
            $('#ugolok-avatar').attr('src', data.file)
            $('.hideForm-avatar.ugolok').fadeOut(250);
          }
        }
      });
    });
    return false;
  });

}); // document ready

function close_form_ugolok() {
  $('.hideForm-avatar.ugolok').fadeOut(250);
}
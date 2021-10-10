var curTime = Math.round(Date.now() / 1000);
var nLine = 0;
var todayLine = 1;
let inProgressGetBanner = false;
let offset = 0;
let promocode = '';
let promoDiscount = 0;
let promoLimit = 0;

const reloadPage = () => document.location.reload(true);

function validateEmail(email) {
  var pattern = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@(([[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return pattern.test(email);
}

function validateEmailVuchebe(email) {
  var pattern = /^(.*)?@vuchebe\.com$/;
  return pattern.test(email);
}

var priceSet;

priceSet = function (data) {
  /*
    * В переменной price приводим получаемую переменную в нужный вид:
    * 1. принудительно приводим тип в число с плавающей точкой,
    *    учли результат 'NAN' то по умолчанию 0
    * 2. фиксируем, что после точки только в сотых долях
    */
  var price = Number.prototype.toFixed.call(parseFloat(data) || 0, 2),
    //заменяем точку на запятую
    price_sep = price.replace(/(\D)/g, ","),
    //добавляем пробел как разделитель в целых
    price_sep = price_sep.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1 ");

  return price_sep + ' руб.';
};

var badEmailCheck = 1;

const calculatePrice = () => {

  const num = parseInt($('.form-banner .price.js-banner-price').val());

  const country = parseInt($('.form-banner .js-banner-country').val());
  const region = $('.form-banner .js-banner-region').val();
  const city = parseInt($('.form-banner .js-banner-city-current').val());

  let total = bannerPrice['BASE']['price'] * num;
  let originalPrice = bannerPrice['BASE']['price'];
  let originalName = bannerPrice['BASE']['name'];
  let tarifID = 'BASE';

  if (country > 0) {
    total = bannerPrice['COUNTRY']['price'] * num;
    originalPrice = bannerPrice['COUNTRY']['price'];
    originalName = bannerPrice['COUNTRY']['name'];
    tarifID = 'COUNTRY';
  }
  if (region != '0') {
    total = bannerPrice['REGION']['price'] * num;
    originalPrice = bannerPrice['REGION']['price'];
    originalName = bannerPrice['REGION']['name'];
    tarifID = 'REGION';
  }
  if (city > 0) {

    const capital = $('.form-banner .js-banner-city-capital').val();
    const mainCity = $('.form-banner .js-banner-city-main-city').val();

    if (capital === 'Y' && bannerPrice['CITY']['priceCapital']) {
      total = bannerPrice['CITY']['priceCapital'] * num;
      originalPrice = bannerPrice['CITY']['priceCapital'];
      originalName = bannerPrice['CITY']['name'] + ' (Столица)';
    } else if (mainCity === 'Y' && bannerPrice['CITY']['priceMainCity']) {
      total = bannerPrice['CITY']['priceMainCity'] * num;
      originalPrice = bannerPrice['CITY']['priceMainCity'];
      originalName = bannerPrice['CITY']['name'] + ' (Главный город)';
    } else {
      total = bannerPrice['CITY']['price'] * num;
      originalPrice = bannerPrice['CITY']['price'];
      originalName = bannerPrice['CITY']['name'];
    }
    tarifID = 'CITY';
  }

  if(promoDiscount) {
    if(promoLimit) {
      if(promoLimit >= num) {
        total = total / 100 * promoDiscount
      } else {
        const discountLimit = promoLimit * originalPrice / 100 * promoDiscount
        total -= discountLimit
      }
    } else {
      let discount = total / 100 * promoDiscount
      total -= discount
    }
  }

  $('.form-banner .js-price').text(priceSet(total));

  $('.form-banner .tarif-name.js-tarif').data('tarif', tarifID);
  $('.form-banner .tarif-name.js-tarif').text(originalName);
  $('.form-banner .js-cost').text(priceSet(originalPrice));

  return false;
}

const calculateGroupUser = () => {

  const all     = $('#chat div.all:not(.system)');
  const online  = $('#chat div.online');
  const admin   = $('#chat div.admin');
  const user    = $('#chat div.user');
  const teacher = $('#chat div.teacher');

  if (all)
    $('#group-filter span.js-all').text(all.length);
  else
    $('#group-filter span.js-all').text('0');

  if (online)
    $('#group-filter span.js-online').text(online.length);
  else
    $('#group-filter span.js-online').text('0');

  if (admin)
    $('#group-filter span.js-admin').text(admin.length);
  else
    $('#group-filter span.js-admin').text('0');

  if (user)
    $('#group-filter span.js-user').text(user.length);
  else
    $('#group-filter span.js-user').text('0');

  if (teacher)
    $('#group-filter span.js-teacher').text(teacher.length);
  else
    $('#group-filter span.js-teacher').text('0');

  return false;
}

$(document).ready(function () {

  $('#maket-button').click(function () {
    $(this).prev().fadeToggle(300);
  });

  $('.st-setting .setting-options').click(function () {
    $(this).next().slideToggle(500);
  });

  $('.st-select-city .st-search-city button').click(function () {
    $('.st-select-city .st-search-city input').val('');
  });


  $('.st-aside-menu .title .all').click(function () {
    $('.st-select-search input:checkbox').prop('checked', true);
    $('.st-select-search .select').addClass('st-cheked');
  });

  $('.st-aside-menu .title .reset').click(function () {
    $('.st-select-search input:checkbox').prop('checked', false);
    $('.st-select-search .select').removeClass('st-cheked');
  });

  $('.st-select-city.popup .close').click(function () {
    $('.st-select-city.popup, .foneBg-2').fadeOut(200);
  });

  $('.st-select-city .name-city').click(function () {
    $('.st-select-city.popup').fadeIn(200);
    $('.foneBg-2').fadeIn(200);
  });


  $('.st-baloon-ico').click(function () {
    $(this).children('.st-baloon').fadeToggle(200);
  });

  $('.st-carousel.news-2 .owl-carousel').owlCarousel({
    loop: true,
    mouseDrag: false,
    margin: 10,
    navText: ["", ""],
    nav: true,
    responsive: {
      0: {
        items: 1,
      },
      600: {
        items: 1,
      },
      1000: {
        items: 2,
        mouseDrag: false,
      }
    }
  });

  $('.st-carousel.news-3 .owl-carousel').owlCarousel({
    loop: true,
    mouseDrag: false,
    margin: 10,
    navText: ["", ""],
    nav: true,
    responsive: {
      0: {
        items: 1,
      },
      600: {
        items: 4,
      },
      1000: {
        items: 4,
        mouseDrag: false,
      }
    }
  });

  $('.st-carousel.news-33 .owl-carousel').owlCarousel({
    loop: ($('.st-carousel.news-33 .owl-carousel').children().length) === 1 ? false : true,
    mouseDrag: false,
    margin: 10,
    navText: ["", ""],
    nav: true,
    responsive: {
      0: {
        items: 1,
      },
      600: {
        items: 4,
      },
      1000: {
        items: 4,
        mouseDrag: false,
      }
    }
  });

  $('.btn-toggle').click(function (e) {
    if ($($(this).attr('data-toggle')).hasClass('visible')) {
      $($(this).attr('data-toggle')).addClass('visible');
    } else {
      $($(this).attr('data-toggle')).addClass('visible');
    }
    $($(this).attr('data-toggle')).slideToggle(450);
  });

  $("a.topLink").click(function () {
    $("html, body").animate({
      scrollTop: $($(this).attr("href")).offset().top + "px"
    }, {
      duration: 500
    });
    return false;
  });

  $('.st-select-search label input:checkbox:checked').each(function (index) {
    $(this).parent().parent().parent().addClass('st-cheked');
  });

  $('.st-select-search .select').click(function () {
    if ($(this).hasClass('st-cheked')) {
      $(this).removeClass('st-cheked');
    } else {
      $(this).addClass('st-cheked');
    }
  });

  setTimeout(function () {
    $('.style-input input, .style-select select').styler();
  }, 100);

  mask();

  click_button();

  // ----------------------- Многошаговая Регистрация ------------------------------
  //

  $('#form-reg .email').focus();

  $('#form-reg').on('blur', '.email', function () {

    let email = $('#form-reg .email').val();

    if (email.length < 4)
      return false;

    $.ajax({
      type: 'POST',
      url: '/ajax/check_email.php',
      data: { 'email': email },
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          if (result.res.length > 0) {
            badEmailCheck = 1;
            $('#form-reg .email').css('color', 'red');
            $('#form-reg .color-orange').css('color', 'red');
            $('#form-reg .color-orange').text('Такой E-mail уже используется');
            $('#form-reg .color-orange').show();

            $('#form-reg .success-duble-email-hide').hide()
            $('#form-reg .duble-email a').data('email', email);
            $('#form-reg .duble-email a span').text(email);
            $('#form-reg .duble-email span').text(email);
            $('#form-reg .duble-email-hide').show();
          } else {
            badEmailCheck = 0;
            $('#form-reg .color-orange').css('color', 'green');
            $('#form-reg .color-orange').text('OK');
            $('#form-reg .color-orange').show();
          }
        }
      }
    });



    return false;
  });

  var captha = 0;
  var current_step = 1;

  $('.js-btn-ok-1').on('click', function (e) {
    e.preventDefault();
    // Validation Step 1
    let email = $('#form-reg .email').val();
    let password = $('#form-reg .password').val();
    let password_confirm = $('#form-reg .password_confirm').val();
    let firstname = $('#form-reg .firstname').val();
    let lastname = $('#form-reg .lastname').val();
    let captcha_word = $('#form-reg .captcha_word').val();
    let captcha_sid = $('#form-reg .captcha_sid').val();

    $('.error-reset').css('color', '#a7a7a7');
    $('#form-reg .password').attr('type', 'password');
    $('#form-reg .password_confirm').attr('type', 'password');

    if (!validateEmail(email)) {
      $('#form-reg .email').css('color', 'red');
      $('#form-reg .email').val('Вы не правельно ввели Email');
      return false;
    }

    if (badEmailCheck) {
      $('#form-reg .email').css('color', 'red');
      $('#form-reg .color-orange').css('color', 'red');
      $('#form-reg .color-orange').text('Такой E-mail уже используется');
      $('#form-reg .color-orange').show();

      return false;
    }

    if (password.length < 6) {
      $('#form-reg .password').attr('type', 'text');
      $('#form-reg .password').css('color', 'red');
      $('#form-reg .password').val('Не менее 6 символов');
      return false;
    }

    if (password != password_confirm) {
      $('#form-reg .password_confirm').attr('type', 'text');
      $('#form-reg .password_confirm').css('color', 'red');
      $('#form-reg .password_confirm').val('Не совпадает с паролём');
      return false;
    }

    if (firstname.length == 0) {
      $('#form-reg .firstname').css('color', 'red');
      $('#form-reg .firstname').val('Введите ваше имя');
      return false;
    }

    if (lastname.length == 0) {
      $('#form-reg .lastname').css('color', 'red');
      $('#form-reg .lastname').val('Введите фамилию');
      return false;
    }

    if (captha) {
      $('.js-step').hide();
      $('.js-step-2').show();
      $('.br-step-2').css('color', '#000000');
      $('.br-step-2').css('cursor', 'pointer');
      current_step = 2;
      return false;
    }

    if (captcha_word.length == 0) {
      $('#form-reg .captcha_word').css('color', 'red');
      $('#form-reg .captcha_word').val('Заполните');
      return false;
    }

    let data_captcha = {};
    data_captcha['captcha_word'] = captcha_word;
    data_captcha['captcha_sid'] = captcha_sid;

    $.ajax({
      type: 'POST',
      url: '/ajax/captcha_check.php',
      data: data_captcha,
      dataType: 'json',
      success: function (result) {
        if (result.status) {
          if (result.status == 'success') {
            captha = 1;
            $('.captcha_word').attr('disabled', true);
            $('.js-step').hide();
            $('.js-step-2').show();
            $('.br-step-2').css('color', '#000000');
            $('.br-step-2').css('cursor', 'pointer');
            current_step = 2;
          } else {
            $('#form-reg .captcha_word').css('color', 'red');
            $('#form-reg .captcha_word').val('Неверный код');
            return false;
          }
        }
      }
    });

    return false;
  });

  $('.js-btn-ok-2, .js-btn-mv-2').on('click', function () {
    // Validation Step 2 is Ok
    //
    $('.js-step').hide();
    $('.js-step-3').show();
    $('.br-step-3').css('color', '#000000');
    $('.br-step-3').css('cursor', 'pointer');
    current_step = 3;

    return false;
  });

  $('#form-reg').on('change', '.js-law', function (e) {
    e.preventDefault();

    if ($("#form-reg .js-law").is(':checked')) {
      $("#form-reg .law-text").slideUp();
    } else {
      $("#form-reg .law-text").slideDown();
    }

    return false;
  });

  $('#form-reg').on('submit', function (e) {
    e.preventDefault();

    if (!$("#form-reg .js-law").is(':checked')) {
      $("#form-reg .law-text").slideDown();
      return false;
    } else {
      $("#form-reg .law-text").hide();
    }

    $.ajax({
      type: 'POST',
      url: '/ajax/register.php',
      data: $("#form-reg").serialize(),
      dataType: 'json',
      success: function (result) {
        if (result.status) {
          if (result.status == 'success') {
            //window.location = 'http://vuchebe.com/user/' + result.res + '/';

            $("#error-message").css('color', 'green');
            $("#error-message").text(result.message);
            $("#error-message").show();
          } else {
            $("#error-message").text(result.message);
            $("#error-message").show();
          }
        }
      }
    });

    return false;
  });

  $('.br-step-1').on('click', function () {
    if (current_step == 1)
      return false;
    $('.js-step').hide();
    $('.js-step-1').show();
    $('.br-step-2, .br-step-3').css('color', '#9f9f9f');
    $('.br-step-2, .br-step-3').css('cursor', 'default');
    current_step = 1;

    return false;
  });

  $('.br-step-2').on('click', function () {
    if (current_step <= 2)
      return false;
    $('.js-step').hide();
    $('.js-step-2').show();
    $('.br-step-3').css('color', '#9f9f9f');
    $('.br-step-3').css('cursor', 'default');
    current_step = 2;

    return false;
  });

  $('#cb').on('click', function () {
    $.getJSON('/ajax/captcha.php', function (data) {
      $('.capcha_img img').attr('src', '/bitrix/tools/captcha.php?captcha_sid=' + data);
      $('.captcha_sid').val(data);
    });
    return false;
  });

  $('#form-reg .email, #form-feedback .email, #form-profile .email, #pro-reg .email').on('focus', function () {
    $(this).css('color', '#a7a7a7');
    $('#form-reg .color-orange').hide();
    $('#pro-reg .color-orange').hide();
    //if($(this).val() == 'Вы не правельно ввели Email')
    $(this).val('');
    return false;
  });

  $('#form-reg .password').on('focus', function () {
    $(this).css('color', '#a7a7a7');
    $(this).attr('type', 'password');
    if ($(this).val() == 'Не менее 6 символов')
      $(this).val('');
    return false;
  });

  $('#form-reg .password_confirm').on('focus', function () {
    $(this).css('color', '#a7a7a7');
    $(this).attr('type', 'password');
    if ($(this).val() == 'Не совпадает с паролём')
      $(this).val('');
    return false;
  });

  $('#form-reg .firstname, #form-feedback .firstname, #form-profile .fname').on('focus', function () {
    $(this).css('color', '#a7a7a7');
    if ($(this).val() == 'Введите ваше имя')
      $(this).val('');
    return false;
  });

  $('#form-reg .lastname, #form-profile .lname').on('focus', function () {
    $(this).css('color', '#a7a7a7');
    if ($(this).val() == 'Введите фамилию')
      $(this).val('');
    return false;
  });

  $('#form-reg .captcha_word, #form-feedback .captcha_word, #form-news-uz-add .captcha_word').on('focus', function () {
    $(this).css('color', '#a7a7a7');
    if ($(this).val() == 'Заполните' || $(this).val() == 'Неверный код')
      $(this).val('');
    return false;
  });

  $('#form-reg').on('keyup', '.js-reg-city', function (e) {
    e.preventDefault();

    let $this = $(this);

    let city = $this.val();

    if (city.length < 2) {
      $('#form-reg .auto-complit').hide();
      $('#form-reg .auto-complit').empty();
      return false;
    }

    $.ajax({
      type: 'POST',
      url: '/ajax/reg_city.php',
      data: { 'str_city': city },
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          if (result.res.length > 0) {
            let re;
            let podstr = '';
            let html = '';
            $.each(result.res, function () {
              re = new RegExp(city, 'i');
              podstr = this.name.replace(re, '<b>' + city.replace(/(^|\s)\S/g, function (a) { return a.toUpperCase() }) + '</b>');
              let original = this.name;
              html += `
								<div class="item" data-id="${this.id}">
									<div data-original="${original}" data-reg="${this.region}">${podstr} <span style="font-size: 12px; margin-left: 10px; color: #a7a7a7;">${this.region}</span></div>
								</div>`;
            });
            $('#form-reg .auto-complit').empty();
            $('#form-reg .auto-complit').append(html);
            $('#form-reg .auto-complit').show();
          } else {
            $('#form-reg .auto-complit').hide();
            $('#form-reg .auto-complit').empty();
          }
        }
      }
    });

    return false;
  });

  $('#form-reg').on('click', '.auto-complit .item', function (e) {
    e.preventDefault();

    let $this = $(this);

    let cityId = $this.data('id');
    let cityName = $this.find('div').data('original');
    $('#form-reg .js-reg-city_id').val(cityId);
    $('#form-reg .js-reg-city').val(cityName);
    $('#form-reg .auto-complit').hide();
    $('#form-reg .auto-complit').empty();

    return false;
  });

  $('#form-reg').on('click', '.duble-email a', function (e) {
    e.preventDefault();

    const $this = $(this)

    const email = $this.data('email')

    $.ajax({
      type: 'POST',
      url: '/ajax/activate_two.php',
      data: { email },
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
            $('#form-reg .duble-email-hide').hide()
            $('#form-reg .success-duble-email-hide').show()
          } else {
            $('#form-reg .auto-complit').hide();
            $('#form-reg .auto-complit').empty();
          }
        }
    });

    return false;
  });

  /*  End Register  */

  $('#form-feedback .message_fb').on('focus', function () {
    $(this).css('color', '#a7a7a7');
    if ($(this).val() == 'Введите текст сообщения')
      $(this).val('');
    return false;
  });

  $('#form-feedback').on('change', '.js-law', function (e) {
    e.preventDefault();

    if ($("#form-feedback .js-law").is(':checked')) {
      $("#form-feedback .law-text").slideUp();
    } else {
      $("#form-feedback .law-text").slideDown();
    }

    return false;
  });

  $('#form-feedback').on('submit', function (e) {
    e.preventDefault();
    // Validation
    //
    let email = $('#form-feedback .email').val();
    let firstname = $('#form-feedback .firstname').val();
    let message_fb = $('#form-feedback .message_fb').val();
    let captcha_word = $('#form-feedback .captcha_word').val();

    $('.error-reset').css('color', '#a7a7a7');

    if (firstname.length == 0) {
      $('#form-feedback .firstname').css('color', 'red');
      $('#form-feedback .firstname').val('Введите ваше имя');
      return false;
    }

    if (!validateEmail(email)) {
      $('#form-feedback .email').css('color', 'red');
      $('#form-feedback .email').val('Вы не правельно ввели Email');
      return false;
    }

    if (message_fb.length == 0) {
      $('#form-feedback .message_fb').css('color', 'red');
      $('#form-feedback .message_fb').val('Введите текст сообщения');
      return false;
    }

    if (captcha_word.length == 0) {
      $('#form-feedback .captcha_word').css('color', 'red');
      $('#form-feedback .captcha_word').val('Заполните');
      return false;
    }

    if (!$("#form-feedback .js-law").is(':checked')) {
      $("#form-feedback .law-text").slideDown();
      return false;
    } else {
      $("#form-feedback .law-text").hide();
    }

    $.ajax({
      type: 'POST',
      url: '/ajax/feedback.php',
      data: $("#form-feedback").serialize(),
      dataType: 'json',
      success: function (result) {
        if (result.status) {
          if (result.status == 'success') {
            $('#form-feedback .firstname').val('');
            $('#form-feedback .email').val('');
            $('#form-feedback .message_fb').val('');
            $('#form-feedback .captcha_word').val('');

            $.getJSON('/ajax/captcha.php', function (data) {
              $('.capcha_img img').attr('src', '/bitrix/tools/captcha.php?captcha_sid=' + data);
              $('.captcha_sid').val(data);
            });

            $("#error-message").text(result.message);
            $('#error-message').css('color', 'green');
            $("#error-message").show();

          } else {
            $("#error-message").text(result.message);
            $("#error-message").show();
          }
        }
      }
    });

    return false;
  });

  $('#form-jobs').on('change', '.js-law', function (e) {
    e.preventDefault();

    if ($("#form-jobs .js-law").is(':checked')) {
      $("#form-jobs .law-text").slideUp();
    } else {
      $("#form-jobs .law-text").slideDown();
    }

    return false;
  });

  $('#form-jobs input, #form-jobs textarea').on('focus', function () {
    $(this).css('color', '#000');
    if ($(this).val() === 'Заполните' || $(this).val() === 'Email введён не корректно' || $(this).val() === 'Напишите о себе')
      $(this).val('');
    return false;
  });

  $('#form-jobs').on('submit', function (e) {
    e.preventDefault();

    // Validation
    let lastName = $('#form-jobs .last_name').val().trim();
    let firstName = $('#form-jobs .first_name').val().trim();
    let secondName = $('#form-jobs .second_name').val().trim();

    let email = $('#form-jobs .email').val();
    let phone = $('#form-jobs .phone').val();

    let message = $('#form-jobs .message').val().trim();

    let captcha_word = $('#form-jobs .captcha_word').val();

    $('.error-reset').css('color', '#a7a7a7');

    if (lastName.length === 0) {
      $('#form-jobs .last_name').css('color', 'red');
      $('#form-jobs .last_name').val('Заполните');
      return false;
    }

    if (firstName.length === 0) {
      $('#form-jobs .first_name').css('color', 'red');
      $('#form-jobs .first_name').val('Заполните');
      return false;
    }

    if (secondName.length === 0) {
      $('#form-jobs .second_name').css('color', 'red');
      $('#form-jobs .second_name').val('Заполните');
      return false;
    }

    if (!validateEmail(email)) {
      $('#form-jobs .email').css('color', 'red');
      $('#form-jobs .email').val('Email введён не корректно');
      return false;
    }

    if (phone.length === 0) {
      $('#form-jobs .phone').css('color', 'red');
      $('#form-jobs .phone').val('Заполните');
      return false;
    }

    let links = [];
    $("#form-jobs .links").each(function () {
      if ($(this).val())
        links.push($(this).val());
    });

    if (links.length === 0) {
      $('#form-jobs .links:first').css('color', 'red');
      $('#form-jobs .links:first').val('Заполните');
      return false;
    }

    if (message.length === 0) {
      $('#form-jobs .message').css('color', 'red');
      $('#form-jobs .message').val('Напишите о себе');
      return false;
    }

    if (captcha_word.length === 0) {
      $('#form-jobs .captcha_word').css('color', 'red');
      $('#form-jobs .captcha_word').val('Заполните');
      return false;
    }

    if (!$("#form-jobs .js-law").is(':checked')) {
      $("#form-jobs .law-text").slideDown();
      return false;
    } else {
      $("#form-jobs .law-text").hide();
    }

    $.ajax({
      type: 'POST',
      url: '/ajax/jobs_add.php',
      data: $("#form-jobs").serialize(),
      dataType: 'json',
      success: function (result) {
        if (result.status) {
          if (result.status == 'success') {

            $('#form-feedback .captcha_word').val('');

            $.getJSON('/ajax/captcha.php', function (data) {
              $('.capcha_img img').attr('src', '/bitrix/tools/captcha.php?captcha_sid=' + data);
              $('.captcha_sid').val(data);
            });

            $("#error-message").text(result.message);
            $('#error-message').css('color', 'green');
            $("#error-message").slideDown();

          } else {
            $("#error-message").text(result.message);
            $('#error-message').css('color', 'red');
            $("#error-message").slideDown();
          }
        }
      }
    });

    return false;
  });

  $('.js-profile-edit').click(function () {
    let city = '';
    if ($('.js-city'))
      city = $('.js-city').text().trim();

    let notes = '';
    if ($('.js-notes'))
      notes = $('.js-notes').text().trim();

    let pol = '';
    if ($('.js-pol'))
      pol = $('.js-pol').data('val');

    let vk = '';
    let fb = '';
    let ok = '';
    let tw = '';
    let inst = '';
    let you = '';
    let lj = '';

    if ($('.js-vk'))
      vk = $('.js-vk').attr('href');

    if ($('.js-fb'))
      fb = $('.js-fb').attr('href');

    if ($('.js-ok'))
      ok = $('.js-ok').attr('href');

    if ($('.js-tw'))
      tw = $('.js-tw').attr('href');

    if ($('.js-inst'))
      inst = $('.js-inst').attr('href');

    if ($('.js-you'))
      you = $('.js-you').attr('href');

    if ($('.js-lj'))
      lj = $('.js-lj').attr('href');

    let hideDay = 0;
    let hideStatus = 0;
    let hideCity = 0;
    let hidePhone = 0;
    let hideEmail = 0;
    let hideSoc = 0;
    let hideNote = 0;
    let hidePol = 0;

    if ($('.js-birthday').data('hide'))
      hideDay = $('.js-birthday').data('hide');
    if ($('.js-icq').data('hide'))
      hideStatus = $('.js-icq').data('hide');
    if ($('.js-r-city').data('hide'))
      hideCity = $('.js-r-city').data('hide');
    if ($('.js-phone').data('hide'))
      hidePhone = $('.js-phone').data('hide');
    if ($('.js-email-parent').data('hide'))
      hideEmail = $('.js-email-parent').data('hide');
    if ($('.js-links').data('hide'))
      hideSoc = $('.js-links').data('hide');
    if ($('.js-notes').data('hide'))
      hideNote = $('.js-notes').data('hide');
    if ($('.js-pol').data('hide'))
      hidePol = $('.js-pol').data('hide');

    $('.hideForm2 .fname').val(fname);
    $('.hideForm2 .lname').val(lname);
    $('.hideForm2 .sname').val(sname);

    $('.hideForm2 .day').find('option[value=' + day + ']').prop('selected', true);
    $('.hideForm2 .month').find('option[value=' + month + ']').prop('selected', true);
    $('.hideForm2 .year').find('option[value=' + year + ']').prop('selected', true);

    $('.hideForm2 .icq').val(icq);
    $('.hideForm2 .city').val(city);
    $('.hideForm2 .r-city').val(r_city);
    $('.hideForm2 .phone').val(phone);

    if (email == 'не установлен')
      email = '';
    $('.hideForm2 .email').val(email);

    $('.hideForm2 .vk').val(vk);
    $('.hideForm2 .fb').val(fb);
    $('.hideForm2 .ok').val(ok);
    $('.hideForm2 .tw').val(tw);
    $('.hideForm2 .inst').val(inst);
    $('.hideForm2 .you').val(you);
    $('.hideForm2 .lj').val(lj);
    $('.hideForm2 .notes').val(notes);
    $('input[name="PROFILE_POL"][value="' + pol + '"]').prop('checked', true);
    $('input[name="PROFILE_POL"][value="' + pol + '"]').addClass('active');

    var top_form = $(window).scrollTop();
    var height_form = $('.hideForm2 .form-open-block form').height();
    var marg_top = $(window).height() / 2;

    $('.hideForm2 .form-open-block').css({
      'height': $(window).height(),
      'position': 'absolute',
      'top': top_form,
    });
    $('.hideForm2').css({ 'height': $(document).height(), });

    $('.foneBg').css({ 'display': 'block' });

    $('.hideForm2 .hide-day').val(hideDay);
    if (hideDay) {
      $('.hideForm2 .hide-day-text').text('скрыто');
    }

    $('.hideForm2 .hide-status').val(hideStatus);
    if (hideStatus) {
      $('.hideForm2 .hide-status-text').text('скрыто');
    }

    $('.hideForm2 .hide-r-city').val(hideCity);
    if (hideCity) {
      $('.hideForm2 .hide-r-city-text').text('скрыто');
    }

    $('.hideForm2 .hide-phone').val(hidePhone);
    if (hidePhone) {
      $('.hideForm2 .hide-phone-text').text('скрыто');
    }

    $('.hideForm2 .hide-email').val(hideEmail);
    if (hideEmail) {
      $('.hideForm2 .hide-email-text').text('скрыто');
    }

    $('.hideForm2 .hide-soc').val(hideSoc);
    if (hideSoc) {
      $('.hideForm2 .hide-soc-text').text('скрыто');
    }

    $('.hideForm2 .hide-note').val(hideNote);
    if (hideNote) {
      $('.hideForm2 .hide-note-text').text('скрыто');
    }

    $('.hideForm2 .hide-pol').val(hidePol);
    if (hidePol) {
      $('.hideForm2 .hide-pol-text').text('скрыто');
    }

    $('.hideForm-profile-edit .form-open-block form .warning-text').hide();
    delText = 0;

    $('.hideForm2').fadeIn(250);

  });

  $('#form-profile').on('change', '.js-profile-country', function (e) {
    e.preventDefault();

    let $this = $(this);

    let countryId = $this.val();

    $.ajax({
      type: 'POST',
      url: '/ajax/profile_region.php',
      data: { 'country_id': countryId },
      dataType: 'json',
      success: function (result) {
        if (result.status && result.status == 'success') {
          if (result.res.REGION.length > 0) {
            let html = `<option value="0">Выберите</option>`;
            $.each(result.res.REGION, function (i, val) {
              html += `<option value="${val}">${val}</option>`;
            });
            $('#form-profile .js-profile-region').empty();
            $('#form-profile .js-profile-region').append(html);
            $('#form-profile .js-profile-region').attr('disabled', false);
            $('#form-profile .js-profile-city').val('');
            $('#form-profile .auto-complit').hide();
            $('#form-profile .auto-complit').empty();
          } else {
            $('#form-profile .js-profile-region').empty();
            $('#form-profile .js-profile-region').attr('disabled', true);
            $('#form-profile .js-profile-city').val('');
            $('#form-profile .auto-complit').hide();
            $('#form-profile .auto-complit').empty();
          }

        }
      }
    });

    return false;
  });

  $('#form-profile').on('change', '.js-profile-region', function (e) {
    e.preventDefault();

    $('#form-profile .js-profile-city').val('');
    $('#form-profile .auto-complit').hide();
    $('#form-profile .auto-complit').empty();

    return false;
  });

  $('#form-profile').on('keyup', '.js-profile-city', function (e) {
    e.preventDefault();

    let $this = $(this);

    let countryId = $('#form-profile .js-profile-country').val();
    let regionName = $('#form-profile .js-profile-region').val();
    let strCity = $this.val();

    if (!strCity) {
      $('#form-profile .auto-complit').hide();
      $('#form-profile .auto-complit').empty();
      return false;
    }

    $.ajax({
      type: 'POST',
      url: '/ajax/profile_city.php',
      data: { 'country_id': countryId, 'region_name': regionName, 'str_city': strCity },
      dataType: 'json',
      success: function (result) {
        if (result.status && result.status == 'success') {
          let html = '';
          if (result.res.CITY.length > 0) {
            $.each(result.res.CITY, function () {
              if (this) {
                var re = new RegExp(strCity, 'i');
                podstr = this.NAME.replace(re, '<b>' + strCity + '</b>');
                html += '<div class="item"><div>' + podstr + '</div></div>';
              }
            });
            $('#form-profile .auto-complit').empty();
            $('#form-profile .auto-complit').append(html);
            $('#form-profile .auto-complit').show();
          } else {
            $('#form-profile .auto-complit').hide();
            $('#form-profile .auto-complit').empty();
          }
        }
      }
    });

    return false;
  });

  $('#form-profile').on('click', '.auto-complit .item', function (e) {
    e.preventDefault();

    let $this = $(this);

    let cityName = $this.find('div').text();
    $('#form-profile .js-profile-city').val(cityName);
    $('#form-profile .auto-complit').hide();
    $('#form-profile .auto-complit').empty();

    return false;
  });

  $('#form-profile .hide-day-text, #form-profile .hide-status-text, #form-profile .hide-r-city-text, #form-profile .hide-phone-text, #form-profile .hide-email-text, #form-profile .hide-soc-text, #form-profile .hide-note-text, #form-profile .hide-pol-text').on('click', function (e) {
    e.preventDefault();

    //let $this = $(this);
    let strButton = '';

    let dataHide = $(this).next('input').val();
    let testInt = parseInt(dataHide);

    if (testInt == 1) {
      strButton = $(this).data('show');
      $(this).text(strButton);
      $(this).next().val('0');
    } else {
      $(this).text("скрыто");
      $(this).next().val('1');
    }

    return false;
  });

  $('#form-profile').on('click', '.js-law', function (e) {
    //e.preventDefault();

    console.log($(this).val(), $('input[name="PROFILE_POL"]:checked').val())

    if($('input[name="PROFILE_POL"]:checked').hasClass('active')) {
      $('input[name="PROFILE_POL"]').prop('checked', false);
      $('input[name="PROFILE_POL"]').removeClass('active')
    } else {
      $('input[name="PROFILE_POL"]').removeClass('active')
      $(this).addClass('active')
    }

    //return false;
  });

  $('.js-submit-profile').on('click', function (e) {
    e.preventDefault();
    // Validation
    //
    fname = $('.hideForm2 .fname').val().trim();
    lname = $('.hideForm2 .lname').val().trim();
    sname = $('.hideForm2 .sname').val().trim();

    day = $('.hideForm2 .day').val();
    month = $('.hideForm2 .month').val();
    year = $('.hideForm2 .year').val();

    icq = $('.hideForm2 .icq').val().trim();
    city = $('.hideForm2 .city').val().trim();
    r_city = $('.hideForm2 .r-city').val().trim();
    phone = $('.hideForm2 .phone').val().trim();
    email = $('.hideForm2 .email').val().trim();
    notes = $('.hideForm2 .notes').val().trim();
    let vk = $('.hideForm2 .vk').val().trim();
    let fb = $('.hideForm2 .fb').val().trim();
    let ok = $('.hideForm2 .ok').val().trim();
    let tw = $('.hideForm2 .tw').val().trim();
    let inst = $('.hideForm2 .inst').val().trim();
    let you = $('.hideForm2 .you').val().trim();
    let lj = $('.hideForm2 .lj').val().trim();

    $('.error-reset').css('color', '#a7a7a7');

    if (fname.length == 0) {
      $('#form-profile .fname').css('color', 'red');
      $('#form-profile .fname').val('Введите ваше имя');
      return false;
    }

    if (lname.length == 0) {
      $('#form-profile .lname').css('color', 'red');
      $('#form-profile .lname').val('Введите фамилию');
      return false;
    }

    if (!validateEmail(email)) {
      $('#form-profile .email').css('color', 'red');
      $('#form-profile .email').val('Вы не правельно ввели Email');
      return false;
    }

    $.ajax({
      type: 'POST',
      url: '/ajax/profile.php',
      data: $("#form-profile").serialize(),
      dataType: 'json',
      success: function (result) {
        if (result.status) {
          if (result.status == 'success') {
            $('.user-full-name').text(fname + ' ' + lname);
            $('.js-name-user').html(result.message);
            $('.js-name-user').data('fname', fname);
            $('.js-name-user').data('lname', lname);

            if (result.bday) {
              $('.js-birthday').text(result.bday);
              $('.js-birthday-show').show();
            } else {
              $('.js-birthday-show').hide();
            }

            if (icq == '')
              icq = 'не установлен';
            $('.js-icq').text(icq);

            if (city == '')
              city = 'не установлен';
            $('.js-city').text(city);

            if (r_city == '')
              r_city = 'не установлен';
            $('.js-r-city').text(r_city);

            if (phone == '')
              phone = 'не установлен';
            $('.js-phone').text(phone);

            $('.js-email').attr('href', 'mailto:' + email);
            $('.js-email').text(email);

            $('.js-vk').attr('href', vk);
            $('.js-fb').attr('href', fb);
            $('.js-ok').attr('href', ok);
            $('.js-tw').attr('href', tw);
            $('.js-inst').attr('href', inst);
            $('.js-you').attr('href', you);
            $('.js-lj').attr('href', lj);

            if (notes == '')
              notes = 'не установлено';
            $('.js-notes').text(notes);

            document.location.reload(true);
          } else {
            $("#error-message-profile").text(result.message);
            $("#error-message-profile").show();
          }
        }
      }
    });

    return false;
  });

  $('.add-group-chat').click(function (e) {
    e.preventDefault();

    const $this = $(this);

    const id = parseInt($this.data('id'));

    $('.hideForm-group-chat .form-open-block form .warning-text').hide();

    var top_form = $(window).scrollTop();
    var height_form = $('.hideForm-group-chat .form-open-block form').height();
    var marg_top = $(window).height() / 2;

    $('.hideForm-group-chat .form-open-block').css({
      'height': $(window).height(),
      'position': 'absolute',
      'top': top_form,
    });
    $('.hideForm-group-chat').css({ 'height': $(document).height(), });

    $('#form-group-chat .js-name').val('')
    $('#form-group-chat .js-add-user').val('')
    $('#form-group-chat .auto-complit').hide()
    $('#form-group-chat .auto-complit').empty()

    $('#form-group-chat .js-error-block').hide()

    if (id > 0) {

      $.ajax({
        type: 'POST',
        url: '/ajax/chat_group_setting.php',
        data: { id },
        dataType: 'json',
        success: function (result) {
          if (result.status) {
            if (result.status == 'success') {

              $('#form-group-chat .js-submit-group-chat').data('owner', result.info.owner)
              $('#form-group-chat .js-name').val(result.info.name)

              if(result.info.avatar) {
                $('#form-group-chat img.profile-avatar').attr('src', result.info.avatar)
              }

              if (result.user.length > 0) {

                let html = ``;
                $.each(result.user, function () {
                  html += `
                    <div class="row-line mt-10 user-group-chat${this.ID === result.info.owner ? ' owner' : ''}" data-id="${this.ID}">
                      <div class="col-12">
                        <div class="${this.TEACHER ? 'user-name-te' : 'user-name-st'}">
                          <a href="/user/${this.ID}/" target="_blank"><img src="${this.AVATAR}"/></a>
                        </div>
                        <div class="user-name-st name">
                          <a href="/user/${this.ID}/" target="_blank">${this.NAME_DISPLAY}</a>
                        </div>
                        <div class="label user-name-st check">
                          <label class="radio">
                              <input class="js-admin" type="checkbox" name="admin[]" value="${this.ID}"${this.ADMIN ? ' checked' : ''} />
                              <div class="radio__text"></div>
                          </label>
                        </div>
                        <div class="user-name-st" style="float: right;">
                          <span class="color-silver js-del-user-chat">Удалить</span>
                        </div>
                      </div>
                    </div>
                  `;
                });
                $('#form-group-chat #group-user').empty();
                $('#form-group-chat #group-user').append(html);

                $('#form-group-chat .js-submit-group-chat').data('id', id)
                $('#form-group-chat .js-clear-div span.js-del-group-post').data('chat', id)

                $('#form-group-chat .js-clear-div').show()
              }
            } else {
              $("#error-message-setting").text(result.message);
              $("#error-message-setting").show();
            }
          }
        }
      });
    } else {

      $('#form-group-chat .js-del-user-chat').hide()
      $('#form-group-chat .js-admin').attr('disabled', true)

      $('#form-group-chat .owner').removeClass('new')
      $('#form-group-chat .owner').addClass('user-group-chat')

      $('#form-group-chat .user-group-chat.new').remove()

      $('#form-group-chat .user-group-chat.owner').show()
    }

    $('.foneBg').css({ 'display': 'block' });

    $('.hideForm-group-chat').fadeIn(250);

    return false;
  });

  $('#form-group-chat').on('input change', '.js-add-user', function (e) {
    e.preventDefault();

    let $this = $(this);

    let strUser = $this.val();

    if (!strUser) {
      $('#form-group-chat .auto-complit').hide();
      $('#form-group-chat .auto-complit').empty();
      return false;
    }

    let book = 0;

    if ($('#form-group-chat .js-in-book').is(':checked'))
      book = 1;

    let load = [];

    $('#form-group-chat .user-group-chat').each(function (index, value) {
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
            $('#form-group-chat .auto-complit').empty();
            $('#form-group-chat .auto-complit').append(html);
            $('#form-group-chat .auto-complit').show();
          } else {
            $('#form-group-chat .auto-complit').hide();
            $('#form-group-chat .auto-complit').empty();
          }
        }
      }
    });

    return false;
  });

  $('#form-group-chat').on('change', '.js-in-book', function () {
    $('#form-group-chat .js-add-user').change()
  });

  $('#form-group-chat').on('click', '.auto-complit .item', function (e) {
    //e.preventDefault();

    const $this = $(this);

    const id = $this.data('id');
    const origin = $this.data('origin');
    const avatar = $this.data('avatar');
    const type = $this.data('type');
    const online = $this.data('online');

    let load = [];
    let loadId = 0;

    $('#form-group-chat .js-add-user').val('');
    $('#form-group-chat .auto-complit').hide();
    $('#form-group-chat .auto-complit').empty();
    $('#form-group-chat .js-add-user').focus();

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
          <div class="label user-name-st check">
            <label class="radio">
                <input class="js-admin" type="checkbox" name="admin[]" value="${id}" />
                <div class="radio__text"></div>
            </label>
          </div>
          <div class="user-name-st" style="float: right;">
            <span class="color-silver js-del-user-chat">Удалить</span>
          </div>
        </div>
      </div>
    `;

    $('#form-group-chat #group-user').append(html);

    $('#form-group-chat .user-group-chat').each(function (index, value) {
      loadId = $(this).data('id');
      load.push(loadId);
    });

    if (load.length > 1) {
      $('#form-group-chat .js-del-user-chat').show()
      $('#form-group-chat .js-admin').attr('disabled', false)
    } else {
      $('#form-group-chat .js-del-user-chat').hide()
      $('#form-group-chat .js-admin').attr('disabled', true)
    }

    $('#form-group-chat #group-user .user-group-chat.new:last').slideDown();


    //return false;
  });

  $('#form-group-chat').on('click', '.js-del-user-chat', function () {

    const $this = $(this);

    let load = [];
    let loadId = 0;

    const el = $this.closest('.user-group-chat');

    el.slideUp();
    el.removeClass('user-group-chat');

    $('#form-group-chat .user-group-chat').each(function (index, value) {
      loadId = $(this).data('id');
      load.push(loadId);
    });

    if (load.length > 1) {
      $('#form-group-chat .js-del-user-chat').show()
      $('#form-group-chat .js-admin').attr('disabled', false)
    } else {
      $('#form-group-chat .js-del-user-chat').hide()
      $('#form-group-chat .user-group-chat .js-admin').attr('disabled', true)
      $('#form-group-chat .user-group-chat .js-admin').attr('checked', true)
      $('#form-group-chat .js-error-block').hide()
    }

    return false;
  });

  $('#form-group-chat').on('click', '.js-submit-group-chat', function (e) {
    e.preventDefault();

    if (pro)
      return false;

    const $this = $(this);

    $('#form-group-chat .js-error-block').hide()

    const id = parseInt($this.data('id'))
    const owner = parseInt($this.data('owner'))
    const name = $('#form-group-chat .js-name').val()
    const avatar = $('#form-group-chat img.profile-avatar').attr('src')

    if (name.length <= 0) {
      $('#form-group-chat .js-error-name').text('Введите название чата')
      $('#form-group-chat .js-error-name').show()
      return false
    }

    let users = []
    let userId = 0

    $('#form-group-chat .user-group-chat').each(function (index, value) {
      userId = $(this).data('id')
      users.push(userId)
    });

    if (users.length <= 0) {
      $('#form-group-chat .js-error-group-user').text('Необходим хотя бы один член группы')
      $('#form-group-chat .js-error-group-user').show()
      return false
    }

    let admins = []
    let adminId = 0

    if (users.length == 1) {
      admins.push(users[0])
    } else {
      $('#form-group-chat .user-group-chat .js-admin:checked').each(function (index, value) {
        adminId = parseInt($(this).val())
        admins.push(adminId)
      });
    }

    if (admins.length <= 0) {
      $('#form-group-chat .js-error-group-user').text('Установите администратора')
      $('#form-group-chat .js-error-group-user').show()
      return false
    }

    let message = 'Групповой чат успешно создан.';
    $.ajax({
      type: 'POST',
      url: '/ajax/group_chat.php',
      data: { id, owner, name, users, admins, avatar },
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          console.log(result);
          if (id)
            message = 'Групповой чат успешно изменён.';

          const html = `
					<div class="row-line" style="margin: 50px 0;">
					  <div class="col-12">
						<div style="font-size: 24px; line-height: 1.3; color: green; text-align: center;">
						  ${message}<br/>
						</div>
					  </div>
					</div>
				  `;

          $('#form-group-chat .row-line').remove();
          $('#form-group-chat #error-message-setting').after(html);

          setTimeout(reloadPage, 2000);

        } else if (result.status == 'error') {
          $('#form-group-chat .js-error-name').text(result.message)
          $('#form-group-chat .js-error-name').show()
        }
      }
    });

    return false;
  });

  $('.js-del-user-chat-always').on('click', function (e) {
    e.preventDefault();

    if (pro)
      return false;

    const $this = $(this);

    const id = parseInt($this.data('id'))
    const chat = parseInt($this.data('chat-id'))

    $.ajax({
      type: 'POST',
      url: '/ajax/del_user_group_chat.php',
      data: { id, chat },
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          document.location = `https://vuchebe.com/user/${result.return}/dialogs/`;
        }
      }
    });

    return false;
  });

  $('#chat, #form-group-chat').on('click', '.js-del-group-post', function (e) {
    e.preventDefault();

    if (pro)
      return false;

    const $this = $(this);

    const id = parseInt($this.data('id-post'))
    const chat = parseInt($this.data('chat'))
    const owner = parseInt($this.data('owner'))

    $.ajax({
      type: 'POST',
      url: '/ajax/del_user_group_post.php',
      data: { id, chat, owner },
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          if (id) {
            const el = $this.closest('.all')
            el.removeClass('all admin user teacher')
            el.slideUp()
            calculateGroupUser()
          } else {
            document.location.reload(true);
          }
        }
      }
    });

    return false;
  });

  $(".js-group-list").on('click', function () {
    $(".js-group-list").removeClass('color-silver');
    $(this).addClass('color-silver');
    filter = $(this).data('filter');
    $("#chat .all").hide();
    if (filter == 'all') {
      $("#chat .all").fadeIn(1000);
    } else {
      $("#chat ." + filter).fadeIn(1000);
    }
    $("#chat").animate({ scrollTop: $("#chat").prop("scrollHeight") }, "slow");
    return false;
  });

  $("#group-filter .js-message-list").on('click', function () {
    $(".js-message-list").removeClass('color-silver');
    $(this).addClass('color-silver');
    filter = $(this).data('filter');
    $("#chat .all").hide();
    if (filter == 'all') {
      $("#chat .all").fadeIn(1000);
    } else {
      $("#chat ." + filter).fadeIn(1000);
    }
    return false;
  });

  $('#form-group-chat-post').on('submit', function (e) {
    e.preventDefault();

    $("#error-message").hide();

    const chat = parseInt($('#form-group-chat-post .chat_id').val(), 10);
    const owner = parseInt($('#form-group-chat-post .owner-id').val(), 10);
    const user = parseInt($('#form-group-chat-post .user-id').val(), 10);
    const message = $('#form-group-chat-post .message').val().trim();

    const type = $('#group-filter.m-header .color-silver').data('filter');

    if (chat <= 0 || owner <= 0 || user <= 0) {
      $("#error-message").text('Ошибка чата. Перезагрузите страницу.');
      $("#error-message").show();
      return false;
    }

    if (message.length == 0) {
      $('#form-group-chat-post .message').css('color', 'red');
      $('#form-group-chat-post .message').val('Введите текст сообщения');
      return false;
    }

    $.ajax({
      type: 'POST',
      url: '/ajax/chat_group_post.php',
      data: $("#form-group-chat-post").serialize(),
      dataType: 'json',
      success: function (result) {
        if (result.status) {
          if (result.status == 'success') {
            $('#form-group-chat-post .message').val('');
            $('#form-group-chat-post .message').focus();

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

            if (type == 'online') {
              displayNone = result.add.online ? '' : ' style="display: none;"';
            }

            if (type == 'admin') {
              displayNone = result.add.admin ? '' : ' style="display: none;"';
            }

            if (type == 'user') {
              displayNone = result.add.user ? '' : ' style="display: none;"';
            }

            if (type == 'teacher') {
              displayNone = result.add.teacher ? '' : ' style="display: none;"';
            }

            html += '<div data-res="' + result.add.id + '" class="chat-right online-user-' + result.add.user_id + ' all' + result.add.class + '"' + displayNone + '>';
            html += '<div class="message_chat_wrapper" style="position: relative;">';
            html += '<div class="message_chat_user">';
            html += '<a href="/user/' + user + '/">Я</a> <span>' + result.add.time + '</span>';
            html += '</div>';
            if (result.add.color) {
              html += '<div class="message_chat" style="margin-right: 6px; position: relative;"><div class="del-mes-right js-del" style="bottom: 12px; left: -72px;" data-type="bookmark" data-id="' + result.add.id + '" data-pos="right">в закадки</div><div class="del-mes-right js-del-group-post" style="bottom: -1px; left: -60px;" data-type="post" data-id-post="' + result.add.id + '" data-owner="' + result.add.user_id + '" data-chat="' + result.add.chat + '">удалить</div>' + result.add.message + '</div>';
              html += '<img style="right: 7px;" class="avatar_duz" src="/upload/main/ug_right_3.png">';
            } else {
              html += '<div id="chat-id-' + result.add.id + '" class="message_chat no_show_ajax no_show" data-id="' + result.add.id + '"><div class="del-mes-right js-del" style="bottom: 12px; left: -72px;" data-type="bookmark" data-id="' + result.add.id + '" data-pos="right">в закадки</div><div class="del-mes-right js-del-group-post" style="bottom: -1px; left: -60px;" data-type="post" data-id-post="' + result.add.id + '" data-owner="' + result.add.user_id + '" data-chat="' + result.add.chat + '">удалить</div>' + result.add.message + '</div>';
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
            $("#chat").animate({ scrollTop: $("#chat").prop("scrollHeight") }, "slow");
          } else {
            if (result.add.event == 'block') {

              let popupSelector = '.hideForm-news-edit.no-chat';

              openPopup(popupSelector);

              $('.foneBg').css({ 'display': 'block' });
              $(popupSelector).fadeIn(250);
            } else {
              $("#error-message").text(result.message);
              $("#error-message").show();
            }
          }
        }
      }
    });

    return false;
  });

  $('#form-chat .message').on('focus', function () {
    $(this).css('color', '#000');
    if ($(this).val() == 'Введите текст сообщения')
      $(this).val('');
    return false;
  });

  var files;

  $('input[type=file]#file').on('change', function (e) {
    //e.stopPropagation();
    //e.preventDefault();

    const $this = $(this);
    const typeUpload = $(this).data('type');

    $('#file').blur();
    if (!this.files.length) return;



    $('.form-banner .js-error-img').hide();

    var data = new FormData();
    data.append('my', this.files[0]);
    data.append('upload', 1);
    data.append('tu', typeUpload);

    if (typeUpload == 'logo' || typeUpload == 'history') {
      data.append('vuz', $(this).data('id'));
      data.append('iblock', $(this).data('iblock'));
    }

    $.ajax({
      url: '/ajax/profile_avatar.php',
      type: 'POST', // важно!
      data: data,
      cache: false,
      dataType: 'json',
      processData: false,
      contentType: false,
      success: function (respond, status, jqXHR) {
        $('.profile-avatar').attr('src', respond.file);
        if (typeUpload == 'avatar')
          $('.user-name img.ava').attr('src', respond.file);
        if (typeUpload == 'banner')
          $('.form-banner img.profile-banner').attr('src', respond.file);
      },
      error: function (jqXHR, status, errorThrown) {
        console.log('ОШИБКА AJAX запроса: ' + status, jqXHR);
      }
    });
  });

  $('.form-banner').on('change', '.js-banner-country', function () {

    $('.form-banner .js-error-country').hide();

    let text = bannerPrice['BASE']['description'];

    const country = parseInt($('.form-banner .js-banner-country').val());

    if (country > 0) {

      text = bannerPrice['COUNTRY']['description'];

      $.ajax({
        type: 'POST',
        url: '/ajax/profile_region.php',
        data: { 'country_id': country },
        dataType: 'json',
        success: function (result) {
          let html = `<option value="0">Выберите</option>`;
          if (result.status && result.status == 'success') {
            if (result.res.REGION.length > 0) {
              $.each(result.res.REGION, function (i, val) {
                html += `<option value="${val}">${val}</option>`;
              });
            }
          }
          $('.form-banner .js-banner-region').empty();
          $('.form-banner .js-banner-region').append(html);
        }
      });
    }

    $('.form-banner .js-banner-region option[value=0]').prop('selected', true);

    $('.form-banner .js-banner-city-current').val(0);
    $('.form-banner .js-banner-city').val('');

    $('.form-banner .auto-complit').hide();
    $('.form-banner .auto-complit').empty();

    $('.form-banner .js-banner-title').text(text);

    calculatePrice();
    return false;
  });

  $('.form-banner').on('change', '.js-banner-region', function () {

    $('.form-banner .js-error-region').hide();

    let text = bannerPrice['COUNTRY']['description'];

    const region = $('.form-banner .js-banner-region').val();

    if (region != '0') {
      text = bannerPrice['REGION']['description'];
    }

    $('.form-banner .js-banner-city-current').val(0);
    $('.form-banner .js-banner-city').val('');

    $('.form-banner .auto-complit').hide();
    $('.form-banner .auto-complit').empty();

    $('.form-banner .js-banner-title').text(text);

    calculatePrice();
    return false;
  });

  $('.form-banner .js-banner-city-current').on('change', function () {

    let text = bannerPrice['REGION']['description'];

    const city = parseInt($('.form-banner .js-banner-city-current').val());

    if (city > 0) {
      text = bannerPrice['CITY']['description'];
    }

    $('.form-banner .js-banner-title').text(text);

    calculatePrice();
    return false;
  });

  $('.form-banner .price.js-banner-price').on('change keyup', function () {

    calculatePrice();
    return false;
  });

  $('.form-banner').on('keyup', '.js-banner-city', function (e) {
    e.preventDefault();

    let $this = $(this);

    const country = parseInt($('.form-banner .js-banner-country').val());
    const region = $('.form-banner .js-banner-region').val();

    let strCity = $this.val();

    $('.form-banner .js-error-city').hide();

    if (!country) {
      $('.form-banner .js-error-country').text('Нужно указать страну');
      $('.form-banner .js-error-country').fadeIn();
      $('.form-banner .auto-complit').hide();
      $('.form-banner .auto-complit').empty();
      return false;
    }

    if (region == '0') {
      $('.form-banner .js-error-region').text('Нужно указать регион');
      $('.form-banner .js-error-region').fadeIn();
      $('.form-banner .auto-complit').hide();
      $('.form-banner .auto-complit').empty();
      return false;
    }

    if (strCity.length < 2) {
      $('.form-banner .auto-complit').hide();
      $('.form-banner .auto-complit').empty();
      return false;
    }

    $.ajax({
      type: 'POST',
      url: '/ajax/profile_city.php',
      data: { 'country_id': country, 'region_name': region, 'str_city': strCity },
      dataType: 'json',
      success: function (result) {
        if (result.status && result.status == 'success') {
          let html = '';
          if (result.res.CITY.length > 0) {
            let re;
            let podstr = '';
            let html = '';
            $.each(result.res.CITY, function () {
              re = new RegExp(strCity, 'i');
              podstr = this.NAME.replace(re, '<b>' + strCity.replace(/(^|\s)\S/g, function (a) { return a.toUpperCase() }) + '</b>');
              let original = this.NAME;
              html += `
								<div class="item" data-id="${this.ID}" data-main-city="${this.PROPERTY_TOPCITY_VALUE}" data-capital="${this.PROPERTY_CAPITAL_VALUE}" data-original="${original}">${podstr}</div>`;
            });
            $('.form-banner .auto-complit').empty();
            $('.form-banner .auto-complit').append(html);
            $('.form-banner .auto-complit').show();
          } else {
            $('.form-banner .auto-complit').hide();
            $('.form-banner .auto-complit').empty();
          }
        }
      }
    });

    return false;
  });

  $('.form-banner').on('click', '.auto-complit .item', function (e) {
    e.preventDefault();

    let $this = $(this);

    const cityId = $this.data('id');
    const cityName = $this.data('original');
    const mainCity = $this.data('main-city');
    const capital = $this.data('capital');

    $('.form-banner .js-banner-city').val(cityName);
    $('.form-banner .auto-complit').hide();
    $('.form-banner .auto-complit').empty();

    $('.form-banner .js-banner-city-current').val(cityId);
    $('.form-banner .js-banner-city-main-city').val(mainCity);
    $('.form-banner .js-banner-city-capital').val(capital);
    $('.form-banner .js-banner-city-current').change();

    return false;
  });

  $('.form-banner').on('change', '.js-law', function (e) {
    e.preventDefault();

    if ($(".form-banner .js-law").is(':checked')) {
      $(".form-banner .law-text").slideUp();
    } else {
      $(".form-banner .law-text").slideDown();
    }

    return false;
  });

  $('.form-banner').on('click', '.js-add-promocode', function (e) {
    e.preventDefault();

    const promoStr = $('.form-banner .js-banner-promocode').val().trim();

    if(promoStr.length <= 0) {
      promocode = ''
      promoDiscount = 0
      promoLimit = 0
      $('.form-banner .js-promocode-info').css('color', 'red')
      $('.form-banner .js-promocode-info').text('вы не ввели промокод')
      $('.form-banner .js-promocode-info').show()
      $('.form-banner .js-banner-promocode').prop('disabled', false)
      $('.form-banner .js-banner-promocode').css('color', '#000000')
      calculatePrice();
      return false;
    }

    $.ajax({
      type: 'POST',
      url: '/ajax/promocode_check.php',
      data: { promo: promoStr},
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          promocode = result.promo.str
          promoDiscount = result.promo.percent
          promoLimit = result.promo.limit
          $('.form-banner .js-promocode-info').css('color', 'green')
          $('.form-banner .js-promocode-info').text(result.promo.title)
          $('.form-banner .js-promocode-info').show()
          $('.form-banner .js-banner-promocode').prop('disabled', true)
          $('.form-banner .js-banner-promocode').css('color', '#9f9f9f')
        } else {
          promocode = ''
          promoDiscount = 0
          promoLimit = 0
          $('.form-banner .js-promocode-info').css('color', 'red')
          $('.form-banner .js-promocode-info').text('недействителен')
          $('.form-banner .js-promocode-info').show()
          $('.form-banner .js-banner-promocode').prop('disabled', false)
          $('.form-banner .js-banner-promocode').css('color', '#000000')
        }
        calculatePrice();
      }
    });


    if ($(".form-banner .js-law").is(':checked')) {
      $(".form-banner .law-text").slideUp();
    } else {
      $(".form-banner .law-text").slideDown();
    }

    return false;
  });

  $('.form-banner').on('click', '.js-add-banner', function (e) {
    e.preventDefault();
    // Validation

    $('.form-banner .js-error-block').hide();

    const img = $('.form-banner .profile-banner').attr('src');
    const type = $('.form-banner .profile-banner').data('type');

    const name = $('.form-banner .js-banner-name').val().trim();
    const link = $('.form-banner .js-banner-link').val().trim();

    if (img == '/local/templates/vuchebe/images/empty_banner.png' ||
      img == '/local/templates/vuchebe/images/empty_banner2.png') {

      $('.form-banner .js-error-img').text('Загрузите пожалуйста баннер');
      $('.form-banner .js-error-img').fadeIn();
      return false;
    }

    if (name.length == 0) {
      $('.form-banner .js-error-name').text('Вы не ввели название баннера');
      $('.form-banner .js-error-name').fadeIn();
      return false;
    }

    if (link.length == 0) {
      $('.form-banner .js-error-link').text('Вы не ввели ссылку');
      $('.form-banner .js-error-link').fadeIn();
      return false;
    }

    const captcha_word = $('.form-banner .captcha_word').val();
    const captcha_sid = $('.form-banner .captcha_sid').val();

    if (captcha_word.length == 0) {
      $('.form-banner .st-captcha-input .label').hide();
      $('.form-banner .st-captcha-input .label').text('Вы не ввели код');
      $('.form-banner .st-captcha-input .label').css('color', '#ff471a');
      $('.form-banner .st-captcha-input .label').fadeIn();
      return false;
    }

    if (!$(".form-banner .js-law").is(':checked')) {
      $(".form-banner .law-text").slideDown();
      return false;
    } else {
      $(".form-banner .law-text").hide();
    }

    const num = parseInt($('.form-banner .price.js-banner-price').val());

    const country = parseInt($('.form-banner .js-banner-country').val());
    const region = $('.form-banner .js-banner-region').val();
    const city = parseInt($('.form-banner .js-banner-city-current').val());

    // Определяем тарифный план и стоимость

    let plan = 'BASE';
    let planTax = bannerPrice[plan]['price'];
    let total = bannerPrice[plan]['price'] * num;

    if (country > 0) {
      plan = 'COUNTRY';
      planTax = bannerPrice[plan]['price'];
      total = bannerPrice[plan]['price'] * num;
    }
    if (region != '0') {
      plan = 'REGION';
      planTax = bannerPrice[plan]['price'];
      total = bannerPrice[plan]['price'] * num;
    }
    if (city > 0) {
      plan = 'CITY';

      const capital = $('.form-banner .js-banner-city-capital').val();
      const mainCity = $('.form-banner .js-banner-city-main-city').val();

      if (capital === 'Y' && bannerPrice[plan]['priceCapital']) {
        planTax = bannerPrice[plan]['priceCapital'];
      } else if (mainCity === 'Y' && bannerPrice[plan]['priceMainCity']) {
        planTax = bannerPrice[plan]['priceMainCity'];
      } else {
        planTax = bannerPrice[plan]['price'];
      }

      total = planTax * num;
    }

    //if(promoDiscount) {
    //  planTax -= planTax / 100 * promoDiscount
    //}

    const dataCaptcha = {
      captcha_word: captcha_word,
      captcha_sid: captcha_sid,
    };

    // Объект заказа
    const dataOrder = {
      type,
      img,
      name,
      link,
      country,
      region,
      city,
      num,
      plan,
      plan_tax: planTax,
      total,
      promocode,
      promoDiscount,
      promoLimit
    }

    $.ajax({
      type: 'POST',
      url: '/ajax/captcha_check.php',
      data: dataCaptcha,
      dataType: 'json',
      success: function (result) {
        if (result.status) {
          if (result.status == 'success') {

            $.ajax({
              type: 'POST',
              url: '/ajax/add_order_banner.php',
              data: dataOrder,
              dataType: 'json',
              success: function (result) {
                $('.st-content-right .form-banner').hide();
                if (result.status && result.status == 'success') {
                  $('.st-content-right .success-banner').fadeIn();
                } else {
                  $('.st-content-right .error-banner').fadeIn();
                }
              }
            });

          } else {
            $('.form-banner .st-captcha-input .label').hide();
            $('.form-banner .st-captcha-input .label').text('Неправильный код');
            $('.form-banner .st-captcha-input .label').css('color', '#ff471a');
            $('.form-banner .st-captcha-input .label').fadeIn();
          }

          $.getJSON('/ajax/captcha.php', function (data) {
            $('.capcha_img img').attr('src', '/bitrix/tools/captcha.php?captcha_sid=' + data);
            $('.captcha_sid').val(data);
          });

          return false;
        }
      }
    });

    return false;
  });

  $('.js-reload-ug').on('click', function (e) {
    e.preventDefault();

    let id_ug = $('.js-link-ug').data('res');

    $.ajax({
      type: 'POST',
      url: '/ajax/ugolok_znaniy.php',
      data: { 'id': id_ug },
      dataType: 'json',
      success: function (result) {
        if (result.status) {
          if (result.status == 'success') {
            $(".js-link-ug").data('res', result.id_ug);
            $(".js-link-ug").attr('href', result.link);
            $(".js-text-ug").html(result.message);
            $(".js-author-ug").text(result.sign);
          }
        }
      }
    });

    return false;
  });

  $('#form-chat').on('submit', function (e) {
    e.preventDefault();

    $("#error-message").hide();

    let owner_id = parseInt($('#form-chat .owner-id').val(), 10);
    let owner_display_name = $('#form-chat .owner-display-name').val().trim();
    let from_id = parseInt($('#form-chat .from-id').val(), 10);
    let from_display_name = $('#form-chat .from-display-name').val().trim();
    let message = $('#form-chat .message').val().trim();
    let avatar = $('#form-chat .avatar').val().trim();

    if (owner_id <= 0 || owner_display_name.length == 0 || from_id <= 0 || from_display_name.length == 0 || avatar.length == 0) {
      $("#error-message").text('Ошибка чата. Перезагрузите страницу.');
      $("#error-message").show();
      return false;
    }

    if (message.length == 0) {
      $('#form-chat .message').css('color', 'red');
      $('#form-chat .message').val('Введите текст сообщения');
      return false;
    }

    $.ajax({
      type: 'POST',
      url: '/ajax/chat_post.php',
      data: $("#form-chat").serialize(),
      dataType: 'json',
      success: function (result) {
        if (result.status) {
          if (result.status == 'success') {
            $('#form-chat .message').val('');
            $('#form-chat .message').focus();

            let html = ''
            const timeLine = $('div').is('#time-line')

            if (!timeLine) {
              html += `
								<div class="line-today" id="time-line" style="height: 1px; border-top: 1px solid #ff4719; position: relative; top: 0px; text-align: center; margin-top: 35px;">
									<div style="display: inline-block; padding: 5px 15px; background-color: #ffffff; position: relative; top: -14px;">Сегодня</div>
								</div>
							`;
            }


            html += '<div class="chat-right">';
            html += '<div class="message_chat_wrapper" style="position: relative;">';
            html += '<div class="message_chat_user">';
            html += '<a href="/user/">Я</a> <span>' + result.add.time + '</span>';
            html += '</div>';
            if (result.add.color) {
              html += '<div class="message_chat" style="margin-right: 6px; position: relative;"><div class="del-mes-right js-del" style="bottom: 12px; left: -72px;" data-type="bookmark" data-id="' + result.add.id + '" data-owner="' + owner_id + '" data-from="' + from_id + '" data-pos="right">в закадки</div><div class="del-mes-right js-del" style="bottom: -1px; left: -60px;" data-type="post" data-id="' + result.add.id + '" data-owner="' + owner_id + '" data-from="' + from_id + '">удалить</div>' + result.add.message + '</div>';
              html += '<img style="right: 7px;" class="avatar_duz" src="/upload/main/ug_right_3.png" alt="' + owner_display_name + '">';
            } else {
              html += '<div id="chat-id-' + result.add.id + '" class="message_chat no_show_ajax no_show" data-id="' + result.add.id + '"><div class="del-mes-right js-del" style="bottom: 12px; left: -72px;" data-type="bookmark" data-id="' + result.add.id + '" data-owner="' + owner_id + '" data-from="' + from_id + '" data-pos="right">в закадки</div><div class="del-mes-right js-del" style="bottom: -1px; left: -60px;" data-type="post" data-id="' + result.add.id + '" data-owner="' + owner_id + '" data-from="' + from_id + '">удалить</div>' + result.add.message + '</div>';
              html += '<img style="right: 7px;" class="avatar_duz" src="/upload/main/ug_right_3_no.png" alt="' + owner_display_name + '">';
            }
            if (result.add.teacher) {
              html += '<img class="avatar_chat" style="border: 2px solid #ff5b32;" src="' + avatar + '" alt="' + owner_display_name + '">';
            } else {
              html += '<img class="avatar_chat" src="' + avatar + '" alt="' + owner_display_name + '">';
            }
            html += '</div>';
            html += '</div>';
            $('#chat').append(html);
            $("#chat").animate({ scrollTop: $("#chat").prop("scrollHeight") }, "slow");
          } else {
            if (result.add.event == 'block') {

              let popupSelector = '.hideForm-news-edit.no-chat';

              openPopup(popupSelector);

              $('.foneBg').css({ 'display': 'block' });
              $(popupSelector).fadeIn(250);
            } else {
              $("#error-message").text(result.message);
              $("#error-message").show();
            }
          }
        }
      }
    });

    return false;
  });

  $('#form-chat .message').on('focus', function () {
    $(this).css('color', '#000');
    if ($(this).val() == 'Введите текст сообщения')
      $(this).val('');
    return false;
  });

  var filter = 'all';
  var filterUS = 'all';

  $(".js-filter").on('click', function () {
    $(".js-filter").removeClass('color-silver');
    $(this).addClass('color-silver');
    filter = $(this).data('filter');
    if (filter == 'all' && filterUS == 'all') {
      $(".news-item").show();
    } else if (filterUS == 'all') {
      $(".news-item").hide();
      $(".news-item." + filter).show();
    } else if (filter == 'all') {
      $(".news-item").hide();
      $(".news-item").each(function () {
        fus = $(this).data('filter');
        if (filterUS == fus)
          $(this).show();
      });
    } else {
      $(".news-item").hide();
      $(".news-item").each(function () {
        fus = $(this).data('filter');
        if ($(this).hasClass(filter) && filterUS == fus)
          $(this).show();
      });
    }
    return false;
  });

  $(".st-tags-block .tag").on('click', function () {
    $(".st-tags-block .tag").removeClass('active');
    $(this).addClass('active');
    filterUS = $(this).data('filter');
    if (filter == 'all' && filterUS == 'all') {
      $(".news-item").show();
    } else if (filterUS == 'all') {
      $(".news-item").hide();
      $(".news-item." + filter).show();
    } else if (filter == 'all') {
      $(".news-item").hide();
      $(".news-item").each(function () {
        fus = $(this).data('filter');
        if (filterUS == fus)
          $(this).show();
      });
    } else {
      $(".news-item").hide();
      $(".news-item").each(function () {
        fus = $(this).data('filter');
        if ($(this).hasClass(filter) && filterUS == fus)
          $(this).show();
      });
    }
    return false;
  });

  $(".js-filter-one").on('click', function () {
    $(".js-filter-one").removeClass('color-silver');
    $(this).addClass('color-silver');
    filter = $(this).data('filter');
    if (filter == 'all') {
      $(".news-item.one p").show();
    } else {
      $(".news-item.one p.see").hide();
      $(".news-item.one p." + filter).show();
    }
    return false;
  });

  $("#page").on('click', '.js-user-list', function () {
    $("#page .filter").removeClass('color-silver');
    $(this).addClass('color-silver');
    filter = $(this).data('filter');
    if (filter == 'all') {
      $("#box-line .news-item").fadeIn();
    } else {
      $("#box-line .news-item").hide();
      $("#box-line .news-item." + filter).fadeIn();
    }
    return false;
  });

  $("#page-rating-vuz").on('click', '.js-b-right', function (e) {
    e.preventDefault();

    if (pro)
      return false;

    let $this = $(this);
    let curr = $this.prev('.st-baloon');

    if (like)
      return false;

    $.ajax({
      type: 'POST',
      url: '/ajax/golos_vuz.php',
      data: { 'id_vuz': id_vuz, 'id_user': id_user, 'type': 'deslike', 'status': deslike },
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          if (deslike) {
            $this.removeClass('active');
            deslike = 0;
          } else {
            $this.addClass('active');
            deslike = 1;
          }
          html = '';
          if (curr.length) {
            curr.find('a').remove();
            curr.find('.more-baloon').remove();
            let total = 4;
            if (result.res.length > 4) {
              total = 3;
            }
            $.each(result.res, function (i, valBest) {
              if (i >= total) {
                html += '<div class="more-baloon"><span data-id-vuz="' + id_vuz + '" data-type="vuz" data-id="' + id_vuz + '" data-hash="deslike" style="margin-left: 10px; font-size: 10px; top: 12px; position: relative;" title="Показать всех">ещё</span></div>';
                return false;
              }
              html += '<a href="/user/' + valBest.id + '/">';
              html += '<div class="image">';
              html += '<img style="height: 22px;" src="' + valBest.avatar + '" alt="' + valBest.format_name + '" title="' + valBest.format_name + '">';
              html += '</div>';
              html += '</a>';
            });
            curr.append(html);
          } else {
            html += '<div class="st-baloon my-baloon js-baloon" style="right: 0px;">';
            $.each(result.res, function (i, valBest) {
              html += '<a href="/user/' + valBest.id + '/">';
              html += '<div class="image">';
              html += '<img style="height: 22px;" src="' + valBest.avatar + '" alt="' + valBest.format_name + '" title="' + valBest.format_name + '">';
              html += '</div>';
              html += '</a>';
              if (i >= 3)
                return false;
            });
            html += '</div>';
            $this.before(html);
            curr = $this.prev('.st-baloon');
            curr.fadeIn();
          }
          $(".js-b-right span").html('<i class="fa fa-thumbs-o-down" style="margin-right: 7px;"></i>' + result.res.length);
          if (!parseInt(result.res.length, 10) && curr.length)
            curr.remove();
        }
      }
    });
    return false;
  });

  $("#page-rating-vuz").on('click', '.js-b-left', function (e) {
    e.preventDefault();

    if (pro)
      return false;

    let $this = $(this);
    let curr = $this.prev('.st-baloon');

    if (deslike)
      return false;

    $.ajax({
      type: 'POST',
      url: '/ajax/golos_vuz.php',
      data: { 'id_vuz': id_vuz, 'id_user': id_user, 'type': 'like', 'status': like },
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          if (like) {
            $this.removeClass('active');
            like = 0;
          } else {
            $this.addClass('active');
            like = 1;
          }
          html = '';
          if (curr.length) {
            curr.find('a').remove();
            curr.find('.more-baloon').remove();
            let total = 4;
            if (result.res.length > 4) {
              total = 3;
            }
            $.each(result.res, function (i, valBest) {
              if (i >= total) {
                html += '<div class="more-baloon"><span data-id-vuz="' + id_vuz + '" data-type="vuz" data-id="' + id_vuz + '" data-hash="like" style="margin-left: 10px; font-size: 10px; top: 12px; position: relative;" title="Показать всех">ещё</span></div>';
                return false;
              }
              html += '<a href="/user/' + valBest.id + '/">';
              html += '<div class="image">';
              html += '<img style="height: 22px;" src="' + valBest.avatar + '" alt="' + valBest.format_name + '" title="' + valBest.format_name + '">';
              html += '</div>';
              html += '</a>';
            });
            curr.append(html);
          } else {
            html += '<div class="st-baloon my-baloon js-baloon">';
            $.each(result.res, function (i, valBest) {
              html += '<a href="/user/' + valBest.id + '/">';
              html += '<div class="image">';
              html += '<img style="height: 22px;" src="' + valBest.avatar + '" alt="' + valBest.format_name + '" title="' + valBest.format_name + '">';
              html += '</div>';
              html += '</a>';
              if (i >= 3)
                return false;
            });
            html += '</div>';
            $this.before(html);
            curr = $this.prev('.st-baloon');
            curr.fadeIn();
          }

          $(".js-b-left span").html('<i class="fa fa-thumbs-o-up" style="margin-right: 7px;"></i>' + result.res.length);
          if (!parseInt(result.res.length, 10) && curr.length)
            curr.remove();
        }
      }
    });
    return false;
  });

  $("#box-line").on('click', '.js-btn-text-full', function () {
    let parent = $(this).parent().parent('.news-item');
    parent.find('.js-text-short').hide();
    parent.find('.js-text-full').show();
    return false;
  });

  $("#box-line").on('click', '.js-btn-text-short', function () {
    let parent = $(this).parent().parent('.news-item');
    parent.find('.js-text-full').hide();
    parent.find('.js-text-short').show();
    return false;
  });

  $("#box-line").on('click', '.js-b-right-2', function (e) {
    e.preventDefault();

    if (pro)
      return false;

    let $this = $(this);
    let curr = $this.prev('.st-baloon');

    let cnt = $this.data('cnt');
    let parentPost = $(this).parent('.page-rating');
    let id_vuz = parentPost.data('vuz');
    let id_post = parentPost.data('post');
    let like = parentPost.find(".js-b-left-2").data('my');
    let deslike = parentPost.find(".js-b-right-2").data('my');
    if (like)
      return false;
    $.ajax({
      type: 'POST',
      url: '/ajax/golos_vuz_post.php',
      data: { 'id_vuz': id_vuz, 'type': 'deslike', 'status': deslike, 'cnt': cnt, 'id_post': id_post },
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          if (deslike) {
            $this.removeClass('active');
            deslike = 0;
          } else {
            $this.addClass('active');
            deslike = 1;
          }
          html = '';
          if (curr.length) {
            curr.find('a').remove();
            curr.find('.more-baloon').remove();
            let total = 4;
            if (result.res.length > 4) {
              total = 3;
            }
            $.each(result.res, function (i, valBest) {
              if (i >= total) {
                html += '<div class="more-baloon"><span data-id-vuz="' + id_vuz + '" data-type="vuz" data-id="' + id_vuz + '" data-hash="like" style="margin-left: 10px; font-size: 10px; top: 12px; position: relative;" title="Показать всех">ещё</span></div>';
                return false;
              }
              html += '<a href="/user/' + valBest.id + '/">';
              html += '<div class="image">';
              html += '<img style="height: 22px;" src="' + valBest.avatar + '" alt="' + valBest.format_name + '" title="' + valBest.format_name + '">';
              html += '</div>';
              html += '</a>';
            });
            curr.append(html);
          } else {
            html += '<div class="st-baloon" style="left: 100px; height: 52px;">';
            $.each(result.res, function (i, valBest) {
              html += '<a href="/user/' + valBest.id + '/">';
              html += '<div class="image">';
              html += '<img style="height: 22px;" src="' + valBest.avatar + '" alt="' + valBest.format_name + '" title="' + valBest.format_name + '">';
              html += '</div>';
              html += '</a>';
              if (i >= 3)
                return false;
            });
            html += '</div>';
            $this.before(html);
            curr = $this.prev('.st-baloon');
            curr.fadeIn();
          }
          $this.data("cnt", result.res.length);
          $this.data("my", deslike);
          $this.find("span").html('<i class="fa fa-thumbs-o-down" style="margin-right: 7px;"></i>' + result.res.length);
          if (!parseInt(result.res.length, 10) && curr.length)
            curr.remove();
        }
      }
    });
    return false;
  });

  $("#box-line").on('click', '.js-b-left-2', function (e) {
    e.preventDefault();

    if (pro)
      return false;

    let $this = $(this);
    let curr = $this.prev('.st-baloon');

    let cnt = $this.data('cnt');
    let parentPost = $(this).parent('.page-rating');
    let id_vuz = parentPost.data('vuz');
    let id_post = parentPost.data('post');
    let like = parentPost.find(".js-b-left-2").data('my');
    let deslike = parentPost.find(".js-b-right-2").data('my');
    if (deslike)
      return false;
    $.ajax({
      type: 'POST',
      url: '/ajax/golos_vuz_post.php',
      data: { 'id_vuz': id_vuz, 'type': 'like', 'status': like, 'cnt': cnt, 'id_post': id_post },
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          if (like) {
            $this.removeClass('active');
            like = 0;
          } else {
            $this.addClass('active');
            like = 1;
          }
          html = '';
          if (curr.length) {
            curr.find('a').remove();
            curr.find('.more-baloon').remove();
            let total = 4;
            if (result.res.length > 4) {
              total = 3;
            }
            $.each(result.res, function (i, valBest) {
              if (i >= total) {
                html += '<div class="more-baloon"><span data-id-vuz="' + id_vuz + '" data-type="vuz" data-id="' + id_vuz + '" data-hash="like" style="margin-left: 10px; font-size: 10px; top: 12px; position: relative;" title="Показать всех">ещё</span></div>';
                return false;
              }
              html += '<a href="/user/' + valBest.id + '/">';
              html += '<div class="image">';
              html += '<img style="height: 22px;" src="' + valBest.avatar + '" alt="' + valBest.format_name + '" title="' + valBest.format_name + '">';
              html += '</div>';
              html += '</a>';
            });
            curr.append(html);
          } else {
            html += '<div class="st-baloon" style="left: 0px; height: 52px;">';
            $.each(result.res, function (i, valBest) {
              html += '<a href="/user/' + valBest.id + '/">';
              html += '<div class="image">';
              html += '<img style="height: 22px;" src="' + valBest.avatar + '" alt="' + valBest.format_name + '" title="' + valBest.format_name + '">';
              html += '</div>';
              html += '</a>';
              if (i >= 3)
                return false;
            });
            html += '</div>';
            $this.before(html);
            curr = $this.prev('.st-baloon');
            curr.fadeIn();
          }
          $this.data("cnt", result.res.length);
          $this.data("my", like);
          $this.find("span").html('<i class="fa fa-thumbs-o-up" style="margin-right: 7px;"></i>' + result.res.length);
          if (!parseInt(result.res.length, 10) && curr.length)
            curr.remove();
        }
      }
    });
    return false;
  });

  $('.js-new-post').on('click', function () {

    if (pro)
      return false;

    let namePost = $(this).data('name');
    let vuzPost = $(this).data('vuz');

    var top_form = $(window).scrollTop();
    var height_form = $('.hideForm3 .form-open-block form').height();
    var marg_top = $(window).height() / 2;

    $('.hideForm3 .form-open-block').css({
      'height': $(window).height(),
      'position': 'absolute',
      'top': top_form,
    });
    $('.hideForm3').css({ 'height': $(document).height(), });

    $('.hideForm3 .name_post').val(namePost);
    $('.hideForm3 .id_vuz_post').val(vuzPost);

    $('.foneBg').css({ 'display': 'block' });

    $('.hideForm3').fadeIn(250);

  });

  $('#box-line').on('click', '.js-comment-post', function () {

    if (pro)
      return false;

    let parentPost = $(this).parent('.page-rating');
    let namePost = parentPost.data('name');
    let vuzPost = parentPost.data('vuz');
    let idPost = parentPost.data('post');

    var top_form = $(window).scrollTop();
    var height_form = $('.hideForm4 .form-open-block form').height();
    var marg_top = $(window).height() / 2;

    $('.hideForm4 .form-open-block').css({
      'height': $(window).height(),
      'position': 'absolute',
      'top': top_form,
    });
    $('.hideForm4').css({ 'height': $(document).height(), });

    $('.hideForm4 .name_post').val(namePost);
    $('.hideForm4 .id_vuz_post').val(vuzPost);
    $('.hideForm4 .id_post').val(idPost);

    $('.hideForm4 .name_form span').text('Новый комментарий');
    $('.hideForm4 .message_post').val('');
    $('.hideForm4 .js-submit-comment-post').removeClass('edit');

    $('.foneBg').css({ 'display': 'block' });

    $('.hideForm4').fadeIn(250);

  });

  $(".js-submit-new-post").on('click', function () {

    if (pro)
      return false;

    let user_post = $('.hideForm3 .user_post').val();
    let name_post = $('.hideForm3 .name_post').val();
    let vuz_post = $('.hideForm3 .id_vuz_post').val();
    let message_post = $('.hideForm3 .message_post').val().trim();
    let type_post = 'new';
    let outStr = '';
    if (message_post.length == 0) {
      $("#error-message-new-post").text('Введите текст отзыва');
      $("#error-message-new-post").show();
      return false;
    }
    $.ajax({
      type: 'POST',
      url: '/ajax/new_post.php',
      data: { 'user_post': user_post, 'name_post': name_post, 'vuz_post': vuz_post, 'message_post': message_post, 'type_post': type_post },
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          let html = '<div id="post-' + result.id + '" data-post="' + result.id + '" class="news-item">';
          html += '<img src="' + result.user_avatar + '" alt="img" style="width: 44px; border-radius: 50%; border: 1px solid #ff471a;">';
          html += '<div class="news-name" style="display: inline-block; position: relative; top: -7px; left: 5px; width: 80%;">';
          html += '<a href="/user/' + user_post + '/">';
          html += '<span>' + result.user_name + '</span>';
          html += '</a>';
          html += '<span style="color: #9f9f9f; margin-left: 10px; font-size: 13px;">' + result.format_time + '</span>';
          html += '</div>';
          if (message_post.length > 200) {
            outStr = message_post.substr(0, 198) + '.. <a href="#" class="js-btn-text-full btn-post">читать весь</a>';
            outStrFullBr = message_post.replace(/\r?\n/g, "<br>");
            html += '<p class="js-text-full" style="display: none; margin-top: 5px;">' + outStrFullBr + ' <a href="#" class="js-btn-text-short btn-post">свернуть</a></p>';
          } else {
            outStr = message_post;
          }
          outStrBr = outStr.replace(/\r?\n/g, "<br>");
          html += '<p class="js-text-short" style="margin-top: 5px;">' + outStrBr + '</p>';
          html += '<div class="page-rating" data-post="' + result.id + '" data-vuz="' + vuz_post + '" data-name="' + name_post + '" style="margin: 0px 0px 5px 0px;">';
          html += '<a href="#" data-my="0" data-cnt="0" class="button js-b-left-2 b-left" style="position: relative; left: 0px; top: 0px;"><span><i class="fa fa-thumbs-o-up" style="margin-right: 7px;"></i>0</span></a>';
          html += '<a href="#" data-my="0" data-cnt="0" class="button js-b-right-2 b-right" style="position: relative; right: 0px; top: 0px; margin-left: 5px;"><span><i class="fa fa-thumbs-o-down" style="margin-right: 7px;"></i>0</span></a>';
          html += '<a class="js-edit-post" data-text="' + message_post + '" style="text-decoration: none; cursor: pointer; margin-left: 20px;"><span style="border-bottom: 1px dashed; margin-left: 3px;">редактировать</span></a>';
          html += '<a class="js-delete-post" style="text-decoration: none; cursor: pointer; margin-left: 20px;"><span style="border-bottom: 1px dashed; margin-left: 3px; color: #9f9f9f;">удалить</span></a>';
          html += '</div>';
          html += '</div>';
          $('#box-line').prepend(html);
          $('.hideForm3 .message_post').val('');
        }
      }
    });
    close_form();
    return false;
  });

  $(".js-submit-comment-post").on('click', function () {

    if (pro)
      return false;

    let user_post = $('.hideForm4 .user_post').val();
    let name_post = $('.hideForm4 .name_post').val();
    let vuz_post = $('.hideForm4 .id_vuz_post').val();
    let id_post = $('.hideForm4 .id_post').val();
    let message_post = $('.hideForm4 .message_post').val().trim();
    let type_post = 'comment';

    if ($(this).hasClass('edit')) {
      type_post = 'edit';
    }

    if (message_post.length == 0) {
      $("#error-message-comment-post").text('Введите текст комментария');
      $("#error-message-comment-post").show();
      return false;
    }
    $.ajax({
      type: 'POST',
      url: '/ajax/new_post.php',
      data: { 'user_post': user_post, 'name_post': name_post, 'vuz_post': vuz_post, 'id_post': id_post, 'message_post': message_post, 'type_post': type_post },
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          if (type_post == 'edit') {
            if (message_post.length > 200) {
              outStr = message_post.substr(0, 198) + '.. <a href="#" class="js-btn-text-full btn-post">читать весь</a>';
              outStrFullBr = message_post.replace(/\r?\n/g, "<br>");
              $('#post-' + id_post + ' .js-text-full').html(outStrFullBr);
            } else {
              outStr = message_post;
            }
            outStrBr = outStr.replace(/\r?\n/g, "<br>");
            $('#post-' + id_post + ' .js-text-short').html(outStrBr);
          } else {
            let html = '<div id="post-' + result.id + '" data-post="' + result.id + '" class="news-item" style="margin-left: 30px; margin-top: 15px; margin-bottom: 0px; padding-bottom: 0px; border-bottom: none;">';
            html += '<img src="' + result.user_avatar + '" alt="img" style="width: 22px; top: -3px; border-radius: 50%; border: 1px solid #ff471a;">';
            html += '<div class="news-name" style="display: inline-block; position: relative; top: -7px; left: 5px; width: 80%;">';
            html += '<a href="/user/' + user_post + '/" style="font-size: 14px;">';
            html += '<span>' + result.user_name + '</span>';
            html += '</a>';
            html += '<span style="color: #9f9f9f; margin-left: 10px; font-size: 13px;">' + result.format_time + '</span>';
            html += '</div>';
            if (message_post.length > 200) {
              outStr = message_post.substr(0, 198) + '.. <a href="#" class="js-btn-text-full btn-post">читать весь</a>';
              outStrFullBr = message_post.replace(/\r?\n/g, "<br>");
              html += '<p class="js-text-full" style="display: none; margin-top: 5px;">' + outStrFullBr + ' <a href="#" class="js-btn-text-short btn-post">свернуть</a></p>';
            } else {
              outStr = message_post;
            }
            outStrBr = outStr.replace(/\r?\n/g, "<br>");
            html += '<p class="js-text-short" style="margin-top: 5px;">' + outStrBr + '</p>';
            html += '<div class="page-rating" data-post="' + result.id + '" data-vuz="' + vuz_post + '" data-name="' + name_post + '" style="margin: 0px 0px 5px 0px;">';
            html += '<a href="#" data-my="0" data-cnt="0" class="button js-b-left-2 b-left" style="position: relative; left: 0px; top: 0px;"><span><i class="fa fa-thumbs-o-up" style="margin-right: 7px;"></i>0</span></a>';
            html += '<a href="#" data-my="0" data-cnt="0" class="button js-b-right-2 b-right" style="position: relative; right: 0px; top: 0px; margin-left: 5px;"><span><i class="fa fa-thumbs-o-down" style="margin-right: 7px;"></i>0</span></a>';
            html += '<a class="js-edit-post" data-text="' + message_post + '" style="text-decoration: none; cursor: pointer; margin-left: 20px;"><span style="border-bottom: 1px dashed; margin-left: 3px;">редактировать</span></a>';
            html += '<a class="js-delete-post" style="text-decoration: none; cursor: pointer; margin-left: 15px;"><span style="border-bottom: 1px dashed; margin-left: 3px; color: #9f9f9f;">удалить</span></a>';
            html += '</div>';
            html += '</div>';
            $('#post-' + id_post).append(html);
          }
          $('.hideForm4 .message_post').val('');
        }
      }
    });
    close_form();
    return false;
  });

  $('#box-line').on('click', '.js-edit-post', function () {

    if (pro)
      return false;

    let parentPost = $(this).parent('.page-rating');
    let namePost = parentPost.data('name');
    let vuzPost = parentPost.data('vuz');
    let idPost = parentPost.data('post');
    let textPost = $(this).data('text');

    var top_form = $(window).scrollTop();
    var height_form = $('.hideForm4 .form-open-block form').height();
    var marg_top = $(window).height() / 2;

    $('.hideForm4 .form-open-block').css({
      'height': $(window).height(),
      'position': 'absolute',
      'top': top_form,
    });
    $('.hideForm4').css({ 'height': $(document).height(), });

    $('.hideForm4 .name_post').val(namePost);
    $('.hideForm4 .id_vuz_post').val(vuzPost);
    $('.hideForm4 .id_post').val(idPost);
    $('.hideForm4 .message_post').val(textPost);

    $('.hideForm4 .name_form span').text('Редактирование комментария');
    $('.hideForm4 .js-submit-comment-post').addClass('edit');

    $('.foneBg').css({ 'display': 'block' });

    $('.hideForm4').fadeIn(250);

  });

  $('#box-line').on('click', '.js-delete-post', function () {

    if (pro)
      return false;

    let parentPost = $(this).parent('.page-rating');
    let idPost = parentPost.data('post');

    $.ajax({
      type: 'POST',
      url: '/ajax/del_post.php',
      data: { 'id_post': idPost },
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          $('#post-' + idPost + ' .js-text-short').css('color', '#9f9f9f');
          $('#post-' + idPost + ' .js-text-short').html('Сообщение было удалено');
        }
      }
    });
    return false;
  });

  $('#box-line').on('click', '.js-abuse-post', function () {

    if (pro)
      return false;

    let $this = $(this);
    let idPost = $this.data('id');
    let userPost = $this.data('user');
    let namePost = $this.data('name');
    let textPost = $this.data('text');
    let urlPost = detailPageUrl + '?sect=reviews';

    $.ajax({
      type: 'POST',
      url: '/ajax/abuse_post.php',
      data: { 'vuz_post': id_vuz, 'id_post': idPost, 'user_post': userPost, 'name_post': namePost, 'text_post': textPost, 'url_post': urlPost },
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          $('#post-' + idPost + ' .js-text-short').css('color', '#9f9f9f');
          $('#post-' + idPost + ' .js-text-short').html('Ваша жалоба отправлена');
          $this.remove();
        }
      }
    });
    return false;
  });

  $('#form-news-avatar').on('click', '.js-abuse-avatar', function (e) {
    e.preventDefault();

    if (pro)
      return false;

    let $this = $(this);
    let idUser = $this.data('user');
    let userURL = $this.data('url');
    let fName = $this.data('fname');
    let sName = $this.data('sname');
    let lName = $this.data('lname');

    $.ajax({
      type: 'POST',
      url: '/ajax/abuse_avatar.php',
      data: { 'user': idUser, 'url': userURL, 'fname': fName, 'sname': sName, 'lname': lName },
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          $this.css('cursor', 'text');
          $('.js-abuse-avatar span').css('border-bottom', 'none');
          $('.js-abuse-avatar span').css('color', '#ff471a');
          $('.js-abuse-avatar span').html('отправлено');
          $this.removeClass('js-abuse-avatar');
        }
      }
    });
    return false;
  });

  $("#box-line").on('click', '.js-news-right', function (e) {
    e.preventDefault();

    if (pro)
      return false;

    let $this = $(this);
    let curr = $this.prev('.st-baloon');

    let cnt = $this.data('cnt');
    let parentNews = $(this).parent('.page-rating');
    let id_vuz = parentNews.data('vuz');
    let id_news = parentNews.data('news');
    let like = parentNews.find(".js-news-left").data('my');
    let deslike = parentNews.find(".js-news-right").data('my');
    if (like)
      return false;
    $.ajax({
      type: 'POST',
      url: '/ajax/golos_vuz_news.php',
      data: { 'id_vuz': id_vuz, 'type': 'deslike', 'status': deslike, 'cnt': cnt, 'id_news': id_news },
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          if (deslike) {
            $this.removeClass('active');
            deslike = 0;
          } else {
            $this.addClass('active');
            deslike = 1;
          }
          html = '';
          if (curr.length) {
            curr.find('a').remove();
            curr.find('.more-baloon').remove();
            let total = 4;
            if (result.res.length > 4) {
              total = 3;
            }
            $.each(result.res, function (i, valBest) {
              if (i >= total) {
                html += '<div class="more-baloon"><span data-id-vuz="' + id_vuz + '" data-type="news" data-id="' + id_news + '" data-hash="deslike" style="margin-left: 10px; font-size: 10px; top: 12px; position: relative;" title="Показать всех">ещё</span></div>';
                return false;
              }
              html += '<a href="/user/' + valBest.id + '/">';
              html += '<div class="image" style="height: 42px;">';
              html += '<img style="height: 22px;" src="' + valBest.avatar + '" alt="' + valBest.format_name + '" title="' + valBest.format_name + '">';
              html += '</div>';
              html += '</a>';
            });
            curr.append(html);
          } else {
            html += '<div class="st-baloon" style="height: 52px; right: 0px;">';
            $.each(result.res, function (i, valBest) {
              html += '<a href="/user/' + valBest.id + '/">';
              html += '<div class="image" style="height: 42px;">';
              html += '<img style="height: 22px;" src="' + valBest.avatar + '" alt="' + valBest.format_name + '" title="' + valBest.format_name + '">';
              html += '</div>';
              html += '</a>';
              if (i >= 3)
                return false;
            });
            html += '</div>';
            $this.before(html);
            curr = $this.prev('.st-baloon');
            curr.fadeIn();
          }
          $this.data("cnt", result.res.length);
          $this.data("my", deslike);
          $this.find("span").html('<i class="fa fa-thumbs-o-down" style="margin-right: 7px;"></i>' + result.res.length);
          if (!parseInt(result.res.length, 10) && curr.length)
            curr.remove();
        }
      }
    });
    return false;
  });

  $("#box-line").on('click', '.js-news-left', function (e) {
    e.preventDefault();

    if (pro)
      return false;

    let $this = $(this);
    let curr = $this.prev('.st-baloon');

    let cnt = $this.data('cnt');
    let parentNews = $(this).parent('.page-rating');
    let id_vuz = parentNews.data('vuz');
    let id_news = parentNews.data('news');
    let like = parentNews.find(".js-news-left").data('my');
    let deslike = parentNews.find(".js-news-right").data('my');
    if (deslike)
      return false;
    $.ajax({
      type: 'POST',
      url: '/ajax/golos_vuz_news.php',
      data: { 'id_vuz': id_vuz, 'type': 'like', 'status': like, 'cnt': cnt, 'id_news': id_news },
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          if (like) {
            $this.removeClass('active');
            like = 0;
          } else {
            $this.addClass('active');
            like = 1;
          }
          html = '';
          if (curr.length) {
            curr.find('a').remove();
            curr.find('.more-baloon').remove();
            let total = 4;
            if (result.res.length > 4) {
              total = 3;
            }
            $.each(result.res, function (i, valBest) {
              if (i >= total) {
                html += '<div class="more-baloon"><span data-id-vuz="' + id_vuz + '" data-type="news" data-id="' + id_news + '" data-hash="like" style="margin-left: 10px; font-size: 10px; top: 12px; position: relative;" title="Показать всех">ещё</span></div>';
                return false;
              }
              html += '<a href="/user/' + valBest.id + '/">';
              html += '<div class="image" style="height: 42px;">';
              html += '<img style="height: 22px;" src="' + valBest.avatar + '" alt="' + valBest.format_name + '" title="' + valBest.format_name + '">';
              html += '</div>';
              html += '</a>';
            });
            curr.append(html);
          } else {
            html += '<div class="st-baloon" style="height: 52px; right: 100px;">';
            $.each(result.res, function (i, valBest) {
              html += '<a href="/user/' + valBest.id + '/">';
              html += '<div class="image" style="height: 42px;">';
              html += '<img style="height: 22px;" src="' + valBest.avatar + '" alt="' + valBest.format_name + '" title="' + valBest.format_name + '">';
              html += '</div>';
              html += '</a>';
              if (i >= 3)
                return false;
            });
            html += '</div>';
            $this.before(html);
            curr = $this.prev('.st-baloon');
            curr.fadeIn();
          }
          $this.data("cnt", result.res.length);
          $this.data("my", like);
          $this.find("span").html('<i class="fa fa-thumbs-o-up" style="margin-right: 7px;"></i>' + result.res.length);
          if (!parseInt(result.res.length, 10) && curr.length)
            curr.remove();
        }
      }
    });
    return false;
  });

  $(".js-news-list").on('click', function () {
    $(".js-news-list").removeClass('color-silver');
    $(this).addClass('color-silver');
    filter = $(this).data('filter');
    if (filter == 'all') {
      inProgress = false;
      inProgressGetBanner = false;
      offset = 0;

      $('#banner-list .remove-banner').remove()

      $(".news-item").fadeIn(1000);
    } else {

      inProgress = false;
      inProgressGetBanner = false;
      offset = 0;

      $('#banner-list .remove-banner').remove()

      let lbf = $(".news-item." + filter).length;

      $(".news-item").hide();
      $(".line-today").hide();
      if (lbf) {
        $(".news-item." + filter).fadeIn(1000);
        $(".today-" + filter).fadeIn(1000);
      }
    }
    return false;
  });

  $(".js-educations-list").on('click', function () {
    $(".js-educations-list").removeClass('color-silver');
    $(this).addClass('color-silver');
    filter = $(this).data('filter');
    if (filter == 'all') {
      $(".news-item").fadeIn(1000);
    } else {
      $(".news-item").hide();
      $(".news-item." + filter).fadeIn(1000);
    }
    return false;
  });

  $(".js-events-list").on('click', function () {
    $(".js-events-list").removeClass('color-silver');
    $(this).addClass('color-silver');
    filter = $(this).data('filter');
    if (filter == 'id_event') {
      $(".news-item").hide();
      $(".news-item.event_id").fadeIn(1000);
    } else {
      $(".news-item").hide();
      $(".news-item.event_date").fadeIn(1000);
    }
    return false;
  });

  $(".js-vuz-list").on('click', function () {
    $(".js-vuz-list").removeClass('color-silver');
    $(this).addClass('color-silver');
    filter = $(this).data('filter');

    inProgress = false;
    inProgressGetBanner = false;
    offset = 0;

    $('#banner-list .remove-banner').remove()

    lazyList();

    $("#page .line").hide();
    $("#" + filter).fadeIn(1000);

    return false;
  });

  $('.js-orders-list').on('click', function () {

    if ($(this).hasClass('color-silver')) {
      return false;
    }

    $(".js-orders-list").removeClass('color-silver');
    $(this).addClass('color-silver');
    filter = $(this).data('filter');

    inProgress = false;
    inProgressGetBanner = false;
    offset = 0;

    $('#banner-list .remove-banner').remove()

    lazyListOrders();

    $("#page .line-orders").hide();
    $(".line-orders." + filter).fadeIn(1000);

    return false;
  });

  $('#page .filter-service').on('click', function () {

    if ($(this).hasClass('color-silver')) {
      return false;
    }

    $(".filter-service").removeClass('color-silver');
    $(this).addClass('color-silver');
    const filter = $(this).data('filter');

    //inProgress = false;
    //lazyListOrders();

    //$('#page .line').hide();
    //$('#' + filter).fadeIn(1000);

    $('#page .news-item.all').hide();

    if ($(this).hasClass('sort')) {
      const type = $(this).data('sort')

      const myList = $('#all')
      let newsItem = myList.find('.news-item')

      let sortList = Array.prototype.sort.bind(newsItem);

      sortList(function (a, b) {

        let aData = $(a).data(type)
        let bData = $(b).data(type)

        if (aData > bData) {
          return -1;
        }
        if (aData < bData) {
          return 1;
        }
        // a должно быть равным b
        return 0;
      });

      myList.empty()
      myList.append(newsItem)

    }

    $('#page .news-item.' + filter).fadeIn(1000);

    return false;
  });

  $("#box-line").on('click', '.js-event-left', function (e) {
    e.preventDefault();

    if (pro)
      return false;

    let $this = $(this);
    let curr = $this.prev('.st-baloon');

    let cnt = $this.data('cnt');
    let parentNews = $this.parent().parent('.right');
    let id_vuz = parentNews.data('vuz');
    let id_event = parentNews.data('event');
    let like = parentNews.find(".js-event-left").data('my');
    let deslike = parentNews.find(".js-event-right").data('my');
    if (deslike)
      return false;
    $.ajax({
      type: 'POST',
      url: '/ajax/golos_vuz_events.php',
      data: { 'id_vuz': id_vuz, 'type': 'like', 'status': like, 'cnt': cnt, 'id_event': id_event },
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          if (like) {
            $this.removeClass('active');
            like = 0;
          } else {
            $this.addClass('active');
            like = 1;
          }
          html = '';
          if (curr.length) {
            curr.find('a').remove();
            curr.find('.more-baloon').remove();
            let total = 4;
            if (result.res.length > 4) {
              total = 3;
            }
            $.each(result.res, function (i, valBest) {
              if (i >= total) {
                html += '<div class="more-baloon"><span data-id-vuz="' + id_vuz + '" data-type="events" data-id="' + id_event + '" data-hash="like" style="margin-left: 10px; font-size: 10px; top: 12px; position: relative;" title="Показать всех">ещё</span></div>';
                return false;
              }
              html += '<a href="/user/' + valBest.id + '/">';
              html += '<div class="image" style="height: 42px;">';
              html += '<img style="height: 22px;" src="' + valBest.avatar + '" alt="' + valBest.format_name + '" title="' + valBest.format_name + '">';
              html += '</div>';
              html += '</a>';
            });
            curr.append(html);
          } else {
            html += '<div class="st-baloon" style="height: 52px; right: 0px; top: -60px;">';
            $.each(result.res, function (i, valBest) {
              html += '<a href="/user/' + valBest.id + '/">';
              html += '<div class="image" style="height: 42px;">';
              html += '<img style="height: 22px;" src="' + valBest.avatar + '" alt="' + valBest.format_name + '" title="' + valBest.format_name + '">';
              html += '</div>';
              html += '</a>';
              if (i >= 3)
                return false;
            });
            html += '</div>';
            $this.before(html);
            curr = $this.prev('.st-baloon');
            curr.fadeIn();
          }
          $this.data("cnt", result.res.length);
          $this.data("my", like);
          $this.find("span").html('<i class="fa fa-thumbs-o-up" style="margin-right: 7px;"></i>' + result.res.length);
          if (!parseInt(result.res.length, 10) && curr.length)
            curr.remove();
        }
      }
    });
    return false;
  });

  $("#box-line").on('click', '.js-event-right', function (e) {
    e.preventDefault();

    if (pro)
      return false;

    let $this = $(this);
    let curr = $this.prev('.st-baloon');

    let cnt = $this.data('cnt');
    let parentNews = $this.parent().parent('.right');
    let id_vuz = parentNews.data('vuz');
    let id_event = parentNews.data('event');
    let like = parentNews.find(".js-event-left").data('my');
    let deslike = parentNews.find(".js-event-right").data('my');
    if (like)
      return false;
    $.ajax({
      type: 'POST',
      url: '/ajax/golos_vuz_events.php',
      data: { 'id_vuz': id_vuz, 'type': 'deslike', 'status': deslike, 'cnt': cnt, 'id_event': id_event },
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          if (deslike) {
            $this.removeClass('active');
            deslike = 0;
          } else {
            $this.addClass('active');
            deslike = 1;
          }
          html = '';
          if (curr.length) {
            curr.find('a').remove();
            curr.find('.more-baloon').remove();
            let total = 4;
            if (result.res.length > 4) {
              total = 3;
            }
            $.each(result.res, function (i, valBest) {
              if (i >= total) {
                html += '<div class="more-baloon"><span data-id-vuz="' + id_vuz + '" data-type="events" data-id="' + id_event + '" data-hash="deslike" style="margin-left: 10px; font-size: 10px; top: 12px; position: relative;" title="Показать всех">ещё</span></div>';
                return false;
              }
              html += '<a href="/user/' + valBest.id + '/">';
              html += '<div class="image" style="height: 42px;">';
              html += '<img style="height: 22px;" src="' + valBest.avatar + '" alt="' + valBest.format_name + '" title="' + valBest.format_name + '">';
              html += '</div>';
              html += '</a>';
            });
            curr.append(html);
          } else {
            html += '<div class="st-baloon" style="height: 52px; right: 0px; top: -60px;">';
            $.each(result.res, function (i, valBest) {
              html += '<a href="/user/' + valBest.id + '/">';
              html += '<div class="image" style="height: 42px;">';
              html += '<img style="height: 22px;" src="' + valBest.avatar + '" alt="' + valBest.format_name + '" title="' + valBest.format_name + '">';
              html += '</div>';
              html += '</a>';
              if (i >= 3)
                return false;
            });
            html += '</div>';
            $this.before(html);
            curr = $this.prev('.st-baloon');
            curr.fadeIn();
          }
          $this.data("cnt", result.res.length);
          $this.data("my", deslike);
          $this.find("span").html('<i class="fa fa-thumbs-o-down" style="margin-right: 7px;"></i>' + result.res.length);
          if (!parseInt(result.res.length, 10) && curr.length)
            curr.remove();
        }
      }
    });
    return false;
  });

  $("#box-line").on('click', '.js-event-go', function (e) {
    e.preventDefault();

    if (pro)
      return false;

    let $this = $(this);
    let lk = $this.data('lk');
    let curr = $this.prev('.st-baloon');

    let parentNews = $this.parent().parent('.right');
    let id_vuz = parentNews.data('vuz');
    let id_event = parentNews.data('event');

    $.ajax({
      type: 'POST',
      url: '/ajax/events_go.php',
      data: { 'id_vuz': id_vuz, 'id_event': id_event },
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          if (result.del) {
            $this.removeClass('active');
            deslike = 0;
            if (lk) {
              $this.parent().parent().parent().slideUp();
              return false;
            }
          } else {
            $this.addClass('active');
            deslike = 1;
          }
          html = '';
          if (curr.length) {
            curr.find('a').remove();
            curr.find('.more-baloon').remove();
            let total = 4;
            if (result.res.length > 4) {
              total = 3;
            }
            $.each(result.res, function (i, valBest) {
              if (i >= total) {
                html += '<div class="more-baloon"><span data-id-vuz="' + id_vuz + '" data-type="events" data-id="' + id_event + '" data-hash="go" style="margin-left: 10px; font-size: 10px; top: 12px; position: relative;" title="Показать всех">ещё</span></div>';
                return false;
              }
              html += '<a href="/user/' + valBest.id + '/">';
              html += '<div class="image" style="height: 42px;">';
              html += '<img style="height: 22px;" src="' + valBest.avatar + '" alt="' + valBest.format_name + '" title="' + valBest.format_name + '">';
              html += '</div>';
              html += '</a>';
            });
            curr.append(html);
          } else {
            html += '<div class="st-baloon" style="height: 52px; right: 0px; top: -60px;">';
            $.each(result.res, function (i, valBest) {
              html += '<a href="/user/' + valBest.id + '/">';
              html += '<div class="image" style="height: 42px;">';
              html += '<img style="height: 22px;" src="' + valBest.avatar + '" alt="' + valBest.format_name + '" title="' + valBest.format_name + '">';
              html += '</div>';
              html += '</a>';
              if (i >= 3)
                return false;
            });
            html += '</div>';
            $this.before(html);
            curr = $this.prev('.st-baloon');
            curr.fadeIn();
          }

          $this.find("span").html('Я пойду (' + result.res.length + ')');
          if (!parseInt(result.res.length, 10) && curr.length)
            curr.remove();
        }
      }
    });
    return false;
  });

  // -------------------------------------- Lazy Load -------------------------------------------
  //
  /* Переменная-флаг для отслеживания того, происходит ли в данный момент ajax-запрос. В самом начале даем ей значение false, т.е. запрос не в процессе выполнения */
  var inProgress = false;

  if (startFrom > 0) {
    $(window).scroll(function () {
      /* Если высота окна + высота прокрутки больше или равны высоте всего документа и ajax-запрос в настоящий момент не выполняется, то запускаем ajax-запрос */
      if ($(window).scrollTop() + $(window).height() >= $(document).height() - 500 && !inProgress) {

        if (!id_vuz) {
          let id_vuz = $("#box-line").data('vuz');
        }
        let type = $(".filter.color-silver").data('filter');
        if (!type)
          type = $("#box-line").data('type');

        let lb = $(".news-item." + type).length;

        let html = '';

        let myLike = 0;
        let activeLike = '';
        let myDeslyke = 0;
        let activeDeslyke = '';
        let myGo = 0;
        let activeGo = '';
        let uKey = '';
        let loadKey = [];

        $('#' + type + ' .news-item').each(function () {
          uKey = $(this).data('ukey');
          loadKey.push(uKey);
        });

        if (!loadKey.length) {
          $('.news-item.' + type).each(function () {
            uKey = $(this).data('ukey');
            loadKey.push(uKey);
          });
        }

        $.ajax({
          url: '/ajax/lazy_load.php',
          method: 'POST',
          data: { "cur": curPage, "startFrom": lb, "id_vuz": id_vuz, "type": type, "u_key": loadKey },
          beforeSend: function () {
            inProgress = true;
            inProgressGetBanner = true;
          }
        }).done(function (data) {
          data = jQuery.parseJSON(data);
          if (data.res.length > 0) {

            let placeholderEvent = new Array('Название', 'Дата', 'Время', 'Адрес', 'Координаты Яндекс', 'Телефон', 'Контактное лицо', 'Ссылка на страницу', 'Комментарий', 'Текст', 'Облако тегов', 'Тег', 'Запасная строка', 'Дополнительная строка', 'Внутренний комментарий'); // 15
            let placeholderOpendoor = new Array('Название', 'Дата', 'Время', 'Адрес', 'Координаты Яндекс', 'Телефон', 'Ссылка на страницу', 'Комментарий', 'Текст', 'ucheba.ru', 'Запасная ссылка', 'Дополнительная строка', 'Внутренний комментарий');
            let total = 0;

            $.each(data.res, function () {

              let $this = this;
              html = '';

              myLike = 0;
              activeLike = '';
              myDeslyke = 0;
              activeDeslyke = '';
              myGo = 0;
              activeGo = '';

              html += timeLine($this);

              if (this['TYPE'] == 'news') {

                let n = parseInt(this['ID'], 10);

                if (arrLikeNews.includes(n)) {
                  myLike = 1;
                  activeLike = ' active';
                }

                if (arrDeslikeNews.includes(n)) {
                  myDeslyke = 1;
                  activeDeslyke = ' active';
                }

                if (arrLikeNewsCnt[n])
                  likeCnt = arrLikeNewsCnt[n];
                else
                  likeCnt = 0;

                if (arrDeslikeNewsCnt[n])
                  deslikeCnt = arrDeslikeNewsCnt[n];
                else
                  deslikeCnt = 0;

                html += '<div class="news-item news" style="position: relative;">';
                if (this['ADMINS']) {
                  html += '<div class="color-silver js-news-edit" data-block="news" data-id="' + this['ID'] + '" style="position: absolute; right: 5px; cursor: pointer; border-bottom: 1px dashed #9f9f9f;">изменить</div>';
                }
                if (this['PICTURE']) {
                  html += '<div class="image brd left"><img src="' + this['PICTURE'] + '" alt="' + this['NAME'] + '" title="' + this['NAME'] + '" style="max-width: 200px;"></div>';
                }
                html += '<div class="date" style="margin-bottom: 7px;">' + this['FORMAT_DATE'] + '</div>';
                html += '<div class="news-name">';
                html += '<a href="' + detailPageUrl + '?sect=news&s=' + this['ID'] + '"><span>' + this['NAME'] + '</span></a>';
                html += '</div>';
                html += '<p>' + this['FULL_TEXT'] + '</p>';
                html += '<div class="page-rating" data-news="' + this['ID'] + '" data-vuz="' + id_vuz + '" data-name="' + this['NAME'] + '" style="margin: 0px 0px 5px 0px; text-align: right;">';
                if (this['LIKE']) {
                  html += '<div class="st-baloon" style="right: 100px; height: 52px;">';
                  $.each(this['LIKE'], function (i, baloon) {
                    html += '<a href="/user/' + baloon['id_user'] + '/">';
                    html += '<div class="image">';
                    html += '<img src="' + baloon['user_avatar'] + '" alt="' + baloon['user_name'] + '" title="' + baloon['user_name'] + '">';
                    html += '</div>';
                    html += '</a>';
                  });
                  html += '</div>';
                }
                html += '<a href="#" data-my="' + myLike + '" data-cnt="' + likeCnt + '" class="button js-news-left b-left' + activeLike + '" style="position: relative; left: 0px; top: 0px;"><span><i class="fa fa-thumbs-o-up" style="margin-right: 7px;"></i>' + likeCnt + '</span></a>';
                if (this['DESLIKE']) {
                  html += '<div class="st-baloon" style="right: 0px; height: 52px;">';
                  $.each(this['DESLIKE'], function (i, baloon) {
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

              if (this['TYPE'] == 'events') {

                let n = this['ID'];

                if (arrLikeEvents.includes(n)) {
                  myLike = 1;
                  activeLike = ' active';
                }

                if (arrDeslikeEvents.includes(n)) {
                  myDeslyke = 1;
                  activeDeslyke = ' active';
                }

                if (arrLikeEventsCnt[n]) {
                  likeCnt = arrLikeEventsCnt[n];
                } else {
                  likeCnt = 0;
                }

                if (arrDeslikeEventsCnt[n]) {
                  deslikeCnt = arrDeslikeEventsCnt[n];
                } else {
                  deslikeCnt = 0;
                }

                if (arrGoEvents.includes(n)) {
                  myGo = 1;
                  activeGo = ' active';
                }

                if (arrGoEventsCnt[n])
                  goCnt = arrGoEventsCnt[n];
                else
                  goCnt = 0;

                html += '<div class="news-item events" data-id="' + this['ID'] + '" data-id-od="' + this['ID_OPENDOOR'] + '" data-ukey="' + this['ID_OPENDOOR'] + '">';
                html += '<div class="right" data-vuz="' + id_vuz + '" data-event="' + this['ID'] + '" style="position: relative;">';
                if (this['ADMINS']) {
                  html += '<div style="position: relative; top: -10px; right: 5px; text-align: right;">';
                  html += '<div class="color-silver js-news-edit" data-block="events" data-id="' + this['ID'] + '" style="cursor: pointer; border-bottom: 1px dashed #9f9f9f; display: inline-block;">изменить</div>';
                  html += '</div>';
                }
                if (this.DATA[1]) {
                  html += '<div class="date-ico" style="margin-bottom: 10px;"><span>' + this['DAY'] + '</span>' + this['MONTH'] + '</div>';
                }
                if (this.DATA[4]) {
                  html += '<div class="btns text-right" style="text-align: left;"><a href="/map/?map=' + id_vuz + '&event=' + this['ID'] + '" class="button"><span style="font-family: Verdana;">на карте</span></a></div>';
                }
                html += likeButton(this, $this);
                html += deslikeButton(this, $this);
                html += goButton(this, $this);
                html += '</div>';
                if (this['sort'] < curTime) {
                  html += '<div class="date" style="margin-bottom: 7px;">' + this['FORMAT_DATE'] + ' (событие уже прошло)</div>';
                } else {
                  html += '<div class="date" style="margin-bottom: 7px;">' + this['FORMAT_DATE'] + '</div>';
                }
                html += '<div class="news-name">';
                html += '<span>' + this.DATA[0] + '</span>';
                html += '</div>';
                html += '<p style="margin-right: 100px;">';
                for (let n = 1; n < placeholderEvent.length; n++) {
                  if (n == 1 || n == 2 || n == 4 || n == 10 || n == 11 || n == 12 || n == 13 || n == 14)
                    continue;
                  if (this.DATA[n].trim()) {
                    if (n == 7) {
                      html += placeholderEvent[n] + ': <a href="' + this.DATA[n] + '" target="blank">' + this.DATA[n].trim() + '</a><br>';
                    } else if (n == 8 || n == 9) {
                      html += this.DATA[n].trim() + '<br>';
                    } else {
                      html += placeholderEvent[n] + ': ' + this.DATA[n].trim() + '<br>';
                    }
                  }
                }
                html += '</p>';
                html += '</div>';
              }

              if (this['TYPE'] == 'opendoor') {

                html += '<div class="news-item opendoor">';
                html += '<div class="right" style="position: relative;">';
                if (this['ADMINS']) {
                  html += '<div style="position: relative; top: -10px; right: 5px; text-align: right;">';
                  html += '<div class="color-silver js-news-edit" data-block="opendoor" data-id="' + this['ID'] + '" style="cursor: pointer; border-bottom: 1px dashed #9f9f9f; display: inline-block;">изменить</div>';
                  html += '</div>';
                }
                if (this.DATA[1]) {
                  html += '<div class="date-ico" style="margin-bottom: 10px;"><span>' + this['DAY'] + '</span>' + this['MONTH'] + '</div>';
                }
                if (this.DATA[4]) {
                  html += '<div class="btns text-right" style="text-align: left;"><a href="/map/?map=' + id_vuz + '&opendoor=' + (this['ID'] + 1) + '" class="button"><span style="font-family: Verdana;">на карте</span></a></div>';
                }
                html += '</div>';
                if (this['sort'] < curTime) {
                  html += '<div class="date" style="margin-bottom: 7px;">' + this['FORMAT_DATE'] + ' (событие уже прошло)</div>';
                } else {
                  html += '<div class="date" style="margin-bottom: 7px;">' + this['FORMAT_DATE'] + '</div>';
                }
                html += '<div class="news-name">';
                if (this.DATA[6]) {
                  html += '<a href="' + this.DATA[6] + '"><span>' + this.DATA[0] + '</span></a>';
                } else {
                  html += '<span>' + this.DATA[0] + '</span>';
                }
                html += '</div>';
                html += '<p>';
                for (let n = 1; n < placeholderOpendoor.length; n++) {
                  if (n == 1 || n == 2 || n == 4 || n == 9 || n == 10 || n == 11 || n == 12)
                    continue;
                  if (this.DATA[n].trim()) {
                    if (n == 6) {
                      html += placeholderOpendoor[n] + ': <a href="' + this.DATA[n] + '" target="blank">' + this.DATA[n].trim() + '</a><br>';
                    } else if (n == 8) {
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
            });
            if (!lb) {
              $("#box-line .news-item." + type).fadeIn(1000);
              $(".today-" + type).fadeIn(1000);
            }
            inProgress = false;
            inProgressGetBanner = false;
            startFrom += 10;
          }
        });
      }
    });
  }

  function lazyListSearch() {

    let type = $("#filterinput").val();
    let cnt = 20;
    let total = $(".filter-search .filter.active").data('cnt');
    let totalShow = 0;

    let html = '';

    let myLike = 0;
    let activeLike = '';
    let myDeslyke = 0;
    let activeDeslyke = '';
    let myGo = 0;
    let activeGo = '';
    let idObj = 0;
    let uKey = '';
    let loadKey = [];
    let n = 0;

    const load = {
      user: [],
      teacher: [],
      uz: [],
      news: [],
      events: [],
      ug: []
    };

    for (keyLoad in load) {
      if (type === 'all' || type === keyLoad) {
        $('.line .news-item.' + keyLoad).each(function () {
          idObj = $(this).data('id');
          load[keyLoad].push(idObj);
        });
      }
    }

    $.ajax({
      url: '/ajax/lazy_load_search.php',
      method: 'POST',
      data: { "s": search, "startFrom": startFromSearch, "filter": type, "cnt": cnt, "load": load },
      beforeSend: function () {
        inProgress = true;
        inProgressGetBanner = true;
      }
    }).done(function (data) {
      data = jQuery.parseJSON(data);
      if (data.res.length > 0) {
        $.each(data.res, function () {

          let $this = this;

          myLike = 0;
          activeLike = '';
          myDeslyke = 0;
          activeDeslyke = '';
          myGo = 0;
          activeGo = '';

          if (this['type'] == 'user' || this['type'] == 'teacher') {
            console.log(this['type']);
            html += `
						<div class="news-item ${this['type']}" data-id="${this.data["ID"]}">
			                <div class="col-3 content-left" style="padding-right: 0; padding: 0;">
			                    <div class="image brd rad-50" style="text-align: center; width: 142px;">
			                        <img src="${this.data["AVATAR"]}" alt="img" style="height: 111px; width: 111px;${this['type'] == 'teacher' ? ' border: 3px solid #ff5b32;' : ''}">
			                    </div>
			                </div>
			                <div class="col-9 content-right" style="padding: 0;">
			                    <div class="page-info" style="position: absolute; width: 480px;">
			                        <h1 class="name-user">
			                            <span><a href="/user/${this.data["ID"]}/" class="display-name">${this.data["FULL_NAME"]}</a></span>`;
            if (this.data["ONLINE"]) {
              html += `
			                            <div style="display: inline-block; position: relative; top: -1px; margin-left: 5px; width: 10px; height: 10px; border-radius: 50%; background-color: #ff471a;" title="В сети"></div>`;
            }
            html += `
			                        </h1>
			                        <div class="contact-info">
			                            <div class="btns" style="margin-left: 20px; margin-top: 25px; width: 145px; display: inline-block;">
			                                <a style="height: 33px;" href="#" class="button js-bookmark${this['bookmark'] == 1 ? ' active' : ''}" data-state="${this['bookmark'] == 1 ? '1' : '0'}" data-type="5" data-id="${this.data["ID"]}" data-no-close="1">
			                                    <span style="font-size: 16px; padding-top: 5px;">закладки</span>
			                                </a>
			                            </div>
			                            <div class="btns right" style="margin-left: 25px; cursor: pointer; display: inline-block; float: none; position: relative; top: -2px;">
			                                <a style="height: 31px;" href="/user/chat/${this.data["ID"]}/" class="button small">сообщение</a>
			                            </div>
			                        </div>
			                        <br>
			                    </div>
			                </div>
						</div>`;
          } else if (this['type'] == 'uz') {
            html += '<div class="news-item uz" data-id="' + this.data["ID"] + '">';
            html += '<div class="col-3 content-left" style="padding-right: 0; padding: 0;">';
            if (this.data["PIC"]) {
              html += '<div class="image left brd" style="width: 100%;">';
              html += '<img style="width: 100%;" src="' + this.data["PIC"] + '" alt="' + this.data["NAME"] + '" title="' + this.data["NAME"] + '" />';
              html += '</div>';
            }
            html += '<div class="btns" style="margin-top: 10px;">';
            if (this.bookmark) {
              html += '<a href="#" class="button js-bookmark active" data-state="1" data-type="' + this.data["type"] + '" data-id="' + this.data["ID"] + '" data-no-close="1">';
            } else {
              html += '<a href="#" class="button js-bookmark" data-state="0" data-type="' + this.data["type"] + '" data-id="' + this.data["ID"] + '" data-no-close="1">';
            }
            html += '<span style="font-size: 18px;">закладки</span>';
            html += '</a>';
            html += '</div>';
            html += '</div>';
            html += '<div class="col-9 content-right">';
            html += '<div class="news-name">';
            html += '<a href="' + this.data["DETAIL_PAGE_URL"] + '"><span>' + this.data["NAME"] + '</span></a>';
            html += '</div>';
            html += '<p>';
            if (this.data["ADRESS"]) {
              html += 'Адрес:&nbsp;' + this.data["ADRESS"] + '<br>';
            }
            if (this.data["PROPERTY_SITE_VALUE"]) {
              html += 'Сайт:&nbsp;<a href="' + this.data["PROPERTY_SITE_VALUE"] + '">' + this.data["PROPERTY_SITE_VALUE"] + '</a><br>';
            }
            if (this.data["PROPERTY_PHONE_VALUE"]) {
              html += 'Телефон:&nbsp;' + this.data["PROPERTY_PHONE_VALUE"] + '<br>';
            }
            if (this.data["PROPERTY_EMAIL_VALUE"]) {
              html += 'Электронная почта:&nbsp;<a href="mailto:' + this.data["PROPERTY_EMAIL_VALUE"] + '">' + this.data["PROPERTY_EMAIL_VALUE"] + '</a>';
            }
            html += '</p>';
            html += '</div>';
            html += '</div>';
          } else if (this['type'] == 'news') {

            n = parseInt(this.data["ID"], 10);

            if (arrLikeNews.includes(n)) {
              myLike = 1;
              activeLike = ' active';
            }

            if (arrDeslikeNews.includes(n)) {
              myDeslyke = 1;
              activeDeslyke = ' active';
            }

            if (arrLikeNewsCnt[n])
              likeCnt = arrLikeNewsCnt[n];
            else
              likeCnt = 0;

            if (arrDeslikeNewsCnt[n])
              deslikeCnt = arrDeslikeNewsCnt[n];
            else
              deslikeCnt = 0;

            html += '<div class="news-item news" data-id="' + this.data["ID"] + '" style="position: relative;">';
            html += '<div class="col-3 content-left" style="padding-right: 0; padding: 0;">';
            if (this.data["PIC"]) {
              html += '<div class="image left brd" style="width: 100%;">';
              html += '<img style="width: 100%;" src="' + this.data["PIC"] + '" alt="' + this.data["NAME"] + '" title="' + this.data["NAME"] + '" />';
              html += '</div>';
            }
            html += '</div>';
            html += '<div class="col-9 content-right">';
            html += '<div class="date" style="margin-bottom: 7px;">' + this.data["FORMAT_DATE"] + '</div>';
            html += '<div class="news-name">';
            html += '<a href="' + this.data["DETAIL_PAGE_URL"] + '?sect=news&s=' + this.data["ID"] + '"><span>' + this.data["NAME"] + '</span></a>';
            html += '</div>';
            html += '<p>' + this.data["DETAIL_TEXT"] + '</p>';
            html += '<div class="page-rating" data-news="' + this.data['ID'] + '" data-vuz="' + this.data['VUZ_ID'] + '" data-name="' + this.data['NAME'] + '" style="margin: 0px 0px 5px 0px; text-align: right; position: absolute; bottom: 14px; right: 0px;">';
            if (this.data['LIKE']) {
              html += '<div class="st-baloon" style="right: 100px; height: 52px;">';
              $.each(this.data['LIKE'], function (i, baloon) {
                html += '<a href="/user/' + baloon['id_user'] + '/">';
                html += '<div class="image">';
                html += '<img src="' + baloon['user_avatar'] + '" alt="' + baloon['user_name'] + '" title="' + baloon['user_name'] + '">';
                html += '</div>';
                html += '</a>';
              });
              html += '</div>';
            }
            html += '<a href="#" data-my="' + myLike + '" data-cnt="' + likeCnt + '" class="button js-news-left b-left' + activeLike + '" style="position: relative; left: 0px; top: 0px;"><span><i class="fa fa-thumbs-o-up" style="margin-right: 7px;"></i>' + likeCnt + '</span></a>';
            if (this.data['DESLIKE']) {
              html += '<div class="st-baloon" style="right: 0px; height: 52px;">';
              $.each(this.data['DESLIKE'], function (i, baloon) {
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
            html += '</div>';
          } else if (this['type'] == 'events') {

            n = parseInt(this.ID, 10);

            /*if(arrLikeEvents.includes(n)) {
              myLike = 1;
              activeLike = ' active';
      }

            if(arrDeslikeEvents.includes(n)) {
              myDeslyke = 1;
              activeDeslyke = ' active';
      }

      if(arrLikeEventsCnt[n])
        likeCnt = arrLikeNewsCnt[n];
      else
        likeCnt = 0;

      if(arrDeslikeEventsCnt[n])
        deslikeCnt = arrDeslikeNewsCnt[n];
      else
        deslikeCnt = 0;*/

            html += '<div class="news-item events" data-id="' + this.ID_OPENDOOR + '">';
            html += '<div class="col-3 content-left" style="padding-right: 0; padding: 0;">';
            if (this.PIC) {
              html += '<div class="image left brd" style="width: 100%;">';
              html += '<img style="width: 100%;" src="' + this.PIC + '" alt="' + this.NAME + '" title="' + this.NAME + '" />';
              html += '</div>';
            }
            html += '</div>';
            html += '<div class="col-9 content-right" style="padding-right: 0;">';
            html += '<div class="right" data-vuz="' + this.ID + '" data-event="' + this.ID_OPENDOOR + '">';
            html += '<div class="date-ico" style="margin-bottom: 10px;">';
            html += '<span>' + this.DAY + '</span>';
            html += this.MONTH;
            html += '</div>';
            if (this['DATA'][4]) {
              html += '<div class="btns text-right"><a href="/map/?map=' + this['ID'] + '&event=' + this['ID_OPENDOOR'] + '" class="button"><span style="font-family: Verdana;">на карте</span></a></div>';
            }
            html += likeButton(this, $this);
            html += deslikeButton(this, $this);
            html += goButton(this, $this);
            html += '</div>';
            html += '<div class="date" style="margin-bottom: 7px;">' + this.FORMAT_DATE + '</div>';
            html += '<div class="news-name">';
            html += '<a href="' + this.URL + '"><span>' + this.NAME_OPENDOOR + '</span></a>';
            html += '</div>';
            html += '<p>';
            html += this['HTML'];
            html += '</p>';
            html += '</div>';
            html += '</div>';
          }
        });
        $(".line.all").append(html);
        inProgress = false;
        inProgressGetBanner = false;
      }
    });
  }

  if (startFromSearch > 0) {
    $(window).scroll(function () {
      /* Если высота окна + высота прокрутки больше или равны высоте всего документа и ajax-запрос в настоящий момент не выполняется, то запускаем ajax-запрос */
      if ($(window).scrollTop() + $(window).height() >= $(document).height() - 500 && !inProgress) {
        lazyListSearch();
      }
    });
  }

  function lazyList() {

    let type = $('#page .m-header .color-silver').data('filter');
    let load = [];
    let loadKey = [];
    let id = 0;
    let uKey = '';
    let htmlBanner = '';
    let html = '';

    //cnt = 40;

    $('#' + type + ' .news-item').each(function (index, value) {
      id = $(this).data('id');
      load.push(id);
    });

    $('#' + type + ' .news-item').each(function (index, value) {
      uKey = $(this).data('ukey');
      loadKey.push(uKey);
    });

    $.ajax({
      url: '/ajax/lazy_load_list.php',
      method: 'POST',
      data: { "cur": curList, "type": type, "cnt": cnt, "load": load, "u_key": loadKey, "search": search },
      beforeSend: function () {
        inProgress = true;
        inProgressGetBanner = true;
      }
    }).done(function (data) {
      data = jQuery.parseJSON(data);
      if (data.res.length > 0) {

        if(isMobile()) {
          htmlBanner += `
            <div class="modules-left clear new-banner remove-banner">
                <div class="section__item" style="position: relative; display: block; margin: 111px auto;">
                    <div class="loader01"></div>
                </div>
            </div>
        `;

          $("#" + type).append(htmlBanner)

          htmlBanner = ``;

          let loadBanner = [];
          let idBanner = 0;

          $('.js-click-banner.side-banner').each(function () {
            idBanner = $(this).data('id');
            loadBanner.push(idBanner);
          });

          $.ajax({
            url: '/ajax/get_dinamic_banner.php',
            method: 'POST',
            data: { "load": loadBanner },
            beforeSend: function () {
              inProgressGetBanner = true;
            }
          }).done(function (dataBanner) {
            dataBanner = jQuery.parseJSON(dataBanner);

            if (dataBanner.status == 'success' && dataBanner.banner) {

              if(dataBanner.banner.id) {
                htmlBanner += `
                        <div class="st-banner" style="position: relative; display: none;">
                            <div class="hide-banner js-hide-banner">реклама</div>
                            <div class="image brd">
                                <a href="#" data-id="${ dataBanner.banner.id }" class="js-click-banner side-banner" ${ dataBanner.banner.target }><img src="${ dataBanner.banner.src }" title="${ dataBanner.banner.name }" alt="${ dataBanner.banner.name }"></a>
                            </div>
                        </div>    
                    `;
              } else {
                htmlBanner += `
                      <div class="st-banner" style="position: relative; display: none;">
                          <div class="hide-banner js-hide-banner">реклама</div>
                          <div class="image brd">
                              <a class="default-banner" href="${dataBanner.banner.href}" ${dataBanner.banner.target}><img src="${dataBanner.banner.src}" title="${dataBanner.banner.name}" alt="${dataBanner.banner.name}"></a>
                          </div>
                      </div>    
                  `;
              }

              if(htmlBanner.length > 0) {
                $('#' + type + ' .new-banner').empty()
                $('#' + type + ' .new-banner').append( htmlBanner)
                $('#' + type + ' .new-banner .st-banner').fadeIn()
                $('#' + type + ' .new-banner').removeClass('new-banner')
              }
            }
            inProgressGetBanner = false;
          });
        }

        html = ``

        if (curList == 'user') {
          $.each(data.res, function () {
            if (this['TEACHER']) {
              html += '<div class="news-item teacher" id="user-' + this['ID'] + '" data-id="' + this['ID'] + '">';
            } else {
              html += '<div class="news-item us" id="user-' + this['ID'] + '" data-id="' + this['ID'] + '">';
            }
            html += '<div class="col-3 content-left">';
            html += '<div class="image brd rad-50">';
            if (this['TEACHER']) {
              html += '<img src="' + this['AVATAR'] + '" alt="" title="" style="height: 111px; width: 111px; border: 3px solid #ff5b32;">';
            } else {
              html += '<img src="' + this['AVATAR'] + '" alt="" title="" style="height: 111px; width: 111px;">';
            }
            html += '</div>';
            html += '<br>';
            html += '</div>';
            html += '<div class="col-9 content-right">';
            html += '<div class="page-info" style="position: absolute;">';
            html += '<h1 class="name-user">';
            if (this['AUTHORIZE']) {
              html += '<span><a href="/user/' + this['URL'] + '/" class="display-name">' + this['F_NAME'] + '</a></span>';
            } else {
              html += '<span><a href="/user/' + this['URL'] + '/" class="display-name js-noauth">' + this['F_NAME'] + '</a></span>';
            }
            if (this['ONLINE']) {
              html += '<div style="display: inline-block; position: relative; top: -1px; margin-left: 5px; width: 10px; height: 10px; border-radius: 50%; background-color: #ff471a;" title="В сети"></div>';
            }
            html += '</h1>';
            html += '<div class="contact-info">';
            html += '<div class="btns" style="margin-left: 20px; margin-top: 25px; width: 145px; display: inline-block;">';
            if (this['BOOKMARK']) {
              html += '<a style="height: 33px;" href="#" class="button js-bookmark active" data-state="1" data-type="5" data-id="' + this['ID'] + '" data-no-close="1">';
            } else {
              html += '<a style="height: 33px;" href="#" class="button js-bookmark" data-state="0" data-type="5" data-id="' + this['ID'] + '" data-no-close="1">';
            }
            html += '<span style="font-size: 16px; padding-top: 5px;">закладки</span>';
            html += '</a>';
            html += '</div>';
            html += '<div class="btns right" style="margin-left: 25px; cursor: pointer; display: inline-block; float: none; position: relative; top: -2px;">';
            html += '<a style="height: 31px; font-size: 16px;" href="/user/chat/' + this['ID'] + '/" class="button small">сообщение</a>';
            html += '</div>';
            html += '</div>';
            html += '<br>';
            html += '</div>';
            html += '</div>';
            html += '</div>';
          });
          $("#" + type).append(html);
        } else if (curList == 'open-days') {

          $.each(data.res, function () {

            html += timeLine(this);

            html += '<div class="news-item open-days" data-id="' + this['ID'] + '" data-id-od="' + this['ID_OPENDOOR'] + '" data-ukey="' + this['ID_OPENDOOR'] + '">';
            html += '<div class="right">';
            html += '<div class="date-ico" style="margin-bottom: 10px;">';
            html += '<span>' + this['DAY'] + '</span>';
            html += this['MONTH'];
            html += '</div>';
            if (this['DATA'][4]) {
              html += '<div class="btns text-right"><a href="/map/?map=' + this['ID'] + '&opendoor=' + this['ID_OPENDOOR'] + '" class="button"><span style="font-family: Verdana;">на карте</span></a></div>';
            }
            html += '</div>';
            html += '<div class="image left brd">';
            html += '<a href="' + this['URL'] + '">';
            html += '<img style="width: 111px;" src="' + this['IMG'] + '" alt="' + this['NAME'] + '" title="' + this['NAME'] + '">';
            html += '</a>';
            html += '</div>';
            if (this['sort'] < curTime) {
              html += '<div class="date" style="margin-bottom: 7px;">' + this['FORMAT_DATE'] + ' (событие уже прошло)</div>';
            } else {
              html += '<div class="date" style="margin-bottom: 7px;">' + this['FORMAT_DATE'] + '</div>';
            }
            html += '<div class="news-name">';
            if (this['DATA'][6]) {
              html += '<a href="' + this['DATA'][6] + '" target="blank"><span>' + this['NAME_OPENDOOR'] + '</span></a>';
            } else {
              html += '<span>' + this['NAME_OPENDOOR'] + '</span>';
            }
            html += '</div>';
            html += '<p>';
            html += this['HTML'];
            html += '</p>';
            html += '</div>';
          });
          $("#" + type).append(html);
        } else if (curList == 'news-universities' || curList == 'news-colleges' || curList == 'news-schools' || curList == 'news-language-class' || curList == 'news-education') {
          if (type == 'news') {
            $.each(data.res, function () {
              html += '<div class="news-item" data-id="' + this['ID'] + '">';
              if (this['IMG']) {
                html += '<div class="image brd left">';
                html += '<a href="' + this['URL'] + '">';
                html += '<img src="' + this['IMG'] + '" alt="' + this['NAME'] + '" title="' + this['NAME'] + '" style="max-width: 200px;">';
                html += '</a>';
                html += '</div>';
              }
              html += '<div class="date" style="margin-bottom: 7px;">' + this['FORMAT_DATE'] + '</div>';
              html += '<div class="news-name" style="margin-bottom: 15px;">';
              html += '<a href="' + this['URL'] + '"><span>' + this['NAME'] + '</span></a>';
              html += '</div>';
              html += '<p>';
              html += this['TEXT'];
              html += '</p>';
              html += '</div>';
            });
          } else if (type == 'events') {

            let auth = '';
            let active = '';
            let total = 0;
            $.each(data.res, function () {
              let $this = this;

              html += timeLine(this);

              html += '<div class="news-item open-days" data-id="' + this['ID'] + '" data-id-od="' + this['ID_OPENDOOR'] + '" data-ukey="' + this['ID_OPENDOOR'] + '">';
              html += '<div class="right" data-vuz="' + this['ID'] + '" data-event="' + this['ID_OPENDOOR'] + '">';
              html += '<div class="date-ico" style="margin-bottom: 10px;">';
              html += '<span>' + this['DAY'] + '</span>';
              html += this['MONTH'];
              html += '</div>';
              if (this['DATA'][4]) {
                html += '<div class="btns text-right"><a href="/map/?map=' + this['ID'] + '&event=' + this['ID_OPENDOOR'] + '" class="button"><span style="font-family: Verdana;">на карте</span></a></div>';
              }
              html += likeButton(this, $this);
              html += deslikeButton(this, $this);
              html += goButton(this, $this);
              html += '</div>';
              html += '<div class="image left brd">';
              html += '<a href="' + this['URL'] + '">';
              html += '<img style="width: 111px;" src="' + this['IMG'] + '" alt="' + this['NAME'] + '" title="' + this['NAME'] + '">';
              html += '</a>';
              html += '</div>';
              html += `<div class="date" style="margin-bottom: 7px;">${this['FORMAT_DATE']} ${this['sort'] < curTime ? ' (событие уже прошло)' : ''} </div>`;
              html += '<div class="news-name">';
              html += `${this['DATA'][7] ? `<a href="${this['DATA'][7]}" target="blank"><span>${this['NAME_OPENDOOR']}</span></a>` : `<span>${this['NAME_OPENDOOR']}</span>`}`;
              html += '</div>';
              html += '<p style="margin-right: 100px;">';
              html += this['HTML'];
              html += '</p>';
              html += '</div>';
            });
          }
          $("#" + type).append(html);
        } else {
          $.each(data.res, function () {
            html += '<div class="news-item" data-id="' + this['ID'] + '">';
            if(this['YEAR']) {
              html += '<div class="year-mobile">';
              html += '<div class="stick-year" style="margin: 5px auto;">';
              html += '<div class="text">';
              html += 'год <br>основания';
              html += '<span>' + this['YEAR'] + '</span>';
              html += '</div>';
              html += '</div>';
              html += '</div>';
            }
            html += '<div class="col-2 img-mobile">';
            html += '<div class="image brd left">';
            html += '<img style="width: 122px;" src="' + this['IMG'] + '" alt="' + this['NAME'] + '" title="' + this['NAME'] + '" />';
            html += '</div>';
            html += '</div>';
            if(this['YEAR']) {
              html += '<div class="col-8" style="padding: 0 0 0 15px; width: 60%;">';
            } else {
              html += '<div class="col-8" style="padding: 0 0 0 15px; width: 80%;">';
            }
            html += '<div class="news-name">';
            html += '<a href="' + this['URL'] + '"><span class="crop-height">' + this['NAME'] + '</span></a>';
            html += '</div>';
            html += '<p>';
            if (this['ADRESS']) {
              html += 'Адрес:&nbsp;' + this['ADRESS'] + '<br>';
            }
            if (this['SITE']) {
              html += 'Сайт:&nbsp;<a href="' + this['SITE'] + '">' + this['SITE'] + '</a><br>';
            }
            if (this['PHONE']) {
              html += 'Телефон:&nbsp;' + this['PHONE'] + '<br>';
            }
            if (this['EMAIL']) {
              html += 'Электронная почта:&nbsp;<a href="mailto:' + this['EMAIL'] + '">' + this['EMAIL'] + '</a><br>';
            }
            html += '</p>';
            html += '</div>';
            html += '<div class="col-2" style="padding: 0 0 0 15px; width: 20%;">';
            if(this['YEAR']) {
              html += '<div class="stick-year year-desctop">';
              html += '<div class="text">';
              html += 'год <br>основания';
              html += '<span>' + this['YEAR'] + '</span>';
              html += '</div>';
              html += '</div>';
            }
            html += '</div>';
            html += '</div>';
          });
          $("#" + type).append(html);
        }
        inProgress = false;
        inProgressGetBanner = false;
      }
    });
  }

  if (startFromList > 0) {
    $(window).scroll(function () {
      /* Если высота окна + высота прокрутки больше или равны высоте всего документа и ajax-запрос в настоящий момент не выполняется, то запускаем ajax-запрос */
      if ($(window).scrollTop() + $(window).height() >= $(document).height() - 1000 && !inProgress) {
        lazyList();
      }
    });
  }

  function lazyListOrders() {

    const type = $('#page .m-header .color-silver').data('filter');
    let load = [];
    let id = 0;
    let html = '';

    $('.line-orders.' + type + ' .news-item').each(function (index, value) {
      id = $(this).data('id');
      load.push(id);
    });

    $.ajax({
      url: '/ajax/lazy_load_orders.php',
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
                <div class="col-3 width-sm content-left" style="padding: 0;">
                  <div class="image left brd" style="width: 100%;">
                    <img style="width: 100%;" src="${this['PIC']}" alt="${this['NAME']}" title="${this['NAME']}" />
                  </div>
                  <div class="btns" style="margin-top: 10px;">
                    <a href="#" class="button js-order-start ${this['PROPERTY_LAUNCHED_VALUE'] == 'Y' ? ' active' : ''}" data-id="${this['ID']}" data-status="${this['PROPERTY_LAUNCHED_VALUE'] == 'Y' ? '1' : '0'}">
                      <span style="font-size: 18px; text-decoration-color: #ff4719;">Пуск / Стоп</span>
                    </a>
                  </div>
                </div>
                <div class="col-9 width-sm content-right">
                  <div class="news-name">
                    ${this['ARTICLE']}
                  </div>
                  <div style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">`;
          if (this['PROPERTY_REJECTED_VALUE'] == 'Y') {
            html += `
                      <div class="params-banner-top" style="white-space: normal; margin: 15px 0px 15px 0px;">Причина отказа:<br/>${this['PROPERTY_REASON_VALUE']}</div>`;
          }
          html += `
                    <div class="params-banner-top">Статус заказа: <span style="${this['STATUS_STYLE']}">${this['STATUS_NAME']}</span></div>
                    <div class="params-banner">Название баннера: ${this['NAME']}</div>
                    <div class="params-banner">Ссылка: <a href="${this['PROPERTY_URL_VALUE']}" target="blank">${this['PROPERTY_URL_VALUE']}</a></div>
                    <div class="params-banner">Количество показов: ${this['PROPERTY_COUNTER_VALUE'] ? this['PROPERTY_COUNTER_VALUE'] : '0'} (из ${this['PROPERTY_LIMIT_VALUE'] ? this['PROPERTY_LIMIT_VALUE'] : '0'})</div>
                    <div class="params-banner">Количество переходов: ${this['PROPERTY_CLICK_VALUE'] ? this['PROPERTY_CLICK_VALUE'] : '0'}</div>
                    <div class="params-banner">Баннер скрыли: ${this['PROPERTY_HIDE_VALUE'] ? this['PROPERTY_HIDE_VALUE'] : '0'}</div>
                    <div class="params-banner">Тариф: <a href="#" data-tarif="${this['PLAN_CODE']}" class="color-silver js-tarif">${this['PLAN']}</a></div>
                    <div class="params-banner">Стоимость показа: ${this['PLAN_TAX']} руб.</div>`;
          if(this['PROMOCODE']) {
            html += `
                <div class="params-banner">Промокод:  ${this['PROMOCODE']} (${this['DISCOUNT']}%)</div>
            `;
          }
          html += `
                    <div class="params-banner-top">
                      <a href="${this['REPEAT']}" class="color-silver">Повторить заказ</a>
                      <a class="color-silver js-info-order" data-id="${this['ID']}">Детализация заказа</a>
                      <a class="color-silver js-delete-order" data-id="${this['ID']}">Удалить заказ</a>
                    </div>
                  </div>
                </div>
              </div>`;
        });
        $('.line-orders.' + type).append(html);

        inProgress = false;
        inProgressGetBanner = false;
      }
    });
  }

  $("#page").on('click', '.js-delete-order', function (e) {
    e.preventDefault();
    const $this = $(this);

    const id = $this.data('id')

    $.ajax({
      type: 'POST',
      url: '/ajax/order_delete.php',
      data: { id },
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          $this.closest('.news-item').slideUp()
        }
      }
    });

    return false;
  });

  $("#page").on('click', '.js-info-order', function (e) {
    e.preventDefault();

    const $this = $(this);

    const id = $this.data('id')

    /** Создание формы **/
    const top_form = $(window).scrollTop();
    $('.hideForm-banner-info .form-open-block').css({
      'height': $(window).height(),
      'position': 'absolute',
      'top': top_form,
    });
    $('.hideForm-banner-info').css({ 'height': $(document).height(), });

    /** Получение данных по заказу **/
    $.ajax({
      type: 'POST',
      url: '/ajax/order_info.php',
      data: { id },
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          const data = result.res

          const title = `Детализация заказа №${data.ID} от ${data.DATE_FORMAT}`
          $('#form-banner-info .name_form span').text(title)

          let html = `
                <div style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
              `
          if(data.REJECTED == 'Y') {
            html += `
                <div class="params-banner-top" style="white-space: normal; margin: 15px 0px 15px 0px;">Причина отказа:<br/>${data.REASON}</div>
              `
          }

          html += `
                <div class="params-banner-top">Статус заказа: <span style="${data.STATUS_STYLE}">${data.STATUS_NAME}</span></div>
                <div class="params-banner">Название баннера: ${data.NAME}</div>
                <div class="params-banner">Ссылка: <a href="${data.URL}" target="blank">${data.URL}</a></div>
                <div class="params-banner">Количество показов: ${data.COUNTER ? data.COUNTER : '0'} (из ${data.LIMIT ? data.LIMIT : '0'})</div>
                <div class="params-banner">Количество переходов: ${data.B_CLICK ? data.B_CLICK : '0'}</div>
                <div class="params-banner">Баннер скрыли: ${data.HIDE ? data.HIDE : '0'}</div>
                <div class="params-banner">Тариф: <a href="#" data-type="${data.TYPE_BANNER}" data-tarif="${data.PLAN}" class="color-silver js-tarif">${data.PLAN_NAME}</a></div>
                <div class="params-banner">Стоимость показа: ${data.PLAN_TAX} руб.</div>`
          if(data.PROMOCODE) {
              html += ` 
                    <div class="params-banner">${data.STRPROMOCODE}</div>
              `
          }
          html += `    
            </div>
          `
          $('#form-banner-info #banner-info').empty()
          $('#form-banner-info #banner-info').append(html)

          if(!result.list.length) {
            $('#form-banner-info .head-line').hide()
            $('#form-banner-info .list-line').hide()
          } else {
            html = ``;
            $.each(result.list, function () {
                html += `
                  <div class="row-line one-line">
                    <div class="col-4">${this.date_format}</div>
                  `
                if(this.direction == 7) {
                  html += `
                    <div class="col-3 red-line">-${this.tax} руб.</div>
                  `
                } else {
                  html += `
                    <div class="col-3 silver-line">${this.tax} руб.</div>
                  `
                }
                html += `    
                    <div class="col-5">${this.disc}</div>
                  </div>
                `;
            });

            $('#form-banner-info #banner-info-list').empty()
            $('#form-banner-info #banner-info-list').append(html)
          }

          /** Вывод формы на экран **/
          $('.foneBg').css({ 'display': 'block' });
          $('.hideForm-banner-info').fadeIn(250);
        }
      }
    });

    return false;
  });

  if (startFromListOrders > 0) {
    $(window).scroll(function () {
      /* Если высота окна + высота прокрутки больше или равны высоте всего документа и ajax-запрос в настоящий момент не выполняется, то запускаем ajax-запрос */
      if ($(window).scrollTop() + $(window).height() >= $(document).height() - 500 && !inProgress) {
        lazyListOrders();
      }
    });
  }

  var intervalID;

  $("#box-line").on('mouseenter', '.js-event-go, .js-event-left, .js-group-list, .js-event-right, .js-news-left, .js-news-right, .js-b-left-2, .js-b-right-2, .js-noauth', function (e) {
    e.preventDefault();
    let $this = $(this);
    let curr = $this.prev('.st-baloon');
    if (!curr.length)
      return false;
    $('.st-baloon').hide();
    clearInterval(intervalID);
    intervalID = setTimeout(function () {
      curr.fadeIn();
    }, 1000);
    return false;
  });

  $("#box-line").on('mouseleave', '.js-event-go, .js-event-left, .js-group-list, .js-event-right, .js-news-left, .js-news-right, .js-b-left-2, .js-b-right-2, .js-noauth', function (e) {
    e.preventDefault();
    let $this = $(this);
    let curr = $this.prev('.st-baloon');
    if (!curr.length)
      return false;
    clearInterval(intervalID);
    intervalID = setTimeout(function () {
      curr.fadeOut();
    }, 1000);
    return false;
  });

  $("#box-line").on('mouseenter', '.st-baloon', function (e) {
    e.preventDefault();
    let $this = $(this);
    clearInterval(intervalID);
    $this.show();
    return false;
  });

  $("#box-line").on('mouseleave', '.st-baloon', function (e) {
    e.preventDefault();
    let $this = $(this);
    clearInterval(intervalID);
    intervalID = setTimeout(function () {
      $this.fadeOut();
    }, 1000);
    return false;
  });

  $("#page-rating-vuz").on('mouseenter', '.js-vuz-left, .js-vuz-right', function (e) {
    e.preventDefault();
    let $this = $(this);
    let curr = $this.prev('.js-baloon');
    if (!curr.length)
      return false;
    $('.st-baloon').hide();
    clearInterval(intervalID);
    intervalID = setTimeout(function () {
      curr.fadeIn();
    }, 1000);
    return false;
  });

  $("#page-rating-vuz").on('mouseleave', '.js-vuz-left, .js-vuz-right', function (e) {
    e.preventDefault();
    let $this = $(this);
    let curr = $this.prev('.js-baloon');
    if (!curr.length)
      return false;
    clearInterval(intervalID);
    intervalID = setTimeout(function () {
      curr.fadeOut();
    }, 1000);
    return false;
  });

  $("#page-rating-vuz").on('mouseenter', '.js-baloon', function (e) {
    e.preventDefault();
    let $this = $(this);
    clearInterval(intervalID);
    $this.show();
    return false;
  });

  $("#page-rating-vuz").on('mouseleave', '.js-baloon', function (e) {
    e.preventDefault();
    let $this = $(this);
    clearInterval(intervalID);
    intervalID = setTimeout(function () {
      $this.fadeOut();
    }, 1000);
    return false;
  });

  var nextAdress = 1;

  $(".js-vuz-edit").on('click', function (e) {
    e.preventDefault();
    let block = $(this).data('block');
    let iblock = $(this).data('iblock');

    nextAdress = 1;

    var top_form = $(window).scrollTop();
    var height_form = $('.hideForm-vuz-edit.' + block + ' .form-open-block form').height();
    var marg_top = $(window).height() / 2;

    $('.hideForm-vuz-edit.' + block + ' .form-open-block').css({
      'height': $(window).height(),
      'position': 'absolute',
      'top': top_form,
    });
    $('.hideForm-vuz-edit.' + block).css({ 'height': $(document).height(), });

    $('.hideForm-vuz-edit.first .name, .hideForm-vuz-edit.first .name-full').css('color', '#a7a7a7');

    $('.js-submit-vuz-edit').data('iblock', iblock);

    if (iblock == 2) {
      $('.hideForm-vuz-edit .name, .hideForm-vuz-edit .name_form span').text('Редактирование ВУЗа');
    } else if (iblock == 3) {
      $('.hideForm-vuz-edit .name, .hideForm-vuz-edit .name_form span').text('Редактирование колледжа');
    } else if (iblock == 4) {
      $('.hideForm-vuz-edit .name, .hideForm-vuz-edit .name_form span').text('Редактирование школы');
    } else if (iblock == 6) {
      $('.hideForm-vuz-edit .name, .hideForm-vuz-edit .name_form span').text('Редактирование языкового курса');
      $('.hideForm-vuz-edit.first .adress-last').remove();
      $('.hideForm-vuz-edit.first .adress-first .label').text('Адрес 1');
      $('.hideForm-vuz-edit.first .adress-first input').addClass('adress-num-' + nextAdress);
      $('.hideForm-vuz-edit.first .hide-input').show();
    }

    if (block == 'license') {

      let rukovodstvo = {};
      if (iblock == 2) {
        rukovodstvo = { 4: 'Декан', 5: 'Директор', 6: 'Директор института', 7: 'Директор филиала', 8: 'И.о. ректора', 9: 'Начальник', 10: 'Начальник Академии', 11: 'Начальник училища', 12: 'Ректор' };
      } else if (iblock == 3) {
        rukovodstvo = { 13: 'Начальник', 14: 'И.о. ректора', 15: 'Директор филиала', 16: 'Директор института', 17: 'Ректор', 18: 'Директор', 19: 'Начальник училища', 20: 'Декан', 21: 'Начальник Академии' };
      } else if (iblock == 4) {
        rukovodstvo = { 93: 'Начальник', 94: 'И.о. ректора', 95: 'Директор филиала', 96: 'Директор института', 97: 'Ректор', 98: 'Директор', 99: 'Начальник училища', 100: 'Декан', 101: 'Начальник Академии' };
      }

      let html = '<option value="">Неустановлено</option>';
      $.each(rukovodstvo, function (index, val) {
        html = '<option value="' + index + '">' + val + '</option>';
        $('.hideForm-vuz-edit.license .rukovodstvo').append(html);
      });
    }

    $.ajax({
      type: 'POST',
      url: '/ajax/vuz_edit.php',
      data: { 'id_vuz': id_vuz, 'type': block, 'iblock': iblock },
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {

          if (block == 'first') {

            let html = '<option value="">Неустановлено</option>';
            $.each(result.res['COUNTRY_ARR'], function (index, val) {
              html += '<option value="' + val['ID'] + '">' + val['NAME'] + '</option>';
            });
            $('.hideForm-vuz-edit.first .country').append(html);

            html = '<option value="">Неустановлено</option>';
            $.each(result.res['CITY_ARR'], function (index, val) {
              html += '<option value="' + val['ID'] + '">' + val['NAME'] + '</option>';
            });
            $('.hideForm-vuz-edit.first .city').append(html);

            $('.hideForm-vuz-edit.' + block + ' .name').val(result.res['NAME']);
            $('.hideForm-vuz-edit.' + block + ' .name-full').val(result.res['FULL_NAME']);
            $('.hideForm-vuz-edit.' + block + ' .abbr').val(result.res['ABBR']);
            $('.hideForm-vuz-edit.' + block + ' .year').val(result.res['YEAR']);
            $(".hideForm-vuz-edit." + block + " .country option[value='" + result.res['COUNTRY'] + "']").attr("selected", "selected");
            $(".hideForm-vuz-edit." + block + " .city option[value='" + result.res['CITY'] + "']").attr("selected", "selected"); $('.hideForm-vuz-edit.' + block + ' .phone-first').val(result.res['PHONE']);
            $('.hideForm-vuz-edit.' + block + ' .phone-pk').val(result.res['PHONE_PK']);
            $('.hideForm-vuz-edit.' + block + ' .email').val(result.res['EMAIL']);
            $('.hideForm-vuz-edit.' + block + ' .email-pk').val(result.res['EMAIL_PK']);
            $('.hideForm-vuz-edit.' + block + ' .site').val(result.res['SITE']);
            $('.hideForm-vuz-edit.' + block + ' .e-pk').val(result.res['ELECTRON_PR']);
            if (iblock == 6) {
              $('.hideForm-vuz-edit.' + block + ' .hours').val(result.res['COST_HOUR']);
              $('.hideForm-vuz-edit.' + block + ' .month').val(result.res['COST_MONTH']);

              $('.hideForm-vuz-edit.' + block + ' .license').val(result.res['LICENCE']);
              $('.hideForm-vuz-edit.' + block + ' .free').val(result.res['TESTLESSON']);
              $('.hideForm-vuz-edit.' + block + ' .group').val(result.res['GROUP_CURS']);
              $('.hideForm-vuz-edit.' + block + ' .kind').val(result.res['CHILDREN']);
              $('.hideForm-vuz-edit.' + block + ' .pay').val(result.res['PAYMENT']);

              /*let html = '<option value="">Неустановлено</option>';
              $.each(result.res['SECTIONS'], function(index_sec, val_sec){
                html += '<option value="' + index_sec + '">' + val_sec + '</option>';

              });
              $('.hideForm-vuz-edit.first .city').append(html);

              $('.hideForm-vuz-edit.first .city option[value=' + result.res['SECTION'] + ']').prop('selected', true);*/

              let yk = 0;
              let arr = [];
              html = '';
              $.each(result.res['ADRESS'], function (index_yk, val_yk) {
                yk = index_yk + 1;
                arr = val_yk.split('&')
                if (yk == 1) {
                  $('.hideForm-vuz-edit.first .adress-first input').val(arr[0]);
                } else {
                  html += '<div class="row-line mt-10 adress-last">';
                  html += '<div class="col-12">';
                  html += '<div class="label">Адрес ' + yk + '</div>';
                  html += '<input class="addres js-vuz-edit-form adress-num-' + yk + '" type="text" value="' + arr[0] + '">';
                  html += '</div>';
                  html += '</div>';
                }
                nextAdress = nextAdress + 1;
              });
              $('#form-vuz-first .add-button').before(html);
            } else {
              $('.hideForm-vuz-edit.' + block + ' .addres').val(result.res['ADRESS']);
            }
          } else if (block == 'soc') {
            $('.hideForm-vuz-edit.' + block + ' .vk').val(result.res['VK']);
            $('.hideForm-vuz-edit.' + block + ' .fb').val(result.res['FB']);
            $('.hideForm-vuz-edit.' + block + ' .ok').val(result.res['OK']);
            $('.hideForm-vuz-edit.' + block + ' .tw').val(result.res['TWITTER']);
            $('.hideForm-vuz-edit.' + block + ' .wik').val(result.res['WIKI']);
            $('.hideForm-vuz-edit.' + block + ' .inst').val(result.res['INSTA']);
            $('.hideForm-vuz-edit.' + block + ' .you').val(result.res['YOUTUBE']);
          } else if (block == 'license') {
            $(".hideForm-vuz-edit." + block + " .gov :contains('" + result.res['GOV'] + "')").attr("selected", "selected");
            $('.hideForm-vuz-edit.' + block + ' .ga-num').val(result.res['GA_NUM']);
            $('.hideForm-vuz-edit.' + block + ' .ga-start').val(result.res['GA_START']);
            $('.hideForm-vuz-edit.' + block + ' .ga-end').val(result.res['GA_END']);
            $('.hideForm-vuz-edit.' + block + ' .ga-svid').val(result.res['GA_SVID']);
            $('.hideForm-vuz-edit.' + block + ' .licese-num').val(result.res['LICESE_NUM']);
            $('.hideForm-vuz-edit.' + block + ' .licese-start').val(result.res['LICESE_START']);
            $('.hideForm-vuz-edit.' + block + ' .licese-end').val(result.res['LICESE_END']);
            $('.hideForm-vuz-edit.' + block + ' .licese-link').val(result.res['LICESE_LINK']);
            $('.hideForm-vuz-edit.' + block + ' .akk-num').val(result.res['AKK_NUM']);
            $('.hideForm-vuz-edit.' + block + ' .akk-start').val(result.res['AKK_START']);
            $('.hideForm-vuz-edit.' + block + ' .akk-end').val(result.res['AKK_END']);
            $('.hideForm-vuz-edit.' + block + ' .ga-link').val(result.res['GA_LINK']);
            $('.hideForm-vuz-edit.' + block + ' .uchreditel').val(result.res['UCHREDITEL']);
            $(".hideForm-vuz-edit." + block + " .rukovodstvo :contains('" + result.res['RUKOVODSTVO'] + "')").attr("selected", "selected");
            $('.hideForm-vuz-edit.' + block + ' .fio-rukovodstvo').val(result.res['FIO_RUKOVODSTVO']);
          } else if (block == 'service') {
            $('.hideForm-vuz-edit.' + block + ' .park').val(result.res['PARKING']);
            $('.hideForm-vuz-edit.' + block + ' .wi-fi').val(result.res['WIFI']);
            $('.hideForm-vuz-edit.' + block + ' .stol').val(result.res['STOLOVAYA']);
            $('.hideForm-vuz-edit.' + block + ' .med-punkt').val(result.res['MEDPUNKT']);
            $('.hideForm-vuz-edit.' + block + ' .sport').val(result.res['SPORT']);
            $('.hideForm-vuz-edit.' + block + ' .book').val(result.res['BOOK']);
            $('.hideForm-vuz-edit.' + block + ' .war').val(result.res['WAR']);
            $('.hideForm-vuz-edit.' + block + ' .muzey').val(result.res['MUSEUM']);
            $('.hideForm-vuz-edit.' + block + ' .water').val(result.res['WATER']);
            $('.hideForm-vuz-edit.' + block + ' .akt-zal').val(result.res['AKT_ZAL']);
          } else if (block == 'history') {
            $('.hideForm-vuz-edit.' + block + ' .message').html(result.res['HISTORY']);
          }
        }
      }
    });

    $('.foneBg').css({ 'display': 'block' });

    $('.hideForm-vuz-edit.' + block).fadeIn(250);

    return false;
  });

  $("#form-vuz-first").on('click', '.add-button .add-adres', function (e) {
    e.preventDefault();

    let html = '';

    html += '<div class="row-line mt-10 adress-last">';
    html += '<div class="col-12">';
    html += '<div class="label">Адрес ' + nextAdress + '</div>';
    html += '<input class="addres js-vuz-edit-form adress-num-' + nextAdress + '" type="text">';
    html += '</div>';
    html += '</div>';

    $('#form-vuz-first .add-button').before(html);

    nextAdress = nextAdress + 1;

    return false;
  });

  $(".js-submit-vuz-edit").on('click', function (e) {
    e.preventDefault();
    let block = $(this).data('form');
    let iblock = $(this).data('iblock');

    dataform = {};

    if (block == 'first') {
      name = $('.hideForm-vuz-edit.' + block + ' .name').val();
      namefull = $('.hideForm-vuz-edit.' + block + ' .name-full').val();
      if (name == '' || name == 'Поле обязательно для заполнения') {
        $('.hideForm-vuz-edit.' + block + ' .name').css('color', 'red');
        $('.hideForm-vuz-edit.' + block + ' .name').val('Поле обязательно для заполнения');
        return false;
      }
      /*if(namefull == '' || namefull == 'Поле обязательно для заполнения') {
        $('.hideForm-vuz-edit.' + block + ' .name-full').css('color', 'red');
        $('.hideForm-vuz-edit.' + block + ' .name-full').val('Поле обязательно для заполнения');
        return false;
      }*/
      abbr = $('.hideForm-vuz-edit.' + block + ' .abbr').val();
      year = $('.hideForm-vuz-edit.' + block + ' .year').val();
      country = $(".hideForm-vuz-edit." + block + " .country option:selected").val();
      city = $('.hideForm-vuz-edit.' + block + ' .city').val();
      phone = $('.hideForm-vuz-edit.' + block + ' .phone-first').val();
      phonepk = $('.hideForm-vuz-edit.' + block + ' .phone-pk').val();
      email = $('.hideForm-vuz-edit.' + block + ' .email').val();
      emailpk = $('.hideForm-vuz-edit.' + block + ' .email-pk').val();
      site = $('.hideForm-vuz-edit.' + block + ' .site').val();
      epk = $('.hideForm-vuz-edit.' + block + ' .e-pk').val();

      hours = $('.hideForm-vuz-edit.' + block + ' .hours').val();
      month = $('.hideForm-vuz-edit.' + block + ' .month').val();

      license = $('.hideForm-vuz-edit.' + block + ' .license').val();
      free = $('.hideForm-vuz-edit.' + block + ' .free').val();
      group = $('.hideForm-vuz-edit.' + block + ' .group').val();
      kind = $('.hideForm-vuz-edit.' + block + ' .kind').val();
      pay = $('.hideForm-vuz-edit.' + block + ' .pay').val();

      let adress = [];
      $('.hideForm-vuz-edit.' + block + ' .addres').each(function (index) {
        adress.push($(this).val().trim());
      });

      dataform = { 'name': name, 'namefull': namefull, 'abbr': abbr, 'year': year, 'country': country, 'city': city, 'phone': phone, 'phonepk': phonepk, 'email': email, 'emailpk': emailpk, 'site': site, 'epk': epk, 'adress': adress, 'hours': hours, 'month': month, 'license': license, 'free': free, 'group': group, 'kind': kind, 'pay': pay };
    } else if (block == 'soc') {
      vk = $('.hideForm-vuz-edit.' + block + ' .vk.js-soc').val();
      fb = $('.hideForm-vuz-edit.' + block + ' .fb.js-soc').val();
      ok = $('.hideForm-vuz-edit.' + block + ' .ok.js-soc').val();
      tw = $('.hideForm-vuz-edit.' + block + ' .tw.js-soc').val();
      wik = $('.hideForm-vuz-edit.' + block + ' .wik.js-soc').val();
      inst = $('.hideForm-vuz-edit.' + block + ' .inst.js-soc').val();
      you = $('.hideForm-vuz-edit.' + block + ' .you.js-soc').val();
      dataform = { 'vk': vk, 'fb': fb, 'ok': ok, 'tw': tw, 'wik': wik, 'inst': inst, 'you': you };
    } else if (block == 'license') {
      gov = $(".hideForm-vuz-edit." + block + " .gov option:selected").val();
      ganum = $('.hideForm-vuz-edit.' + block + ' .ga-num').val();
      gastart = $('.hideForm-vuz-edit.' + block + ' .ga-start').val();
      gaend = $('.hideForm-vuz-edit.' + block + ' .ga-end').val();
      gasvid = $('.hideForm-vuz-edit.' + block + ' .ga-svid').val();
      licesenum = $('.hideForm-vuz-edit.' + block + ' .licese-num').val();
      licesestart = $('.hideForm-vuz-edit.' + block + ' .licese-start').val();
      liceseend = $('.hideForm-vuz-edit.' + block + ' .licese-end').val();
      liceselink = $('.hideForm-vuz-edit.' + block + ' .licese-link').val();
      akknum = $('.hideForm-vuz-edit.' + block + ' .akk-num').val();
      akkstart = $('.hideForm-vuz-edit.' + block + ' .akk-start').val();
      akkend = $('.hideForm-vuz-edit.' + block + ' .akk-end').val();
      galink = $('.hideForm-vuz-edit.' + block + ' .ga-link').val();
      uchreditel = $('.hideForm-vuz-edit.' + block + ' .uchreditel').val();
      rukovodstvo = $(".hideForm-vuz-edit." + block + " .rukovodstvo option:selected").val();
      fiorukovodstvo = $('.hideForm-vuz-edit.' + block + ' .fio-rukovodstvo').val();
      dataform = { 'gov': gov, 'ganum': ganum, 'gastart': gastart, 'gaend': gaend, 'gasvid': gasvid, 'licesenum': licesenum, 'licesestart': licesestart, 'liceseend': liceseend, 'liceselink': liceselink, 'akknum': akknum, 'akkstart': akkstart, 'akkend': akkend, 'galink': galink, 'uchreditel': uchreditel, 'rukovodstvo': rukovodstvo, 'fiorukovodstvo': fiorukovodstvo };
    } else if (block == 'service') {
      park = $('.hideForm-vuz-edit.' + block + ' .park').val();
      wifi = $('.hideForm-vuz-edit.' + block + ' .wi-fi').val();
      stol = $('.hideForm-vuz-edit.' + block + ' .stol').val();
      medpunkt = $('.hideForm-vuz-edit.' + block + ' .med-punkt').val();
      sport = $('.hideForm-vuz-edit.' + block + ' .sport').val();
      book = $('.hideForm-vuz-edit.' + block + ' .book').val();
      war = $('.hideForm-vuz-edit.' + block + ' .war').val();
      muzey = $('.hideForm-vuz-edit.' + block + ' .muzey').val();
      water = $('.hideForm-vuz-edit.' + block + ' .water').val();
      aktzal = $('.hideForm-vuz-edit.' + block + ' .akt-zal').val();
      dataform = { 'park': park, 'wifi': wifi, 'stol': stol, 'medpunkt': medpunkt, 'sport': sport, 'book': book, 'war': war, 'muzey': muzey, 'water': water, 'aktzal': aktzal };
    } else if (block == 'history') {
      message = $('.hideForm-vuz-edit.' + block + ' .message').val();
      if (message == '' || message == 'Поле обязательно для заполнения') {
        $('.hideForm-vuz-edit.' + block + ' .message').css('color', 'red');
        $('.hideForm-vuz-edit.' + block + ' .message').val('Поле обязательно для заполнения');
        return false;
      }
      dataform = { 'message': message };
    }

    dataform['id_vuz'] = id_vuz;
    dataform['type'] = block;
    dataform['iblock'] = iblock;

    $.ajax({
      type: 'POST',
      url: '/ajax/vuz_edit_save.php',
      data: dataform,
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          document.location.reload(true);
        }
      }
    });

    return false;
  });

  $('.hideForm-vuz-edit.first .name, .hideForm-vuz-edit.first .name-full, .hideForm-vuz-edit.history .message').on('focus', function () {
    $(this).css('color', '#a7a7a7');
    if ($(this).val() == 'Поле обязательно для заполнения')
      $(this).val('');
    return false;
  });

  var nameForm = '';

  $("#box-line").on('click', '.js-news-edit', function (e) {
    e.preventDefault();
    let block = $(this).data('block');
    let id_block = $(this).data('id');
    let iblock = $(this).data('iblock');

    var top_form = $(window).scrollTop();
    var height_form = $('.hideForm-news-edit.' + block + ' .form-open-block form').height();
    var marg_top = $(window).height() / 2;

    $('.hideForm-news-edit.' + block + ' .form-open-block').css({
      'height': $(window).height(),
      'position': 'absolute',
      'top': top_form,
    });
    $('.hideForm-news-edit.' + block).css({ 'height': $(document).height(), });

    $('.hideForm-news-edit.news .name, .hideForm-news-edit.news .message').css('color', '#a7a7a7');

    $.ajax({
      type: 'POST',
      url: '/ajax/news_edit.php',
      data: { 'id_vuz': id_vuz, 'id_block': id_block, 'type': block, 'iblock': iblock },
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          if (block == 'news') {
            $('.hideForm-news-edit.' + block + ' .name').val(result.res['NAME']);
            $('.hideForm-news-edit.' + block + ' .message').html(result.res['DETAIL_TEXT']);
            $('.hideForm-news-edit.' + block + ' .box-img .brd').remove();
            let html = '';
            if (result.res['MORE_PHOTO']) {
              $.each(result.res['MORE_PHOTO'], function (index_mp, val_mp) {
                if (val_mp['ID'] > 0) {
                  html += '<div class="col-3 brd" style="position: relative;" id="delete-img">';
                  html += '<img class="new-photo" src="' + val_mp['SRC'] + '" style="width: 100%;">';
                  html += '</div>';

                  $('.hideForm-news-edit.news .box-img .label-img').text('удалить фото');
                  $('.hideForm-news-edit.news .box-img .label-img').addClass('del-news-img');
                  $('.hideForm-news-edit.news .box-img .label-img').attr('for', '');
                  $('.hideForm-news-edit.news .box-img .label-img').data('id', val_mp['ID']);

                  return false;
                }
              });
            }
            if (html) {
              $('.hideForm-news-edit.' + block + ' .box-img').append(html);
            }
          } else if (block == 'events') {
            $('.hideForm-news-edit.' + block + ' .name').val(result.res['NAME']);
            $('.hideForm-news-edit.' + block + ' .date').val(result.res['DATE']);
            $('.hideForm-news-edit.' + block + ' .time').val(result.res['TIME']);
            $('.hideForm-news-edit.' + block + ' .phone-event').val(result.res['PHONE']);
            $('.hideForm-news-edit.' + block + ' .coord').val(result.res['COORD']);
            $('.hideForm-news-edit.' + block + ' .contact').val(result.res['CONTACT']);
            $('.hideForm-news-edit.' + block + ' .link').val(result.res['LINK']);
            $('.hideForm-news-edit.' + block + ' .adress').val(result.res['ADRESS']);
            $('.hideForm-news-edit.' + block + ' .text').val(result.res['TEXT']);
            $('.hideForm-news-edit.' + block + ' .message').val(result.res['COMMENT']);
          } else if (block == 'opendoor') {
            $('.hideForm-news-edit.' + block + ' .name').val(result.res['NAME']);
            $('.hideForm-news-edit.' + block + ' .date').val(result.res['DATE']);
            $('.hideForm-news-edit.' + block + ' .time').val(result.res['TIME']);
            $('.hideForm-news-edit.' + block + ' .phone-event').val(result.res['PHONE']);
            $('.hideForm-news-edit.' + block + ' .coord').val(result.res['COORD']);
            $('.hideForm-news-edit.' + block + ' .link').val(result.res['LINK']);
            $('.hideForm-news-edit.' + block + ' .adress').val(result.res['ADRESS']);
            $('.hideForm-news-edit.' + block + ' .text').val(result.res['TEXT']);
            $('.hideForm-news-edit.' + block + ' .message').val(result.res['COMMENT']);
          } else if (block == 'programs') {
            $('.hideForm-news-edit.' + block + ' .name').val(result.res['NAME']);
            $('.hideForm-news-edit.' + block + ' .base').val(result.res['BASE']);
            $('.hideForm-news-edit.' + block + ' .ust').val(result.res['UST']);
            $('.hideForm-news-edit.' + block + ' .code').val(result.res['CODE']);
            $('.hideForm-news-edit.' + block + ' .link').val(result.res['LINK']);
            $('.hideForm-news-edit.' + block + ' .text').val(result.res['TEXT']);
            $('.hideForm-news-edit.' + block + ' .message').val(result.res['COMMENT']);

            $('.hideForm-news-edit.' + block + ' .och-start').val(result.res['OCH_START']);
            $('.hideForm-news-edit.' + block + ' .och-dur').val(result.res['OCH_DUR']);
            $('.hideForm-news-edit.' + block + ' .och-price').val(result.res['OCH_PRICE']);
            $('.hideForm-news-edit.' + block + ' .och-pb').val(result.res['OCH_PB']);
            $('.hideForm-news-edit.' + block + ' .och-ekzamen').val(result.res['OCH_EKZAMEN']);
            $('.hideForm-news-edit.' + block + ' .och-dop').val(result.res['OCH_DOP']);

            $('.hideForm-news-edit.' + block + ' .ochzoch-start').val(result.res['OCHZOCH_START']);
            $('.hideForm-news-edit.' + block + ' .ochzoch-dur').val(result.res['OCHZOCH_DUR']);
            $('.hideForm-news-edit.' + block + ' .ochzoch-price').val(result.res['OCHZOCH_PRICE']);
            $('.hideForm-news-edit.' + block + ' .ochzoch-pb').val(result.res['OCHZOCH_PB']);
            $('.hideForm-news-edit.' + block + ' .ochzoch-ekzamen').val(result.res['OCHZOCH_EKZAMEN']);
            $('.hideForm-news-edit.' + block + ' .ochzoch-dop').val(result.res['OCHZOCH_DOP']);

            $('.hideForm-news-edit.' + block + ' .zoch-start').val(result.res['ZOCH_START']);
            $('.hideForm-news-edit.' + block + ' .zoch-dur').val(result.res['ZOCH_DUR']);
            $('.hideForm-news-edit.' + block + ' .zoch-price').val(result.res['ZOCH_PRICE']);
            $('.hideForm-news-edit.' + block + ' .zoch-pb').val(result.res['ZOCH_PB']);
            $('.hideForm-news-edit.' + block + ' .zoch-ekzamen').val(result.res['ZOCH_EKZAMEN']);
            $('.hideForm-news-edit.' + block + ' .zoch-dop').val(result.res['ZOCH_DOP']);

            $('.hideForm-news-edit.' + block + ' .gvd-start').val(result.res['GVD_START']);
            $('.hideForm-news-edit.' + block + ' .gvd-dur').val(result.res['GVD_DUR']);
            $('.hideForm-news-edit.' + block + ' .gvd-price').val(result.res['GVD_PRICE']);
            $('.hideForm-news-edit.' + block + ' .gvd-pb').val(result.res['GVD_PB']);
            $('.hideForm-news-edit.' + block + ' .gvd-ekzamen').val(result.res['GVD_EKZAMEN']);
            $('.hideForm-news-edit.' + block + ' .gvd-dop').val(result.res['GVD_DOP']);

            $('.hideForm-news-edit.' + block + ' .dis-start').val(result.res['DIS_START']);
            $('.hideForm-news-edit.' + block + ' .dis-dur').val(result.res['DIS_DUR']);
            $('.hideForm-news-edit.' + block + ' .dis-price').val(result.res['DIS_PRICE']);
            $('.hideForm-news-edit.' + block + ' .dis-pb').val(result.res['DIS_PB']);
            $('.hideForm-news-edit.' + block + ' .dis-ekzamen').val(result.res['DIS_EKZAMEN']);
            $('.hideForm-news-edit.' + block + ' .dis-dop').val(result.res['DIS_DOP']);
          } else if (block == 'corpus') {
            $('.hideForm-news-edit.' + block + ' .name').val(result.res['NAME']);
            $('.hideForm-news-edit.' + block + ' .phone-corpus').val(result.res['PHONE']);
            $('.hideForm-news-edit.' + block + ' .coord').val(result.res['COORD']);
            $('.hideForm-news-edit.' + block + ' .metro').val(result.res['METRO']);
            $('.hideForm-news-edit.' + block + ' .link').val(result.res['LINK']);
            $('.hideForm-news-edit.' + block + ' .adress').val(result.res['ADRESS']);
            $('.hideForm-news-edit.' + block + ' .text').val(result.res['TEXT']);

          } else if (block == 'fillials') {
            $('.hideForm-news-edit.' + block + ' .name').val(result.res['NAME']);
            $('.hideForm-news-edit.' + block + ' .id-main').val(result.res['ID_MAIN']);
            $('.hideForm-news-edit.' + block + ' .phone-fillials').val(result.res['PHONE']);
            $('.hideForm-news-edit.' + block + ' .coord').val(result.res['COORD']);
            $('.hideForm-news-edit.' + block + ' .metro').val(result.res['METRO']);
            $('.hideForm-news-edit.' + block + ' .link').val(result.res['LINK']);
            $('.hideForm-news-edit.' + block + ' .adress').val(result.res['ADRESS']);
            $('.hideForm-news-edit.' + block + ' .text').val(result.res['TEXT']);

          } else if (block == 'units') {
            $('.hideForm-news-edit.' + block + ' .name').val(result.res['NAME']);
            $('.hideForm-news-edit.' + block + ' .id-v').val(result.res['ID_V']);
            $('.hideForm-news-edit.' + block + ' .id-k').val(result.res['ID_K']);
            $('.hideForm-news-edit.' + block + ' .id-s').val(result.res['ID_S']);
            $('.hideForm-news-edit.' + block + ' .phone-units').val(result.res['PHONE']);
            $('.hideForm-news-edit.' + block + ' .coord').val(result.res['COORD']);
            $('.hideForm-news-edit.' + block + ' .metro').val(result.res['METRO']);
            $('.hideForm-news-edit.' + block + ' .e-mail').val(result.res['E_MAIL']);
            $('.hideForm-news-edit.' + block + ' .link').val(result.res['LINK']);
            $('.hideForm-news-edit.' + block + ' .adress').val(result.res['ADRESS']);
            $('.hideForm-news-edit.' + block + ' .text').val(result.res['TEXT']);

          } else if (block == 'obchegitie') {
            $('.hideForm-news-edit.' + block + ' .adress').val(result.res['ADRESS']);
            $('.hideForm-news-edit.' + block + ' .phone-obchegitie').val(result.res['PHONE']);
            $('.hideForm-news-edit.' + block + ' .coord').val(result.res['COORD']);
            $('.hideForm-news-edit.' + block + ' .metro').val(result.res['METRO']);
            $('.hideForm-news-edit.' + block + ' .contact').val(result.res['CONTACT']);
            $('.hideForm-news-edit.' + block + ' .link').val(result.res['LINK']);
            $('.hideForm-news-edit.' + block + ' .text').val(result.res['TEXT']);

          } else if (block == 'ring') {
            $('.hideForm-news-edit.' + block + ' .name').val(result.res['NAME']);
            $('.hideForm-news-edit.' + block + ' .z-1').val(result.res['Z_1']);
            $('.hideForm-news-edit.' + block + ' .z-2').val(result.res['Z_2']);
            $('.hideForm-news-edit.' + block + ' .z-3').val(result.res['Z_3']);
            $('.hideForm-news-edit.' + block + ' .z-4').val(result.res['Z_4']);
            $('.hideForm-news-edit.' + block + ' .z-5').val(result.res['Z_5']);
            $('.hideForm-news-edit.' + block + ' .z-6').val(result.res['Z_6']);
            $('.hideForm-news-edit.' + block + ' .z-7').val(result.res['Z_7']);
            $('.hideForm-news-edit.' + block + ' .z-8').val(result.res['Z_8']);
            $('.hideForm-news-edit.' + block + ' .z-9').val(result.res['Z_9']);
            $('.hideForm-news-edit.' + block + ' .z-10').val(result.res['Z_10']);
            $('.hideForm-news-edit.' + block + ' .z-11').val(result.res['Z_11']);
            $('.hideForm-news-edit.' + block + ' .z-12').val(result.res['Z_12']);

          } else if (block == 'sections') {
            $('.hideForm-news-edit.' + block + ' .name').val(result.res['NAME']);
            $('.hideForm-news-edit.' + block + ' .phone-sections').val(result.res['PHONE']);
            $('.hideForm-news-edit.' + block + ' .contact').val(result.res['CONTACT']);
            $('.hideForm-news-edit.' + block + ' .link').val(result.res['LINK']);
            $('.hideForm-news-edit.' + block + ' .message').val(result.res['COMMENT']);

          } else if (block == 'fakultets') {
            $('.hideForm-news-edit.' + block + ' .name').val(result.res['NAME']);
            $('.hideForm-news-edit.' + block + ' .metro').val(result.res['METRO']);
            $('.hideForm-news-edit.' + block + ' .coord').val(result.res['COORD']);
            $('.hideForm-news-edit.' + block + ' .phone-fakultets').val(result.res['PHONE']);
            $('.hideForm-news-edit.' + block + ' .e-mail').val(result.res['E_MAIL']);
            $('.hideForm-news-edit.' + block + ' .adress').val(result.res['ADRESS']);
            $('.hideForm-news-edit.' + block + ' .link').val(result.res['LINK']);
            $('.hideForm-news-edit.' + block + ' .spec').val(result.res['SPEC']);
            $('.hideForm-news-edit.' + block + ' .text').val(result.res['TEXT']);
            $('.hideForm-news-edit.' + block + ' .message').val(result.res['COMMENT']);

          }

        }
      }
    });

    if (block == 'news') {
      nameForm = 'Редактирование новости';
    } else if (block == 'events') {
      nameForm = 'Редактирование события';
    } else if (block == 'opendoor') {
      nameForm = 'Редактирование дня открытых дверей';
    } else if (block == 'programs') {
      nameForm = 'Редактирование программы обучения';
    } else if (block == 'corpus') {
      nameForm = 'Редактирование корпуса';
    } else if (block == 'fillials') {
      nameForm = 'Редактирование филиала';
    } else if (block == 'units') {
      nameForm = 'Редактирование подразделения';
    } else if (block == 'obchegitie') {
      nameForm = 'Редактирование общежития';
    } else if (block == 'ring') {
      nameForm = 'Редактирование расписания звонков';
    } else if (block == 'sections') {
      nameForm = 'Редактирование секции';
    } else if (block == 'fakultets') {
      nameForm = 'Редактирование факультета или института';
    }

    $('.hideForm-news-edit.' + block + ' .name_form span').text(nameForm);

    $('.hideForm-news-edit.' + block + ' .js-del-news').show();

    $('.foneBg').css({ 'display': 'block' });

    $('.hideForm-news-edit.' + block).fadeIn(250);
    $('.hideForm-news-edit.' + block + ' .js-submit-news-edit').data('id', id_block);
    $('.hideForm-news-edit.' + block + ' .js-del-news').data('id', id_block);
    $('.hideForm-news-edit.' + block + ' .js-submit-news-edit').data('iblock', iblock);
    $('.hideForm-news-edit.' + block + ' .js-del-news').data('iblock', iblock);
    $('.hideForm-news-edit.' + block + ' .js-submit-news-edit').removeClass('add');
    $('.hideForm-news-edit.' + block + ' .js-submit-news-edit').data('iblock', iblock);

    return false;
  });

  $(".js-submit-news-edit").on('click', function (e) {
    e.preventDefault();
    let block = $(this).data('form');
    let id_block = $(this).data('id');
    let iblock = $(this).data('iblock');

    let ajaxUrl = '/ajax/news_edit_save.php';
    if ($(this).hasClass('add')) {
      ajaxUrl = '/ajax/news_add_save.php';
    }

    if (block == 'news') {
      name = $('.hideForm-news-edit.' + block + ' .name').val();
      message = $('.hideForm-news-edit.' + block + ' .message').val();
      if (name == '' || name == 'Поле обязательно для заполнения') {
        $('.hideForm-news-edit.' + block + ' .name').css('color', 'red');
        $('.hideForm-news-edit.' + block + ' .name').val('Поле обязательно для заполнения');
        return false;
      }
      if (message == '' || message == 'Поле обязательно для заполнения') {
        $('.hideForm-news-edit.' + block + ' .message').css('color', 'red');
        $('.hideForm-news-edit.' + block + ' .message').html('Поле обязательно для заполнения');
        return false;
      }
      dataform = { 'name': name, 'message': message };
    } else if (block == 'events') {
      name = $('.hideForm-news-edit.' + block + ' .name').val();
      dateev = $('.hideForm-news-edit.' + block + ' .date').val();
      timeev = $('.hideForm-news-edit.' + block + ' .time').val();
      if (name == '' || name == 'Поле обязательно для заполнения') {
        $('.hideForm-news-edit.' + block + ' .name').css('color', 'red');
        $('.hideForm-news-edit.' + block + ' .name').val('Поле обязательно для заполнения');
        return false;
      }
      let re = /^\d+\.\d+\.\d+$/;
      if (!re.test(dateev)) {
        $('.hideForm-news-edit.' + block + ' .date').css('color', 'red');
        $('.hideForm-news-edit.' + block + ' .date').val('!');
        return false;
      }
      re = /^\d+:\d+$/;
      if (!re.test(timeev)) {
        $('.hideForm-news-edit.' + block + ' .time').css('color', 'red');
        $('.hideForm-news-edit.' + block + ' .time').val('!');
        return false;
      }
      phoneev = $('.hideForm-news-edit.' + block + ' .phone-event').val();
      coord = $('.hideForm-news-edit.' + block + ' .coord').val();
      contact = $('.hideForm-news-edit.' + block + ' .contact').val();
      link = $('.hideForm-news-edit.' + block + ' .link').val();
      adress = $('.hideForm-news-edit.' + block + ' .adress').val();
      textev = $('.hideForm-news-edit.' + block + ' .text').val();
      message = $('.hideForm-news-edit.' + block + ' .message').val();
      dataform = { 'name': name, 'dateev': dateev, 'timeev': timeev, 'phoneev': phoneev, 'coord': coord, 'contact': contact, 'link': link, 'adress': adress, 'textev': textev, 'message': message };
    } else if (block == 'opendoor') {
      name = $('.hideForm-news-edit.' + block + ' .name').val();
      dateev = $('.hideForm-news-edit.' + block + ' .date').val();
      timeev = $('.hideForm-news-edit.' + block + ' .time').val();
      if (name == '' || name == 'Поле обязательно для заполнения') {
        $('.hideForm-news-edit.' + block + ' .name').css('color', 'red');
        $('.hideForm-news-edit.' + block + ' .name').val('Поле обязательно для заполнения');
        return false;
      }
      let re = /^\d+\.\d+\.\d+$/;
      if (!re.test(dateev)) {
        $('.hideForm-news-edit.' + block + ' .date').css('color', 'red');
        $('.hideForm-news-edit.' + block + ' .date').val('!');
        return false;
      }
      re = /^\d+:\d+$/;
      if (!re.test(timeev)) {
        $('.hideForm-news-edit.' + block + ' .time').css('color', 'red');
        $('.hideForm-news-edit.' + block + ' .time').val('!');
        return false;
      }
      phoneev = $('.hideForm-news-edit.' + block + ' .phone-event').val();
      coord = $('.hideForm-news-edit.' + block + ' .coord').val();
      link = $('.hideForm-news-edit.' + block + ' .link').val();
      adress = $('.hideForm-news-edit.' + block + ' .adress').val();
      textev = $('.hideForm-news-edit.' + block + ' .text').val();
      message = $('.hideForm-news-edit.' + block + ' .message').val();
      dataform = { 'name': name, 'dateev': dateev, 'timeev': timeev, 'phoneev': phoneev, 'coord': coord, 'link': link, 'adress': adress, 'textev': textev, 'message': message };
    } else if (block == 'programs') {
      name = $('.hideForm-news-edit.' + block + ' .name').val();
      if (name == '' || name == 'Поле обязательно для заполнения') {
        $('.hideForm-news-edit.' + block + ' .name').css('color', 'red');
        $('.hideForm-news-edit.' + block + ' .name').val('Поле обязательно для заполнения');
        return false;
      }
      base = $('.hideForm-news-edit.' + block + ' .base').val();
      ust = $('.hideForm-news-edit.' + block + ' .ust').val();
      code = $('.hideForm-news-edit.' + block + ' .code').val();
      link = $('.hideForm-news-edit.' + block + ' .link').val();
      textpr = $('.hideForm-news-edit.' + block + ' .text').val();
      message = $('.hideForm-news-edit.' + block + ' .message').val();
      dataform = { 'name': name, 'base': base, 'ust': ust, 'code': code, 'link': link, 'textpr': textpr, 'message': message };

      dataform['och_start'] = $('.hideForm-news-edit.' + block + ' .och-start').val();
      dataform['och_dur'] = $('.hideForm-news-edit.' + block + ' .och-dur').val();
      dataform['och_price'] = $('.hideForm-news-edit.' + block + ' .och-price').val();
      dataform['och_pb'] = $('.hideForm-news-edit.' + block + ' .och-pb').val();
      dataform['och_ekzamen'] = $('.hideForm-news-edit.' + block + ' .och-ekzamen').val();
      dataform['och_dop'] = $('.hideForm-news-edit.' + block + ' .och-dop').val();

      dataform['ochzoch_start'] = $('.hideForm-news-edit.' + block + ' .ochzoch-start').val();
      dataform['ochzoch_dur'] = $('.hideForm-news-edit.' + block + ' .ochzoch-dur').val();
      dataform['ochzoch_price'] = $('.hideForm-news-edit.' + block + ' .ochzoch-price').val();
      dataform['ochzoch_pb'] = $('.hideForm-news-edit.' + block + ' .ochzoch-pb').val();
      dataform['ochzoch_ekzamen'] = $('.hideForm-news-edit.' + block + ' .ochzoch-ekzamen').val();
      dataform['ochzoch_dop'] = $('.hideForm-news-edit.' + block + ' .ochzoch-dop').val();

      dataform['zoch_start'] = $('.hideForm-news-edit.' + block + ' .zoch-start').val();
      dataform['zoch_dur'] = $('.hideForm-news-edit.' + block + ' .zoch-dur').val();
      dataform['zoch_price'] = $('.hideForm-news-edit.' + block + ' .zoch-price').val();
      dataform['zoch_pb'] = $('.hideForm-news-edit.' + block + ' .zoch-pb').val();
      dataform['zoch_ekzamen'] = $('.hideForm-news-edit.' + block + ' .zoch-ekzamen').val();
      dataform['zoch_dop'] = $('.hideForm-news-edit.' + block + ' .zoch-dop').val();

      dataform['gvd_start'] = $('.hideForm-news-edit.' + block + ' .gvd-start').val();
      dataform['gvd_dur'] = $('.hideForm-news-edit.' + block + ' .gvd-dur').val();
      dataform['gvd_price'] = $('.hideForm-news-edit.' + block + ' .gvd-price').val();
      dataform['gvd_pb'] = $('.hideForm-news-edit.' + block + ' .gvd-pb').val();
      dataform['gvd_ekzamen'] = $('.hideForm-news-edit.' + block + ' .gvd-ekzamen').val();
      dataform['gvd_dop'] = $('.hideForm-news-edit.' + block + ' .gvd-dop').val();

      dataform['dis_start'] = $('.hideForm-news-edit.' + block + ' .dis-start').val();
      dataform['dis_dur'] = $('.hideForm-news-edit.' + block + ' .dis-dur').val();
      dataform['dis_price'] = $('.hideForm-news-edit.' + block + ' .dis-price').val();
      dataform['dis_pb'] = $('.hideForm-news-edit.' + block + ' .dis-pb').val();
      dataform['dis_ekzamen'] = $('.hideForm-news-edit.' + block + ' .dis-ekzamen').val();
      dataform['dis_dop'] = $('.hideForm-news-edit.' + block + ' .dis-dop').val();
    } else if (block == 'corpus') {
      name = $('.hideForm-news-edit.' + block + ' .name').val();
      adress = $('.hideForm-news-edit.' + block + ' .adress').val();
      if (name == '' || name == 'Поле обязательно для заполнения') {
        $('.hideForm-news-edit.' + block + ' .name').css('color', 'red');
        $('.hideForm-news-edit.' + block + ' .name').val('Поле обязательно для заполнения');
        return false;
      }
      if (adress == '' || adress == 'Поле обязательно для заполнения') {
        $('.hideForm-news-edit.' + block + ' .adress').css('color', 'red');
        $('.hideForm-news-edit.' + block + ' .adress').val('Поле обязательно для заполнения');
        return false;
      }
      phonecor = $('.hideForm-news-edit.' + block + ' .phone-corpus').val();
      coord = $('.hideForm-news-edit.' + block + ' .coord').val();
      metro = $('.hideForm-news-edit.' + block + ' .metro').val();
      link = $('.hideForm-news-edit.' + block + ' .link').val();
      textcor = $('.hideForm-news-edit.' + block + ' .text').val();
      dataform = { 'name': name, 'adress': adress, 'phonecor': phonecor, 'coord': coord, 'metro': metro, 'link': link, 'textcor': textcor };
    } else if (block == 'fillials') {
      name = $('.hideForm-news-edit.' + block + ' .name').val();
      if (name == '' || name == 'Поле обязательно для заполнения') {
        $('.hideForm-news-edit.' + block + ' .name').css('color', 'red');
        $('.hideForm-news-edit.' + block + ' .name').val('Поле обязательно для заполнения');
        return false;
      }
      id_main = $('.hideForm-news-edit.' + block + ' .id-main').val();
      phonefil = $('.hideForm-news-edit.' + block + ' .phone-fillials').val();
      coord = $('.hideForm-news-edit.' + block + ' .coord').val();
      metro = $('.hideForm-news-edit.' + block + ' .metro').val();
      link = $('.hideForm-news-edit.' + block + ' .link').val();
      adress = $('.hideForm-news-edit.' + block + ' .adress').val();
      textfil = $('.hideForm-news-edit.' + block + ' .text').val();
      dataform = { 'name': name, 'id_main': id_main, 'phonefil': phonefil, 'coord': coord, 'metro': metro, 'link': link, 'adress': adress, 'textfil': textfil };
    } else if (block == 'units') {
      name = $('.hideForm-news-edit.' + block + ' .name').val();
      if (name == '' || name == 'Поле обязательно для заполнения') {
        $('.hideForm-news-edit.' + block + ' .name').css('color', 'red');
        $('.hideForm-news-edit.' + block + ' .name').val('Поле обязательно для заполнения');
        return false;
      }
      id_v = $('.hideForm-news-edit.' + block + ' .id-v').val();
      id_k = $('.hideForm-news-edit.' + block + ' .id-k').val();
      id_s = $('.hideForm-news-edit.' + block + ' .id-s').val();
      phoneun = $('.hideForm-news-edit.' + block + ' .phone-units').val();
      coord = $('.hideForm-news-edit.' + block + ' .coord').val();
      metro = $('.hideForm-news-edit.' + block + ' .metro').val();
      e_mail = $('.hideForm-news-edit.' + block + ' .e-mail').val();
      link = $('.hideForm-news-edit.' + block + ' .link').val();
      adress = $('.hideForm-news-edit.' + block + ' .adress').val();
      textun = $('.hideForm-news-edit.' + block + ' .text').val();
      dataform = { 'name': name, 'id_v': id_v, 'id_k': id_k, 'id_s': id_s, 'phoneun': phoneun, 'coord': coord, 'metro': metro, 'e_mail': e_mail, 'link': link, 'adress': adress, 'textun': textun };
    } else if (block == 'obchegitie') {
      adress = $('.hideForm-news-edit.' + block + ' .adress').val();
      if (adress == '' || adress == 'Поле обязательно для заполнения') {
        $('.hideForm-news-edit.' + block + ' .adress').css('color', 'red');
        $('.hideForm-news-edit.' + block + ' .adress').val('Поле обязательно для заполнения');
        return false;
      }
      phoneobg = $('.hideForm-news-edit.' + block + ' .phone-obchegitie').val();
      coord = $('.hideForm-news-edit.' + block + ' .coord').val();
      metro = $('.hideForm-news-edit.' + block + ' .metro').val();
      contact = $('.hideForm-news-edit.' + block + ' .contact').val();
      link = $('.hideForm-news-edit.' + block + ' .link').val();
      textobg = $('.hideForm-news-edit.' + block + ' .text').val();
      dataform = { 'adress': adress, 'phoneobg': phoneobg, 'coord': coord, 'metro': metro, 'contact': contact, 'link': link, 'textobg': textobg };
    } else if (block == 'ring') {
      name = $('.hideForm-news-edit.' + block + ' .name').val();
      if (name == '' || name == 'Поле обязательно для заполнения') {
        $('.hideForm-news-edit.' + block + ' .name').css('color', 'red');
        $('.hideForm-news-edit.' + block + ' .name').val('Поле обязательно для заполнения');
        return false;
      }
      z_1 = $('.hideForm-news-edit.' + block + ' .z-1').val();
      z_2 = $('.hideForm-news-edit.' + block + ' .z-2').val();
      z_3 = $('.hideForm-news-edit.' + block + ' .z-3').val();
      z_4 = $('.hideForm-news-edit.' + block + ' .z-4').val();
      z_5 = $('.hideForm-news-edit.' + block + ' .z-5').val();
      z_6 = $('.hideForm-news-edit.' + block + ' .z-6').val();
      z_7 = $('.hideForm-news-edit.' + block + ' .z-7').val();
      z_8 = $('.hideForm-news-edit.' + block + ' .z-8').val();
      z_9 = $('.hideForm-news-edit.' + block + ' .z-9').val();
      z_10 = $('.hideForm-news-edit.' + block + ' .z-10').val();
      z_11 = $('.hideForm-news-edit.' + block + ' .z-11').val();
      z_12 = $('.hideForm-news-edit.' + block + ' .z-12').val();
      dataform = { 'name': name, 'z_1': z_1, 'z_2': z_2, 'z_3': z_3, 'z_4': z_4, 'z_5': z_5, 'z_6': z_6, 'z_7': z_7, 'z_8': z_8, 'z_9': z_9, 'z_10': z_10, 'z_11': z_11, 'z_12': z_12 };
    } else if (block == 'sections') {
      name = $('.hideForm-news-edit.' + block + ' .name').val();
      if (name == '' || name == 'Поле обязательно для заполнения') {
        $('.hideForm-news-edit.' + block + ' .name').css('color', 'red');
        $('.hideForm-news-edit.' + block + ' .name').val('Поле обязательно для заполнения');
        return false;
      }
      phonesec = $('.hideForm-news-edit.' + block + ' .phone-sections').val();
      contact = $('.hideForm-news-edit.' + block + ' .contact').val();
      link = $('.hideForm-news-edit.' + block + ' .link').val();
      message = $('.hideForm-news-edit.' + block + ' .message').val();
      dataform = { 'name': name, 'phonesec': phonesec, 'contact': contact, 'link': link, 'message': message };
    } else if (block == 'fakultets') {
      name = $('.hideForm-news-edit.' + block + ' .name').val();
      if (name == '' || name == 'Поле обязательно для заполнения') {
        $('.hideForm-news-edit.' + block + ' .name').css('color', 'red');
        $('.hideForm-news-edit.' + block + ' .name').val('Поле обязательно для заполнения');
        return false;
      }
      metro = $('.hideForm-news-edit.' + block + ' .metro').val();
      coord = $('.hideForm-news-edit.' + block + ' .coord').val().trim();
      phonefak = $('.hideForm-news-edit.' + block + ' .phone-fakultets').val();
      email = $('.hideForm-news-edit.' + block + ' .e-mail').val();
      adress = $('.hideForm-news-edit.' + block + ' .adress').val();
      link = $('.hideForm-news-edit.' + block + ' .link').val();
      spec = $('.hideForm-news-edit.' + block + ' .spec').val();
      textfak = $('.hideForm-news-edit.' + block + ' .text').val();
      message = $('.hideForm-news-edit.' + block + ' .message').val();
      dataform = { 'name': name, 'metro': metro, 'coord': coord, 'phonefak': phonefak, 'email': email, 'adress': adress, 'link': link, 'spec': spec, 'textfak': textfak, 'message': message };
    }

    dataform['id_vuz'] = id_vuz;
    dataform['type'] = block;

    if (!$(this).hasClass('add')) {
      dataform['id_block'] = id_block;
    } else {
      imgArray = $('.hideForm-news-edit.' + block + ' .box-img .brd img.new-photo');
      if (imgArray.length > 0) {
        let imgSrc = [];
        $.each(imgArray, function () {
          imgSrc.push($(this).attr('src'));
        });
        dataform['images'] = imgSrc;
      }
    }

    dataform['iblock'] = iblock;

    $.ajax({
      type: 'POST',
      url: ajaxUrl,
      data: dataform,
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          document.location.reload(true);
        }
      }
    });

    return false;
  });

  $('.hideForm-news-edit.news .name, .hideForm-news-edit.news .message, .hideForm-news-edit.events .name, .hideForm-news-edit.events .date, .hideForm-news-edit.events .time, .hideForm-news-edit.opendoor .name, .hideForm-news-edit.opendoor .date, .hideForm-news-edit.opendoor .time, .hideForm-news-edit.programs .name, .hideForm-news-edit.corpus .name, .hideForm-news-edit.corpus .adress, .hideForm-news-edit.fillials .name, .hideForm-news-edit.units .name, .hideForm-news-edit.obchegitie .adress, .hideForm-news-edit.ring .name, .hideForm-news-edit.sections .name, .hideForm-news-edit.fakultets .name').on('focus', function () {
    $(this).css('color', '#a7a7a7');
    if ($(this).val() == 'Поле обязательно для заполнения' || $(this).val() == '!')
      $(this).val('');
    return false;
  });

  $(".js-del-news").on('click', function (e) {
    e.preventDefault();
    let block = $(this).data('form');
    let id_block = $(this).data('id');
    let iblock = $(this).data('iblock');

    dataform = {};

    dataform['id_vuz'] = id_vuz;
    dataform['type'] = block;
    dataform['id_block'] = id_block;
    dataform['iblock'] = iblock;

    $.ajax({
      type: 'POST',
      url: '/ajax/news_del.php',
      data: dataform,
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {

          let newsItem = $('.news-item').length;
          if (newsItem > 1) {
            document.location.reload(true);
          } else {
            let backUrl = $('#box-line').data('url');
            document.location = backUrl;
          }

        }
      }
    });

    return false;
  });

  $(".js-new-add").on('click', function (e) {
    e.preventDefault();
    let block = $(this).data('type');
    let iblock = $(this).data('iblock');

    if (block == 'news') {
      nameForm = 'Добавление новости';
    } else if (block == 'events') {
      nameForm = 'Добавление события';
    } else if (block == 'opendoor') {
      nameForm = 'Добавление дня открытых дверей';
    } else if (block == 'programs') {
      nameForm = 'Добавление программы обучения';
    } else if (block == 'corpus') {
      nameForm = 'Добавление корпуса';
    } else if (block == 'fillials') {
      nameForm = 'Добавление филиала';
    } else if (block == 'units') {
      nameForm = 'Добавление подразделения';
    } else if (block == 'obchegitie') {
      nameForm = 'Добавление общежития';
    } else if (block == 'ring') {
      nameForm = 'Добавление расписания звонков';
    } else if (block == 'sections') {
      nameForm = 'Добавление секции';
    } else if (block == 'fakultets') {
      nameForm = 'Добавление факультета или института';
    }

    var top_form = $(window).scrollTop();
    var height_form = $('.hideForm-news-edit.' + block + ' .form-open-block form').height();
    var marg_top = $(window).height() / 2;

    $('.hideForm-news-edit.' + block + ' .form-open-block').css({
      'height': $(window).height(),
      'position': 'absolute',
      'top': top_form,
    });
    $('.hideForm-news-edit.' + block).css({ 'height': $(document).height(), });

    $('.hideForm-news-edit.news .name, .hideForm-news-edit.news .message').css('color', '#a7a7a7');

    $('.hideForm-news-edit.' + block + ' .name_form span').text(nameForm);
    $('.hideForm-news-edit.' + block + ' .js-submit-news-edit').data('id', 0);
    $('.hideForm-news-edit.' + block + ' .js-del-news').data('id', 0);
    $('.hideForm-news-edit.' + block + ' .js-submit-news-edit').data('iblock', iblock);
    $('.hideForm-news-edit.' + block + ' .js-del-news').hide();
    $('.hideForm-news-edit.' + block + ' .box-img .brd').remove();
    $('.hideForm-news-edit.' + block + ' input').val('');
    $('.hideForm-news-edit.' + block + ' textarea').val('');
    $('.hideForm-news-edit.' + block + ' .js-submit-news-edit').addClass('add');

    $('.foneBg').css({ 'display': 'block' });

    $('.hideForm-news-edit.' + block).fadeIn(250);
    return false;
  });

  $('input[type=file]#news_img').on('change', function () {
    event.stopPropagation();
    event.preventDefault();

    let id_block = $('.hideForm-news-edit.news .js-submit-news-edit').data('id');
    let iblock = $('.hideForm-news-edit.news .js-submit-news-edit').data('iblock');

    $('#file').blur();
    if (!this.files.length) return;

    let data = new FormData();
    data.append('my', this.files[0]);
    data.append('upload', 1);
    data.append('id_vuz', id_vuz);
    data.append('id_block', id_block);
    data.append('iblock', iblock);

    $.ajax({
      url: '/ajax/news_add_img.php',
      type: 'POST', // важно!
      data: data,
      cache: false,
      dataType: 'json',
      processData: false,
      contentType: false,
      success: function (respond, status, jqXHR) {
        html = '';
        html += '<div class="col-3 brd" style="position: relative;" id="delete-img">';
        html += '<img class="new-photo" src="' + respond['PHOTO']['SRC'] + '" style="width: 100%;">';
        html += '</div>';
        $('.hideForm-news-edit.news .box-img').append(html);
        $('.hideForm-news-edit.news .box-img .label-img').text('удалить фото');
        $('.hideForm-news-edit.news .box-img .label-img').addClass('del-news-img');
        $('.hideForm-news-edit.news .box-img .label-img').attr('for', '');
        $('.hideForm-news-edit.news .box-img .label-img').data('id', respond['PHOTO']['ID']);
      },
      error: function (jqXHR, status, errorThrown) {
        console.log('ОШИБКА AJAX запроса: ' + status, jqXHR);
      }
    });
  });

  $("#form-news-news").on('click', '.del-news-img', function (e) {
    e.preventDefault();
    $this = $(this);

    let iblock = $('.hideForm-news-edit.news .js-submit-news-edit').data('iblock');
    let id_block = $('.hideForm-news-edit.news .js-submit-news-edit').data('id');
    let id_img = $this.data('id');
    let parent = $this.parent();

    dataform = {};

    dataform['id_vuz'] = id_vuz;
    dataform['id_img'] = id_img;
    dataform['id_block'] = id_block;
    dataform['iblock'] = iblock;

    $.ajax({
      type: 'POST',
      url: '/ajax/news_del_img.php',
      data: dataform,
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          //parent.remove();
          $('#delete-img').remove();
          $('.hideForm-news-edit.news .box-img .label-img').text('добавить фото');
          $('.hideForm-news-edit.news .box-img .label-img').removeClass('del-news-img');
          $('.hideForm-news-edit.news .box-img .label-img').attr('for', 'news_img');
        }
      }
    });

    return false;
  });

  var obr = 0;
  var nameObrArr = ['', 'Название ВУЗа', 'Название колледжа', 'Название школы', 'Название курса'];
  var nameObr = '';
  var country = 0;
  var city = '';
  var name = '';
  var uz = 0;

  $(".js-uz-add").on('click', function (e) {
    e.preventDefault();

    let teacher = 0;
    $('.hideForm-news-edit.uz .start-text').text('Дата начала обучения');
    $('.hideForm-news-edit.uz .end-text').text('Дата выпуска');
    if ($(this).hasClass('teacher')) {
      teacher = 1;
      $('.hideForm-news-edit.uz .start-text').text('Дата начала преподавания');
      $('.hideForm-news-edit.uz .end-text').text('Дата завершения преподавания');
    }

    $('.hideForm-news-edit.uz .name_form span').text('Добавление учебного заведения');

    var top_form = $(window).scrollTop();
    var height_form = $('.hideForm-news-edit.uz .form-open-block form').height();
    var marg_top = $(window).height() / 2;

    $('.hideForm-news-edit.uz .form-open-block').css({
      'height': $(window).height(),
      'position': 'absolute',
      'top': top_form,
    });
    $('.hideForm-news-edit.uz').css({ 'height': $(document).height(), });

    $('.hideForm-news-edit.uz select').find('option[value=0]').prop('selected', true);
    $('.hideForm-news-edit.uz select').find('option[value=""]').prop('selected', true);

    let uz = 0;

    $('.hideForm-news-edit.uz .city').val('');
    $('.hideForm-news-edit.uz .city_id').val(0);
    $('.hideForm-news-edit.uz .city').css('color', 'black');

    $('.hideForm-news-edit.uz .name').val('');
    $('.hideForm-news-edit.uz .name').data('id', 0);
    $('.hideForm-news-edit.uz .name').css('color', 'black');

    $('.hideForm-news-edit.uz .name').data('teacher', teacher);

    $('.hideForm-news-edit.uz .auto-complit-city').hide();
    $('.hideForm-news-edit.uz .auto-complit-city').empty();
    $('.hideForm-news-edit.uz .auto-complit-name').hide();
    $('.hideForm-news-edit.uz .auto-complit-name').empty();

    $('.hideForm-news-edit.uz .vuz-block .js-news-edit-form').attr('disabled', true);
    $('.hideForm-news-edit.uz .vuz-block .js-news-edit-form').find('option[value=0]').prop('selected', true);
    $('.hideForm-news-edit.uz .vuz-block .js-news-edit-form').find('option[value=""]').prop('selected', true);

    $('.hideForm-news-edit.uz .coll-block .js-news-edit-form').attr('disabled', true);
    $('.hideForm-news-edit.uz .coll-block .js-news-edit-form').find('option[value=0]').prop('selected', true);
    $('.hideForm-news-edit.uz .coll-block .js-news-edit-form').find('option[value=""]').prop('selected', true);

    $('.hideForm-news-edit.uz .lang-block .js-news-edit-form').attr('disabled', true);
    $('.hideForm-news-edit.uz .lang-block .js-news-edit-form').find('option[value=0]').prop('selected', true);
    $('.hideForm-news-edit.uz .lang-block .js-news-edit-form').find('option[value=""]').prop('selected', true);

    $('.hideForm-news-edit.uz .vuz-block').hide();
    $('.hideForm-news-edit.uz .coll-block').hide();
    $('.hideForm-news-edit.uz .shool-block').hide();
    $('.hideForm-news-edit.uz .lang-block').hide();

    $('.hideForm-news-edit.uz .js-del-uz').hide();
    $('.hideForm-news-edit.uz .js-del-uz').data('id', 0);

    $('.foneBg').css({ 'display': 'block' });

    $('.hideForm-news-edit.uz').fadeIn(250);
    return false;
  });

  $(".hideForm-news-edit.uz .obr").on('change', function (e) {
    e.preventDefault();

    let obr = $(this).val();

    if (obr > 0) {

      let nameObr = nameObrArr[obr];
      $('#naz').text(nameObr);

      $(this).find('option[value=0]').attr('disabled', true);

      $('.hideForm-news-edit.uz .country').find('option[value=0]').attr('disabled', false);
      $('.hideForm-news-edit.uz .country').find('option[value=0]').prop('selected', true);
      $('.hideForm-news-edit.uz .country').attr('disabled', false);
      $('.hideForm-news-edit.uz .country').css('color', 'black');

      let html = '';
      html += '<option value="">Выберите страну</option>';
      $('.hideForm-news-edit.uz .region').empty();
      $('.hideForm-news-edit.uz .region').append(html);
      $('.hideForm-news-edit.uz .region').attr('disabled', true);

      let city = 0;
      $('.hideForm-news-edit.uz .city').val('Выберите страну');
      $('.hideForm-news-edit.uz .city').attr('disabled', true);
      $('.hideForm-news-edit.uz .city_id').val(0);

      let uz = 0;
      $('.hideForm-news-edit.uz .name').val('Выберите страну');
      $('.hideForm-news-edit.uz .name').data('id', 0);
      $('.hideForm-news-edit.uz .name').attr('disabled', true);

      $('.hideForm-news-edit.uz .auto-complit-city').hide();
      $('.hideForm-news-edit.uz .auto-complit-city').empty();
      $('.hideForm-news-edit.uz .auto-complit-name').hide();
      $('.hideForm-news-edit.uz .auto-complit-name').empty();

      $('.hideForm-news-edit.uz .js-block').hide();
      $('.hideForm-news-edit.uz .vuz-block .js-news-edit-form').attr('disabled', true);
      $('.hideForm-news-edit.uz .vuz-block .js-news-edit-form').find('option[value=0]').prop('selected', true);
      $('.hideForm-news-edit.uz .vuz-block .js-news-edit-form').find('option[value=""]').prop('selected', true);

      $('.hideForm-news-edit.uz .coll-block .js-news-edit-form').attr('disabled', true);
      $('.hideForm-news-edit.uz .coll-block .js-news-edit-form').find('option[value=0]').prop('selected', true);
      $('.hideForm-news-edit.uz .coll-block .js-news-edit-form').find('option[value=""]').prop('selected', true);

      $('.hideForm-news-edit.uz .shool-block .js-news-edit-form').attr('disabled', true);
      $('.hideForm-news-edit.uz .shool-block .js-news-edit-form').find('option[value=0]').prop('selected', true);
      $('.hideForm-news-edit.uz .shool-block .js-news-edit-form').find('option[value=""]').prop('selected', true);

      $('.hideForm-news-edit.uz .lang-block .js-news-edit-form').attr('disabled', true);
      $('.hideForm-news-edit.uz .lang-block .js-news-edit-form').find('option[value=0]').prop('selected', true);
      $('.hideForm-news-edit.uz .lang-block .js-news-edit-form').find('option[value=""]').prop('selected', true);

      if (obr == 1)
        $('.hideForm-news-edit.uz .vuz-block').slideDown();
      if (obr == 2)
        $('.hideForm-news-edit.uz .coll-block').slideDown();
      if (obr == 3)
        $('.hideForm-news-edit.uz .shool-block').slideDown();
      if (obr == 4)
        $('.hideForm-news-edit.uz .lang-block').slideDown();
    }
    return false;
  });

  $(".hideForm-news-edit.uz .country").on('change', function (e) {
    e.preventDefault();

    let obr = $(".hideForm-news-edit.uz .obr").val();
    let country = $(this).val();

    if (country > 0) {
      $(this).find('option[value=0]').attr('disabled', true);

      let dataform = {};

      dataform['obr'] = obr;
      dataform['country'] = country;

      $.ajax({
        type: 'POST',
        url: '/ajax/get_region_uz.php',
        data: dataform,
        dataType: 'json',
        success: function (result) {
          if (result.status == 'success') {
            let html = '';
            if (result.res.length > 0) {
              html += '<option value="">Неустановлено</option>';
              $.each(result.res, function () {
                html += '<option value="' + this + '">' + this + '</option>';
              });
              $('.hideForm-news-edit.uz .region').empty();
              $('.hideForm-news-edit.uz .region').append(html);
              $('.hideForm-news-edit.uz .region').attr('disabled', false);
              $('.hideForm-news-edit.uz .region').css('color', 'black');
              $('.hideForm-news-edit.uz .city').val('Выберите регион');
              $('.hideForm-news-edit.uz .city').attr('disabled', true);
              $('.hideForm-news-edit.uz .name').val('Выберите регион');
              $('.hideForm-news-edit.uz .name').attr('disabled', true);
              $('.hideForm-news-edit.uz .auto-complit-city').hide();
              $('.hideForm-news-edit.uz .auto-complit-city').empty();
              $('.hideForm-news-edit.uz .auto-complit-name').hide();
              $('.hideForm-news-edit.uz .auto-complit-name').empty();
            } else {
              $('.hideForm-news-edit.uz .region').attr('disabled', true);
            }
          }
        }
      });
    }
    return false;
  });

  $(".hideForm-news-edit.uz .region").on('change', function (e) {
    e.preventDefault();

    let obr = $(".hideForm-news-edit.uz .obr").val();
    let country = $(".hideForm-news-edit.uz .country").val();
    let region = $(this).val();

    if (region.length) {
      $(this).find('option[value=0]').attr('disabled', true);

      $('.hideForm-news-edit.uz .city').val('');
      $('.hideForm-news-edit.uz .city').attr('disabled', false);
      $('.hideForm-news-edit.uz .city').css('color', 'black');
      $('.hideForm-news-edit.uz .name').val('');
      $('.hideForm-news-edit.uz .name').attr('disabled', false);
      $('.hideForm-news-edit.uz .name').css('color', 'black');
      $('.hideForm-news-edit.uz .auto-complit-city').hide();
      $('.hideForm-news-edit.uz .auto-complit-city').empty();
      $('.hideForm-news-edit.uz .auto-complit-name').hide();
      $('.hideForm-news-edit.uz .auto-complit-name').empty();
    }
    return false;
  });

  $(".hideForm-news-edit.uz .city").on('keyup', function (e) {
    e.preventDefault();

    let country = $(".hideForm-news-edit.uz .country").val();
    let region = $(".hideForm-news-edit.uz .region").val();
    let city = $(this).val().trim();

    if (city.length > 1) {

      let dataform = {};

      dataform['country'] = country;
      dataform['region'] = region;
      dataform['city'] = city;

      $.ajax({
        type: 'POST',
        url: '/ajax/get_city_uz.php',
        data: dataform,
        dataType: 'json',
        success: function (result) {
          if (result.status == 'success') {
            if (result.res.length > 0) {
              let re;
              let podstr = '';
              let html = '';
              $.each(result.res, function () {
                re = new RegExp(city, 'i');
                podstr = this.name.replace(re, '<b>' + city.replace(/(^|\s)\S/g, function (a) { return a.toUpperCase() }) + '</b>');
                let original = this.name;
                if (this.region) {
                  html += `
										<div class="item" data-id="${this.id}">
											<div data-original="${original}" data-reg="${this.region}">${podstr} <span style="font-size: 12px; margin-left: 10px; color: #a7a7a7;">${this.region}</span></div>
										</div>`;
                } else {
                  html += `
										<div class="item" data-id="${this.id}">
											<div data-original="${original}" data-region="">${podstr}</div>
										</div>`;
                }
              });
              $('.hideForm-news-edit.uz .auto-complit-city').empty();
              $('.hideForm-news-edit.uz .auto-complit-city').append(html);
              $('.hideForm-news-edit.uz .auto-complit-city').show();
            } else {
              $('.hideForm-news-edit.uz .auto-complit-city').hide();
              $('.hideForm-news-edit.uz .auto-complit-city').empty();
            }
          }
        }
      });
    } else {
      $('.hideForm-news-edit.uz .auto-complit-city').hide();
      $('.hideForm-news-edit.uz .auto-complit-city').empty();
    }
    return false;
  });

  $("#form-news-uz").on('click', '.auto-complit-city .item', function (e) {
    e.preventDefault();

    let cityID = $(this).data('id');
    let cityName = $(this).find('div').data('original');
    let regName = $(this).find('div span');
    if (cityID > 0) {

      $('.hideForm-news-edit.uz .city').val(cityName);
      $('.hideForm-news-edit.uz .city_id').val(cityID);

      if (regName.length)
        $('.hideForm-news-edit.uz .region').find('option[value="' + regName.text() + '"]').prop('selected', true);

      $('.hideForm-news-edit.uz .auto-complit-city').hide();
      $('.hideForm-news-edit.uz .auto-complit-city').empty();

      $('.hideForm-news-edit.uz .name').val('');

      $('.hideForm-news-edit.uz .js-news-edit-form').attr('disabled', false);
    }
    return false;
  });

  $(".hideForm-news-edit.uz .name").on('keyup', function (e) {
    e.preventDefault();

    let name = $(this).val().trim();

    if (name.length > 1) {

      let dataform = {};

      let obr = $(".hideForm-news-edit.uz .obr").val();
      let country = $(".hideForm-news-edit.uz .country").val();
      let region = $(".hideForm-news-edit.uz .region").val();
      let cityId = $('.hideForm-news-edit.uz .city_id').val();

      dataform['obr'] = obr;
      dataform['country'] = country;
      dataform['region'] = region;
      dataform['city'] = cityId;
      dataform['name'] = name;

      $.ajax({
        type: 'POST',
        url: '/ajax/get_name.php',
        data: dataform,
        dataType: 'json',
        success: function (result) {
          if (result.status == 'success') {
            if (result.res.length > 0) {
              let re;
              let podstr = '';
              let html = '';
              $.each(result.res, function () {
                re = new RegExp(name, 'i');
                podstr = this.name.replace(re, '<b>' + name + '</b>');
                html += `
									<div class="item" data-id="${this.id}">
										<div>${podstr}</div>
									</div>`;
              });
              $('.hideForm-news-edit.uz .auto-complit-name').empty();
              $('.hideForm-news-edit.uz .auto-complit-name').append(html);
              $('.hideForm-news-edit.uz .auto-complit-name').show();
            } else {
              $('.hideForm-news-edit.uz .auto-complit-name').hide();
              $('.hideForm-news-edit.uz .auto-complit-name').empty();
            }
          }
        }
      });
    } else {
      $('.hideForm-news-edit.uz .auto-complit-name').hide();
      $('.hideForm-news-edit.uz .auto-complit-name').empty();
    }
    return false;
  });

  $("#form-news-uz").on('click', '.auto-complit-name .item', function (e) {
    e.preventDefault();

    let uz = $(this).data('id');
    let nameUz = $(this).find('div').text();
    let obr = $(".hideForm-news-edit.uz .obr").val();

    if (uz > 0) {

      $('.hideForm-news-edit.uz .name').val(nameUz);
      $('.hideForm-news-edit.uz .name').data('id', uz);
      $('.hideForm-news-edit.uz .auto-complit-name').hide();
      $('.hideForm-news-edit.uz .auto-complit-name').empty();

      $('.hideForm-news-edit.uz .js-news-edit-form').attr('disabled', false);

      let dataform = {};

      dataform['obr'] = obr;
      dataform['uz'] = uz;
      dataform['type'] = 'fack';

      $.ajax({
        type: 'POST',
        url: '/ajax/get_data.php',
        data: dataform,
        dataType: 'json',
        success: function (result) {
          if (result.status == 'success') {
            let html = '';
            if (result.res.length > 0) {
              $('.hideForm-news-edit.uz .vuz-block .fack').parent().parent().slideDown();
              $.each(result.res, function (i, v) {
                if (v) {
                  html += '<option value="' + i + '">' + v + '</option>';
                }
              });
              $('.hideForm-news-edit.uz .vuz-block .fack').append(html);
            } else {
              $('.hideForm-news-edit.uz .vuz-block .fack').empty();
              $('.hideForm-news-edit.uz .vuz-block .fack').parent().parent().slideUp();
            }
          }
        }
      });
    }
    return false;
  });

  $("#form-news-uz").on('click', '.js-submit-uz-edit', function (e) {
    e.preventDefault();

    let uz = $('.hideForm-news-edit.uz .name').data('id');

    if (!uz) {
      $('.hideForm-news-edit.uz .name').css('color', 'red');
      $('.hideForm-news-edit.uz .name').val('Необходимо выбрать учебное заведение');
      return false;
    }

    let teacher = $('.hideForm-news-edit.uz .name').data('teacher');

    let obr = $(".hideForm-news-edit.uz .obr").val();
    let countryId = $(".hideForm-news-edit.uz .country").val();
    let countryName = $(".hideForm-news-edit.uz .country option:selected").text();
    let region = $(".hideForm-news-edit.uz .region").val();
    let cityId = $('.hideForm-news-edit.uz .city_id').val();
    let cityName = $('.hideForm-news-edit.uz .city').val();

    let dataform = {};

    dataform['obr'] = obr;
    dataform['country_id'] = countryId;
    dataform['country_name'] = countryName;
    dataform['region'] = region;
    dataform['city_id'] = cityId;
    dataform['city_name'] = cityName;
    dataform['uz'] = uz;
    dataform['type'] = 'add';
    dataform['teacher'] = teacher;

    if ($(this).hasClass('update')) {
      dataform['type'] = 'update';
      dataform['id'] = $(this).data('id');
    }

    if (obr == 1) {
      dataform['fack'] = $('.hideForm-news-edit.uz .vuz-block .fack').val();
      dataform['forma'] = $('.hideForm-news-edit.uz .vuz-block .forma').val();
      dataform['status'] = $('.hideForm-news-edit.uz .vuz-block .status').val();
      dataform['group'] = $('.hideForm-news-edit.uz .vuz-block .group').val();
      dataform['start'] = $('.hideForm-news-edit.uz .vuz-block .start').val();
      dataform['end'] = $('.hideForm-news-edit.uz .vuz-block .end').val();
    } else if (obr == 2) {
      dataform['spec'] = $('.hideForm-news-edit.uz .coll-block .spec').val();
      dataform['group'] = $('.hideForm-news-edit.uz .coll-block .group').val();
      dataform['start'] = $('.hideForm-news-edit.uz .coll-block .start').val();
      dataform['end'] = $('.hideForm-news-edit.uz .coll-block .end').val();
    } else if (obr == 3) {
      dataform['group'] = $('.hideForm-news-edit.uz .shool-block .klass').val();
      dataform['start'] = $('.hideForm-news-edit.uz .shool-block .start').val();
      dataform['end'] = $('.hideForm-news-edit.uz .shool-block .end').val();
    } else if (obr == 4) {
      dataform['start'] = $('.hideForm-news-edit.uz .lang-block .start').val();
      dataform['end'] = $('.hideForm-news-edit.uz .lang-block .end').val();
    }

    $.ajax({
      type: 'POST',
      url: '/ajax/add_uz.php',
      data: dataform,
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          document.location.reload(true);
        }
      }
    });

    return false;
  });

  $('.hideForm-news-edit.uz .city').on('focus', function (e) {
    uz = 0;
    $('.hideForm-news-edit.uz .city').val('');
    $('.hideForm-news-edit.uz .city_id').val(0);
    $('.hideForm-news-edit.uz .city').css('color', 'black');
    return false;
  });

  $('.hideForm-news-edit.uz .name').on('focus', function (e) {
    uz = 0;
    $('.hideForm-news-edit.uz .name').val('');
    $('.hideForm-news-edit.uz .name').data('id', 0);
    $('.hideForm-news-edit.uz .name').css('color', 'black');
    return false;
  });

  $(".js-uz-edit").on('click', function (e) {
    e.preventDefault();

    var top_form = $(window).scrollTop();
    var height_form = $('.hideForm-news-edit.uz .form-open-block form').height();
    var marg_top = $(window).height() / 2;

    $('.hideForm-news-edit.uz .form-open-block').css({
      'height': $(window).height(),
      'position': 'absolute',
      'top': top_form,
    });
    $('.hideForm-news-edit.uz').css({ 'height': $(document).height(), });

    let teacher = 0;
    $('.hideForm-news-edit.uz .start-text').text('Дата начала обучения');
    $('.hideForm-news-edit.uz .end-text').text('Дата выпуска');
    if ($(this).hasClass('teacher')) {
      teacher = 1;
      $('.hideForm-news-edit.uz .start-text').text('Дата начала преподавания');
      $('.hideForm-news-edit.uz .end-text').text('Дата завершения преподавания');
    }

    $('.hideForm-news-edit.uz .name_form span').text('Редактирование учебного заведения');

    let id = $(this).data('id');

    $('.hideForm-news-edit.uz .js-del-uz').show();
    $('.hideForm-news-edit.uz .js-del-uz').data('id', id);

    $('.hideForm-news-edit.uz .js-submit-uz-edit').data('id', id);
    $('.hideForm-news-edit.uz .js-submit-uz-edit').addClass('update');
    $('.hideForm-news-edit.uz .name').data('teacher', teacher);

    $('.hideForm-news-edit.uz .vuz-block').hide();
    $('.hideForm-news-edit.uz .coll-block').hide();
    $('.hideForm-news-edit.uz .shool-block').hide();
    $('.hideForm-news-edit.uz .lang-block').hide();

    dataform = {};

    dataform['id'] = id;

    $.ajax({
      type: 'POST',
      url: '/ajax/edit_uz.php',
      data: dataform,
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          let uz = result.res.uz_id;
          let obr = 0;
          let country = 0;
          let region = '';
          let city = 0;

          if (result.res.country_id) {
            $('.hideForm-news-edit.uz .obr').find('option[value=' + result.res.type + ']').prop('selected', true);
            obr = result.res.type;
            $('.hideForm-news-edit.uz .country').attr('disabled', false);
          }

          if (result.res.country_id) {
            $('.hideForm-news-edit.uz .country').find("option[value='" + result.res.country_id + "']").prop('selected', true);
            country = result.res.country_id;
            $('.hideForm-news-edit.uz .region').attr('disabled', false);
          }

          if (result.res.arRegion.length) {
            html = '';
            $.each(result.res.arRegion, function () {
              html += '<option value="' + this + '">' + this + '</option>';
            });
            $('.hideForm-news-edit.uz .region').append(html);
            if (result.res.region) {
              $('.hideForm-news-edit.uz .region').find("option[value='" + result.res.region + "']").prop('selected', true);
              region = result.res.region;
            }
          }

          if (result.res.city_name) {
            $('.hideForm-news-edit.uz .city').val(result.res.city_name);
            if (result.res.city_id) {
              $('.hideForm-news-edit.uz .city_id').val(result.res.city_id);
            }
          }

          $('.hideForm-news-edit.uz .name').attr('disabled', false);
          $('.hideForm-news-edit.uz .name').val(result.res.data.NAME);
          name = result.res.data.NAME;

          if (result.res.type == 1) {
            $('.hideForm-news-edit.uz .vuz-block').show();

            if (result.res.fack_arr) {
              html = '';
              $.each(result.res.fack_arr, function (i, v) {
                html += '<option value="' + i + '">' + v + '</option>';
              });
              $('.hideForm-news-edit.uz .fack').append(html);
              $('.hideForm-news-edit.uz .fack').attr('disabled', false);
              $('.hideForm-news-edit.uz .fack').find('option[value=' + result.res.fack + ']').prop('selected', true);
            }

            $('.hideForm-news-edit.uz .forma').attr('disabled', false);
            $('.hideForm-news-edit.uz .forma').find('option:contains("' + result.res.forma + '")').prop('selected', true);

            $('.hideForm-news-edit.uz .status').attr('disabled', false);
            $('.hideForm-news-edit.uz .status').find('option:contains("' + result.res.status + '")').prop('selected', true);

            $('.hideForm-news-edit.uz .group').val(result.res.grupe);

          } else if (result.res.type == 2) {
            $('.hideForm-news-edit.uz .coll-block').show();

            $('.hideForm-news-edit.uz .spec').val(result.res.spec);
            $('.hideForm-news-edit.uz .group').val(result.res.grupe);
          } else if (result.res.type == 3) {
            $('.hideForm-news-edit.uz .shool-block').show();

            $('.hideForm-news-edit.uz .klass').val(result.res.grupe);
          } else if (result.res.type == 4) {
            $('.hideForm-news-edit.uz .lang-block').show();
          }

          $('.hideForm-news-edit.uz .start').find('option[value=' + result.res.start_p + ']').prop('selected', true);
          $('.hideForm-news-edit.uz .end').find('option[value=' + result.res.end_p + ']').prop('selected', true);
        }
      }
    });

    $('.foneBg').css({ 'display': 'block' });

    $('.hideForm-news-edit.uz').fadeIn(250);

    return false;
  });

  $(".js-del-uz").on('click', function (e) {
    e.preventDefault();
    let id = $(this).data('id');

    dataform = {};

    dataform['id'] = id;

    $.ajax({
      type: 'POST',
      url: '/ajax/uz_del.php',
      data: dataform,
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          document.location.reload(true);
        }
      }
    });

    return false;
  });

  var select_uz = 0;
  var name_uz = '';

  $(".js-select-uz").on('change', function (e) {
    e.preventDefault();
    select_uz = $(this).val();
    if (select_uz) {
      $(this).find('option[value=0]').attr('disabled', true);
      $('.js-name-uz').attr('disabled', false);
      $('.js-name-uz').val('');
      name_uz = '';
    } else {
      $('.js-name-uz').attr('disabled', true);
      $('.js-name-uz').val('');
    }
    return false;
  });

  $(".js-name-uz").on('keyup', function (e) {
    e.preventDefault();
    name = $(this).val().trim();
    if (name.length > 1) {
      dataform = {};

      dataform['obr'] = select_uz;
      dataform['name'] = name;

      $.ajax({
        type: 'POST',
        url: '/ajax/get_name_reg.php',
        data: dataform,
        dataType: 'json',
        success: function (result) {
          if (result.status == 'success') {
            let html = '';
            if (result.res.length > 0) {
              $.each(result.res, function () {
                if (this) {
                  var re = new RegExp(name, 'i');
                  podstr = this.name.replace(re, '<b>' + name + '</b>');
                  html += '<div class="item" data-id="' + this.id + '"><div>' + podstr + '</div></div>';
                }
              });
              $('.auto-complit-reg').empty();
              $('.auto-complit-reg').append(html);
              $('.auto-complit-reg').show();
            } else {
              $('.auto-complit-reg').hide();
              $('.auto-complit-reg').empty();
            }
          }
        }
      });
    } else {
      $('.auto-complit-reg').hide();
      $('.auto-complit-reg').empty();
    }
    return false;
  });

  $("#step-3").on('click', '.auto-complit-reg .item', function (e) {
    e.preventDefault();
    uz = $(this).data('id');
    nameUz = $(this).find('div').text();

    if (uz > 0) {

      $('.js-name-uz').val(nameUz);
      $('.js-name-uz').data('id', uz);
      $('.auto-complit-reg').hide();
      $('.auto-complit-reg').empty();

      $('.js-id-uz').val(uz);

    }
    return false;
  });

  // ---- Закладки ------------
  $(".js-bookmark").on('click', function (e) {
    e.preventDefault();

    if (pro)
      return false;

    let $this = $(this);
    let id = $this.data('id');
    let type = $this.data('type');
    let state = $this.data('state');

    dataform = {};

    dataform['id'] = id;
    dataform['type'] = type;
    dataform['state'] = state;

    $.ajax({
      type: 'POST',
      url: '/ajax/bookmark.php',
      data: dataform,
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          if (state) {
            $this.data('state', 0);
            $this.removeClass('active');
            let noClose = $this.data('no-close');
            if ($this.parent().parent().parent().hasClass('news-item') && !noClose) {
              $this.parent().parent().parent().slideUp();
            }
            if ($this.parent().parent().parent().parent().parent().hasClass('news-item') && !noClose) {
              $this.parent().parent().parent().parent().parent().slideUp();
            }
          } else {
            $this.data('state', 1);
            $this.addClass('active');
          }
        }
      }
    });

    return false;
  });

  // ---- Закладки Динамические ------------
  $('#box-line').on('click', '.js-bookmark', function (e) {
    e.preventDefault();

    if (pro)
      return false;

    let $this = $(this);
    let id = $this.data('id');
    let type = $this.data('type');
    let state = $this.data('state');

    dataform = {};

    dataform['id'] = id;
    dataform['type'] = type;
    dataform['state'] = state;

    $.ajax({
      type: 'POST',
      url: '/ajax/bookmark.php',
      data: dataform,
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          if (state) {
            $this.data('state', 0);
            $this.removeClass('active');
            let noClose = $this.data('no-close');
            if ($this.parent().parent().parent().hasClass('news-item') && !noClose) {
              $this.parent().parent().parent().slideUp();
            }
            if ($this.parent().parent().parent().parent().parent().hasClass('news-item') && !noClose) {
              $this.parent().parent().parent().parent().parent().slideUp();
            }
          } else {
            $this.data('state', 1);
            $this.addClass('active');
          }
        }
      }
    });

    return false;
  });

  // ---- Пуск / Стоп Динамика ------------
  $('#page').on('click', '.js-order-start', function (e) {
    e.preventDefault();

    const $this = $(this);

    if (!pro || $this.hasClass('no-moderate'))
      return false;

    const id = parseInt($this.data('id'));
    const status = parseInt($this.data('status'));

    const type = $('#page .m-header .color-silver').data('filter');

    dataform = {};

    dataform['id'] = id;
    dataform['status'] = status;

    $.ajax({
      type: 'POST',
      url: '/ajax/start_stop_order.php',
      data: dataform,
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          $('#page .filter span.js-start').text(result.start);
          $('#page .filter span.js-stop').text(result.stop);

          if (status) {
            $this.removeClass('active');
            $this.data('status', 0);
          } else {
            $this.addClass('active');
            $this.data('status', 1);
          }

          if (type == 'start' || type == 'stop')
            $this.parent().parent().parent().remove();
        } else {
          $this.next().text(result.message);
          $this.next().slideDown();
          setTimeout(function () {
            $this.next().slideUp();
          }, 2500)
        }
      }
    });

    return false;
  });

  var delText = 0;

  $('.js-setting').click(function (e) {
    e.preventDefault();

    delText = 0;
    $('.hideForm-setting .form-open-block form .warning-text').hide();

    const teacher = $(this).data('teacher');
    const chat = $(this).data('chat');
    const offline = $(this).data('offline');
    const color = $(this).data('color');
    const url = $(this).data('url');

    const top_form = $(window).scrollTop();
    const height_form = $('.hideForm-setting .form-open-block form').height();
    const marg_top = $(window).height() / 2;

    $('.hideForm-setting .form-open-block').css({
      'height': $(window).height(),
      'position': 'absolute',
      'top': top_form,
    });
    $('.hideForm-setting').css({ 'height': $(document).height(), });

    $('.st-setting .setting-options').click();

    if (teacher) {
      $('.hideForm-setting .form-open-block form .js-teacher').prop('checked', true);
      $('.hideForm-setting .form-open-block form .radio-text').show();
    } else {
      $('.hideForm-setting .form-open-block form .js-teacher').prop('checked', false);
      $('.hideForm-setting .form-open-block form .radio-text').hide();
    }

    if (color) {
      $('.hideForm-setting .form-open-block form .js-color-off').prop('checked', true);
      $('.hideForm-setting .form-open-block form .color-text').show();
    } else {
      $('.hideForm-setting .form-open-block form .js-color-off').prop('checked', false);
      $('.hideForm-setting .form-open-block form .color-text').hide();
    }

    if (chat) {
      $('.hideForm-setting .form-open-block form .js-chat-off').prop('checked', true);
    } else {
      $('.hideForm-setting .form-open-block form .js-chat-off').prop('checked', false);
    }

    if (url) {
      $('.hideForm-setting .form-open-block form .js-url').val(url);
    }

    if (offline) {
      $('.hideForm-setting .form-open-block form .js-offline').prop('checked', true);
      $('.hideForm-setting .form-open-block form .offline-text').show();
    } else {
      $('.hideForm-setting .form-open-block form .js-offline').prop('checked', false);
      $('.hideForm-setting .form-open-block form .offline-text').hide();
    }

    $('.hideForm-setting .form-open-block form .warning-text').hide();

    $('.foneBg').css({ 'display': 'block' });

    $('.hideForm-setting').fadeIn(250);

    return false;
  });

  $('.hideForm-setting .form-open-block form .js-color-off').change(function (e) {
    e.preventDefault();

    let color = $('.hideForm-setting .form-open-block form .js-color-off:checked').val();

    if (color) {
      $('.hideForm-setting .form-open-block form .color-text').slideDown();
    } else {
      $('.hideForm-setting .form-open-block form .color-text').slideUp();
    }

    return false;
  });

  $('.hideForm-setting .form-open-block form .js-offline').change(function (e) {
    e.preventDefault();

    let offline = $('.hideForm-setting .form-open-block form .js-offline:checked').val();

    if (offline) {
      $('.hideForm-setting .form-open-block form .offline-text').slideDown();
    } else {
      $('.hideForm-setting .form-open-block form .offline-text').slideUp();
    }

    return false;
  });

  $('.hideForm-setting .form-open-block form .js-teacher').change(function (e) {
    e.preventDefault();

    let teacher = $('.hideForm-setting .form-open-block form .js-teacher:checked').val();

    if (teacher) {
      $('.hideForm-setting .form-open-block form .radio-text').slideDown();
    } else {
      $('.hideForm-setting .form-open-block form .radio-text').slideUp();
    }

    return false;
  });

  $('#form-setting').on('blur', '.js-url', function () {

    const url = $('#form-setting .js-url').val();

    if (url.length < 1)
      return false;

    $.ajax({
      type: 'POST',
      url: '/ajax/check_url.php',
      data: { url },
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          $('#form-setting .color-orange').css('color', 'green');
          $('#form-setting .color-orange').text('OK');
          $('#form-setting .color-orange').show();
        } else {
          $('#form-setting .color-orange').css('color', 'red');
          $('#form-setting .color-orange').text('URL занят');
          $('#form-setting .color-orange').show();
        }
      }
    });

    return false;
  });

  $('.js-submit-setting').on('click', function (e) {
    e.preventDefault();
    // Validation
    //

    const url = $('#form-setting .js-url').val();

    if(url.length > 0) {
      $.ajax({
        type: 'POST',
        url: '/ajax/check_url.php',
        data: { url },
        dataType: 'json',
        success: function (result) {
          if (result.status == 'success') {
            $.ajax({
              type: 'POST',
              url: '/ajax/setting.php',
              data: $("#form-setting").serialize(),
              dataType: 'json',
              success: function (result) {
                if (result.status) {
                  if (result.status == 'success') {
                    document.location.reload(true);
                    close_form();
                  } else {
                    $("#error-message-setting").text(result.message);
                    $("#error-message-setting").show();
                  }
                }
              }
            });
          } else {
            $('#form-setting .color-orange').css('color', 'red');
            $('#form-setting .color-orange').text('URL занят');
            $('#form-setting .color-orange').show();
          }
        }
      });
    } else {
      $.ajax({
        type: 'POST',
        url: '/ajax/setting.php',
        data: $("#form-setting").serialize(),
        dataType: 'json',
        success: function (result) {
          if (result.status) {
            if (result.status == 'success') {
              document.location.reload(true);
              close_form();
            } else {
              $("#error-message-setting").text(result.message);
              $("#error-message-setting").show();
            }
          }
        }
      });
    }

    return false;
  });

  $('.js-avatar').click(function (e) {
    e.preventDefault();

    let img = $(this).attr('src');
    let user = $(this).data('user');
    let fname = $(this).data('fname');
    let sname = $(this).data('sname');
    let lname = $(this).data('lname');

    $('.hideForm-avatar-big .form-open-block form .pop-avatar').attr('src', img);

    var top_form = $(window).scrollTop();
    var height_form = $('.hideForm-avatar-big .form-open-block form').height();
    var marg_top = $(window).height() / 2;

    $('.hideForm-avatar-big .form-open-block').css({
      'height': $(window).height(),
      'position': 'absolute',
      'top': top_form,
    });
    $('.hideForm-avatar-big').css({ 'height': $(document).height(), });

    $('.foneBg').css({ 'display': 'block' });

    $('.hideForm-avatar-big .js-abuse-avatar').data('url', img);
    $('.hideForm-avatar-big .js-abuse-avatar').data('user', user);
    $('.hideForm-avatar-big .js-abuse-avatar').data('fname', fname);
    $('.hideForm-avatar-big .js-abuse-avatar').data('sname', sname);
    $('.hideForm-avatar-big .js-abuse-avatar').data('lname', lname);

    $('.hideForm-avatar-big').fadeIn(250);

    return false;
  });

  $('.hideForm-setting .form-open-block form .del-text').click(function (e) {
    e.preventDefault();

    if (!delText) {
      $('.hideForm-setting .form-open-block form .warning-text').slideDown();
      delText = 1;
    } else {
      $('.hideForm-setting .form-open-block form .warning-text').slideUp();
      delText = 0;
    }

    return false;
  });

  $('.hideForm-setting .del-action').on('click', function (e) {
    e.preventDefault();

    $.ajax({
      type: 'POST',
      url: '/ajax/del_user.php',
      dataType: 'json',
      success: function (result) {
        if (result.status) {
          document.location = 'http://vuchebe.com/';
        } else {
          $("#error-message-setting").text(result.message);
          $("#error-message-setting").show();
        }
      }
    });

    return false;
  });

  $('.hideForm-profile-edit .form-open-block form .del-text').click(function (e) {
    e.preventDefault();

    if (!delText) {
      $('.hideForm-profile-edit .form-open-block form .warning-text').slideDown();
      delText = 1;
    } else {
      $('.hideForm-profile-edit .form-open-block form .warning-text').slideUp();
      delText = 0;
    }

    return false;
  });

  $('.hideForm-profile-edit .del-action').on('click', function (e) {
    e.preventDefault();

    $.ajax({
      type: 'POST',
      url: '/ajax/del_avatar.php',
      dataType: 'json',
      success: function (result) {
        if (result.status) {
          document.location.reload(true);
        } else {
          $("#error-message-setting").text(result.message);
          $("#error-message-setting").show();
        }
      }
    });

    return false;
  });

  $('.js-admins-add').click(function (e) {
    e.preventDefault();

    let vuzId = $(this).data('vuz-id');
    let oldId = $(this).data('id');
    let iblock = $(this).data('iblock');

    $('.hideForm-admins .form-open-block form input.vuz-id').val(vuzId);

    $('.hideForm-admins .form-open-block form .admin-id').val('');
    $('.hideForm-admins .form-open-block form .admin-id').css('color', '#a7a7a7');

    $('.hideForm-admins .form-open-block form input.old-id').val(oldId);

    $('.hideForm-admins .form-open-block form .name_form span').text('Добавление администратора');

    $('.hideForm-admins .form-open-block form .js-submit-admins').data('vuz-id', vuzId);
    $('.hideForm-admins .form-open-block form .js-del-admins').hide();
    $('.hideForm-admins .form-open-block form .js-abort').show();

    $('.hideForm-admins .form-open-block form .js-submit-admins').data('iblock', iblock);

    var top_form = $(window).scrollTop();
    var height_form = $('.hideForm-admins .form-open-block form').height();
    var marg_top = $(window).height() / 2;

    $('.hideForm-admins .form-open-block').css({
      'height': $(window).height(),
      'position': 'absolute',
      'top': top_form,
    });
    $('.hideForm-admins').css({ 'height': $(document).height(), });

    $('.foneBg').css({ 'display': 'block' });

    $('.hideForm-admins').fadeIn(250);

    return false;
  });

  $('.js-admins-edit').click(function (e) {
    e.preventDefault();

    let vuzId = $(this).data('vuz-id');
    let iblock = $(this).data('iblock');

    let adminId = $(this).data('id');
    let adminIdUrl = 'http://vuchebe.com/user/' + adminId + '/';

    $('.hideForm-admins .form-open-block form input.vuz-id').val(vuzId);

    $('.hideForm-admins .form-open-block form .admin-id').val(adminIdUrl);
    $('.hideForm-admins .form-open-block form .admin-id').css('color', '#a7a7a7');

    $('.hideForm-admins .form-open-block form input.old-id').val(adminId);

    $('.hideForm-admins .form-open-block form .name_form span').text('Редактирование администратора');

    $('.hideForm-admins .form-open-block form .js-abort').hide();
    $('.hideForm-admins .form-open-block form .js-del-admins').show();

    $('.hideForm-admins .form-open-block form .js-submit-admins').data('iblock', iblock);
    $('.hideForm-admins .form-open-block form .js-del-admins').data('iblock', iblock);

    var top_form = $(window).scrollTop();
    var height_form = $('.hideForm-admins .form-open-block form').height();
    var marg_top = $(window).height() / 2;

    $('.hideForm-admins .form-open-block').css({
      'height': $(window).height(),
      'position': 'absolute',
      'top': top_form,
    });
    $('.hideForm-admins').css({ 'height': $(document).height(), });

    $('.foneBg').css({ 'display': 'block' });

    $('.hideForm-admins').fadeIn(250);

    return false;
  });

  $('.js-submit-admins').on('click', function (e) {
    e.preventDefault();
    // Validation
    let vuzId = $('.hideForm-admins .form-open-block form input.vuz-id').val();
    let oldAdmin = $('.hideForm-admins .form-open-block form input.old-id').val();
    let newAdminUrl = $('.hideForm-admins .form-open-block form input.admin-id').val();
    let iblock = $(this).data('iblock');
    let newAdmin = newAdminUrl.replace(/[^-0-9]/ig, '').trim();

    if (!newAdmin) {
      $('.hideForm-admins .form-open-block form input.admin-id').css('color', 'red');
      $('.hideForm-admins .form-open-block form input.admin-id').val('Введите ID пользователя');
      return false;
    }

    if ($('#user-' + newAdmin).length && oldAdmin != newAdmin) {
      $('.hideForm-admins .form-open-block form input.admin-id').css('color', 'red');
      $('.hideForm-admins .form-open-block form input.admin-id').val('Администратор с таким ID уже есть');
      return false;
    }

    $.ajax({
      type: 'POST',
      url: '/ajax/admins.php',
      data: { 'vuz_id': vuzId, 'old_id': oldAdmin, 'admin_id': newAdmin, 'iblock': iblock },
      dataType: 'json',
      success: function (result) {
        if (result.status) {
          if (result.status == 'success') {
            document.location.reload(true);
          } else {
            $("#error-message-admins").text(result.message);
            $("#error-message-admins").show();
          }
        }
      }
    });

    return false;
  });

  $('.js-del-admins').on('click', function (e) {
    e.preventDefault();

    let vuzId = $('.hideForm-admins .form-open-block form input.vuz-id').val();
    let oldAdmin = $('.hideForm-admins .form-open-block form input.old-id').val();
    let iblock = $(this).data('iblock');

    let newAdminUrl = $('.hideForm-admins .form-open-block form input.admin-id').val();
    let newAdmin = newAdminUrl.replace(/[^-0-9]/ig, '').trim();

    $.ajax({
      type: 'POST',
      url: '/ajax/del_admins.php',
      data: { 'vuz_id': vuzId, 'old_id': oldAdmin, 'admin_id': newAdmin, 'iblock': iblock },
      dataType: 'json',
      success: function (result) {
        if (result.status) {
          if (result.status == 'success') {
            document.location.reload(true);
          } else {
            $("#error-message-admins").text(result.message);
            $("#error-message-admins").show();
          }
        }
      }
    });

    return false;
  });

  $('.hideForm-admins .form-open-block form input.admin-id').on('focus', function () {
    $(this).css('color', '#a7a7a7');
    if ($(this).val() == 'Введите ID пользователя' || $(this).val() == 'Администратор с таким ID уже есть')
      $(this).val('');
    return false;
  });

  $('.js-vacancies-add').click(function (e) {
    e.preventDefault();

    let vuzId = $(this).data('vuz-id');
    let vacId = $(this).data('id');

    $('.hideForm-vacancies .form-open-block form input, .hideForm-vacancies .form-open-block form textarea').val('');
    $('.hideForm-vacancies .form-open-block form input, .hideForm-vacancies .form-open-block form textarea').css('color', '#a7a7a7');

    $('.hideForm-vacancies .form-open-block form .name_form span').text('Добавление вакансии');

    $('.hideForm-vacancies .form-open-block form input.vuz-id').val(vuzId);
    $('.hideForm-vacancies .form-open-block form input.vac-id').val(vacId);

    $('.hideForm-vacancies .form-open-block form .js-submit-vacancies').data('vuz-id', vuzId);
    $('.hideForm-vacancies .form-open-block form .js-del-vacancies').hide();
    $('.hideForm-vacancies .form-open-block form .js-abort').show();

    var top_form = $(window).scrollTop();
    var height_form = $('.hideForm-vacancies .form-open-block form').height();
    var marg_top = $(window).height() / 2;

    $('.hideForm-vacancies .form-open-block').css({
      'height': $(window).height(),
      'position': 'absolute',
      'top': top_form,
    });
    $('.hideForm-vacancies').css({ 'height': $(document).height(), });

    $('.foneBg').css({ 'display': 'block' });

    $('.hideForm-vacancies').fadeIn(250);

    return false;
  });

  $('.js-vacancies-edit').click(function (e) {
    e.preventDefault();

    let vuzId = $(this).data('vuz-id');
    let vacId = $(this).data('id');

    $('.hideForm-vacancies .form-open-block form .name_form span').text('Редактирование вакансии');

    $('.hideForm-vacancies .form-open-block form input.vuz-id').val(vuzId);
    $('.hideForm-vacancies .form-open-block form input.vac-id').val(vacId);

    $('.hideForm-vacancies .form-open-block form .js-submit-vacancies').data('vuz-id', vuzId);
    $('.hideForm-vacancies .form-open-block form .js-abort').hide();
    $('.hideForm-vacancies .form-open-block form .js-del-vacancies').show();

    $('.hideForm-vacancies .form-open-block form input, .hideForm-vacancies .form-open-block form textarea').css('color', '#a7a7a7');

    $.ajax({
      type: 'POST',
      url: '/ajax/get_vacancies.php',
      data: { 'vuz_id': vuzId, 'vac_id': vacId },
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          $('.hideForm-vacancies .form-open-block form .name').val(result.res['NAME']);
          $('.hideForm-vacancies .form-open-block form .phone-vacancies').val(result.res['PROPERTY_PHONE_VALUE']);
          $('.hideForm-vacancies .form-open-block form .e-mail').val(result.res['PROPERTY_EMAIL_VALUE']);
          $('.hideForm-vacancies .form-open-block form .contacts').val(result.res['PROPERTY_CONTACTS_VALUE']);
          $('.hideForm-vacancies .form-open-block form .spec').val(result.res['PROPERTY_FAKULTET_VALUE']);
          $('.hideForm-vacancies .form-open-block form .message').val(result.res['DETAIL_TEXT']);
        }
      }
    });

    var top_form = $(window).scrollTop();
    var height_form = $('.hideForm-vacancies .form-open-block form').height();
    var marg_top = $(window).height() / 2;

    $('.hideForm-vacancies .form-open-block').css({
      'height': $(window).height(),
      'position': 'absolute',
      'top': top_form,
    });
    $('.hideForm-vacancies').css({ 'height': $(document).height(), });

    $('.foneBg').css({ 'display': 'block' });

    $('.hideForm-vacancies').fadeIn(250);

    return false;
  });

  $('.js-submit-vacancies').on('click', function (e) {
    e.preventDefault();
    // Validation
    let name = $('.hideForm-vacancies .form-open-block form .name').val();

    if (!name) {
      $('.hideForm-vacancies .form-open-block form .name').css('color', 'red');
      $('.hideForm-vacancies .form-open-block form .name').val('Обязательно для заполнения');
      return false;
    }

    $.ajax({
      type: 'POST',
      url: '/ajax/vacancies.php',
      data: $("#form-vacancies").serialize(),
      dataType: 'json',
      success: function (result) {
        if (result.status) {
          if (result.status == 'success') {
            document.location.reload(true);
          } else {
            $("#error-message-vacancies").text(result.message);
            $("#error-message-vacancies").show();
          }
        }
      }
    });

    return false;
  });

  $('.js-del-vacancies').on('click', function (e) {
    e.preventDefault();

    $.ajax({
      type: 'POST',
      url: '/ajax/del_vacancies.php',
      data: $("#form-vacancies").serialize(),
      dataType: 'json',
      success: function (result) {
        if (result.status) {
          if (result.status == 'success') {
            document.location.reload(true);
          } else {
            $("#error-message-admins").text(result.message);
            $("#error-message-admins").show();
          }
        }
      }
    });

    return false;
  });

  $('.hideForm-vacancies .form-open-block form .name').on('focus', function () {
    $(this).css('color', '#a7a7a7');
    if ($(this).val() == 'Обязательно для заполнения')
      $(this).val('');
    return false;
  });

  // ---- PopUp Baloon ------------
  $("#page").on('click', '.st-baloon .more-baloon span', function (e) {
    e.preventDefault();

    let $this = $(this);

    let vuz = $this.data('id-vuz');
    let type = $this.data('type');
    let id = $this.data('id');
    let hash = $this.data('hash');

    $('#form-baloon #box-line').hide();

    dataform = {};

    dataform['vuz'] = vuz;
    dataform['type'] = type;
    dataform['id'] = id;
    dataform['hash'] = hash;

    if (type == 'group-chat') {
      const chat = $this.data('chat');
      const typeuser = $this.data('type-user');

      dataform['chat'] = chat;
      dataform['typeuser'] = typeuser;
    }

    $.ajax({
      type: 'POST',
      url: '/ajax/baloon.php',
      data: dataform,
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          let namePopUp = 'Прогосовавшие пользователи';

          if (type == 'group-chat')
            namePopUp = 'Список пользователей';

          let html = `<div class="name_form text-center"><span>${namePopUp}</span></div>`;
          if (result.res.length > 0) {
            $.each(result.res, function () {
              if (this) {
                html += '<div class="news-item">';
                if (this.teacher == 'Y') {
                  html += '<img src="' + this.avatar + '" alt="img" style="border: 2px solid #ff471a;">';
                } else {
                  html += '<img src="' + this.avatar + '" alt="img">';
                }
                html += '<div class="name-user">';
                if (result.auth) {
                  html += '<a href="/user/' + this.id + '/">' + this.format_name + '</a>';
                } else {
                  html += '<a href="/user/' + this.id + '/" class="js-noauth">' + this.format_name + '</a>';
                }
                if (this.online == 'Y') {
                  html += '<div class="is-online" title="В сети"></div>';
                }
                html += '</div>';
                html += '</div>';
              }
            });
            html += '<a href="javascript:void(0);" class="close" onclick="close_form();"></a>';
            $('#form-baloon #box-line').empty();
            $('#form-baloon #box-line').append(html);
            $('#form-baloon #box-line').show();
          }

          var top_form = $(window).scrollTop();
          var height_form = $('.hideForm-news-edit.baloon .form-open-block form').height();
          var marg_top = $(window).height() / 2;

          $('.hideForm-news-edit.baloon .form-open-block').css({
            'height': $(window).height(),
            'position': 'absolute',
            'top': top_form,
          });
          $('.hideForm-news-edit.baloon').css({ 'height': $(document).height(), });

          $('.foneBg').css({ 'display': 'block' });

          $('.hideForm-news-edit.baloon').fadeIn(250);
          $("#form-baloon #box-line").scrollTop(0);
        }
      }
    });

    return false;
  });

  $("#form-baloon").on('click', '.js-noauth', function (e) {
    e.preventDefault();

    if (pro)
      return false;

    close_form();

    $(window).scrollTop(0);
    var top_form = $(window).scrollTop();
    var height_form = $('.form-open-block form').height();
    var marg_top = $(window).height() / 2;

    $('.form-open-block').css({
      'height': $(window).height(),
      'position': 'absolute',
      'top': top_form,
    });
    $('.hideForm').css({
      'height': $(document).height(),
    });

    $('.foneBg').css({ 'display': 'block' });

    $('.hideForm').fadeIn(250);

    return false;
  });

  $("#chat, #box-line, #page").on('mouseenter', '.message_chat', function (e) {
    e.preventDefault();
    let $this = $(this);
    let curr = $this.find('.js-del, .js-del-user-chat-always, .js-del-group-post, .js-up-down, .del-post');
    if (!curr.length)
      return false;
    $('.js-del, .js-del-user-chat-always, .js-del-group-post, .js-up-down').hide();
    clearInterval(intervalID);
    intervalID = setTimeout(function () {
      curr.fadeIn();
    }, 1000);
    return false;
  });

  $("#chat, #box-line, #page").on('mouseleave', '.message_chat', function (e) {
    e.preventDefault();
    let $this = $(this);
    let curr = $this.find('.js-del, .js-del-user-chat-always, .js-del-group-post, .js-up-down, .del-post');
    if (!curr.length)
      return false;
    clearInterval(intervalID);
    intervalID = setTimeout(function () {
      curr.fadeOut();
    }, 1000);
    return false;
  });

  $("#chat, #box-line, #page").on('mouseenter', '.js-del, .js-del-user-chat-always, .js-del-group-post, .js-up-down, .del-post', function (e) {
    e.preventDefault();
    let $this = $(this);
    clearInterval(intervalID);
    $this.show();
    return false;
  });

  $("#chat, #box-line, #page").on('mouseleave', '.js-del, .js-del-user-chat-always, .js-del-group-post, .js-up-down, .del-post', function (e) {
    e.preventDefault();
    let $this = $(this);
    clearInterval(intervalID);
    intervalID = setTimeout(function () {
      $('.js-del, .js-del-user-chat-always, .js-del-group-post, .js-up-down').fadeOut();
    }, 1000);
    return false;
  });

  $("#chat, #box-line").on('click', '.js-del', function (e) {
    e.preventDefault();
    let $this = $(this);

    let type = $this.data('type');
    let id = $this.data('id');
    let owner = $this.data('owner');
    let from = $this.data('from');
    let spam = $this.data('spam');
    let pos = $this.data('pos');

    $.ajax({
      type: 'POST',
      url: '/ajax/del_chat.php',
      data: { 'type': type, 'id': id, 'owner': owner, 'from': from, 'spam': spam },
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          if (type == 'post') {
            $this.parent().parent().parent().slideUp();
            $('#chat-id-' + id).removeClass('no_show');
          }
          if (type == 'chat')
            $this.parent().parent().parent().parent().parent().parent().parent().parent().slideUp();
          if (type == 'bookmark') {
            $this.data('type', 'del-chat-bookmark');
            $this.text('убрать из закладок');
            if (pos == 'right') {
              $this.css('left', '-132px');
            } else {
              $this.css('right', '-132px');
            }
          }
          if (type == 'del-bookmark')
            $this.parent().parent().parent().parent().parent().slideUp();
          if (type == 'del-chat-bookmark') {
            $this.data('type', 'bookmark');
            $this.text('в закадки');
            if (pos == 'right') {
              $this.css('left', '-72px');
            } else {
              $this.css('right', '-72px');
            }
          }
          if (type == 'spam') {
            $this.data('type', 'no-spam');
            $this.data('spam', result.id);
            $this.css('right', '-58px');
            $this.text('не спам');
          }
          if (type == 'no-spam') {
            $this.data('type', 'spam');
            $this.data('spam', 0);
            $this.css('right', '-40px');
            $this.text('спам');
          }
        }
      }
    });

    return false;
  });

  $('#textarea-input').autoResize({
    // установим максимальный размер растяжения
    limit: 100,
  });

  // ---------- Popup Bloked User
  $("#form-setting").on('click', '.block-user', function (e) {
    e.preventDefault();

    if (pro)
      return false;

    let $this = $(this);

    $('.hideForm-setting.setting').hide();
    $('#form-block-user #box-line').hide();

    dataform = {};

    $.ajax({
      type: 'POST',
      url: '/ajax/block_user.php',
      data: dataform,
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          let html = '<div class="name_form text-center"><span>Заблокированные пользователи</span></div>';
          if (result.res.length > 0) {
            $.each(result.res, function () {
              if (this) {
                html += '<div class="news-item">';
                if (this.teacher == 'Y') {
                  html += '<img src="' + this.avatar + '" alt="img" style="border: 2px solid #ff471a;">';
                } else {
                  html += '<img src="' + this.avatar + '" alt="img">';
                }
                html += '<div class="name-user" style="margin-left: 55px; display: block; top: -32px; width: auto;">';
                html += '<a href="/user/' + this.id + '/">' + this.format_name + '</a>';
                if (this.online == 'Y') {
                  html += '<div class="is-online" title="В сети"></div>';
                }
                html += '<div class="color-silver js-unblock js-hidden js-submit-del_user" data-form="del" data-id="' + this.id + '">разблокировать</div>';
                html += '</div>';
                html += '</div>';
              }
            });
            html += '<div id="form-block" style="border: none;">';
            html += '<div class="label">Заблокировать пользователя</div>';
            html += '<input name="add_block" class="sname js-block_user-add error-reset" type="text" placeholder="вставте ссылку">';
            html += '</div>';
            html += '<div class="row-line mb-10 mt-15 css-btn" style="margin-top: 25px;">';
            html += '<div class="col-4">';
            html += '<button type="submit" class="js-submit-block_user" data-form="add"><span>Сохранить</span></button>';
            html += '</div>';
            html += '<div class="col-4">';
            html += '<button class="gray" type="button" style="background-color: #a7a7a7;" onclick="close_form();"><span>Отмена</span></button>';
            html += '</div>';
            html += '</div>';
            $('#form-block-user #box-line').empty();
            $('#form-block-user #box-line').append(html);
            $('#form-block-user #box-line').show();
          } else {
            html += '<div id="form-block" style="border: none;">';
            html += '<div class="label">Заблокировать пользователя</div>';
            html += '<input name="add_block" class="sname js-block_user-add error-reset" type="text" placeholder="вставте ссылку">';
            html += '</div>';
            html += '<div class="row-line mb-10 mt-15 css-btn" style="margin-top: 25px;">';
            html += '<div class="col-4">';
            html += '<button type="submit" class="js-submit-block_user" data-form="add"><span>Сохранить</span></button>';
            html += '</div>';
            html += '<div class="col-4">';
            html += '<button class="gray" type="button" style="background-color: #a7a7a7;" onclick="close_form();"><span>Отмена</span></button>';
            html += '</div>';
            html += '</div>';
            $('#form-block-user #box-line').empty();
            $('#form-block-user #box-line').append(html);
            $('#form-block-user #box-line').show();
          }


          var top_form = $(window).scrollTop();
          var height_form = $('.hideForm-news-edit.block-user .form-open-block form').height();
          var marg_top = $(window).height() / 2;

          $('.hideForm-news-edit.block-user .form-open-block').css({
            'height': $(window).height(),
            'position': 'absolute',
            'top': top_form,
          });
          $('.hideForm-news-edit.block-user').css({ 'height': $(document).height(), });

          $('.foneBg').css({ 'display': 'block' });

          $('.hideForm-news-edit.block-user').fadeIn(250);
          $("#form-baloon #box-line").scrollTop(0);
        }
      }
    });

    return false;
  });

  $("#form-block-user").on('click', '.js-submit-block_user, .js-submit-del_user', function (e) {
    e.preventDefault();

    if (pro)
      return false;

    let $this = $(this);
    let add = '';
    let type = $this.data('form');

    if (type == 'add') {
      let addUrl = $('.js-block_user-add').val();
      add = addUrl.replace(/[^-0-9]/ig, '').trim();
    } else if (type == 'del') {
      add = $this.data('id');
    }

    if (!add) {
      close_form();
      return false;
    }

    $.ajax({
      type: 'POST',
      url: '/ajax/block_user_add.php',
      data: { 'id': add, 'type': type },
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          let html = '<div class="name_form text-center"><span>Заблокированные пользователи</span></div>';
          if (result.res.length > 0) {
            $.each(result.res, function () {
              if (this) {
                html += '<div class="news-item">';
                if (this.teacher == 'Y') {
                  html += '<img src="' + this.avatar + '" alt="img" style="border: 2px solid #ff471a;">';
                } else {
                  html += '<img src="' + this.avatar + '" alt="img">';
                }
                html += '<div class="name-user" style="margin-left: 55px; display: block; top: -32px; width: auto;">';
                html += '<a href="/user/' + this.id + '/">' + this.format_name + '</a>';
                if (this.online == 'Y') {
                  html += '<div class="is-online" title="В сети"></div>';
                }
                html += '<div class="color-silver js-unblock js-hidden js-submit-del_user" data-form="del" data-id="' + this.id + '">разблокировать</div>';
                html += '</div>';
                html += '</div>';
              }
            });
            html += '<div id="form-block" style="border: none;">';
            html += '<div class="label">Заблокировать пользователя</div>';
            html += '<input name="add_block" class="sname js-block_user-add error-reset" type="text" placeholder="вставте ссылку">';
            html += '</div>';
            html += '<div class="row-line mb-10 mt-15" style="margin-top: 25px;">';
            html += '<div class="col-4">';
            html += '<button type="submit" class="js-submit-block_user" data-form="add"><span>Сохранить</span></button>';
            html += '</div>';
            html += '<div class="col-4">';
            html += '<button class="gray" type="button" style="background-color: #a7a7a7;" onclick="close_form();"><span>Отмена</span></button>';
            html += '</div>';
            html += '</div>';
            $('#form-block-user #box-line').empty();
            $('#form-block-user #box-line').append(html);
          } else {
            html += '<div id="form-block" style="border: none;">';
            html += '<div class="label">Заблокировать пользователя</div>';
            html += '<input name="add_block" class="sname js-block_user-add error-reset" type="text" placeholder="вставте ссылку">';
            html += '</div>';
            html += '<div class="row-line mb-10 mt-15" style="margin-top: 25px;">';
            html += '<div class="col-4">';
            html += '<button type="submit" class="js-submit-block_user" data-form="add"><span>Сохранить</span></button>';
            html += '</div>';
            html += '<div class="col-4">';
            html += '<button class="gray" type="button" style="background-color: #a7a7a7;" onclick="close_form();"><span>Отмена</span></button>';
            html += '</div>';
            html += '</div>';
            $('#form-block-user #box-line').empty();
            $('#form-block-user #box-line').append(html);
          }
        }
      }
    });

    return false;
  });

  // ---------- Popup Смена пароля
  $("#form-setting").on('click', '.change-pass', function (e) {
    e.preventDefault();

    let $this = $(this);

    $('.hideForm-setting.setting').fadeOut(250);

    var top_form = $(window).scrollTop();
    var height_form = $('.hideForm-news-edit.change-pass .form-open-block form').height();
    var marg_top = $(window).height() / 2;

    $('.hideForm-news-edit.change-pass .form-open-block').css({
      'height': $(window).height(),
      'position': 'absolute',
      'top': top_form,
    });
    $('.hideForm-news-edit.change-pass').css({ 'height': $(document).height(), });

    $('.foneBg').css({ 'display': 'block' });

    $("#form-change-pass .js-info").hide();
    $("#form-change-pass .js-info").removeClass('show-info');
    $("#form-change-pass .js-info div").text('');

    $("#form-change-pass .js-old-pass").val('');
    $("#form-change-pass .js-new-pass").val('');
    $("#form-change-pass .js-confirm-pass").val('');

    $("#form-change-pass .send-info").hide();
    $("#form-change-pass .send-old-pass").show();

    $('.hideForm-news-edit.change-pass').fadeIn(250);

    return false;
  });

  $("#form-change-pass").on('click', '.send-old-pass', function (e) {
    e.preventDefault();

    $.ajax({
      type: 'POST',
      url: '/ajax/send_pass.php',
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          $("#form-change-pass .send-old-pass").hide();
          $("#form-change-pass .send-info").show();

          setTimeout(close_form, 2000);
        } else {
          console.log('Error');
        }
      }
    });

    return false;
  });

  $("#form-change-pass").on('click', '.js-submit-change-pass', function (e) {
    e.preventDefault();

    let oldPass = $("#form-change-pass .js-old-pass").val();
    let newPass = $("#form-change-pass .js-new-pass").val();
    let confirmPass = $("#form-change-pass .js-confirm-pass").val();

    if (!oldPass || !newPass || !confirmPass) {
      $("#form-change-pass .js-info div").css('color', 'red');
      $("#form-change-pass .js-info div").text('Все поля обязательны для заполнения');

      if (!$("#form-change-pass .js-info").hasClass('show-info')) {
        $("#form-change-pass .js-info").slideDown();
        $("#form-change-pass .js-info").addClass('show-info');
      }

      return false;
    }

    const re = /^\w{6,}$/;
    if (!re.test(newPass)) {
      $("#form-change-pass .js-info div").css('color', 'red');
      $("#form-change-pass .js-info div").text('Новый пароль не менее 6 знаков. Допустимые символы: a-z A-Z 0-9 _');

      if (!$("#form-change-pass .js-info").hasClass('show-info')) {
        $("#form-change-pass .js-info").slideDown();
        $("#form-change-pass .js-info").addClass('show-info');
      }

      return false;
    }

    if (newPass !== confirmPass) {
      $("#form-change-pass .js-info div").css('color', 'red');
      $("#form-change-pass .js-info div").text('Новый пароль и подтверждение не совпадают');

      if (!$("#form-change-pass .js-info").hasClass('show-info')) {
        $("#form-change-pass .js-info").slideDown();
        $("#form-change-pass .js-info").addClass('show-info');
      }

      return false;
    }

    $.ajax({
      type: 'POST',
      url: '/ajax/change_pass.php',
      data: { 'old_pass': oldPass, 'new_pass': newPass, 'confirm_pass': confirmPass },
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          $("#form-change-pass .js-info div").css('color', 'green');
          $("#form-change-pass .js-info div").text('Пароль успешно изменён');

          if (!$("#form-change-pass .js-info").hasClass('show-info')) {
            $("#form-change-pass .js-info").slideDown();
            $("#form-change-pass .js-info").addClass('show-info');
          }

          setTimeout(close_form, 1500);
        } else {
          $("#form-change-pass .js-info div").css('color', 'red');
          $("#form-change-pass .js-info div").text(result.res);

          if (!$("#form-change-pass .js-info").hasClass('show-info')) {
            $("#form-change-pass .js-info").slideDown();
            $("#form-change-pass .js-info").addClass('show-info');
          }
        }
      }
    });

    return false;
  });

  $("#form-change-pass").on('focus', '.js-old-pass, .js-new-pass, .js-confirm-pass', function (e) {
    e.preventDefault();

    $("#form-change-pass .js-info").slideUp();
    $("#form-change-pass .js-info").removeClass('show-info');
    $("#form-change-pass .js-info div").text('');

    return false;
  });

  $(".st-content-search .filter-search .filter").on('click', function () {
    $(".st-content-search .filter-search .filter").removeClass('active');
    $(this).addClass('active');
    let filter = $(this).data('filter');
    $("#filterinput").val(filter);
    let cntFilter = 0;
    if (filter == 'all') {
      cntFilter = $("#box-line .news-item").length;

      inProgress = false;
      inProgressGetBanner = false;
      offset = 0;

      lazyListSearch();


      $(".news-item").hide();
      $(".news-item").fadeIn(1000);
    } else {

      inProgress = false;
      inProgressGetBanner = false;

      lazyListSearch();

      $(".news-item").hide();
      $(".news-item." + filter).fadeIn(1000);
    }
    return false;
  });

  $("#page").on('click', '.js-hide-block', function (e) {
    e.preventDefault();

    let parent = $(this).parent().parent();

    if (parent.hasClass("hide")) {
      parent.find('.hide-block').slideDown();
      parent.removeClass("hide");
      parent.find('.news-item:first').removeClass("one");
      $(this).addClass("active");
    } else {
      parent.find('.hide-block').slideUp();
      parent.addClass("hide");
      parent.find('.news-item:first').addClass("one");
      $(this).removeClass("active");
    }

    return false;
  });

  $('#user-search').on('submit', function () {

    let searchText = $('#search-text').val();
    if (searchText.length < 3) {
      $('#search-text').css('color', 'red');
      $('#search-text').val('Введите имя и фамилию пользователя');
      return false;
    }

    $('#user-search').submit();
    return false;
  });

  $('#search-text').on('focus', function (e) {
    e.preventDefault();

    let searchText = $('#search-text').val();
    $('#search-text').css('color', '#a7a7a7');
    if (searchText == 'Введите имя и фамилию пользователя')
      $('#search-text').val('');
    return false;
  });

  $('#support-search').on('submit', function () {

    let searchText = $('#support-text').val();
    if (searchText.length < 3) {
      $('#support-text').css('color', 'red');
      $('#support-text').val('Введите имя, фамилию или номер тикета');
      return false;
    }
    $('#support-search').submit();
    return false;
  });

  $('#support-text').on('focus', function (e) {
    e.preventDefault();

    let searchText = $('#support-text').val();
    $('#support-text').css('color', '#a7a7a7');
    if (searchText == 'Введите имя, фамилию или номер тикета')
      $('#support-text').val('');
    return false;
  });

  $('#autoresponse').on('click', '.js-autoresponse', function (e) {
    e.preventDefault();

    const autoText = $(this).text()
    const textarea = $('#form-support-chat-post .message')
    const oldDetx  = textarea.val()
    let newText    = ''

    if(oldDetx == '')
      newText = autoText
    else
      newText = `${oldDetx} ${autoText}`

    textarea.css('color', '#000000')
    textarea.focus()
    textarea.val(newText)

    return false;
  });

  //-------------------------------------- Ajax Map ----------------------------------------------//

  function get_map() {

    let countryId = 0;
    let cityId = 0;
    let vuzId = [];
    let geo;

    if ($('.js-counry-top.active').length)
      countryId = $('.js-counry-top.active').data('id');

    if ($('.js-list-top.active').length)
      cityId = $('.js-list-top.active').data('id');

    if (!cityId) {
      if ($('.js-city-top.active').length)
        cityId = $('.js-city-top.active').data('id');
    }

    if ($('.js-map.st-cheked').length) {
      $('.js-map.st-cheked').each(function () {
        vuzId.push($(this).data('id'));
      });
    }

    $.ajax({
      type: 'POST',
      url: '/ajax/get_map.php',
      data: { 'countryId': countryId, 'cityId': cityId, 'vuzId': vuzId },
      dataType: 'json',
      success: function (result) {

        if (result.status == 'success') {

          myMap.geoObjects.removeAll();
          clusterer = new ymaps.Clusterer({
            // Зададим массив, описывающий иконки кластеров разного размера.
            clusterIcons: [
              {
                href: '/local/templates/vuchebe/images/b.png',
                size: [40, 40],
                offset: [-20, -20]
              },
              {
                href: '/local/templates/vuchebe/images/b.png',
                size: [60, 60],
                offset: [-30, -30]
              }],
            minClusterSize: 3,
            clusterNumbers: [15]
          });

          geoObjects = [];

          if (typeof result.res.universities != 'undefined' && result.res.universities.length > 0) {
            $.each(result.res.universities, function () {

              geo = new ymaps.Placemark([this[2], this[1]], {
                balloonContent: '<div class="image" style="width: 59px; float: left; border: 1px solid #ff471a;"><img style="margin: 3px; width: 51px;" src="' + this[6] + '" alt="img"></div>' +
                  '<div class="text" style="width: calc(100% - 59px); float: left; padding-left: 10px; font-size: 12px; color: #2a2929;">' +
                  '<a style="color: #ff471a; margin-bottom: 5px;" class="title" href="' + this[4] + '">' + this[3] + '</a>' +
                  'Адрес: ' + this[0] +
                  '<br>Телефон: ' + this[5] +
                  '</div>'
              }, {
                iconLayout: 'default#image',
                iconImageHref: '/local/templates/vuchebe/images/' + this[7],
                iconImageSize: [29, 39],
                iconImageOffset: [-14, -40]
              });
              geoObjects.push(geo);

            });
          }

          if (typeof result.res.colleges != 'undefined' && result.res.colleges.length > 0) {
            $.each(result.res.colleges, function () {

              geo = new ymaps.Placemark([this[2], this[1]], {
                balloonContent: '<div class="image" style="width: 59px; float: left; border: 1px solid #ff471a;"><img style="margin: 3px; width: 51px;" src="' + this[6] + '" alt="img"></div>' +
                  '<div class="text" style="width: calc(100% - 59px); float: left; padding-left: 10px; font-size: 12px; color: #2a2929;">' +
                  '<a style="color: #ff471a; margin-bottom: 5px;" class="title" href="' + this[4] + '">' + this[3] + '</a>' +
                  'Адрес: ' + this[0] +
                  '<br>Телефон: ' + this[5] +
                  '</div>'
              }, {
                iconLayout: 'default#image',
                iconImageHref: '/local/templates/vuchebe/images/' + this[7],
                iconImageSize: [29, 39],
                iconImageOffset: [-14, -40]
              });
              geoObjects.push(geo);

            });
          }

          if (typeof result.res.schools != 'undefined' && result.res.schools.length > 0) {
            $.each(result.res.schools, function () {

              geo = new ymaps.Placemark([this[2], this[1]], {
                balloonContent: '<div class="image" style="width: 59px; float: left; border: 1px solid #ff471a;"><img style="margin: 3px; width: 51px;" src="' + this[6] + '" alt="img"></div>' +
                  '<div class="text" style="width: calc(100% - 59px); float: left; padding-left: 10px; font-size: 12px; color: #2a2929;">' +
                  '<a style="color: #ff471a; margin-bottom: 5px;" class="title" href="' + this[4] + '">' + this[3] + '</a>' +
                  'Адрес: ' + this[0] +
                  '<br>Телефон: ' + this[5] +
                  '</div>'
              }, {
                iconLayout: 'default#image',
                iconImageHref: '/local/templates/vuchebe/images/' + this[7],
                iconImageSize: [29, 39],
                iconImageOffset: [-14, -40]
              });
              geoObjects.push(geo);

            });
          }

          if (typeof result.res.languageClass != 'undefined' && result.res.languageClass.length > 0) {
            $.each(result.res.languageClass, function () {

              geo = new ymaps.Placemark([this[2], this[1]], {
                balloonContent: '<div class="image" style="width: 59px; float: left; border: 1px solid #ff471a;"><img style="margin: 3px; width: 51px;" src="' + this[6] + '" alt="img"></div>' +
                  '<div class="text" style="width: calc(100% - 59px); float: left; padding-left: 10px; font-size: 12px; color: #2a2929;">' +
                  '<a style="color: #ff471a; margin-bottom: 5px;" class="title" href="' + this[4] + '">' + this[3] + '</a>' +
                  'Адрес: ' + this[0] +
                  '<br>Телефон: ' + this[5] +
                  '</div>'
              }, {
                iconLayout: 'default#image',
                iconImageHref: '/local/templates/vuchebe/images/' + this[7],
                iconImageSize: [29, 39],
                iconImageOffset: [-14, -40]
              });
              geoObjects.push(geo);

            });
          }
          if (vuzId.length > 0) {
            clusterer.add(geoObjects);
            myMap.geoObjects.add(clusterer);

            myMap.setBounds(clusterer.getBounds(), {
              checkZoomRange: true
            });
          }
        }
      }
    });

  }

  $('#map-menu').on('click', '.js-map', function (e) {
    e.preventDefault();

    get_map();

    return false;
  });


  //-------------------------------------- Top Panel ---------------------------------------------//

  $('#popup').on('click dblclick', '.js-counry-top', function (e) {
    e.preventDefault();

    $this = $(this);

    let idTab = $this.data('id');
    let nameTab = $this.find('span').text();
    let setCookies = 0;
    let map = 0;

    if ($this.hasClass('active') || e.type == 'dblclick')
      setCookies = 1;

    if ($('.general-map #map').length)
      map = 1;

    if (e.type == 'dblclick')
      $('.st-select-city.popup, .foneBg-2').fadeOut(200);

    $.ajax({
      type: 'POST',
      url: '/ajax/panel_country.php',
      data: { 'id': idTab, 'set_cookies': setCookies },
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          $('.js-counry-top').removeClass('active');
          $('.js-counry-top:contains(' + $this.text() + ')').addClass('active');
          if (!result.res.CNT_CITY) {
            $('#popup .list > .container').hide();
          } else {
            let html = '';

            $('#popup .list-city').empty();

            if (result.res.CITY_TOP.length) {
              html = '';
              $.each(result.res.CITY_TOP, function (index, value) {
                if (result.res.CITY_ACTIVE == value.ID) {
                  html += '<a href="#" style="margin: 0 14px 5px 0;" data-id="' + value.ID + '" class="js-city-top js-city-top-' + value.ID + ' active">' + value.NAME + '</a>';
                } else {
                  html += '<a href="#" style="margin: 0 14px 5px 0;" data-id="' + value.ID + '" class="js-city-top js-city-top-' + value.ID + '">' + value.NAME + '</a>';
                }
              });
              $('#popup .list-city').append(html);
            }

            $('#popup .list-abc').empty();

            html = '';
            $.each(result.res.ABC, function (index, value) {
              if (result.res.ABC_ACTIVE == value) {
                html += '<a href="#" style="margin: 0 14px 5px 0;" data-id="' + value + '" class="js-abc-top active">' + value + '</a>';
              } else {
                html += '<a href="#" style="margin: 0 14px 5px 0;" data-id="' + value + '" class="js-abc-top">' + value + '</a>';
              }
            });
            $('#popup .list-abc').append(html);

            $('#title-abc').text(result.res.ABC_ACTIVE);
            $('#panel-line').empty();

            html = '<div class="item-city">';
            let numRow = 0;
            $.each(result.res.CITY, function (index, value) {
              if (result.res.CITY_ACTIVE == value.ID) {
                html += '<a href="#" data-id="' + value.ID + '" class="js-list-top js-list-top-' + value.ID + ' active"><span>' + value.NAME + '</span></a>';
              } else {
                html += '<a href="#" data-id="' + value.ID + '" class="js-list-top js-list-top-' + value.ID + '"><span>' + value.NAME + '</span></a>';
              }
              numRow = numRow + 1;
              if (numRow == 12) {
                html += '</div>';
                html += '<div class="item-city">';
                numRow = 0;
              }
            });

            html += '</div>';
            $('#panel-line').append(html);

            $('#popup .list > .container').show();
            $('#name-city.name-city a').show();
            $('#name-city-top.name-city a').show();
          }
          $('#name-city.name-city a').text($this.text());
          $('#name-city-top.name-city a').text($this.text());

          if (map)
            get_map();

          if ((setCookies && !map) || chatPage) {
            document.location.reload(true);
          }

        }
      }
    });

    return false;
  });

  $('#popup').on('click', '.js-abc-top', function (e) {
    e.preventDefault();

    $this = $(this);

    let idTab = $('#popup .js-counry-top.active').data('id');
    let idAbc = $this.data('id');

    $.ajax({
      type: 'POST',
      url: '/ajax/panel_abc.php',
      data: { 'id': idTab, 'id_abc': idAbc },
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          $('.js-abc-top').removeClass('active');
          $this.addClass('active');
          $('#title-abc').text(idAbc);
          $('#panel-line').empty();

          let html = '<div class="item-city">';
          let numRow = 0;
          $.each(result.res.CITY, function (index, value) {
            if (result.res.CITY_ACTIVE == value.ID) {
              html += '<a href="#" data-id="' + value.ID + '" class="js-list-top js-list-top-' + value.ID + ' active"><span>' + value.NAME + '</span></a>';
            } else {
              html += '<a href="#" data-id="' + value.ID + '" class="js-list-top js-list-top-' + value.ID + '"><span>' + value.NAME + '</span></a>';
            }
            numRow = numRow + 1;
            if (numRow == 12) {
              html += '</div>';
              html += '<div class="item-city">';
              numRow = 0;
            }
          });

          html += '</div>';
          $('#panel-line').append(html);
        }
      }
    });

    return false;
  });

  $('#popup').on('click', '.js-list-top, .js-city-top', function (e) {
    e.preventDefault();

    $this = $(this);

    let idTab = $('#popup .js-counry-top.active').data('id');
    let idCity = $this.data('id');
    let nameCity = '';
    let map = 0;

    if ($this.find('span').text())
      nameCity = $this.find('span').text();
    else
      nameCity = $this.text();

    if ($('.general-map #map').length)
      map = 1;

    $.ajax({
      type: 'POST',
      url: '/ajax/panel_city.php',
      data: { 'id': idTab, 'id_city': idCity, 'city_name': nameCity },
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          $('.js-list-top').removeClass('active');
          $('.js-list-top-' + idCity).addClass('active');

          $('.js-city-top').removeClass('active');
          $('.js-city-top-' + idCity).addClass('active');

          $('#name-city.name-city a').text(nameCity);
          $('#name-city-top.name-city a').text(nameCity);

          $('.st-select-city.popup, .foneBg-2').fadeOut(200);

          if (map)
            get_map();

          if (frontPage || chatPage)
            document.location.reload(true);
        }
      }
    });

    return false;
  });

  $('#popup').on('keyup', '#search-location', function (e) {
    e.preventDefault();

    $this = $(this);

    let idTab = $('#popup .js-counry-top.active').data('id');
    let searchStr = $this.val();

    if (searchStr.length < 3)
      return false;

    $.ajax({
      type: 'POST',
      url: '/ajax/panel_search.php',
      data: { 'id': idTab, 'search': searchStr },
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          if (result.res.CITY.length) {
            $('.js-abc-top').removeClass('active');
            $('.js-abc-top:contains(' + result.res.ABC + ')').addClass('active');
            $('#title-abc').text(result.res.ABC);
            $('#panel-line').empty();

            let html = '<div class="item-city">';
            let numRow = 0;
            $.each(result.res.CITY, function (index, value) {
              if (result.res.CITY_ACTIVE == value.ID) {
                html += '<a href="#" data-id="' + value.ID + '" class="js-list-top js-list-top-' + value.ID + ' active"><span>' + value.NAME + '</span></a>';
              } else {
                html += '<a href="#" data-id="' + value.ID + '" class="js-list-top js-list-top-' + value.ID + '"><span>' + value.NAME + '</span></a>';
              }
              numRow = numRow + 1;
              if (numRow == 12) {
                html += '</div>';
                html += '<div class="item-city">';
                numRow = 0;
              }
            });

            html += '</div>';
            $('#panel-line').append(html);
          }
        }
      }
    });

    return false; //
  });

  $('.js-cookies-button').on('click', function (e) {
    e.preventDefault();

    $this = $(this);

    $.ajax({
      type: 'POST',
      url: '/ajax/hide_cookies.php',
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          $('.js-cookies-banner').fadeOut();
        }
      }
    });

    return false;
  });

  $('.line, #top-banner-list, #banner-list').on('mouseenter', '.js-hide-banner', function (e) {
    e.preventDefault();

    let curText = $(this).text();
    if (curText == 'реклама') {
      $(this).text('скрыть');
    }
    return false;
  });

  $('.line, #top-banner-list, #banner-list').on('mouseleave', '.js-hide-banner', function (e) {
    e.preventDefault();

    let curText = $(this).text();
    if (curText == 'скрыть') {
      $(this).text('реклама');
    }
    return false;
  });

  $('.line, #top-banner-list, #banner-list').on('click', '.js-hide-banner', function (e) {
    e.preventDefault();

    $this = $(this);

    let curText = $this.text();
    if (curText != 'скрыто') {

      let id = $this.parent().find('.js-click-banner').data('id');

      $.ajax({
        type: 'POST',
        url: '/ajax/hide_banner.php',
        data: { 'id': id },
        dataType: 'json',
        success: function (result) {
          if (result.status == 'success') {
            $this.next().slideUp('.image.brd');
            $this.text('скрыто');
          }
        }
      });

    } else {
      $(this).next().slideDown('.image.brd');
      $(this).text('реклама');
    }
    return false;
  });

  $('.line, #top-banner-list, #banner-list').on('click', '.js-click-banner', function (e) {
    e.preventDefault();

    $this = $(this);

    let id = $this.data('id');

    $.ajax({
      type: 'POST',
      url: '/ajax/click_banner.php',
      data: { 'id': id },
      dataType: 'json',
      success: function (result) {
        if (result.status == 'success') {
          window.open(result.res);
          //location = result.res;
        }
      }
    });

    return false;
  });

});// document ready

function chat_loop(owner, from) {

  let arrNoShow = [];
  let strNoShow = '';

  let noShow = $('#chat .chat-right .message_chat.no_show');
  if (noShow.length > 0) {
    let chatId = 0;
    $.each(noShow, function () {
      chatId = $(this).data('id');
      arrNoShow.push(chatId);
    });
    strNoShow = arrNoShow.join(',');
  }

  $.ajax({
    type: 'POST',
    url: '/ajax/chat_update.php',
    data: { 'owner_id': owner, 'from_id': from, 'no_show': strNoShow },
    dataType: 'json',
    success: function (result) {
      if (result.status) {
        if (result.status == 'success') {
          if (result.update.length > 0) {
            $.each(result.update, function (index, value) {

              let html = ''
              const timeLine = $('div').is('#time-line')

              if (!timeLine) {
                html += `
								<div class="line-today" id="time-line" style="height: 1px; border-top: 1px solid #ff4719; position: relative; top: 0px; text-align: center; margin-top: 35px;">
									<div style="display: inline-block; padding: 5px 15px; background-color: #ffffff; position: relative; top: -14px;">Сегодня</div>
								</div>
							`;
              }

              html += '<div class="chat-left" data-res="' + value.id + '">';
              html += '<div class="message_chat_wrapper">';
              html += '<div class="message_chat_user">';
              html += '<a href="/user/' + value.id + '/">' + value.format_name + '</a> <span style="color: gray;">' + value.str_time + '</span>';
              if (value.online) {
                html += '<div style="display: inline-block; position: relative; top: -1px; margin-left: 2px; width: 8px; height: 8px; border-radius: 50%; background-color: #ff471a;" title="В сети"></div>';
              }
              html += '</div>';
              if (value.teacher) {
                html += '<img class="avatar_chat" src="' + value.avatar_url + '" style="border: 2px solid #ff5b32;">';
              } else {
                html += '<img class="avatar_chat" src="' + value.avatar_url + '" style="border: 1px solid #ff5b32;">';
              }
              html += '<img style="right: -7px; z-index: 1;" class="avatar_duz" src="/upload/main/ug_left_3.png">';
              html += '<div class="message_chat" style="margin-left: 6px; position: relative;"><div class="del-mes-left js-del" style="bottom: 25px; right: ' + value.book_css + ';" data-type="' + value.book_type + '" data-id="' + value.id + '" data-owner="' + owner + '" data-from="' + from + '" data-pos="left">' + value.book_name + '</div><div class="del-mes-left js-del" style="bottom: 12px; right: ' + value.spam_css + ';" data-type="' + value.spam_type + '" data-spam="' + value.spam_data + '" data-id="' + value.id + '" data-owner="' + owner + '" data-from="' + from + '">' + value.spam_name + '</div><div class="del-mes-left js-del" style="bottom: -1px; right: -60px;" data-type="post" data-id="' + value.id + '" data-owner="' + owner + '" data-from="' + from + '">удалить</div>' + value.message + '</div>';
              html += '</div>';
              html += '</div>';
              $('#chat').append(html);
            });
            $("#chat").animate({ scrollTop: $("#chat").prop("scrollHeight") }, "slow");
          }
          if (result.show.length > 0) {
            $.each(result.show, function () {
              $('#chat-id-' + this.id).removeClass('no_show');
              $('#chat-id-' + this.id).next().attr('src', '/upload/main/ug_right_3.png');
            });
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

function chat_loop_group() {

  const group = parseInt($('#form-group-chat-post .chat-id').val());
  const type = $('#group-filter.m-header .color-silver').data('filter');

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
    url: '/ajax/chat_update_group.php',
    data: { load, group },
    dataType: 'json',
    success: function (result) {
      if (result.status) {
        if (result.status == 'success') {
          if (result.update.length > 0) {
            $.each(result.update, function () {

              const timeLine = $('div').is('#time-line')

              if (!timeLine) {
                html += `
								<div class="line-today" id="time-line" style="height: 1px; border-top: 1px solid #ff4719; position: relative; top: 0px; text-align: center; margin-top: 35px;">
									<div style="display: inline-block; padding: 5px 15px; background-color: #ffffff; position: relative; top: -14px;">Сегодня</div>
								</div>
							`;
              }

              if (type == 'online')
                displayNone = this.online ? '' : ' style="display: none;"';

              if (type == 'admin')
                displayNone = this.admin ? '' : ' style="display: none;"';

              if (type == 'user')
                displayNone = this.user ? '' : ' style="display: none;"';

              if (type == 'teacher')
                displayNone = this.teacher ? '' : ' style="display: none;"';

              html += `
								<div data-res="${this.id}" class="chat-left online-user-${this.userid} all${this.class}"${displayNone}>
									<div class="message_chat_wrapper">
										<div class="message_chat_user">
											<a href="/user/${this.userid}/">${this.displayname}</a> <span style="color: gray;">${this.time}</span>
											${this.online ? '<div style="display: inline-block; position: relative; top: -1px; margin-left: 2px; width: 8px; height: 8px; border-radius: 50%; background-color: #ff471a;" title="В сети"></div>' : ''}
										</div>
										<img class="avatar_chat" src="${this.avatar}" ${this.teacher ? 'style="border: 2px solid #ff5b32;"' : 'style="border: 1px solid #ff5b32;"'} />
										<img style="right: -7px; z-index: 1;" class="avatar_duz" src="/upload/main/ug_left_3.png">
										<div class="message_chat" style="margin-left: 1px; position: relative;"><div class="del-mes-left js-del" style="bottom: 25px; right: -79px;" data-type="bookmark" data-id="${this.id}" data-owner="${this.userid}" data-from="${this.usermain}" data-pos="left">в закладки</div><div class="del-mes-left js-del" style="bottom: 12px; right: -40px;" data-type="7" data-spam="0" data-id="${this.id}" data-owner="${this.userid}" data-from="0">спам</div><!--<div class="del-mes-left js-del-group-post" style="bottom: -1px; right: -60px;" data-type="post" data-id-post="${this.id}" data-owner="${this.userid}" data-chat="${group}">удалить</div>-->${this.message}</div>
									</div>
								</div>
                `;
              $('#chat').append(html);
            });
            $("#chat").animate({ scrollTop: $("#chat").prop("scrollHeight") }, "slow");
          }

          $('#chat div.all').removeClass('online');

          $.each(result.online, function () {
            $('#chat div.online-user-' + this.ID).addClass('online');
          });

          calculateGroupUser()

          $('#group-filter div.st-baloon.online-baloon').empty();

          let showBaloon = 4;
          if(result.online.length > 4) {
            showBaloon = 3;
          }

          let htmlBaloon = ``;

          let en = 0;
          $.each(result.online, function () {
            if(en >= showBaloon) {
              htmlBaloon += `<div class="more-baloon"><span data-type="group-chat" data-chat="${group}" data-type-user="online" style="margin-left: 10px; font-size: 10px; top: 12px; position: relative;">ещё</span></div>`;
              return false;
            } else {
              en = en + 1;
            }
            htmlBaloon += `
                <a href="/user/${this.ID}/">
                    <div class="image">
                        <img style="height: 22px;" src="${this.avatar}" alt="${this.FULL_NAME}" title="${this.FULL_NAME}">
                    </div>
                </a>`;
          });
          
          $('#group-filter div.st-baloon.online-baloon').append(htmlBaloon);
        } else {
          $("#error-message").text(result.message);
          $("#error-message").show();
        }
      }
    }
  });
  return false;
}

let cntNew = 0;
let cntNewSupport = 0;

function chat_loop_new() {

  $.ajax({
    type: 'POST',
    url: '/ajax/chat_update_new.php',
    dataType: 'json',
    success: function (result) {
      if (result.status) {
        if (result.status == 'success') {
          if (result.new > 0) {
            if (result.sound > cntNew) {
              var audio = new Audio(); // Создаём новый элемент Audio
              audio.src = '/note.mp3'; // Указываем путь к звуку "клика"
              audio.autoplay = true; // Автоматически запускаем

              cntNew = result.sound;
            }
            $('#new-chat').css('display', 'inline-block');
          } else {
            $('#new-chat').css('display', 'none');
          }

          if (result.newSupport > 0) {
            if (result.soundSupport > cntNewSupport) {
              var audio = new Audio(); // Создаём новый элемент Audio
              audio.src = '/note.mp3'; // Указываем путь к звуку "клика"
              audio.autoplay = true; // Автоматически запускаем

              cntNewSupport = result.soundSupport;
            }
            $('#new-support').css('display', 'inline-block');
          } else {
            $('#new-support').css('display', 'none');
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

$(function () {

  $("#wr-tabs").on("click", ".tab", function () {

    var tabs = $("#wr-tabs .tab"),
      cont = $("#wr-tabs .tab-cont");

    // Удаляем классы active
    tabs.removeClass("active");
    cont.removeClass("active");
    // Добавляем классы active
    $(this).addClass("active");
    cont.eq($(this).index()).addClass("active");

    return false;
  });
});

function close_form() {

  $('.hideForm, .hideForm2, .hideForm3, .hideForm4, .hideForm-vuz-edit, .hideForm-news-edit, .popup-section,.popup-section > div, .st-select-city.popup, .foneBg-2, .hideForm-setting, .hideForm-group-chat, .hideForm-support-chat, .hideForm-avatar-big, .hideForm-avatar, .hideForm-admins, .hideForm-vacancies, .hideForm-balance, .hideForm-back, .foneBg, .hideForm-tarif, .hideForm-banner-info, .hideForm-ugolok, .hideForm-top-banner, .hideForm-side-banner').fadeOut(250);

}

function close_form_ug() {
  const type = $('.hideForm-avatar.avatar #upload-input').data('avatar')
  $('.hideForm-avatar.avatar').fadeOut(250)
  if(type == 'user')
    $('.hideForm-avatar.avatar .foneBg').fadeOut(250)

}

function mask() {

  $.mask.definitions['~'] = '[+-]';

  $('#date').mask('99/99/9999');

  $('input.phone').mask('+7(999) 999-9999');

  $('#phoneext').mask("(999) 999-9999? x99999");

  $("#tin").mask("99-9999999");

  $("#ssn").mask("999-99-9999");

  $("#product").mask("a*-999-a999");

  $("#eyescript").mask("~9.99 ~9.99 999");

}

$(function () {

  $(window).scroll(function () {

    if ($(this).scrollTop() >= 400) {

      $('#toTop').fadeIn(800);

    } else {

      $('#toTop').fadeOut(800);

    }

  });

  $('#toTop').click(function () {

    $('body,html').animate({ scrollTop: 0 }, 800);

  });

});

function click_button() {
  $('span.button, span.button-post, .st-setting .user-name a.popup-login, .js-noauth').click(function (e) {
    e.preventDefault();

    $(window).scrollTop(0);
    var top_form = $(window).scrollTop();
    var height_form = $('.form-open-block form').height();
    var marg_top = $(window).height() / 2;

    $('.form-open-block').css({
      'height': $(window).height(),
      'position': 'absolute',
      'top': top_form,
    });
    $('.hideForm').css({
      'height': $(document).height(),
    });

    $('.foneBg').css({ 'display': 'block' });

    $('.hideForm').fadeIn(250);

    return false;
  });
}

function browser_name() {
  var browser_id = navigator.userAgent;
  // перечень условий
  if (browser_id.search(/Chrome/) != -1) return 'Google Chrome';
  if (browser_id.search(/Firefox/) != -1) return 'Firefox';
  if (browser_id.search(/Opera/) != -1) return 'Opera';
  if (browser_id.search(/Safari/) != -1) return 'Safari';
  if (browser_id.search(/MSIE/) != -1) return 'Internet Explorer';

  return 'Не определен';
}

var browser = browser_name();

if (browser == "Google Chrome") {
  // document.write('<style>.content{border:10px solid #fff;}</style>');
  $('body').addClass('Chrome');

}
else if (browser == "Firefox") {
  // document.write('<style>.content{border:10px solid #fff;}</style>');
  $('body').addClass('Firefox');

}
if (browser == "Safari") {
  document.write('<link rel="stylesheet" href="css/safary.css">');
  $('body').addClass('Safari');

}

var errMess = 'Заполните поле';

$('.js-submit').on('click', function () {


  let name = $('#form1 .name').val();
  let pass = $('#form1 .pass').val();

  if (name.length == 0 || name == errMess) {
    $('#form1 .name').css('color', 'red');
    $('#form1 .name').val(errMess);
    return false;
  }

  if (pass.length == 0 || pass == errMess) {
    $('#form1 .pass').attr('type', 'text');
    $('#form1 .pass').css('color', 'red');
    $('#form1 .pass').val(errMess);
    return false;
  }

  $('#form1').submit();
  return false;
});

$('#form1 .name').on('focus', function () {
  let name = $('#form1 .name').val();
  if (name == errMess) {
    $('#form1 .name').css('color', '#a7a7a7');
    $('#form1 .name').val('');
  }
  return false;
});

$('#form1 .pass').on('focus', function () {
  let pass = $('#form1 .pass').val();
  if (pass == errMess) {
    $('#form1 .pass').attr('type', 'password');
    $('#form1 .pass').css('color', '#a7a7a7');
    $('#form1 .pass').val('');
  }
  return false;
});
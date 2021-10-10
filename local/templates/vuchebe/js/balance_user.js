$(document).ready(function() {

	$('#balance').on('keyup', '.js-balance', function(e) {
		e.preventDefault()

		const $this = $(this)

		const strValue = $this.val()
		const intValue = strValue.replace(/[\D]+/g, '')

		$this.val(intValue)

		return false
	});

	$('#balance .js-balance').on('focus', function(){
		$('#balance span.js-error-sum').css('color', '#9f9f9f')
		return false
	});

	$('#balance').on('change', '.js-law', function(e){
		e.preventDefault();

		if($("#balance .js-law").is(':checked')) {
			$("#balance .law-text").slideUp();
		} else {
			$("#balance .law-text").slideDown();
		}

		return false;
	});

	$('#balance').on('click', '.js-balance-submit', function(e){
		e.preventDefault()

		const sumBalance = parseInt($('#balance .js-balance').val())


		if(sumBalance <= 0 || !Number.isInteger(sumBalance)){
			$('#balance span.js-error-sum').css('color', 'red')
			return false;
		}

		if(!$("#balance .js-law").is(':checked')) {
			$("#balance .law-text").slideDown();
			return false;
		} else {
			$("#balance .law-text").hide();
		}

		$.ajax({
			type: 'POST',
			url: '/ajax/balance.php',
			data: {'sum':sumBalance, 'type':'add'},
			dataType: 'json',
			success: function(result){
				if (result.status) {
					if (result.status=='success'){
						$('#balance div.balance-num').text(result.balance)
						$('.js-top-balance').text(result.balance)
						$('#balance div.balance-free').text(result.free)
						$('#balance').data('max', result.free)
						$('#balance div.balance-hold').text(result.hold)
						$('#balance .js-balance').val('')

						const html = `
							<div class="row-line new" style="margin-bottom: 10px; font-size: 15px; display: none;">
								<div class="col-3">${ result.date }</div>
								<div class="col-3" style="color: green;">+${ sumBalance } руб.</div>
								<div class="col-6">Пополнение денежных средств</div>
							</div>
						`;

						$('#balance #balance-list').prepend(html)

						const top_form = $(window).scrollTop()
						const height_form = $('.hideForm-balance .form-open-block form').height()
						const marg_top = $(window).height()/2

						$('.hideForm-balance .form-open-block').css({'height': $(window).height(),
							'position': 'absolute',
							'top':top_form,
						});
						$('.hideForm-balance').css({'height':$(document).height()});

						$('.foneBg').css({'display':'block'})

						$('.hideForm-balance').fadeIn(250);

						setTimeout(function() {
							close_form();

							if($('#balance #balance-list-name').is(':hidden')) {
								$('#balance #balance-list-name').slideDown(500)
								$('#balance #balance-list-table').slideDown(500)

								setTimeout(function () {
									$('#balance #balance-list .row-line.new').slideDown()
									$('#balance #balance-list .row-line.new').removeClass('new')
								}, 500)
							} else {
								$('#balance #balance-list .row-line.new').slideDown()
								$('#balance #balance-list .row-line.new').removeClass('new')
							}
						}, 2500);
					} else {
						$("#error-message").text(result.message);
						$("#error-message").show();
					}
				}
			}
		});

		return false;
	});

	$('#balance').on('click', '.js-check-submit', function(e){
		e.preventDefault()

		const sumBalance = parseInt($('#balance .js-balance').val())


		if(sumBalance <= 0 || !Number.isInteger(sumBalance)){
			$('#balance span.js-error-sum').css('color', 'red')
			return false;
		}

		if(!$("#balance .js-law").is(':checked')) {
			$("#balance .law-text").slideDown();
			return false;
		} else {
			$("#balance .law-text").hide();
		}

		$.ajax({
			type: 'POST',
			url: '/ajax/balance_check.php',
			data: {'sum':sumBalance, 'type':'add'},
			dataType: 'json',
			success: function(result){
				if (result.status) {
					if (result.status=='success'){
						$('#balance div.balance-num').text(result.balance)
						$('.js-top-balance').text(result.balance)
						$('#balance div.balance-free').text(result.free)
						$('#balance').data('max', result.free)
						$('#balance div.balance-hold').text(result.hold)
						$('#balance .js-balance').val('')

						const html = `
							<div class="row-line new" style="margin-bottom: 10px; font-size: 15px; display: none;">
								<div class="col-3">${ result.date }</div>
								<div class="col-3" style="color: #9f9f9f;">${ parseFloat(sumBalance).toFixed( 2 ) } руб.</div>
								<div class="col-6">Заявка на выставление счёта</div>
							</div>
						`;

						$('#balance #balance-list').prepend(html)

						const top_form = $(window).scrollTop()

						$('.hideForm-balance .form-open-block').css({'height': $(window).height(),
							'position': 'absolute',
							'top':top_form,
						});
						$('.hideForm-balance').css({'height':$(document).height()});

						$('.hideForm-balance #textBlock').text(result.message);

						$('.foneBg').css({'display':'block'})

						$('.hideForm-balance').fadeIn(250);

						setTimeout(function() {
							close_form();

							if($('#balance #balance-list-name').is(':hidden')) {
								$('#balance #balance-list-name').slideDown(500)
								$('#balance #balance-list-table').slideDown(500)

								setTimeout(function () {
									$('#balance #balance-list .row-line.new').slideDown()
									$('#balance #balance-list .row-line.new').removeClass('new')
								}, 500)
							} else {
								$('#balance #balance-list .row-line.new').slideDown()
								$('#balance #balance-list .row-line.new').removeClass('new')
							}
						}, 2500);
					} else {
						$("#error-message").text(result.message);
						$("#error-message").show();
					}
				}
			}
		});

		return false;
	});

	$('#balance').on('click', '.balance-back', function(e){
		e.preventDefault()

		const top_form = $(window).scrollTop()
		const height_form = $('.hideForm-back .form-open-block form').height()
		const marg_top = $(window).height()/2

		$('.hideForm-back.back .form-open-block').css({'height': $(window).height(),
			'position': 'absolute',
			'top':top_form,
		});
		$('.hideForm-back.back').css({'height':$(document).height()});

		const maxSum = $('#balance').data('max')
		$('.hideForm-back.back .js-max-sum').text(maxSum)

		$('.hideForm-back.back .js-max-sum-div').css('color', '#000000')
		$('.hideForm-back.back span.js-error-sum').css('color', '#9f9f9f')

		$('.hideForm-back.back .js-start').show()
		$('.hideForm-back.back .js-end').hide()

		$('.foneBg').css({'display':'block'})

		$('.hideForm-back.back').fadeIn(250);

		return false;
	});

	$('.hideForm-back.back').on('change', '.js-law', function(e){
		e.preventDefault();

		if($(".hideForm-back.back .js-law").is(':checked')) {
			$(".hideForm-back.back .law-text").slideUp();
		} else {
			$(".hideForm-back.back .law-text").slideDown();
		}

		return false;
	});

	$('.hideForm-back.back').on('keyup', '.js-balance', function(e) {
		e.preventDefault()

		const $this = $(this)

		const strValue = $this.val()
		const intValue = strValue.replace(/[\D]+/g, '')

		$this.val(intValue)

		return false
	});

	$('.hideForm-back.back .js-balance').on('focus', function(){
		$('.hideForm-back.back .js-max-sum-div').css('color', '#000000')
		$('.hideForm-back.back span.js-error-sum').css('color', '#9f9f9f')
		return false
	});

	$('.hideForm-back.back').on('click', '.js-balance-submit', function(e){
		e.preventDefault()

		const sumBalance = parseInt($('.hideForm-back.back .js-balance').val())
		const maxSum = $('#balance').data('max')


		if(sumBalance <= 0 || !Number.isInteger(sumBalance)){
			$('.hideForm-back.back span.js-error-sum').css('color', 'red')
			return false;
		}

		if(sumBalance > maxSum){
			$('.hideForm-back.back .js-max-sum-div').css('color', 'red')
			return false;
		}

		if(!$(".hideForm-back.back .js-law").is(':checked')) {
			$(".hideForm-back.back .law-text").slideDown();
			return false;
		} else {
			$(".hideForm-back.back .law-text").hide();
		}

		$.ajax({
			type: 'POST',
			url: '/ajax/balance.php',
			data: {'sum':sumBalance, 'type':'hold'},
			dataType: 'json',
			success: function(result){
				if (result.status) {
					if (result.status=='success'){
						$('#balance div.balance-num').text(result.balance)
						$('.js-top-balance').text(result.balance)
						$('#balance div.balance-free').text(result.free)
						$('#balance').data('max', result.free)
						$('#balance div.balance-hold').text(result.hold)

						$('#balance .balance-back-del').show()

						$('.hideForm-back.back .js-balance').val('')
						$('.hideForm-back.back .js-start').hide()
						$('.hideForm-back.back .js-end').show()

						const html = `
							<div class="row-line new" style="margin-bottom: 10px; font-size: 15px; display: none;">
								<div class="col-3">${ result.date }</div>
								<div class="col-3" style="color: #9f9f9f;">-${ result.sum } руб.</div>
								<div class="col-6">Запрос на возврат денежных средств</div>
							</div>
						`;

						$('#balance #balance-list').prepend(html)

						setTimeout(function() {
							close_form();

							if($('#balance #balance-list-name').is(':hidden')) {
								$('#balance #balance-list-name').slideDown(500)
								$('#balance #balance-list-table').slideDown(500)

								setTimeout(function () {
									$('#balance #balance-list .row-line.new').slideDown()
									$('#balance #balance-list .row-line.new').removeClass('new')
								}, 500)
							} else {
								$('#balance #balance-list .row-line.new').slideDown()
								$('#balance #balance-list .row-line.new').removeClass('new')
							}

						}, 2500);

					} else {
						$("#error-message").text(result.message);
						$("#error-message").show();
					}
				}
			}
		});

		return false;
	});

	$('#balance').on('click', '.balance-back-del', function(e){
		e.preventDefault()

		$.ajax({
			type: 'POST',
			url: '/ajax/balance.php',
			data: {'type':'del'},
			dataType: 'json',
			success: function(result){
				if (result.status) {
					if (result.status=='success'){
						$('#balance div.balance-num').text(result.balance)
						$('.js-top-balance').text(result.balance)
						$('#balance div.balance-free').text(result.free)
						$('#balance').data('max', result.free)
						$('#balance div.balance-hold').text(result.hold)

						const html = `
							<div class="row-line new" style="margin-bottom: 10px; font-size: 15px; display: none;">
								<div class="col-3">${ result.date }</div>
								<div class="col-3" style="color: #9f9f9f;">+${ result.sum } руб.</div>
								<div class="col-6">Отмена возврата денежных средств</div>
							</div>
						`;

						$('#balance #balance-list').prepend(html)

						if($('#balance #balance-list-name').is(':hidden')) {
							$('#balance #balance-list-name').slideDown(500)
							$('#balance #balance-list-table').slideDown(500)

							setTimeout(function () {
								$('#balance #balance-list .row-line.new').slideDown()
								$('#balance #balance-list .row-line.new').removeClass('new')
							}, 500)
						} else {
							$('#balance #balance-list .row-line.new').slideDown()
							$('#balance #balance-list .row-line.new').removeClass('new')
						}

						$('#balance .balance-back-del').hide()

					} else {
						$("#error-message").text(result.message);
						$("#error-message").show();
					}
				}
			}
		});

		return false;
	});

}); // document ready
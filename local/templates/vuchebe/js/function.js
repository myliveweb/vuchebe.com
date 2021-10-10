async function postData(url = '', data = {}, method = 'POST') {
	console.log('Fetch started... URL:', url, method, 'Data:', data)
	try {
		const response = await fetch(url, {
			method: method,
			mode: 'cors',
			cache: 'no-cache',
			credentials: 'same-origin',
			headers: {
				'Content-Type': 'application/json'
			},
			redirect: 'follow',
			referrerPolicy: 'no-referrer',
			body: JSON.stringify(data)
		})
		return await response.json()
	} catch(e) {
		console.error('Произошла ошибка:', e)
	}
}

function openPopup(selector) {
	let top_form = $(window).scrollTop();
	let height_form = $(selector + ' .form-open-block form').height();
	let marg_top = $(window).height()/2;

	$(selector + ' .form-open-block').css({'height': $(window).height(),
											'position': 'absolute',
											'top':top_form,
											});
	$(selector).css({'height':$(document).height(),});
}

function goButton(thisContext, appContext) {
	let outHtml = '';
	outHtml += `<div class="btns text-right" style="margin-top: 9px; text-align: left; position: relative;">`;
	if(thisContext['GO_USER'].length) {
		if(thisContext['GO_USER'].length > 4) {
			total = 3;
		} else {
			total = 4;
		}
		outHtml += `<div class="st-baloon">`;
		thisContext['GO_USER'].forEach(function(item, i) {
			if(i >= total) {
				outHtml += `
				<div class="more-baloon">
					<span data-id-vuz="${appContext['ID']}" data-type="events" data-id="${appContext['ID_OPENDOOR']}" data-hash="go">ещё</span>
				</div>`;
				return false;
			} else {
				outHtml += `
				<a href="/user/${item['ID']}/"${thisContext['USER'] ? `` : ` class="js-noauth"`}>
					<div class="image">
						<img style="height: 22px;" src="${item['AVATAR']}" alt="${item['NAME']}" title="${item['NAME']}">
					</div>
				</a>`;
			}
		});
		outHtml += `</div>`;
	}
	outHtml += `
			<a href="#" data-lk="0" class="button ${thisContext['USER'] ? `js-event-go` : `js-noauth`} b-right${thisContext['GO'] ? ` active` : ``}" style="position: relative; right: 0px; top: 0px;">
				<span style="text-decoration: none;">Я пойду (${thisContext['GO_USER'].length})</span>
			</a>
		</div>`;

	return outHtml;
}

function likeButton(thisContext, appContext) {
	let outHtml = '';
	outHtml += `<div class="btns text-right" style="margin-top: 9px; text-align: left; position: relative;">`;
	if(thisContext['LIKE_USER'].length) {
		if(thisContext['LIKE_USER'].length > 4) {
			total = 3;
		} else {
			total = 4;
		}
		outHtml += `<div class="st-baloon">`;
		thisContext['LIKE_USER'].forEach(function(item, i) {
			if(i >= total) {
				outHtml += `
				<div class="more-baloon">
					<span data-id-vuz="${appContext['ID']}" data-type="events" data-id="${appContext['ID_OPENDOOR']}" data-hash="like">ещё</span>
				</div>`;
				return false;
			} else {
				outHtml += `
				<a href="/user/${item['ID']}/"${thisContext['USER'] ? `` : ` class="js-noauth"`}>
					<div class="image">
						<img style="height: 22px;" src="${item['AVATAR']}" alt="${item['NAME']}" title="${item['NAME']}">
					</div>
				</a>`;
			}
		});
		outHtml += `</div>`;
	}
	outHtml += `
			<a href="#" data-my="${thisContext['LIKE_ON']}" data-cnt="${thisContext['LIKE_USER'].length}" class="button ${thisContext['USER'] ? `js-event-left` : `js-noauth`}${thisContext['LIKE_ON'] ? ` active` : ``}" style="position: relative; left: 0px; top: 0px;">
				<span style="text-decoration: none;"><i class="fa fa-thumbs-o-up" style="margin-right: 7px;"></i>${thisContext['LIKE_USER'].length}</span>
			</a>
		</div>`;

	return outHtml;
}

function deslikeButton(thisContext, appContext) {
	let outHtml = '';
	outHtml += `<div class="btns text-right" style="margin-top: 9px; text-align: left; position: relative;">`;
	if(thisContext['DESLIKE_USER'].length) {
		if(thisContext['DESLIKE_USER'].length > 4) {
			total = 3;
		} else {
			total = 4;
		}
		outHtml += `<div class="st-baloon">`;
		thisContext['DESLIKE_USER'].forEach(function(item, i) {
			if(i >= total) {
				outHtml += `
				<div class="more-baloon">
					<span data-id-vuz="${appContext['ID']}" data-type="events" data-id="${appContext['ID_OPENDOOR']}" data-hash="like">ещё</span>
				</div>`;
				return false;
			} else {
				outHtml += `
				<a href="/user/${item['ID']}/"${thisContext['USER'] ? `` : ` class="js-noauth"`}>
					<div class="image">
						<img style="height: 22px;" src="${item['AVATAR']}" alt="${item['NAME']}" title="${item['NAME']}">
					</div>
				</a>`;
			}
		});
		outHtml += `</div>`;
	}
	outHtml += `
			<a href="#" data-my="${thisContext['DESLIKE_ON']}" data-cnt="${thisContext['DESLIKE_USER'].length}" class="button ${thisContext['USER'] ? `js-event-right` : `js-noauth`}${thisContext['DESLIKE_ON'] ? ` active` : ``}" style="position: relative; left: 0px; top: 0px;">
				<span style="text-decoration: none;"><i class="fa fa-thumbs-o-down" style="margin-right: 7px;"></i>${thisContext['DESLIKE_USER'].length}</span>
			</a>
		</div>`;

	return outHtml;
}

function timeLine(thisContext) {
	let outHtml = '';
	if(thisContext['sort'] < curTime && todayLine > 0) {
		todayLine = 0;
		if(nLine > 0) {
			outHtml += `
			<div class="line-today today-${thisContext['TYPE']}"  style="height: 1px; border-top: 1px solid #ff4719; position: relative; top: -21px; text-align: center;">
				<div style="display: inline-block; padding: 5px 15px; background-color: #ffffff; position: relative; top: -14px;">Сегодня</div>
			</div>`;
		}
	}
	nLine = nLine + 1;

	return outHtml;
}
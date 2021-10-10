<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/pages.css">
<link rel="stylesheet" href="/user/groupchat/main.css">
<style>
    #chat .message_chat_wrapper .message-chat-system {
        color: gray;
        font-size: 11px;
        cursor: default;
        text-align: center;
        margin-bottom: 25px;
    }
    #chat .message_chat_wrapper .message-chat-system a {
        border-bottom: 1px dashed #9f9f9f;
        color: #9f9f9f;
        text-decoration: none;
        padding-bottom: 1px;
        transition: all 0.5s linear;
    }
    #chat .message_chat_wrapper .message-chat-system a:hover {
        border-bottom: none;
    }
</style>
<div class="hideForm spam">
	<div class="foneBg" onClick="close_form();"></div>
  	<div class="form-open-block">
	  	<form id="form-spam" method="post" action="" style="width: 670px;">
			<div style="padding-bottom: 20px; max-height: 600px; overflow: auto; cursor: default;" id="spam-line" class="line">
				<div class="name_form text-center"><span>Чат</span></div>
                <div class="contact-form bg-silver" style="margin: 0px auto;">
                    <div class="row-line">
                        <div class="col-12">
                            <div id="chat" style="overflow-x:hidden;">

                            </div>
                        </div>
                    </div>
                    <div class="row-line">
                        <div class="params-banner-top col-12" id="button-box" style="margin-top: 15px; text-align: left;">
                            <a class="color-silver js-spam-button del-avatar" data-type="del-avatar" data-id="" data-user="">Удалить аватар</a>
                            <a class="color-silver js-spam-button reject" data-type="reject" data-id="" data-user="">Отклонить жалобу</a>
                            <a class="color-silver js-spam-button warning" data-type="warning" data-id="" data-user="">Предупреждение</a>
                            <a class="color-silver js-spam-button ban" data-type="ban" data-id="" data-user="">Бан</a>
                            <a class="color-silver js-spam-button del-user" data-type="del-user" data-id="" data-user="">Удалить пользователя</a>
                            <a class="color-silver js-spam-button del-post" data-type="del-post" data-id="" data-user=">" data-post="">Удалить сообщение</a>
                            <a class="color-silver js-spam-button del-chat" data-type="del-chat" data-id="" data-user="" data-post="">Удалить чат</a>
                            <a class="color-silver js-spam-button deactivate" data-type="deactivate" data-id="" data-user="">Удалить жалобу</a>
                            <a class="color-silver js-new-chat js-new-support" data-type="ticket" data-id="" data-user="">Новая заявка</a>
                        </div>
                    </div>
                </div><!-- contact-form -->
                <a href="javascript:void(0);" class="close" onclick="close_form();"></a>
			</div>
	  	</form>
  	</div>
</div><!-- hideForm spam -->
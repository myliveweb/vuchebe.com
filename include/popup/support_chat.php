<style>
.radio input {
    position: absolute;
    z-index: -1;
    opacity: 0;
    margin: 10px 0 0 7px;
}
.radio__text {
    position: relative;
    padding: 6px 0 0 35px;
    cursor: pointer;
}
.radio__text:before {
    content: '';
    position: absolute;
    top: -3px;
    left: 0;
    width: 22px;
    height: 22px;
    border: 1px solid #9f9f9f;
    border-radius: 50%;
    background: #FFF;
}
.radio input:checked + .radio__text:after {
    opacity: 1;
}
.radio input:disabled + .radio__text:after {
    opacity: .5;
}
.radio__text:after {
    content: '';
    position: absolute;
    top: 1px;
    left: 4px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: #ff4719;
    box-shadow: inset 0 1px 1px rgba(0,0,0,.5);
    opacity: 0;
    transition: .2s;
}
.radio-text,
.offline-text,
.color-text {
	color: #9f9f9f;
	margin-top: 15px;
	display: none;
}
.del-text,
.block-user,
.change-pass {
    cursor: pointer;
    border-bottom: 1px dashed #9f9f9f;
    color: #9f9f9f;
}
.warning-text {
    margin-left: 15px;
    color: #9f9f9f;
    margin-top: 15px;
    display: none;
}
.del-action {
    cursor: pointer;
    border-bottom: 1px dashed red;
    color: red;
}

.user-name-st,
.user-name-te {
  display: inline-block;
}
.user-name-st img,
.user-name-te img,
.auto-complit .item img {
  width: 22px;
  height: 22px;
  border-radius: 50%;
}
.user-name-st img {
  border: 1px solid #ff5b32;
}
.user-name-te img {
  border: 2px solid #ff5b32;
}
.user-name-st.name {
  margin-left: 10px;
  position: relative;
  top: -4px;
}
.user-name-st.check {
  float: right;
}
#form-support-chat input {
  color: #000000;
}
.user-name-st span.js-del-user-chat {
  cursor: pointer;
  border-bottom: 1px dashed #9f9f9f;
  position: relative;
  top: 3px;
  margin-right: 80px;
}
#form-support-chat .js-error-block {
  margin-left: 10px;
  font-size: 13px;
  display: none;
}
#form-support-chat .js-clear-div {
  text-align: center; 
  margin: 5px; 
  display: none;
}
#form-support-chat .js-clear-div span {
  cursor: pointer; 
  border-bottom: 1px dashed #9f9f9f; 
  position: relative; 
  top: 12px;
}

#form-support-chat .profile-avatar {
    width: auto;
    height: auto;
}

.auto-complit .item {
    height: 38px;
}
.auto-complit .item div {
    display: inline-block;
}
.auto-complit .item .user img {
    border: 1px solid #ff5b32;
}
.auto-complit .item .teacher img {
    border: 2px solid #ff5b32;
}
.auto-complit .item div.name {
    margin-left: 10px;
    position: relative;
    top: -4px;
}
.auto-complit .item div.book {
    position: relative;
    top: 5px;
    float: right;
    border-bottom: 1px dashed #9f9f9f;
    color: #9f9f9f;
}
.auto-complit .item div.book.hide {
    display: none;
}
    [type="file"] {
        border: 0;
        clip: rect(0, 0, 0, 0);
        height: 1px;
        overflow: hidden;
        padding: 0;
        position: absolute !important;
        white-space: nowrap;
        width: 1px;
    }

    [type="file"] + label {
        border: none;
        color: #fff;
        cursor: pointer;
        display: inline-block;
        font-family: 'Poppins', sans-serif;
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 1rem;
        outline: none;
        padding: 1rem 0rem;
        position: relative;
        transition: all 0.3s;
        vertical-align: middle;
        border-radius: 50px;
        overflow: hidden;
        width: 100%;
        max-width: 738px;
    }

    [type="file"]:focus + label,
    [type="file"] + label:hover {
        outline: none;
    }

    .m-header .filter {
        color: #ff471a;
    }
    .m-header .filter.color-silver {
        color: #9f9f9f;
        text-decoration: none;
        cursor: default;
    }
</style>
<?php
$nameDisplay = trim($USER->GetFirstName());
if(trim($USER->GetSecondName()))
  $nameDisplay .= ' ' . trim($USER->GetSecondName());
if(trim($USER->GetLastName()))
  $nameDisplay .= ' ' . trim($USER->GetLastName());

if (strlen($nameDisplay) <= 0)
  $nameDisplay = $USER->GetLogin();

$classAvatar = 'user-name-st';
if($_SESSION['USER_DATA']['TEACHER'])
  $classAvatar = 'user-name-te';

?>

<?php
$url = getUserUrl($_SESSION['USER_DATA']);
?>

<div class="hideForm-support-chat group-chat" style="display: none;">
	<div class="foneBg" onClick="close_form();"></div>
  	<div class="form-open-block">
	  	<form id="form-support-chat" method="post" action="">
			<div>
				<div class="name_form text-center"><span>Настройки новой заявки</span></div>
				<div id="error-message-setting" style="color: red; font-weight: bold; font-size: 20px; height: 24px; margin-bottom: 15px; display: none;"></div>
        <div class="row-line mt-15">
          <div class="col-12">
            <div class="label" style="display: inline-block;">Участник тикета</div><div style="display: inline-block;"><span class="color-orange js-error-block js-error-group-user"></span></div>
          </div>
        </div>
        <div id="group-user" style="margin-top: 15px;">
          <div class="row-line mt-10 user-group-chat owner" data-id="<?php echo $_SESSION['USER_DATA']['ID']; ?>">
            <div class="col-12">
              <div class="user-name-st name">
                  Отсутствует (необходимо добавить)
              </div>
            </div>
          </div>
        </div>
        <div class="row-line mt-15" style="margin-top: 20px;">
          <div class="col-12">
            <div class="label" style="display: inline-block;">Добавить участника</div>
              <label class="radio" style="display: inline-block; margin-left: 30px; position: relative;">
                  <input class="js-in-book" type="checkbox" name="in_book" value="1" checked />
                  <div class="radio__text">Вкл.\Выкл.</div>
              </label>
            <input class="js-add-user" name="add_user" type="text" value="" placeholder="Ведите имя" />
            <div class="auto-complit" style="overflow: auto;"></div>
          </div>
        </div>
        <div class="row-line mb-10 mt-15 css-btn" style="margin-top: 25px;">
          <div class="col-4">
            <button type="button" class="js-submit-group-chat" data-form="group-chat" data-id="0" data-owner="<?php echo $_SESSION['USER_DATA']['ID']; ?>"><span>Сохранить</span></button>
          </div>
          <div class="col-4">
              <button class="gray" type="button" style="background-color: #a7a7a7;" onclick="close_form();"><span>Отмена</span></button>
          </div>
          <div class="col-4">
            <div class="js-clear-div">
              <span class="color-silver js-del-group-post" data-type="post" data-id-post="0" data-owner="<?php echo $_SESSION['USER_DATA']['ID']; ?>" data-chat="0">Очистить чат</span>
            </div>
          </div>
        </div>
        <a href="javascript:void(0);" class="close" onclick="close_form();"></a>
      </div>
    </form>
  </div>
</div><!-- hideForm-news-edit ring -->
<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$APPLICATION->SetTitle("Дети");
?>
<div class="row">
    <div class="col-md-9">
        <div class="signin-h mb20">Данные о ребенке</div>
        <div class="WYSIWYG">
            <p>Заполните анкету, чтобы мы могли предложить лучшее для Вашего юного исследователя и скидку ко дню рождения!</p>
        </div>
    </div>
</div>
<div class="js-child-block">
    <div class="account-kid__wrap mb35 js-child-wrap">
        <div class="row child-row js-child-list">
            <?
            $double = [];
            $arSelect = Array("ID","PROPERTY_birthday", "PROPERTY_name", "PROPERTY_education", "PROPERTY_grade", "PROPERTY_subjects", "PROPERTY_sex");
            $arFilter = Array("PROPERTY_parent" => $USER->GetID(),"IBLOCK_ID"=>3);
            $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
            while ($ob = $res->GetNextElement()) {
                $subjects = array();
                $arItem = $ob->GetFields();

                if (in_array($arItem['ID'],$double)) continue;
                $arItems[] = $arItem;
                ?>

                <?$double[] = $arItem['ID'];
            } ?>
            <? foreach ($arItems as $arItem):?>

                <div class="col-md-6 col-sm-6 col-xs-12 account-plate__wrap child-wrap js-child-card" data-child-id="<?=$arItem['ID']?>">
                    <div class="account-plate">
                        <span class="btn-remove remove js-child-action" data-action-type="delete"></span>
                        <?
                        $date1 = new DateTime($arItem['PROPERTY_BIRTHDAY_VALUE']);
                        $date2 = new DateTime();
                        $diff = $date2->diff($date1);
                        $age = $diff->y;
                        $yearDeclension = new Bitrix\Main\Grid\Declension('год', 'года', 'лет');
                        $age_string = $yearDeclension->get($age); ?>
                        <div class="signin-h"><?= $arItem['PROPERTY_NAME_VALUE'] ?>
                            , <?= $age ?> <?= $age_string ?>
                            (<?= $arItem['PROPERTY_BIRTHDAY_VALUE'] ?>)
                        </div>
                        <? if (isset($arItem['PROPERTY_EDUCATION_VALUE'])): ?>
                            <div class="signin-name">Где учится</div>
                            <div class="signin-name__text">
                                <?= $arItem['PROPERTY_EDUCATION_VALUE'] ?>
                                <?=(isset($arItem['PROPERTY_GRADE_VALUE']) && $arItem['PROPERTY_EDUCATION_VALUE'] === "Школа") ? ', ' . $arItem['PROPERTY_GRADE_VALUE'] . ' класс' : ''?>
                            </div>
                        <? endif; ?>
                        <? if (isset($arItem['PROPERTY_SUBJECTS_VALUE']) && $arItem['PROPERTY_EDUCATION_VALUE'] === "Школа"): ?>
                            <?php
                            $subjects = array();
                            $db_props = CIBlockElement::GetProperty(3, $arItem['ID'], array("sort" => "asc"), Array("CODE"=>"SUBJECTS"));
                            while($ar_props = $db_props->Fetch()){
                                $subjects[] =  $ar_props['VALUE_ENUM'];
                            }?>
                            <div class="signin-name">Любимые предметы</div>
                            <div class="signin-name__text"><?=  implode(', ',$subjects) ?></div>
                        <? endif; ?>
                        <a class="link-underline js-child-action" data-action-type="edit" href="#edit">Редактировать</a>
                    </div>
                </div>
            <?endforeach;?>
        </div>
    </div>

    <div class="row js-child-more">
        <div class="col-md-12">
            <button class="btn btn-default js-child-action" data-action-type="add">Добавить еще ребенка</button>
        </div>
    </div>

    <div class="hidden js-child-temp">
        <form class="form" action="" role="form">
            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group js-input-container">
                        <div class="radio-item radio-item--inline">
                            <input id="child_male" name="sex[]" type="radio" value="1" checked>
                            <label for="child_male">Мальчик</label>
                        </div>
                        <div class="radio-item radio-item--inline">
                            <input id="child_female" name="sex[]" type="radio" value="2">
                            <label for="child_female">Девочка</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group js-input-container">
                        <input class="form-control" name="name[]" type="text" required>
                        <span class="form-control--name">Имя ребенка*</span>
                    </div>
                    <div class="form-group js-input-container">
                        <input class="form-control form-datapicker" name="birthday[]" type="text" onkeydown="return false" id="birthday_1">
                        <span class="btn btn-transparent btn-promo btn-calendar" data-id="birthday_1">
                                <span class="ico ico--nav ico--calendar">
                                    <object>
                                        <svg viewBox="0 0 24 24">
                                            <use class="sprite-ico sprite-ico--gray" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/i/sprite.svg#icon_calendar" x="0" y="0"></use>
                                        </svg>
                                    </object>
                                </span>
                            </span>
                        <span class="form-control--name">Дата рождения*</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group js-input-container">
                        <select class="form-control js-select-form" name="study[]">
                            <option value="Школа" data-study-type="school">Школа</option>
                            <option value="Детский сад" data-study-type="kindergarten">Детский сад</option>
                        </select>
                        <span class="form-control--name">Где учится</span>
                    </div>
                    <div class="form-group js-input-container js-input-type" data-for-study="school">
                        <select class="form-control js-select-form" name="grade[]">
                            <option value="1">1 класс</option>
                            <option value="2">2 класс</option>
                            <option value="3">3 класс</option>
                            <option value="4">4 класс</option>
                            <option value="5">5 класс</option>
                            <option value="6">6 класс</option>
                            <option value="7">7 класс</option>
                            <option value="8">8 класс</option>
                            <option value="9">9 класс</option>
                            <option value="10">10 класс</option>
                            <option value="11">11 класс</option>
                        </select>
                        <span class="form-control--name">Класс</span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group check-item__list js-input-container js-input-type" data-for-study="school">
                        <div class="check-item check-item--label">Любимые предметы*</div>
                        <div class="check-item check-item--inline">
                            <input id="personal-kid-info-01" name="subjects[]" value="3" type="checkbox">
                            <label for="personal-kid-info-01">Математика</label>
                        </div>
                        <div class="check-item check-item--inline">
                            <input id="personal-kid-info-02" name="subjects[]" value="4"  type="checkbox">
                            <label for="personal-kid-info-02">Окружающий мир</label>
                        </div>
                        <div class="check-item check-item--inline">
                            <input id="personal-kid-info-03" name="subjects[]" value="5" type="checkbox">
                            <label for="personal-kid-info-03">Биология</label>
                        </div>
                        <div class="check-item check-item--inline">
                            <input id="personal-kid-info-04" name="subjects[]" value="6"  type="checkbox">
                            <label for="personal-kid-info-04">Физика</label>
                        </div>
                        <div class="check-item check-item--inline">
                            <input id="personal-kid-info-05" name="subjects[]" value="7"  type="checkbox">
                            <label for="personal-kid-info-05">Химия</label>
                        </div>
                        <div class="check-item check-item--inline">
                            <input id="personal-kid-info-06" name="subjects[]" value="8"  type="checkbox">
                            <label for="personal-kid-info-06">Затрудняюсь ответить</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <input type="checkbox" id="child-callback">
                    <label for="child-callback" class="">Я согласен с <a class="link-underline" href="javascript:;" data-fancybox data-type="ajax"
                            data-src="/include/popup/policy.php">политикой обработки персональных данных</a></label>
                </div>
            </div>

            <div class="row mt35">
                <div class="col-sm-4 col-xs-12">
                    <div class="form-group">
                        <button class="btn btn-block btn-neon js-child-save" data-save-type="add" disabled>Сохранить</button>
                    </div>
                </div>
                <div class="col-sm-4 col-xs-12">
                    <div class="form-group">
                        <button class="btn btn-block btn-default js-child-cancel">Отменить</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
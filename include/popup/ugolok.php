<?php
$img = SITE_TEMPLATE_PATH . "/images/noimage-2.png";
?>
<style>
 #form-ugolok input,
 #form-ugolok textarea {
     color: #303030;
 }
.st-tags-block .tag-ugolok {
    display: inline-block;
    padding: 0 20px;
    border-radius: 5px;
    border: 1px solid #b4b4b4;
    line-height: 30px;
    margin: 5px;
}

.st-tags-block .tag-ugolok.active {
    border: 1px solid #ff4719;
    color: #ff4719;
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
     //border-radius: 50px;
     overflow: hidden;
     width: 100%;
     max-width: 738px;
 }

 [type="file"]:focus + label,
 [type="file"] + label:hover {
     outline: none;
 }
</style>
<div class="hideForm-ugolok ugolok" style="display: none;">
	<div class="foneBg" onClick="close_form();"></div>
  	<div class="form-open-block">
	  	<form id="form-ugolok" method="post" action="" style="width: 580px;">
            <input type="hidden" class="js-element" value="" />
			<div style="padding-bottom: 20px; max-height: 600px; overflow: auto;" id="box-line" class="line">
				<div class="name_form text-center"><span>Редактирование Уголок знаний</span></div>
                <div class="row-line mb-10 mt-15">
                    <div class="col-3">
                       <div class="image brd" style="width: 122px;">
                           <input type="file" id="avatarUgolok" data-type="ugolok" accept="image/*">
                           <label for="avatarUgolok" style="top: -14px; margin-bottom: 0;">
                               <img id="ugolok-avatar" class="js-img" style="cursor: pointer; width: 100%;" src="<?php echo $img; ?>" alt="">
                           </label>
                       </div>
                    </div>
                    <div class="col-9">
                        <div>
                            <div class="label">Название</div>
                            <input class="js-name" type="text">
                        </div>
                        <div style="margin-top: 10px;">
                            <div class="label">Анонс</div>
                            <textarea class="js-anonnce"></textarea>
                        </div>
                        <div style="margin-top: 10px;">
                            <div class="label">Подпись для анонса</div>
                            <input class="js-anonnce-sign" type="text">
                        </div>
                    </div>
                </div>
                <div class="row-line mb-10 mt-15">
                    <div class="col-12 links" style="position: relative;">
                        <i class="ico wik" style="position: absolute; top: 26px; left: 22px;"></i>
                        <div class="label">Википедия</div>
                        <input class="js-wiki" type="text" style="padding: 0 15px 0 30px;" placeholder="Вставьте ссылку">
                    </div>
                </div>
                <div class="row-line mb-10 mt-15">
                    <div class="col-12">
                        <div class="label">Текст статьи</div>
                        <textarea class="js-text"></textarea>
                    </div>
                </div>
                <div class="row-line mb-10 mt-15">
                    <div class="col-12">
                        <div class="st-content-bottom clear">
                            <div class="st-tags-block">
                                <?
                                $SectList = CIBlockSection::GetList(array("SORT"=>"ASC"), array("IBLOCK_ID"=>5, "ACTIVE"=>"Y") ,false, array("ID","IBLOCK_ID","CODE","NAME","SECTION_PAGE_URL"));
                                while($SectListGet = $SectList->GetNext())
                                {
                                    ?>
                                    <a href="<?=$SectListGet['SECTION_PAGE_URL']?>" data-tag="<?php echo $SectListGet['ID']; ?>" class="tag-ugolok"><?=$SectListGet['NAME']?></a>
                                    <!-- select -->
                                    <?
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row-line mb-10 mt-15 css-btn">
                    <div class="col-3">
                        <button type="submit" class="js-submit-ugolok" data-form="ugolok"><span>Сохранить</span></button>
                    </div>
                    <div class="col-3">
                        <button type="button" style="background-color: #a7a7a7; margin-left: 10px;" class="js-abort gray" onclick="close_form();"><span>Отменить</span></button>
                    </div>
                </div>
			</div>
            <a href="javascript:void(0);" class="close" onclick="close_form();"></a>
	  	</form>
  	</div>
</div><!-- hideForm ugolok -->
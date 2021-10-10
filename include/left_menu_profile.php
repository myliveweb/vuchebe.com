<?php

$url = getUserUrl($_SESSION['USER_DATA']);

// Проверка является ли пользователь администратором в учебных заведениях
$arAdmins = array();
$arrFilter = array();

$arSelect = array("ID", "NAME", "IBLOCK_ID");
$arFilter = array("IBLOCK_ID" => array(2, 3, 4, 6), "ACTIVE" => "Y", "!PROPERTY_ADMINS" => false, "PROPERTY_ADMINS" => $_SESSION['USER_DATA']['ID']);
$res = CIBlockElement::GetList(array("ID" => "ASC"), $arFilter, false, false, $arSelect);
while ($row = $res->Fetch()) {
    $arAdmins[] = $row;
}
?>
<div class="left-column left">
	<div class="st-aside static">
		<div class="st-aside-menu">
			<form name="select_all" method="post">
				<div class="title">
				</div>
				<div class="st-select-search">
					<div class="select<? if(!$section && $user_id == $_SESSION['USER_DATA']['ID']) { ?> st-cheked<? } ?>">
						<div class="style-input">
 							<a href="/user/<?php echo $url; ?>/" class="a-label">
								<div>
									Моя страница
								</div>
 							</a>
						</div>
					</div>
					 <!-- select -->
					<div class="select<? if($section == 'dialogs') { ?> st-cheked<? } ?>">
						<div class="style-input">
 							<a href="/user/<?php echo $url; ?>/dialogs/" class="a-label">
								<div>
									Мои сообщения
								</div>
 							</a>
						</div>
					</div>
					 <!-- select -->
					<div class="select<? if($section == 'educations') { ?> st-cheked<? } ?>">
						<div class="style-input">
 							<a href="/user/<?php echo $url; ?>/educations/" class="a-label">
								<div>
									Мои учебные заведения
								</div>
 							</a>
						</div>
					</div>
					 <!-- select -->
					<div class="select<? if($section == 'bookmarks') { ?> st-cheked<? } ?>">
						<div class="style-input">
 							<a href="/user/<?php echo $url; ?>/bookmarks/" class="a-label">
								<div>
									Мои закладки
								</div>
 							</a>
						</div>
					</div>
					 <!-- select -->
					<div class="select<? if($section == 'events') { ?> st-cheked<? } ?>">
						<div class="style-input">
 							<a href="/user/<?php echo $url; ?>/events/" class="a-label">
								<div>
									Мои события
								</div>
 							</a>
						</div>
					</div>
					 <!-- select -->
                    <?php if(sizeof($arAdmins)) { ?>
					<div class="select<? if($section == 'admin') { ?> st-cheked<? } ?>">
						<div class="style-input">
 							<a href="/user/<?php echo $url; ?>/admin/" class="a-label">
								<div>
									Администратор
								</div>
 							</a>
						</div>
					</div>
					 <!-- select -->
                    <?php
                    }
                    if($btnSupport) {
                        ?>
                        <div class="select<? if($section == 'service' || $btnSupportActive) { ?> st-cheked<? } ?>">
                            <div class="style-input">
                                <a href="/user/<?php echo $url; ?>/service/" class="a-label">
                                    <div>
                                        Служба поддержки
                                    </div>
                                </a>
                            </div>
                        </div>
                        <!-- select -->
                        <?php
                    }
                    if(isEdit()) {
                        ?>
                        <div class="select<? if($section == 'control') { ?> st-cheked<? } ?>">
                            <div class="style-input">
                                <a href="/user/<?php echo $url; ?>/control/" class="a-label">
                                    <div>
                                        Заказы
                                    </div>
                                </a>
                            </div>
                        </div>
                        <!-- select -->
                        <div class="select<? if($section == 'check') { ?> st-cheked<? } ?>">
                            <div class="style-input">
                                <a href="/user/<?php echo $url; ?>/check/" class="a-label">
                                    <div>
                                        Выставление счёта
                                    </div>
                                </a>
                            </div>
                        </div>
                        <!-- select -->
                        <div class="select<? if($section == 'adduz') { ?> st-cheked<? } ?>">
                            <div class="style-input">
                                <a href="/user/<?php echo $url; ?>/adduz/" class="a-label">
                                    <div>
                                        Новое учебное заведение
                                    </div>
                                </a>
                            </div>
                        </div>
                        <!-- select -->
                        <div class="select<? if($section == 'reviews') { ?> st-cheked<? } ?>">
                            <div class="style-input">
                                <a href="/user/<?php echo $url; ?>/reviews/" class="a-label">
                                    <div>
                                        Отзывы
                                    </div>
                                </a>
                            </div>
                        </div>
                        <!-- select -->
                        <div class="select<? if($section == 'avatar') { ?> st-cheked<? } ?>">
                            <div class="style-input">
                                <a href="/user/<?php echo $url; ?>/avatar/" class="a-label">
                                    <div>
                                        Аватар
                                    </div>
                                </a>
                            </div>
                        </div>
                        <!-- select -->
                        <div class="select<? if($section == 'spam') { ?> st-cheked<? } ?>">
                            <div class="style-input">
                                <a href="/user/<?php echo $url; ?>/spam/" class="a-label">
                                    <div>
                                        СПАМ
                                    </div>
                                </a>
                            </div>
                        </div>
                        <!-- select -->
                        <div class="select<? if($section == 'refund') { ?> st-cheked<? } ?>">
                            <div class="style-input">
                                <a href="/user/<?php echo $url; ?>/refund/" class="a-label">
                                    <div>
                                        Возврат средств
                                    </div>
                                </a>
                            </div>
                        </div>
                        <!-- select -->
                        <?php
                    }
                    ?>
				</div>
			</form>
		</div>
	</div>
    <?php
    require($_SERVER["DOCUMENT_ROOT"].'/include/sidebanner.php');
    ?>
</div>
 <!-- left-column -->
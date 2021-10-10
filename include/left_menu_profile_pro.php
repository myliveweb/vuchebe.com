<?php

$url = getUserUrl($_SESSION['USER_DATA']);

?>
<style>
.st-content .st-aside::after {
	background: none;
}
</style>
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
					<div class="select<? if($section == 'orders') { ?> st-cheked<? } ?>">
						<div class="style-input">
 							<a href="/user/<?php echo $url; ?>/orders/" class="a-label">
								<div>
									Мои заказы
								</div>
 							</a>
						</div>
					</div>
					 <!-- select -->
					<div class="select<? if($section == 'neworder' || $section == 'topbanner' || $section == 'sidebanner') { ?> st-cheked<? } ?>">
						<div class="style-input">
 							<a href="/user/<?php echo $url; ?>/neworder/" class="a-label">
								<div>
									Оформление заказа
								</div>
 							</a>
						</div>
					</div>
					 <!-- select -->
                    <div class="select<? if($section == 'balance') { ?> st-cheked<? } ?>">
                        <div class="style-input">
                            <a href="/user/<?php echo $url; ?>/balance/" class="a-label">
                                <div>
                                    Денежные средства
                                </div>
                            </a>
                        </div>
                    </div>
                    <!-- select -->
                    <?php if($_SESSION['USER_DATA']['PRO_TYPE'] === 'U') { ?>
                    <div class="select<? if($section == 'check') { ?> st-cheked<? } ?>">
                        <div class="style-input">
                            <a href="/user/<?php echo $url; ?>/check/" class="a-label">
                                <div>
                                    Выставленные счета
                                </div>
                            </a>
                        </div>
                    </div>
                    <!-- select -->
                    <?php } ?>
					<div class="select<? if($section == 'service') { ?> st-cheked<? } ?>">
						<div class="style-input">
 							<a href="/user/<?php echo $url; ?>/service/" class="a-label">
								<div>
									Служба поддержки
								</div>
 							</a>
						</div>
					</div>
					 <!-- select -->
				</div>
			</form>
		</div>
	</div>
    <?php
    require($_SERVER["DOCUMENT_ROOT"].'/include/sidebanner.php');
    ?>
</div>
 <!-- left-column -->
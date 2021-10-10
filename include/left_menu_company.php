<?php
//echo '<pre>'; print_r($arrUri); echo '</pre>';
?>
<div class="left-column left">
	<div class="st-aside static">
		<div class="st-aside-menu">
			<form name="select_all" method="post">
			<div class="title"></div>
				<div class="st-select-search">
					<div class="select<?if($arrUri[2] == 'about'):?> st-cheked<?endif?>">
						<div class="style-input">
							<a href="/company/about/" class="a-label">
								<div>
									<span>О проекте</span>
								</div>
							</a>
						</div>
					</div><!-- select -->
					<div class="select<?if($arrUri[2] == 'feedback'):?> st-cheked<?endif?>">
						<div class="style-input">
							<a href="/company/feedback/" class="a-label">
								<div>
									<span>Обратная связь</span>
								</div>
							</a>
						</div>
					</div><!-- select -->
					<div class="select<?if($arrUri[2] == 'job'):?> st-cheked<?endif?>">
						<div class="style-input">
							<a href="/company/job/" class="a-label">
								<div>
									<span>Вакансии</span>
								</div>
							</a>
						</div>
					</div><!-- select -->
					<div class="select<?if($arrUri[2] == 'banner'):?> st-cheked<?endif?>">
						<div class="style-input">
							<a href="/company/banner/" class="a-label current">
								<div>
									<span>Реклама</span>
								</div>
							</a>
							<div class="sub-menu"<?if($arrUri[3] == 'user'):?> style="display: block;"<?endif?>>
								<a href="/company/banner/user/"<?if($arrUri[3] == 'user'):?> class="sub-current"<?endif?> style="line-height: 20px; padding: 5px 20px;">
									<span>Войти в бизнес-аккаунт</span>
								</a>
							</div>
						</div>
					</div><!-- select -->
					<div class="select<?if($arrUri[2] == 'law'):?> st-cheked<?endif?>">
						<div class="style-input">
							<a href="/company/law/" class="a-label current">
								<div>
									<span>Правовая информация</span>
								</div>
							</a>
                            <?php
                            $arrCode = array();
                            $arrLaw = array();
                            $arSelectLaw = array("ID", "NAME", "IBLOCK_ID", "CODE");
                            $arFilterLaw = array("IBLOCK_ID" => 41, "ACTIVE" => "Y", "PROPERTY_MENU" => "Y");
                            $resLaw = CIBlockElement::GetList(array("SORT" => "ASC", "ID" => "ASC"), $arFilterLaw, false, false, $arSelectLaw);
                            while($rowLaw = $resLaw->GetNext()) {
                                $arrCode[] = $rowLaw['CODE'];
                                $arrLaw[] = $rowLaw;
                            }
                            ?>
							<div class="sub-menu"<?if(in_array($arrUri[3], $arrCode)):?> style="display: block;"<?endif?>>
								<?php foreach ($arrLaw as $law) { ?>
                                <a href="/company/law/<?php echo $law['CODE']; ?>/"<?if($arrUri[3] == $law['CODE']):?> class="sub-current"<?endif?> style="line-height: 20px; padding: 5px 20px;">
									<span><?php echo $law['NAME']; ?></span>
								</a>
                                <?php } ?>
							</div>
						</div>
					</div><!-- select -->
				</div>
			</form>
		</div>
	</div>
    <?php
    require($_SERVER["DOCUMENT_ROOT"].'/include/sidebanner.php');
    ?>
</div><!-- left-column -->
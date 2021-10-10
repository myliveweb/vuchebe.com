<div class="left-column left">
	<div class="st-aside static">
		<div class="st-aside-menu">
			<form name="select_all" method="post">
				<div class="title">
				</div>
				<div class="st-select-search">
				<?
				$SectList = CIBlockSection::GetList(array("SORT"=>"ASC"), array("IBLOCK_ID"=>5, "ACTIVE"=>"Y") ,false, array("ID","IBLOCK_ID","CODE","NAME","SECTION_PAGE_URL"));
				while($SectListGet = $SectList->GetNext())
				{
				?>
					<div class="select<?if($SectListGet['CODE'] == $current_section) { ?> st-cheked<? } ?>">
						<div class="style-input">
 							<a href="<?=$SectListGet['SECTION_PAGE_URL']?>" class="a-label">
								<div>
									 <?=$SectListGet['NAME']?>
								</div>
 							</a>
						</div>
					</div>
					 <!-- select -->
				<?
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
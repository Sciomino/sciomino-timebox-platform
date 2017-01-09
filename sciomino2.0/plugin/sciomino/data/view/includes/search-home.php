<?php
if ($session['response']['param']['skin'] == "sciomino") {
	echo "<div style='float:left'><a class='logo' href='".$XCOW_B['url']."/'><img height='24px' src='".$XCOW_B['url']."/ui/skin/sciomino/gfx/logo_sciomino_transp.png' alt='Sciomino'/></a></div>";
	echo "<div style='float:left'><span id='SciominoForText'>".$XCOW_B['sciomino']['skin-name']."</span></div>";
	echo "<span clear='left'/>";
}
?>

<div id="Search">
	<form action="<?php echo $XCOW_B['url'] ?>/search" method="get" class="search search-people">
		<fieldset class="unite">
			<input class="text placeholder search-name" type="text" name="n" id="zoek_naam" value="<?php echo language('sciomio_text_search_person'); ?>" placeholder="<?php echo language('sciomio_text_search_person'); ?>" maxlength="32"/>
			<input class="submit united search-button" type="submit" value="&raquo;" />
		</fieldset>
	</form>
</div>

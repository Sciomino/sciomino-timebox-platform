<?php
$personen = $page == 'personen' ? 'class="active"' : '';
$kennis = $page == 'kennis' ? 'class="active"' : '';
$inzichten = $page == 'inzichten' ? 'class="active"' : '';
$verbind = $page == 'verbind' ? 'class="active"' : '';
#$profiel = $page == 'profiel' ? 'class="active"' : '';
?>
    <ul class="nav-main">
        <li><a <?php echo $personen; ?> href="<?php echo $XCOW_B['url'] ?>/"><?php echo language(sciomio_word_nav_search); ?></a></li>
        <li><a <?php echo $kennis; ?> href="<?php echo $XCOW_B['url'] ?>/browse"><?php echo language(sciomio_word_nav_browse); ?></a></li>
		<?php
		if ($XCOW_B['sciomino']['skin-insights'] == "yes") {
			echo "<li><a ".$inzichten." href='".$XCOW_B['url']."/insights'>".language(sciomio_word_nav_insights)."</a></li>";
		}
		?>
        <li><a <?php echo $verbind; ?> href="<?php echo $XCOW_B['url'] ?>/act?s[relevant]"><?php echo language(sciomio_word_nav_act); ?></a></li>
        <!--<li><a <?php echo $profiel; ?> href="<?php echo $XCOW_B['url'] ?>/view"><?php echo language(sciomio_word_nav_user); ?></a></li>-->
    </ul>


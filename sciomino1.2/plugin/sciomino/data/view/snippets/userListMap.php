<?php
$act = ' class="active"';
$view = $session['response']['param']['view'];

if ($view) {
    $listview = $view == 'list' ? $act : '';
    $mapview = $view == 'map' ? $act : '';
}
?>

<div class="section">

    <div class="views">
        <span class="label">Weergave</span>
        <ul>
            <li<?php echo $listview ?>><a class="modal" href="<?php echo $XCOW_B['url'] ?>/snippet/user-list-map?view=list&detail=<?php echo $session['response']['param']['detail']; ?>&<?php echo $session['response']['param']['focus']; ?>">Lijst</a></li>
            <li<?php echo $mapview ?>><a class="modal" href="<?php echo $XCOW_B['url'] ?>/snippet/user-list-map?detail=<?php echo $session['response']['param']['detail']; ?>&<?php echo $session['response']['param']['focus']; ?>">Kaart</a></li>
        </ul>
    </div>

<?php

	if ($session['response']['param']['view'] == "map") {
		echo "<div id='map' class='standalone-map'></div>";
		echo "<script type='text/javascript'>var MAP_JSON = new String('".$XCOW_B['url']."/snippet/search-detail?view=map&detail=".$session['response']['param']['detail']."&".$session['response']['param']['focus']."').toString();</script>";
		echo "<script src='/ui/js/ovimaps.js'></script>";
	}
	else {
		echo "<script>ScioMino.SearchDetail.load('".$session['response']['param']['detail']."','".$session['response']['param']['focus']."');</script>";
		echo "<div id='searchDetailWindow'></div>";
	}
?>

</div>

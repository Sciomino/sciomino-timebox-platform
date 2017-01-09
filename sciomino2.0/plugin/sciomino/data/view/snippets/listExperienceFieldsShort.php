<!-- Experience - short list-->

<?php
if (count($session['response']['param']['companyList']) > 0) {
	echo "<div class='paragraph_small'>";
	foreach ($session['response']['param']['companyList'] as $eKey => $eVal) {
		echo "<a href='".$XCOW_B['url']."/browse/experience?e[company]=".urlencode($eKey)."'>$eKey</a> ($eVal)<br/>\n";
	}
	echo "</div>\n";
}
?>

<?php
if (count($session['response']['param']['eventList']) > 0) {
	echo "<div class='paragraph_small'>";
	foreach ($session['response']['param']['eventList'] as $eKey => $eVal) {
		echo "<a href='".$XCOW_B['url']."/browse/experience?e[event]=".urlencode($eKey)."'>$eKey</a> ($eVal)<br/>\n";
	}
	echo "</div>\n";
}
?>

<?php
if (count($session['response']['param']['educationList']) > 0) {
	echo "<div class='paragraph_small'>";
	foreach ($session['response']['param']['educationList'] as $eKey => $eVal) {
		echo "<a href='".$XCOW_B['url']."/browse/experience?e[education]=".urlencode($eKey)."'>$eKey</a> ($eVal)<br/>\n";
	}
	echo "</div>\n";
}
?>

<?php
if (count($session['response']['param']['productList']) > 0) {
	echo "<div class='paragraph_small'>";
	foreach ($session['response']['param']['productList'] as $eKey => $eVal) {
		echo "<a href='".$XCOW_B['url']."/browse/experience?e[product]=".urlencode($eKey)."'>$eKey</a> ($eVal)<br/>\n";
	}
	echo "</div>\n";
}
?>


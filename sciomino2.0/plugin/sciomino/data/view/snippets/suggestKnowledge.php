<?php
echo "<table>\n";
foreach ($session['response']['param']['connectList'] as $connectKey => $connectVal) {
	echo "<tr><td><a onclick='javascript:ScioMino.SuggestKnowledge.update(\"".$connectVal['name']."\")'>".$connectVal['name']."</a></td></tr>\n";
}
echo "</table>\n";
?>


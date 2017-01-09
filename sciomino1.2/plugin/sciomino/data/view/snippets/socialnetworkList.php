<div class="paragraph_table">
<table>
<tr><th></th><th></th></tr>

<?php
foreach ($session['response']['param']['networkList'] as $network) {
	echo "<tr class='paragraph_tablerow'>";
	echo "<td class='paragraph_tabledata'>";
	echo "<b>"."{$network['title']}"."</b><br/>";
	echo "Je deelt hier: "."{$network['description']}"."<br/>";
	echo "Link: "."{$network['relation-self']}"."<br/>";
	echo "</td>";
	echo "<td class='paragraph_tabledata'>"."(<a href='javascript:ScioMino.SocialNetworkEdit.load({$network['Id']});'>bewerken</a> | <a href='javascript:ScioMino.SocialNetworkDelete.action({$network['Id']});'>verwijderen</a>)"."</td>";
	echo "</tr>\n";
}
?>

</table>
</div>

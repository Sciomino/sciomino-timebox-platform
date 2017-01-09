<html>
<head>
<title>upload done</title>
</head>
<?php
if ($session['response']['param']['reload']) {
	echo "<body onload='parent.ScioMino.Wizard.actionPhoto_callback_reload(\"{$session['response']['param']['status']}\");'>\n";
}
else {
	echo "<body onload='parent.ScioMino.Wizard.actionPhoto_callback(\"{$session['response']['param']['status']}\");'>\n";
}
?>
</body>
</html>


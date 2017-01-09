<html>
<head>
<title>upload done</title>
</head>
<?php
if ($session['response']['param']['reload']) {
	echo "<body onload='parent.ScioMino.User.actionProfile_callback_reload(\"{$session['response']['param']['status']}\");'>\n";
}
else {
	echo "<body onload='parent.ScioMino.User.actionProfile_callback(\"{$session['response']['param']['status']}\");'>\n";
}
?>
</body>
</html>


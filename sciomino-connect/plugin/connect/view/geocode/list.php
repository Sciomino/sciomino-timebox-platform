<?php

echo "{\"geo\":[";
$first = 1;
foreach ($session['response']['param']['geoCodeList'] as $geoCodeKey => $geoCodeVal) {
	if ($first) { $first = 0; }
	else { echo ","; }

 	echo "{";
   	echo "\"cc\":\"".$geoCodeVal['cc']."\", ";
   	echo "\"ca\":\"".$geoCodeVal['ca']."\", ";
   	echo "\"name\":\"".$geoCodeVal['name']."\", ";
   	echo "\"lat\":\"".$geoCodeVal['lat']."\",";
  	echo "\"lon\":\"".$geoCodeVal['lon']."\"";
  	echo "}";
}
echo "]}";
?>



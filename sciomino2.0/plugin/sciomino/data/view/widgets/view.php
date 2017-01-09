<?php
$css = "";
$html = "";

# output html & css in string, DON'T USE \n!!!
$html = str_replace(array("\n"), "", $html);
$css = str_replace(array("\n"), "", $css);
echo "SC_WL.setContent({\"html\":\"".$html."\" , \"css\":\"".$css."\"})";
?>


<?php
$css = "
.sc_wi {
	background: #FFF;
	color: #333;

	border: none;
	display: block;

	width: ".$session['response']['param']['width']."px;
	height: auto;
	margin: 10px 0px 10px 0px;
	padding: 0px;

	font-family: 'Helvetica Neue', Helvetica, Arial;
	font-style: normal;
	font-weight: normal;
	font-size: 16px;
	line-height: normal;
	letter-spacing: normal;
	word-spacing: normal;
	text-transform: normal;
	text-align: left;

	visibility: visible;
	z-index: auto;
	clear:both;
}
.sc_wi .sc_wi_outerarea {
	padding: 0px;
	border: 1px solid #eee;
	max-height: 248px;
	overflow: auto;
}
.sc_wi .sc_wi_innerarea {
	margin: 4px 0px;
	padding: 0px;
	font-size: 14px;
}
.sc_wi .sc_wi_footer {
	padding: 5px 15px;
	height: 40px;
	background-color: #eee;
	font-size: 14px;
    line-height: 16px;
}
.sc_wi .sc_wi_head {
	font-weight: bold;
    line-height: 16px;
	display: inline;
	vertical-align: top;
	position: relative;
}
.sc_wi .sc_wi_text {
	clear: left;
	color: #333;
}
.sc_wi .sc_wi_img {
	float: left;
	padding-right: 10px;
	padding-top: 4px;
}
.sc_wi .sc_wi_head .sc_wi_fn {
	color: #4987C8;
}
.sc_wi .sc_wi_link {
    border-top: 1px solid #eee;
    padding: 2px 15px;
    display: block;
    color: #4987C8;
    text-decoration: none;
}
.sc_wi .sc_wi_link:hover {
    background-color: #eee;
}
.sc_wi .sc_wi_link_footer {
	padding-top: 4px;
    display: block;
    color: #4987C8;
    text-decoration: none;
}
";

$html = "";
if (count($session['response']['param']['userList']) > 0) {

	$footerText = "Digg deeper";
	if ($session['response']['param']['language'] == "nl") {
		$footerText = "Verder zoeken";
	}

	$html .= "<div class='sc_wi'><div class='sc_wi_outerarea'>";

	foreach ($session['response']['param']['userList'] as $userKey => $userVal) {
		$html .= "<a class='sc_wi_link' target='_blank' href='".$XCOW_B['this_host'].$XCOW_B['url']."/view?user=".$userVal['Id']."'>";

		if (! isset($userVal['photo'])) { $userVal['photo'] = $XCOW_B['this_host'].$XCOW_B['url']."/ui/gfx/photo.jpg"; }
		else { $userVal['photo'] = str_replace("/upload/",$XCOW_B['this_host'].$XCOW_B['url']."/upload/32x32_", $userVal['photo']); }
		$html .= "<img class='sc_wi_img' alt='' src='".$XCOW_B['url'].$userVal['photo']."' width='32' height='32' border='0'>";

		$html .= "<div class='sc_wi_innerarea'>";

			$html .= "<div class='sc_wi_head'><span class='sc_wi_fn'>".$userVal['FirstName']." ".$userVal['LastName']."</span></div>";
			// nothing else...
			$html .= "<div class='sc_wi_text'>"."</div>";

		$html .= "</div>";
		$html .= "</a>";

	}
	$html .= "</div>";

	$html .= "<div class='sc_wi_footer'>";
	$html .= "<img class='sc_wi_img' alt='' src='".$XCOW_B['this_host'].$XCOW_B['url']."/ui/gfx/icon-sciomino.png' width='32' height='32' border='0'>";
	$html .= "<a class='sc_wi_link_footer' target='_blank' href='".$XCOW_B['this_host'].$XCOW_B['url']."/search?".$session['response']['param']['focus']."'>".$footerText."</a>";
	$html .= "</div>";
	
	$html .= "</div>";
}

# output html & css in string, DON'T USE \n!!!
$html = str_replace(array("\n"), "", $html);
$css = str_replace(array("\n"), "", $css);
echo "SC_WL.setContent({\"html\":\"".$html."\" , \"css\":\"".$css."\"})";
?>


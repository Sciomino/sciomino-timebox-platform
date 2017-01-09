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
	margin: 0px;
	padding: 0px;
	font-size: 14px;
}
.sc_wi .sc_wi_footer {
	padding: 5px 15px;
	border: 1px solid #eee;
	min-height: 40px;
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
	padding-top: 4px;
	margin-left: 42px;
}
.sc_wi .sc_wi_img {
	float: left;
	padding-right: 10px;
	padding-top: 4px;
}
.sc_wi .sc_wi_link {
    padding: 2px 15px;
    display: block;
    color: #4987C8;
    text-decoration: none;
}
.sc_wi .sc_wi_link:hover {
    background-color: #eee;
}
.sc_wi .sc_wi_link_footer {
    display: block;
	background-color: #fff;
    color: #4987C8;
    text-decoration: none;
}
.sc_wi .sc_wi_link_footer:hover {
    background-color: #eee;
}
";

$html = "";
$html .= "<div class='sc_wi'><a class='sc_wi_link_footer' target='_blank' href='".$XCOW_B['this_host'].$XCOW_B['url'].$session['response']['param']['buttonUrl']."'>";
$html .= "<div class='sc_wi_footer'>";
$html .= "<img class='sc_wi_img' alt='' src='".$XCOW_B['this_host'].$XCOW_B['url']."/ui/gfx/icon-sciomino.png' width='32' height='32' border='0'>";
$html .= "<div class='sc_wi_text'>".$session['response']['param']['buttonText']. "</div>";
$html .= "</div>";

$html .= "</a></div>";


# output html & css in string, DON'T USE \n!!!
$html = str_replace(array("\n"), "", $html);
$css = str_replace(array("\n"), "", $css);
echo "SC_WL.setContent({\"html\":\"".$html."\" , \"css\":\"".$css."\"})";
?>


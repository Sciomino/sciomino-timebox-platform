<?php

#
# cool, url rewrite function ;-)
#
function rewrite($url, $param) {

	global $XCOW_B;

	$rewrite = array();
	$matches = array();

	$rewrite['match'] = 0;

	foreach ($XCOW_B['rewrite'] as $rewrite_key => $rewrite_value) {
		if (preg_match ("/^".my_quote_forward($rewrite_key)."$/", $url, $matches)) {
			$rewrite['match'] = 1;

			# $rewrite['url'] = $rewrite_value['url'];
			$rewrite['url'] = preg_replace ("/^(.*)\/\\\$(.*)\/(.*)$/e", "'$1/'.\$matches[$2].'/$3'", $rewrite_value['url']);

			$rewrite['param'] = array();
			if (is_array($rewrite_value['param'])) {
				foreach ($rewrite_value['param'] as $param_key => $param_value) {
					if (preg_match ("/^\\\$(.*)$/", $param_value, $param_matches)) {
						$rewrite['param'][$param_key] = $matches[$param_matches[1]];
					}
					else {
						$rewrite['param'][$param_key] = $param_value;
					}
				}
			}
		}
	}

	return $rewrite;
}

?>

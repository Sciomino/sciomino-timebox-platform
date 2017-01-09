<?php

#
# post http example with XML response
#

# #
# # init
# #
# $url = "URL";
# $postHeader = array();
# $postParam = array();

# #
# # do post
# #
# $postParam['boe'] = "bah";
# $response = postResponse($url, $postHeader, $postParam);
# $head = $response[0];
# $body = $response[1];

# #
# # head (get cookie for next response)
# #
# $headers = parse_headers($head);
# $cookie = parse_cookie($headers);
# $postHeader[] = "Cookie: ".$cookie;

# #
# # body (read the body xml)
# #
# $bodyObj = new Xml2Php2();
# $bodyObj->startProcessing($body, 1);
# $bodyArray = $loginObj->getPhpArray();
	

#
# Connect with applicatie and db: drop $url en get $response.
#
function getResponse($url) {
        global $XCOW_B;

        $buffer = '';

		# get context
		$options = array();
		$options['http'] = array();
		$options['http']['method'] = 'GET';
        $options['http']['user_agent'] = 'xcow connector';
		// this would be nice to show content of HTTP errors, 
		// - but the fact that there is no content on an error is used widely in the system...
		// - so DON'T USE THIS on all calls
		// - instead create getResponseWithError() or something...
		// $options['http']['ignore_errors'] = 'TRUE';
		if ($XCOW_B['use_proxy']) {
			$options['http']['request_fulluri'] = 'TRUE';
			$options['http']['proxy'] = $XCOW_B['proxy'];
		}
		$context = stream_context_create($options);

		# read
        $fp = @fopen($url, "r", false, $context);

        if ($fp) {
                #while (!feof($fp)) {
                #        $buffer .= fgets($fp, 4096);
                #}
                $buffer = stream_get_contents($fp);

                fclose($fp);
        }

        if ($buffer === false) {
				$buffer = "";
		}
		else {
				$buffer = trim($buffer);
		}

        return $buffer;

}

function getResponseWithHeader($url, $headers) {
        global $XCOW_B;

		$head = array();
		$body = '';
		$response = array();

		# get context
		$options = array();
		$options['http'] = array();
		$options['http']['method'] = 'GET';
        $options['http']['user_agent'] = 'xcow connector';
		if ($XCOW_B['use_proxy']) {
			$options['http']['request_fulluri'] = 'TRUE';
			$options['http']['proxy'] = $XCOW_B['proxy'];
		}
		foreach ($headers as $header) {
			$options['http']['header'] .= $header."\r\n";
		}
		$context = stream_context_create($options);

		# read
		$fp = @fopen($url, 'r', false, $context);

        if ($fp) {
				$head = stream_get_meta_data($fp);

                #while (!feof($fp)) {
                #        $body .= fgets($fp, 4096);
                #}
                $body = stream_get_contents($fp);

                fclose($fp);
        }

        if ($body === false) {
				$body = "";
		}
		else {
				$body = trim($body);
		}

		$response[] = $head;
		$response[] = $body;

        return $response;

}

#
# Connect with application: drop url, headers en postParams en get $response
#
function postResponse($url, $headers, $params) {
        global $XCOW_B;

		$head = array();
		$body = '';
		$response = array();

		# post context
		$options = array();
		$options['http'] = array();
		$options['http']['method'] = 'POST';
        $options['http']['user_agent'] = 'xcow connector';
		$options['http']['header'] = 'Content-type: application/x-www-form-urlencoded'."\r\n";
		if ($XCOW_B['use_proxy']) {
			#linkedin wil deze niet bij POST en wel bij GET
			#$options['http']['request_fulluri'] = 'TRUE';
			$options['http']['proxy'] = $XCOW_B['proxy'];
		}
		foreach ($headers as $header) {
			$options['http']['header'] .= $header."\r\n";
		}
		if (is_array($params)) {
			$options['http']['content'] = http_build_query($params);
		}
		else {
			$options['http']['content'] = $params;
		}
		$context = stream_context_create($options);

		# read
		$fp = @fopen($url, 'r', false, $context);

        if ($fp) {
				$head = stream_get_meta_data($fp);

                #while (!feof($fp)) {
                #        $body .= fgets($fp, 4096);
                #}
                $body = stream_get_contents($fp);

                fclose($fp);
        }

        if ($body === false) {
				$body = "";
		}
		else {
				$body = trim($body);
		}

		$response[] = $head;
		$response[] = $body;

        return $response;

}

function parse_headers($head) {
	$headers = array();

	foreach ($head['wrapper_data'] as $header)  {
		list($attribute, $value) = split(':', $header);
		$headers[$attribute] = $value;
	}
	
	return ($headers);
}

function parse_cookie($headers) {

	$cookieParts = array();
	$cookieParts = split(';', $headers['Set-Cookie']);

	return $cookieParts[0];
}

?>

<?php

#
# Functions to check input
#
function noEmptyInput ($input) {
	$ok = 1;
	foreach ($input as $key) {
		if ($key == "") {
			$ok = 0;
			break;
		}
	}
	return $ok;
}

function makeString($string, $length) {
	return substr($string, 0, $length);
}

function makeStringString($string, $matches, $length) {
	$string = substr($string, 0, $length);
	if (in_array($string, $matches)) {
		return $string;
	}
	else {
		return "";
	}
}

function makeTimeString($string) {
	return strtotime($string);
}

function makeBoolString($string) {
	$bool = 0;
	if ($string == "yes" || $string == "1") {
		$bool = 1;
	} 
	return ($bool);
}

function makeIntString($string) {
	$int = 0;
	if (preg_match('/^\d+$/', $string) == 1) {
		$int = $string;
	}
	return $int;	
}

#
# Functions to strip slashes
# - used to unescape quotes, \' wordt '
#
function stripslashes_deep($value)
{
    if(isset($value)) {
	if (get_magic_quotes_gpc()) {
            $value = is_array($value) ?
                array_map('stripslashes_deep', $value) :
                stripslashes($value);
	}
    }
    return $value;
}

function stripslashes_keys($array) {
	$new_array = array();
	if (is_array($array)) {
		foreach ($array as $key => $val) {
			$newkey = stripslashes($key);
			if (is_array($val)) {
				$new_array[$newkey] = stripslashes_keys($val);
			}
			else {
				$new_array[$newkey] = $val;
			}
		}
	}
	return $new_array;
}

#
# Functions to strip tags
# - used to strip html tags, <a href="www.boe.nl">boe</a> wordt boe
#
function striptags_deep($value)
{
    if(isset($value)) {
	    $value = is_array($value) ?
		array_map('striptags_deep', $value) :
		strip_tags($value);
    }
    return $value;
}

function striptags_keys($array) {
	$new_array = array();
	if (is_array($array)) {
		foreach ($array as $key => $val) {
			$newkey = strip_tags($key);
			if (is_array($val)) {
				$new_array[$newkey] = striptags_keys($val);
			}
			else {
				$new_array[$newkey] = $val;
			}
		}
	}
	return $new_array;
}

#
# Functions to strip HTML tags
#
# depricated
# - fromUnicode is absolete, javascript now uses encodeURI
# - my_unquote is absolete, becauses controller now stripslashes for all input params
# - the controller now also strips tags for all input parameters
# - only htmlentities is necessary when outputting content, this is done with new function 'htmlTokens'.
#
function noSecureInput($input, $format) {
	$output = fromUnicode($input);
	$output = my_unquote($output);
	if ($format) {
		$output = nl2br($output);
	}
	return $output;
}

function secureInput($input, $format) {
	$output = fromUnicode($input);
	$output = my_unquote($output);
	$output = htmlspecialchars($output, ENT_QUOTES);
	if ($format) {
		$output = nl2br($output);
	}
	return $output;
}

function moreSecureInput($input, $format) {
	$output = fromUnicode($input);
	$output = my_unquote($output);
	$output = htmlentities ($output, ENT_QUOTES);
	if ($format) {
		$output = nl2br($output);
	}
	return $output;
}

function verySecureInput($input, $format) {
        $output = fromUnicode($input);
	$output = my_unquote($output);
        $output = strip_tags($output);
        if ($format) {
                $output = nl2br($output);
        }
        return $output;
}

#
# Strip HTML tags
# - used when outputting html
#
function htmlTokens ($string) {

	$string = htmlentities($string, ENT_QUOTES, "UTF-8");

        return $string;

}

#
# Strip XML tags
# - used when outputting xml
#
function xmlTokens ($string) {

        $string = str_replace('&', '&amp;', $string);
        $string = str_replace('\'', '&apos;', $string);
        $string = str_replace('"', '&quot;', $string);
        $string = str_replace('<', '&lt;', $string);
        $string = str_replace('>', '&gt;', $string);

        return $string;

}

#
# Strip twitter tags
#
function twitterTokens ($string) {

	$newWords = array();
	$words = explode(" ", $string);
	foreach ($words as $word) {
		if ($word[0] == '@') {
			$newWords[] = "<a href='http://twitter.com/".$word."'>".$word."</a>";
		}
		elseif ($word[0] == '#') {
			$newWords[] = "<a href='http://twitter.com/search?q=".$word."'>".$word."</a>";
		}
		else {
			$newWords[] = $word;
		}
	}
        return implode(" ", $newWords);

}

#
# complete URL's
#

function urlCompletion ($url) {
	# http & https are not in the url
	if ( (stripos($url, "http://") === false) && (stripos($url, "https://") === false) ) {
		if ($url != "") {
			$url = "http://".$url;
		}
	}
	else {
		# http or https should be at the beginning of the url
		$found = 0;
		if ( (stripos($url, "http://") !== false) && (stripos($url, "http://") == 0) ) {
			$found = 1;
		}
		if ( (stripos($url, "https://") !== false) && (stripos($url, "https://") == 0) ) {
			$found = 1;
		}
		if (! $found) {
			$url = "http://".$url;
		}
	}
	return $url;
}

#
# complete hashtags
#

function tagCompletion ($tag) {
	# # is not in the tag
	if ( (stripos($tag, "#") === false) ) {
		if ($tag != "") {
			$tag = "#".$tag;
		}
	}
	else {
		# # should be at the beginning of the tag
		$found = 0;
		if ( (stripos($tag, "#") !== false) && (stripos($tag, "#") == 0) ) {
			$found = 1;
			# allow only one #
			$tag = ltrim($tag, "#");
			$tag = "#".$tag;
		}
		if (! $found) {
			$tag = "#".$tag;
		}
	}
	return $tag;
}

/**
 *  Transforms plain text into valid HTML, escaping special characters and
 *  turning URLs into links.
 *
 *  Author: Søren Løvborg
 *
 *  To the extent possible under law, Søren Løvborg has waived all copyright
 *  and related or neighboring rights to UrlLinker.
 *  http://creativecommons.org/publicdomain/zero/1.0/
*/
function htmlEscapeAndLinkUrls($text)
{
	/*
	 *  Regular expression bits used by htmlEscapeAndLinkUrls() to match URLs.
	 */
	$rexProtocol  = '(https?://)?';
	$rexDomain    = '(?:[-a-zA-Z0-9]{1,63}\.)+[a-zA-Z][-a-zA-Z0-9]{1,62}';
	$rexIp        = '(?:[1-9][0-9]{0,2}\.|0\.){3}(?:[1-9][0-9]{0,2}|0)';
	$rexPort      = '(:[0-9]{1,5})?';
	$rexPath      = '(/[!$-/0-9:;=@_\':;!a-zA-Z\x7f-\xff]*?)?';
	$rexQuery     = '(\?[!$-/0-9:;=@_\':;!a-zA-Z\x7f-\xff]+?)?';
	$rexFragment  = '(#[!$-/0-9:;=@_\':;!a-zA-Z\x7f-\xff]+?)?';
	$rexUsername  = '[^]\\\\\x00-\x20\"(),:-<>[\x7f-\xff]{1,64}';
	$rexPassword  = $rexUsername; // allow the same characters as in the username
	$rexUrl       = "$rexProtocol(?:($rexUsername)(:$rexPassword)?@)?($rexDomain|$rexIp)($rexPort$rexPath$rexQuery$rexFragment)";
	$rexTrailPunct= "[)'?.!,;:]"; // valid URL characters which are not part of the URL if they appear at the very end
	$rexNonUrl    = "[^-_$+.!*'(),;/?:@=&a-zA-Z0-9]"; // characters that should never appear in a URL
	$rexUrlLinker = "{\\b$rexUrl(?=$rexTrailPunct*($rexNonUrl|$))}";

	/**
	 *  $validTlds is an associative array mapping valid TLDs to the value true.
	 *  Since the set of valid TLDs is not static, this array should be updated
	 *  from time to time.
	 *
	 *  List source:  http://data.iana.org/TLD/tlds-alpha-by-domain.txt
	 *  Last updated: 2012-09-06
	 */
	$validTlds = array_fill_keys(explode(" ", ".ac .ad .ae .aero .af .ag .ai .al .am .an .ao .aq .ar .arpa .as .asia .at .au .aw .ax .az .ba .bb .bd .be .bf .bg .bh .bi .biz .bj .bm .bn .bo .br .bs .bt .bv .bw .by .bz .ca .cat .cc .cd .cf .cg .ch .ci .ck .cl .cm .cn .co .com .coop .cr .cu .cv .cw .cx .cy .cz .de .dj .dk .dm .do .dz .ec .edu .ee .eg .er .es .et .eu .fi .fj .fk .fm .fo .fr .ga .gb .gd .ge .gf .gg .gh .gi .gl .gm .gn .gov .gp .gq .gr .gs .gt .gu .gw .gy .hk .hm .hn .hr .ht .hu .id .ie .il .im .in .info .int .io .iq .ir .is .it .je .jm .jo .jobs .jp .ke .kg .kh .ki .km .kn .kp .kr .kw .ky .kz .la .lb .lc .li .lk .lr .ls .lt .lu .lv .ly .ma .mc .md .me .mg .mh .mil .mk .ml .mm .mn .mo .mobi .mp .mq .mr .ms .mt .mu .museum .mv .mw .mx .my .mz .na .name .nc .ne .net .nf .ng .ni .nl .no .np .nr .nu .nz .om .org .pa .pe .pf .pg .ph .pk .pl .pm .pn .post .pr .pro .ps .pt .pw .py .qa .re .ro .rs .ru .rw .sa .sb .sc .sd .se .sg .sh .si .sj .sk .sl .sm .sn .so .sr .st .su .sv .sx .sy .sz .tc .td .tel .tf .tg .th .tj .tk .tl .tm .tn .to .tp .tr .travel .tt .tv .tw .tz .ua .ug .uk .us .uy .uz .va .vc .ve .vg .vi .vn .vu .wf .ws .xn--0zwm56d .xn--11b5bs3a9aj6g .xn--3e0b707e .xn--45brj9c .xn--80akhbyknj4f .xn--80ao21a .xn--90a3ac .xn--9t4b11yi5a .xn--clchc0ea0b2g2a9gcd .xn--deba0ad .xn--fiqs8s .xn--fiqz9s .xn--fpcrj9c3d .xn--fzc2c9e2c .xn--g6w251d .xn--gecrj9c .xn--h2brj9c .xn--hgbk6aj7f53bba .xn--hlcj6aya9esc7a .xn--j6w193g .xn--jxalpdlp .xn--kgbechtv .xn--kprw13d .xn--kpry57d .xn--lgbbat1ad8j .xn--mgb9awbf .xn--mgbaam7a8h .xn--mgbayh7gpa .xn--mgbbh1a71e .xn--mgbc0a9azcg .xn--mgberp4a5d4ar .xn--o3cw4h .xn--ogbpf8fl .xn--p1ai .xn--pgbs0dh .xn--s9brj9c .xn--wgbh1c .xn--wgbl6a .xn--xkc2al3hye2a .xn--xkc2dl3a5ee0h .xn--yfro4i67o .xn--ygbi2ammx .xn--zckzah .xxx .ye .yt .za .zm .zw"), true);

    $html = '';
    $position = 0;
    while (preg_match($rexUrlLinker, $text, $match, PREG_OFFSET_CAPTURE, $position))
    {
        list($url, $urlPosition) = $match[0];

        // Add the text leading up to the URL.
        $html .= htmlspecialchars(substr($text, $position, $urlPosition - $position));

        $protocol    = $match[1][0];
        $username    = $match[2][0];
        $password    = $match[3][0];
        $domain      = $match[4][0];
        $afterDomain = $match[5][0]; // everything following the domain
        $port        = $match[6][0];
        $path        = $match[7][0];

        // Check that the TLD is valid or that $domain is an IP address.
        $tld = strtolower(strrchr($domain, '.'));
        if (preg_match('{^\.[0-9]{1,3}$}', $tld) || isset($validTlds[$tld]))
        {
            // Do not permit implicit protocol if a password is specified, as
            // this causes too many errors (e.g. "my email:foo@example.org").
            if (!$protocol && $password)
            {
                $html .= htmlspecialchars($username);
                
                // Continue text parsing at the ':' following the "username".
                $position = $urlPosition + strlen($username);
                continue;
            }
            
            if (!$protocol && $username && !$password && !$afterDomain)
            {
                // Looks like an email address.
                $completeUrl = "mailto:$url";
                $linkText = $url;
            }
            else
            {
                // Prepend http:// if no protocol specified
                $completeUrl = $protocol ? $url : "http://$url";
                $linkText = "$domain$port$path";
            }
            
            $linkHtml = '<a href="' . htmlspecialchars($completeUrl) . '">'
                . htmlspecialchars($linkText)
                . '</a>';

            // Cheap e-mail obfuscation to trick the dumbest mail harvesters.
            // $linkHtml = str_replace('@', '&#64;', $linkHtml);
            
            // Add the hyperlink.
            $html .= $linkHtml;
        }
        else
        {
            // Not a valid URL.
            $html .= htmlspecialchars($url);
        }

        // Continue text parsing from after the URL.
        $position = $urlPosition + strlen($url);
    }

    // Add the remainder of the text.
    $html .= htmlspecialchars(substr($text, $position));
    return $html;
}

#
# trim for words from a sentence
# - remove null, return, tabs, spaties + leestekens
#
function my_trim($string) {
	return trim($string, "\0\n\r\t .,?!;:");
}

#
# do quote
#
function my_quote($string) {

    $string = str_replace("\\","\\\\",$string);
    $string = str_replace("'","\\'",$string);
    $string = str_replace('"','\\"',$string);

    return $string;

}
function my_quote_forward($string) {

    $string = str_replace("/","\\/",$string);

    return $string;

}


#
# undo quoting
#
function my_unquote($string) {

    $string = str_replace("\\\\","\\",$string);
    $string = str_replace('\\\"','"',$string);
    $string = str_replace("\\\'","'",$string);
    $string = str_replace('\\"','"',$string);
    $string = str_replace("\\'","'",$string);

    return $string;

}

#
# transform to and from Unicode
#
function toUnicode ( $string ) {

    for ($c=0; $c<strlen($string); $c++) {
        $out .= '&#'.ord(substr($string,$c)).';';
    }

    return ($out);

}

function fromUnicode ( $string ) {

    $out = preg_replace("/&#(\d+);/e", "chr(\\1)", $string);

    return ($out);

}

?>

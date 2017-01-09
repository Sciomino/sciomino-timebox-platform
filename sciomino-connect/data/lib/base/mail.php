<?php

##############
# MAIL
##############

function sendMail($s_name, $s_mail, $r_name, $r_mail, $subject, $body) {

	#
	# set "to"
	#
	$to = $r_name." <".$r_mail.">"; 

	#
	# set headers
	# let op: to wordt al gezet in het mail commando
	#
	$headers .= "From: ".$s_name." <".$s_mail.">\r\n"; 
	#$headers .= "To: ".$r_name." <".$r_mail.">\r\n"; 
	$headers .= "Reply-To: ".$s_name." <$s_mail>\r\n"; 
	$headers .= "Return-Path: <".$s_mail.">\r\n"; 
	$headers .= "X-Priority: 3\r\n";
	$headers .= "X-Mailer: xcow mailer\r\n"; 
	$headers .= "Content-type: text/plain; charset=UTF-8;\r\n"; 
	$headers .= "MIME-Version: 1.0\r\n"; 

	#
	# wrap the message
	#
	$body = wordwrap($body, 79);
	
	#
	# send mail
	#
	if ( isset($s_name) && isEmail($s_mail) && isset($r_name) && isEmail($r_mail))
	{
    		mail ($to, $subject, $body, $headers);
    		return 1;
	}
	else {
	        log2file("MAIL ERROR: REFERER=".$_SERVER['HTTP_REFERER'].", to=".$to.", subject=".$subject.", body=".$body.", headers=".$headers);
		return 0;
	}
	
}

function sendMailWithHTML($s_name, $s_mail, $r_name, $r_mail, $subject, $body) {

	#
	# set "to"
	#
	$to = $r_name." <".$r_mail.">"; 

	#
	# set headers
	# let op: to wordt al gezet in het mail commando
	#
	$headers .= "From: ".$s_name." <".$s_mail.">\r\n"; 
	#$headers .= "To: ".$r_name." <".$r_mail.">\r\n"; 
	$headers .= "Reply-To: ".$s_name." <$s_mail>\r\n"; 
	$headers .= "Return-Path: <".$s_mail.">\r\n"; 
	$headers .= "X-Priority: 3\r\n";
	$headers .= "X-Mailer: xcow mailer\r\n"; 
	$headers .= "Content-type: text/html; charset=UTF-8;\r\n"; 
	$headers .= "MIME-Version: 1.0\r\n"; 
    $headers .= "Content-Transfer-Encoding: quoted-printable\r\n";

	#
	# wrap the message
	#
	$body = quoted_printable_encode($body);
	
	#
	# send mail
	#
	if ( isset($s_name) && isEmail($s_mail) && isset($r_name) && isEmail($r_mail))
	{
    		mail ($to, $subject, $body, $headers);
    		return 1;
	}
	else {
	        log2file("MAIL ERROR: REFERER=".$_SERVER['HTTP_REFERER'].", to=".$to.", subject=".$subject.", body=".$body.", headers=".$headers);
		return 0;
	}
	
}

function sendMailWithAttachment($s_name, $s_mail, $r_name, $r_mail, $subject, $body, $attach) {

        global $XCOW_B;

        # mime boundary
        $mime_boundary = "_".$XCOW_B['this_name']."_".md5(time());

        #
        # set "to"
        #
        $to = $r_name." <".$r_mail.">";

        #
        # set headers
        # let op: to wordt al gezet in het mail commando
        #
	$headers .= "From: ".$s_name." <".$s_mail.">\r\n"; 
	#$headers .= "To: ".$r_name." <".$r_mail.">\r\n"; 
	$headers .= "Reply-To: ".$s_name." <$s_mail>\r\n"; 
	$headers .= "Return-Path: <".$s_mail.">\r\n"; 
	$headers .= "X-Priority: 3\r\n";
	$headers .= "X-Mailer: xcow mailer\r\n"; 
        $headers .= "Content-Type: Multipart/Mixed; charset=iso-8859-1; boundary=\"$mime_boundary\"\r\n";
	$headers .= "MIME-Version: 1.0\r\n"; 

        #
        # wrap the body
        #
        $body = wordwrap($body, 79);

        # de text
        $message = "--$mime_boundary\n";
        $message .= "Content-Type: text/plain; charset=UTF-8\n";
        $message .= "Content-Transfer-Encoding: 8bit\n\n";

        $message .= $body;
        $message .= "\n\n";

        # het attachment
        $message .= "--$mime_boundary\n";
        $message .= "Content-Type: application/".$attach['type']."; name=\"".$attach['name']."\"\n";
        $message .= "Content-Transfer-Encoding: base64\n";
        $message .= "Content-Disposition: attachment; filename=\"".$attach['name']."\"\n\n";

        $message .= chunk_split(base64_encode($attach['content']));
        $message .= "\n\n";

        # afsluiten
        $message .= "--$mime_boundary--\n\n";

        #
        # send mail
        #
        if ( isset($r_mail) && isEmail($r_mail) )
        {
                mail ($to, $subject, $message, $headers);
                return 1;
        }
        else {
                log2file("MAIL ERROR: REFERER=".$_SERVER['HTTP_REFERER'].", to=".$to.", subject=".$subject.", body=".$body.", headers=".$headers);
                return 0;
        }

}

#
# This function checks if the referer page is allowed
#
function refererOk ()
{

    $referers = array ("hellup.nl", "www.hellup.nl", "ontwikkel.hellup.nl");
    reset ($referers);
    
    while ( list($key, $value) = each($referers) )
    {
        if (substr($_SERVER['HTTP_REFERER'], 7, strlen($value)) == $value) 
        {
            return true;
        }
    }

    return false;

}

#
# This function checks if a string can be an e-mail address.
#
function isEmail ($EmailAddress)
{

    $match_email  = "/^(.*)@(.*)\.(.*)$/";

    return preg_match($match_email, $EmailAddress);
    
}

?>

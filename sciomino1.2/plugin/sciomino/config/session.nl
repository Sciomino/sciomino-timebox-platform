<?php

global $XCOW_B;

$lang = array();

###########
# NEDERLANDS
###########

# 1. page title

# 2. page header
$lang['session_header_activate'] = "Activeren";
$lang['session_header_anonymous'] = "Anonieme sessie gestart";
$lang['session_header_login'] = "Inloggen";
$lang['session_header_logout'] = "Afmelden";
$lang['session_header_register'] = "Registreren";
$lang['session_header_passnew'] = "Wachtwoord vergeten";
$lang['session_header_passupdate'] = "Wachtwoord wijzigen";
 
# 3. page text
$lang['session_text_username'] = "E-mailadres";
$lang['session_text_userdescription'] = "(Gebruik geen spaties in je gebruikersnaam.)";
$lang['session_text_userpass'] = "Wachtwoord";
$lang['session_text_usermail'] = "E-mailadres";
$lang['session_text_welcome'] = "Welkom ";
$lang['session_text_newuser'] = "Nieuwe gebruiker? ";
$lang['session_text_oldpass'] = "Oud wachtwoord:";
$lang['session_text_newpass'] = "Nieuw wachtwoord:";
$lang['session_text_verifypass'] = "Controleer nieuw wachtwoord:";
$lang['session_text_register_terms'] = "Door te klikken op 'Registreren' ga je akkoord met onze <a href='http://business.sciomino.com/terms-of-use-nl' target='_blank'>voorwaarden</a> en <a href='http://business.sciomino.com/privacy-nl' target='_blank'>privacy verklaring</a> en bevestig je dat je deze hebt gelezen en begrijpt.";
$lang['session_text_register_alternative'] = "(of <a href='javascript:Session.Login.load();'>log in</a>)";
$lang['session_text_login_alternative'] = "(nieuw? <a href='javascript:Session.Register.load();'>registreer</a>)";

# 4. page link / word
$lang['session_word_forgetpass'] = "Wachtwoord vergeten?";
$lang['session_word_login'] = "Inloggen";
$lang['session_word_logout'] = "Afmelden";
$lang['session_word_register'] = "Registreren";
$lang['session_word_pass'] = "Mail nieuw wachtwoord";
$lang['session_word_passupdate'] = "Wachtwoord wijzigen";
$lang['session_word_cancel'] = "Annuleren";

# 5. page status
$lang['session_status_activate_ok'] = "Je registratie is bevestigd.<!--activateCheck-->";
$lang['session_status_activate_wrongkey'] = "De sleutel is niet geldig voor je registratie.";
$lang['session_status_activate_nokey'] = "Om je registratie te bevestigen heb je een sleutel nodig.";
$lang['session_status_login_ok'] = "Je bent aangemeld.";
$lang['session_status_login_wrong'] = "De inlog combinatie is niet juist.";
$lang['session_status_login_firsttime'] = "Voor deze actie moet je eerst inloggen.";
$lang['session_status_logout_ok'] = "Je bent afgemeld.";
$lang['session_status_logout_wrong'] = "Je moet ingelogd zijn om uit te kunnen loggen.";
$lang['session_status_register_requiredfield'] = "Je hebt niet alle verplichte velden ingevuld.";
$lang['session_status_register_nameexists'] = "Het e-mailadres bestaat al.";
$lang['session_status_register_namewrong'] = "Het e-mailadres is niet geldig.";
$lang['session_status_register_passwrong'] = "Het wachtwoord is niet goed, je moet minimaal 2 karakters gebruiken.";
$lang['session_status_register_emailexists'] = "Het e-mailadres bestaat al.";
$lang['session_status_register_emailwrong'] = "Het e-mailadres is niet geldig.";
$lang['session_status_register_emailnotallowed'] = "Het e-mailadres is niet geldig.";
$lang['session_status_register_notshown'] = "Je kunt je registreren via je werkgever. Met je e-mailadres en wachtwoord kun je dan hierboven inloggen.";
$lang['session_status_register_toomany'] = "Het maximum aantal geregistreerde personen is bereikt. Neem contact op met je werkgever.";
$lang['session_status_passnew_emailwrong'] = "Het e-mailadres is niet geldig.";
$lang['session_status_passupdate_ok'] = "Je wachtwoord is gewijzigd";
$lang['session_status_passupdate_oldwrong'] = "Je oude wachtwoord is niet correct.";
$lang['session_status_passupdate_newwrong'] = "Je nieuwe wachtwoord moet minimaal 2 karakters bevatten.";
$lang['session_status_passupdate_nomatch'] = "De twee nieuwe wachtwoorden zijn niet hetzelfde.";

# 6. mail
$lang['session_mail_register_subject'] = "Activeer je account";
$lang['session_mail_register_body'] = "Beste \$user\$,\n\nWelkom bij \$name\$. Met dit e-mailadres is een registratie aangevraagd. Om je account te activeren klik je op de volgende link: \$url\$.\n\nMet vriendelijke groeten,\nHet \$name\$ team.";
$lang['session_mail_register_status'] = "Je bent geregistreerd. <br/><br/>Je kunt bijna beginnen. Er is een mail naar je gestuurd waarmee je je registratie moet bevestigen. <br/><br/> Nodig alvast je contacten uit om samen kennis te delen!";
$lang['session_mail_register2_subject'] = "Je bent geregistreerd";
$lang['session_mail_register2_body'] = "Beste \$user\$,\n\nWelkom bij \$name\$. Met deze mail bevestigen we je registratie.\n\nHappy Mining!\n\nMet vriendelijke groeten,\nHet \$name\$ team.";
$lang['session_mail_register2_status'] = "Je bent geregistreerd. <br/><br/>Je kunt nu met je gebruikersnaam en wachtwoord inloggen en van alle functionaliteiten gebruik maken.";
$lang['session_mail_passnew_subject'] = "Je nieuwe wachtwoord";
$lang['session_mail_passnew_body'] = "Beste \$user\$,\n\nOp dit e-mailadres is een nieuw wachtwoord aangevraagd voor \$name\$. Het wachtwoord behorende bij jouw loginnaam is door ons veranderd in: \$pass\$. Je kunt hiermee weer inloggen op \$host\$. Verander voor de zekerheid snel je wachtwoord weer.\n\nMet vriendelijke groeten,\nHet \$name\$ team.";
$lang['session_mail_passnew_status'] = "Er is een e-mail onderweg naar het adres wat je hebt opgegeven. Hierin staat je nieuwe wachtwoord.";
###########

$XCOW_B['language'] = $XCOW_B['language'] + $lang;

?>

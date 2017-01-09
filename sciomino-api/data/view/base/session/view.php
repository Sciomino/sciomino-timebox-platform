<div class="xcow_paragraph">

<?php
	#
	# this is the difference between login and logout
	#
	if ($session['response']['param']['id']) {
		echo language(session_text_welcome).$session['response']['param']['user']."<br>";
		echo "<a class='xcow_link' href='javascript:Session.Logout.load()'>".language(session_word_logout)."</a>";
	}
	else {
		echo "<a class='xcow_link' href='javascript:Session.Login.load()'>".language(session_word_login)."</a><br>";
		echo "<div id='showRegisterInView'>".language(session_text_newuser)."<a class='xcow_link' href='javascript:Session.Register.load()'>".language(session_word_register)."</a></div>";
	}
?>

</div>


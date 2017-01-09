<?php
	#
	# this is the difference between login and logout
	#
	if ($session['response']['param']['id']) {
		echo "<ul class='nav nav-user'>";
		echo "<li>";
		echo language(session_text_welcome).$session['response']['param']['user'];
		echo "</li>";

		echo "<li>";
		echo ", <a href='javascript:Session.Logout.load()'>".language(session_word_logout)."</a>";
		echo "</li>";
		echo "</ul>";

	}
	else {
		echo "<a class='xcow_link' href='javascript:Session.Login.load()'>".language(session_word_login)."</a><br>";
		echo "<div id='showRegisterInView'>".language(session_text_newuser)."<a class='xcow_link' href='javascript:Session.Register.load()'>".language(session_word_register)."</a></div>";
	}
?>

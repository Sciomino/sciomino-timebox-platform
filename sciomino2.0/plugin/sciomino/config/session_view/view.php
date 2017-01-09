<div class="xcow_paragraph">

<?php
	#
	# this is the difference between login and logout
	#
	if ($session['response']['param']['id']) {
		$SciominoUser = $session['response']['param']['apiUser'];

		if (! isset($session['response']['param']['apiUserPhoto'])) { $session['response']['param']['apiUserPhoto'] = "/ui/gfx/photo.jpg"; }
		else { $session['response']['param']['apiUserPhoto'] = str_replace("/upload/","/upload/32x32_",$session['response']['param']['apiUserPhoto']); }

		echo "<ul class='nav nav-user'>";
		echo "<li class='dropdownAjax dropdown-item'>";
		#echo "<a class='control' href='".$XCOW_B['url']."/snippet/shortcut-list?user=".$SciominoUser."'>"."<img src='".$XCOW_B['url'].$session['response']['param']['apiUserPhoto']."' width='25' height='25' alt='' />"."<em class='user'>".$session['response']['param']['user']."</em></a>";
		echo "<a class='control' href='".$XCOW_B['url']."/snippet/shortcut-list?user=".$SciominoUser."'>"."<img src='".$XCOW_B['url'].$session['response']['param']['apiUserPhoto']."' width='25' height='25' alt='' />"."</a>";
		echo "<div class='dropdown interactive-set'>";
		echo "</div>";
		echo "</li>";

		echo "<li class='dropdownAjax dropdown-item' id='Message-dropdown'>";
		echo "<a class='control' href='".$XCOW_B['url']."/snippet/motd-list?user=".$SciominoUser."'>".language('sciomio_text_session_motd')."</a>";
		echo "<div class='dropdown interactive-set'>";
		echo "</div>";
		echo "</li>";

		echo "<li class='lists dropdownAjax dropdown-item'>";
		echo "<a class='control' href='".$XCOW_B['url']."/snippet/list-list?mode=view&user=".$SciominoUser."'>".language('sciomio_text_session_lists')."</a>";
		echo "<div class='dropdown interactive-set'>";
		echo "</div>";
		echo "</li>";

		if ($XCOW_B['sciomino']['skin-network'] == "yes") {
			echo "<li class='lists dropdownAjax dropdown-item'>";
			echo "<a class='control' href='".$XCOW_B['url']."/snippet/network-list?mode=view&user=".$SciominoUser."'>".language('sciomio_text_session_networks')."</a>";
			echo "<div class='dropdown interactive-set'>";
			echo "</div>";
			echo "</li>";
		}
		
		echo "</ul>";

		#echo "<script src='/ui/js/all.concat.min.js'></script>";

		# echo language(session_text_welcome).$session['response']['param']['user'];
		# echo " <a onClick='ScioMino.Shortcut.loadView(".$SciominoUser.", event)'>V</a>";
		# echo " | <a onClick='ScioMino.Motd.loadView(".$SciominoUser.", event)'>Bericht van de dag</a>";
		# echo " | <a onClick='ScioMino.List.loadView(".$SciominoUser.", event)'>Jouw lijsten</a>";
		# echo " | <a class='xcow_link' href='javascript:Session.Logout.load()'>".language(session_word_logout)."</a>";
	}
	else {
		echo "<a class='xcow_link' href='javascript:Session.Login.load()'>".language(session_word_login)."</a><br>";
		echo "<div id='showRegisterInView'>".language(session_text_newuser)."<a class='xcow_link' href='javascript:Session.Register.load()'>".language(session_word_register)."</a></div>";
	}
?>

</div>


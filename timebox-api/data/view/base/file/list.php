<?php
	foreach ($session['response']['param']['urls'] as $url) {

		echo "<br/><a href='$url'>$url<a/> (<a href='/file/download?file=$url'>".language('base_word_file_download')."</a>)\n";

	}
?>


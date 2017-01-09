	<?php
 	foreach ($session['response']['param']['blogs'] as $blogKey => $blogVal) {
		echo "<div class='article'>";

		echo "<div class='hgroup'>";
		echo "<h3>".language('sciomio_text_blog')."<a href='".$blogVal[$blogKey]['blogLink']."'>".$blogVal[$blogKey]['blogTitle']."</a></h3>";
		#echo "<h3>".$blogVal[1]['header']."</h3>";
		#echo "<h4>Over <a href='#'>energietransitie</a>, <a href='#'>design</a></h4>";
		echo "</div>";

		echo "<ul>";
		foreach ($blogVal as $blog) {
			echo "<li>";
			echo "<h5><a href='".$blog['link']."'>".$blog['title']."</a></h5>";
			echo "<p>".$blog['description']."</p>";
			echo "</li>";
		}
		echo "</ul>";

		echo "<a class='more' href='".$blogVal[$blogKey]['blogLink']."'>".language('sciomio_word_morePosts')."</a>";

		echo "</div>";
	}
	?>

	<?php
	foreach ($session['response']['param']['shares'] as $shareKey => $shareVal) {
		echo "<div class='article'>";

		echo "<div class='hgroup'>";
		echo "<h3>".$shareVal[1]['header']."</h3>";
		#echo "<h4>Over <a href='#'>energietransitie</a>, <a href='#'>design</a></h4>";
		echo "</div>";

		echo "<ul>";
		foreach ($shareVal as $share) {
			echo "<li>";
			echo "<h5>".$share['title']."</h5>";
			echo "<p>".$share['description']."</p>";
			echo "</li>";
		}
		echo "</ul>";

		echo "<a class='more' href='".$share['link']."'>".language('sciomio_word_morePosts')."</a>";

		echo "</div>";
	}
	?>

	<?php
	if (count($session['response']['param']['websites']) > 0) {
		echo "<div class='article'>";

		echo "<div class='hgroup'>";
		echo "<h3>".language('sciomio_text_publication_view_websites')."</h3>";
		#echo "<h4>Over <a href='#'>energietransitie</a>, <a href='#'>design</a></h4>";
		echo "</div>";

		echo "<ul>";
		foreach ($session['response']['param']['websites'] as $website) {
			echo "<li>";
			echo "<a target='_blank' href=".$website['relation-self'].">".$website['relation-self']."</a>";
			echo "</li>";
		}
		echo "</ul>";

		echo "</div>";
	}
	?>

	<?php
	if (count($session['response']['param']['otherPubs']) > 0) {
		echo "<div class='article'>";

		echo "<div class='hgroup'>";
		echo "<h3>".language('sciomio_text_publication_view_otherPub')."</h3>";
		#echo "<h4>Over <a href='#'>energietransitie</a>, <a href='#'>design</a></h4>";
		echo "</div>";

		echo "<ul>";
		foreach ($session['response']['param']['otherPubs'] as $otherPub) {
			echo "<li>";
			echo "<h5>".$otherPub['title']."</h5>";
			echo "<p>".$otherPub['alternative']."</p>";
			echo "<p>".$otherPub['description']."</p>";
			echo "<a target='_blank' href=".$otherPub['relation-self'].">".$otherPub['relation-self']."</a>";
			echo "</li>";
		}
		echo "</ul>";

		echo "</div>";
	}
	?>

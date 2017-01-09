<!-- Experience-->
<ul class="linklist index">
	<li><span class="sectionhead"><?php echo language('sciomio_header_view_product'); ?></span>
		<ul>
			<?php
			if (count($session['response']['param']['productList']) > 0) {
				foreach ($session['response']['param']['productList'] as $eKey => $eVal) {
					echo "<li><a href='".$XCOW_B['url']."/browse/experience?e[Product]=".urlencode($eKey)."'>$eKey <span class='count'>($eVal)</span></a></li>\n";
				}
				#echo "<li><a class='more' href='#'>Meer&hellip;</a></li>";
			}
			else {
				echo "<li>".language('sciomio_text_browse_experience_noproduct')."</li>";
			}
			?>
		</ul>
	</li>

	<li><span class="sectionhead"><?php echo language('sciomio_header_view_company'); ?></span>
		<ul>
			<?php
			if (count($session['response']['param']['companyList']) > 0) {
				foreach ($session['response']['param']['companyList'] as $eKey => $eVal) {
					echo "<li><a href='".$XCOW_B['url']."/browse/experience?e[Company]=".urlencode($eKey)."'>$eKey <span class='count'>($eVal)</span></a></li>\n";
				}
				#echo "<li><a class='more' href='#'>Meer&hellip;</a></li>";
			}
			else {
				echo "<li>".language('sciomio_text_browse_experience_nocompany')."</li>";
			}
			?>
		</ul>
	</li>

	<li><span class="sectionhead"><?php echo language('sciomio_header_view_event'); ?></span>
		<ul>
			<?php
			if (count($session['response']['param']['eventList']) > 0) {
				foreach ($session['response']['param']['eventList'] as $eKey => $eVal) {
					echo "<li><a href='".$XCOW_B['url']."/browse/experience?e[Event]=".urlencode($eKey)."'>$eKey <span class='count'>($eVal)</span></a></li>\n";
				}
				#echo "<li><a class='more' href='#'>Meer&hellip;</a></li>";
			}
			else {
				echo "<li>".language('sciomio_text_browse_experience_noevent')."</li>";
			}
			?>
		</ul>
	</li>
	<li><span class="sectionhead"><?php echo language('sciomio_header_view_education'); ?></span>
		<ul>
			<?php
			if (count($session['response']['param']['educationList']) > 0) {
				foreach ($session['response']['param']['educationList'] as $eKey => $eVal) {
					echo "<li><a href='".$XCOW_B['url']."/browse/experience?e[Education]=".urlencode($eKey)."'>$eKey <span class='count'>($eVal)</span></a></li>\n";
				}
				#echo "<li><a class='more' href='#'>Meer&hellip;</a></li>";
			}
			else {
				echo "<li>".language('sciomio_text_browse_experience_noeducation')."</li>";
			}
			?>
		</ul>
	</li>
</ul>

<?php 
if ($session['response']['param']['thereIsMore'] && $session['response']['param']['limit'] != 0) {
	echo "<a class='more' href='javascript:ScioMino.ListExperienceFields.load(0,\"\")'>".language('sciomio_word_user_experience_more')."</a>";
}
?>


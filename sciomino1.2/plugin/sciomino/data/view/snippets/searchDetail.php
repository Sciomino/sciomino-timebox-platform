<div class="section">
	<h2><?php echo language("sciomio_word_filter_".$session['response']['param']['detail']); ?> <?php echo language('sciomio_text_search_detail'); ?></h2>

	<ul class="linklist index">
		<li><span class="sectionhead"></span>
		<ul>
		<?php
		foreach ($session['response']['param']['searchList'] as $field => $count) {
			if ($session['response']['param']['detail'] == "knowledge") { $url = "k[".urlencode($field)."]";}
			if ($session['response']['param']['detail'] == "hobby") { $url = "h[".urlencode($field)."]";}
			if ($session['response']['param']['detail'] == "product") { $url = "e[Product][".urlencode($field)."]";}
			if ($session['response']['param']['detail'] == "company") { $url = "e[Company][".urlencode($field)."]";}
			if ($session['response']['param']['detail'] == "event") { $url = "e[Event][".urlencode($field)."]";}
			if ($session['response']['param']['detail'] == "education") { $url = "e[Education][".urlencode($field)."]";}
			if ($session['response']['param']['detail'] == "tag") { $url = "t[".urlencode($field)."]";}
			if ($session['response']['param']['detail'] == "list") { $url = "l[".urlencode($field)."]";}
			if ($session['response']['param']['detail'] == "publicList") { $url = "tl[public]=".urlencode($field);}
			if ($session['response']['param']['detail'] == "managerList") { $url = "tl[manager]=".urlencode($field);}
			if ($session['response']['param']['detail'] == "industry") { $url = "p[industry]=".urlencode($field);}
			if ($session['response']['param']['detail'] == "organization") { $url = "p[organization]=".urlencode($field);}
			if ($session['response']['param']['detail'] == "businessunit") { $url = "p[businessunit]=".urlencode($field);}
			if ($session['response']['param']['detail'] == "section") { $url = "p[section]=".urlencode($field);}
			if ($session['response']['param']['detail'] == "role") { $url = "p[role]=".urlencode($field);}
			if ($session['response']['param']['detail'] == "hometown") { $url = "p[hometown]=".urlencode($field);}
			if ($session['response']['param']['detail'] == "workplace") { $url = "p[workplace]=".urlencode($field);}
			echo "<li><a href='".$XCOW_B['url']."/search?".$session['response']['param']['focus']."&".$url."'>".$field." <span class='count'>(".$count.")</span></a></li>";
		}
		?>
		</ul>
		</li>
	</ul>
</div>


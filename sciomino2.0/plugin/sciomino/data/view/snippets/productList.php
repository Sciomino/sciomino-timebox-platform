	<h3><?php echo language('sciomio_header_user_experience_product'); ?></h3>

        <div class="add-header highlight">
            <a class="tinybutton metoo" rel="/snippet/product-list" href="<?php echo $XCOW_B['url'] ?>/snippet/product-new-form"><?php echo language('sciomio_text_ervaring_toevoegen'); ?></a>
	<?php
	$count = count($session['response']['param']['productList']); 
	if ($count == 0) {
		echo "<p>".language('sciomio_text_ervaring_geen')."</p>";
	}
	elseif ($count == 1) {
		echo "<p>".language('sciomio_text_ervaring_een')."</p>";
	}
	else {
		$languageTemplate = array();
		$languageTemplate['count'] = $count;
		echo "<p>".language_template('sciomio_text_ervaring_meer', $languageTemplate)."</p>";
	}
	?>
        </div>

<?php
$oldSubject = "";
foreach ($session['response']['param']['productList'] as $product) {
	if ($product['subject'] != $oldSubject) {
		if ($oldSubject != "") {
			echo "</ul></div>";
		}
		$oldSubject = $product['subject'];
		echo "<div class='section'>";
		echo "<h4><a href='".$XCOW_B['url']."/browse/experience?e[Product]=".urlencode($product['subject'])."'>{$product['subject']}</a></h4>";
		echo "<ul class='comps exp-list'>";
	}

	$verdict = "happy";
	if ($product['like'] == 1) {$verdict = "happy-xl";}
	if ($product['like'] == 2) {$verdict = "happy";}
	if ($product['like'] == 3) {$verdict = "unhappy";}
	if ($product['like'] == 4) {$verdict = "unhappy-xl";}

	echo "<li>";

        echo "<div class='tinybuttons'>";
        echo "<a href='".$XCOW_B['url']."/snippet/product-edit-form?productId=".$product['Id']."' rel='/snippet/product-list' class='tinybutton metoo'>".language('sciomio_word_edit')."</a>";
        echo "<a class='tinybutton delete' href='javascript:ScioMino.ProductDelete.action(".$product['Id'].");'>".language('sciomio_word_delete')."</a>";
        echo "</div>";

	echo "<dl class='exp-item'>";
	echo "<dt><a class='verdict ".$verdict." exp-link' href='".$XCOW_B['url']."/browse/experience?e[Product]=".urlencode($product['subject'])."&title=".urlencode($product['title'])."&alternative=".urlencode($product['alternative'])."'>{$product['title']} - {$product['alternative']}</a></dt>";
	$languageString = "sciomio_word_has_".$product['has'];
	echo "<dd>".language($languageString)."</dd>";
	echo "<dl>";

	#echo "<div class='img-item'>";
	#echo "<div class='img sub'>";
	#echo "<img src='/content/images/dummy-product-100x65.jpg' alt='auto A6' />";
	#echo "</div>";
	echo "<div class='review-item'>";
	echo "<table class='review'>";
	echo "<thead><tr><th style='width:50%'>".language('sciomio_text_view_pluspunten')."</th><th style='width:50%'>".language('sciomio_text_view_minpunten')."</th></tr></thead>";
	echo "<tbody><tr><td>";
	echo "<ul class='ftw'>";
	echo "<li>".$product['positive1']."</li>";
	echo "<li>".$product['positive2']."</li>";
	echo "<li>".$product['positive3']."</li>";
	echo "</ul>";
	echo "</td><td>";
	echo "<ul class='fail'>";
	echo "<li>".$product['negative1']."</li>";
	echo "<li>".$product['negative2']."</li>";
	echo "<li>".$product['negative3']."</li>";
	echo "</ul>";
	echo "</td></tr></tbody>";
	echo "</table>";
	echo "</div>";
	#echo "</div>";

	echo "</li>";
}
echo "</ul>";

# echo "<a href='#' class='more'>toon alle ervaringen&hellip;</a>";

echo "</div>";

?>



<?php
	$personalia = $mijnprofiel == 'personalia' ? 'class="active"' : '';
	$KenE = $mijnprofiel == 'KenE' ? 'class="active"' : '';
	$ervaringen = $mijnprofiel == 'ervaringen' ? 'class="active"' : '';
	$publicaties = $mijnprofiel == 'publicaties' ? 'class="active"' : '';
	$tags = $mijnprofiel == 'tags' ? 'class="active"' : '';
?>
<div class="nav nav-u">
	<div class="page">
		<div class="unit unit2-3">
			<h1 class="icon user"><?php echo language(sciomio_word_nav_head_profile); ?></h1>
			<ul class="nav-profile">
		        <li><a <?php echo $personalia; ?> href="<?php echo $XCOW_B['url'] ?>/user"><?php echo language(sciomio_word_nav_personalia); ?></a></li>
				<li><a <?php echo $publicaties; ?> href="<?php echo $XCOW_B['url'] ?>/user/publication"><?php echo language(sciomio_word_nav_publicaties); ?></a></li>
				<li><a <?php echo $KenE; ?> href="<?php echo $XCOW_B['url'] ?>/user/knowledge"><?php echo language(sciomio_word_nav_kennis); ?></a></li>
				<li><a <?php echo $tags; ?> href="<?php echo $XCOW_B['url'] ?>/user/tag"><?php echo language(sciomio_word_nav_tags); ?></a></li>
				<li><a <?php echo $ervaringen; ?> href="<?php echo $XCOW_B['url'] ?>/user/experience"><?php echo language(sciomio_word_nav_ervaringen); ?></a></li>
			</ul>
		</div>
		<div class="unit unit1-3">
		</div>
	</div>
</div>

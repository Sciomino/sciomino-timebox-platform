<?php
	$focus = $mijnprofiel == 'focus' ? 'class="active"' : '';
	$connect = $mijnprofiel == 'connect' ? 'class="active"' : '';
	$networks = $mijnprofiel == 'networks' ? 'class="active"' : '';
	$notification = $mijnprofiel == 'notification' ? 'class="active"' : '';
	$language = $mijnprofiel == 'language' ? 'class="active"' : '';
	$password = $mijnprofiel == 'password' ? 'class="active"' : '';
	$account = $mijnprofiel == 'account' ? 'class="active"' : '';
?>
<div class="nav nav-u">
	<div class="page">
		<div class="unit unit2-3">
			<h1 class="icon pref"><?php echo language(sciomio_word_nav_head_features); ?></h1>
			<ul class="nav-profile">
				<li><a <?php echo $connect; ?> href="<?php echo $XCOW_B['url'] ?>/setting/connect"><?php echo language(sciomio_word_nav_connect); ?></a></li>
				<?php
				if ($XCOW_B['sciomino']['skin-network'] == "yes") {
					echo "<li><a ".$networks." href='".$XCOW_B['url']."/setting/networks'>".language(sciomio_word_nav_networks)."</a></li>";
				}
				?>
				<li><a <?php echo $focus; ?> href="<?php echo $XCOW_B['url'] ?>/setting/focus"><?php echo language(sciomio_word_nav_focus); ?></a></li>
			</ul>
		</div>
		<div class="unit unit1-3">
			<h3 class="icon pref"><?php echo language(sciomio_word_nav_head_account); ?></h3>
			<ul class="nav-pref">
				<?php
				if (count($XCOW_B['valid_languages']) > 1) {
					echo "<li><a ".$language." href='".$XCOW_B['url']."/setting/language'>".language(sciomio_word_nav_language)."</a></li>";
				}
				?>
				<li><a <?php echo $notification; ?> href="<?php echo $XCOW_B['url'] ?>/setting/notification"><?php echo language(sciomio_word_nav_notification); ?></a></li>
				<li><a <?php echo $password; ?> href="<?php echo $XCOW_B['url'] ?>/setting/password"><?php echo language(sciomio_word_nav_password); ?></a></li>
				<li><a <?php echo $account; ?> href="<?php echo $XCOW_B['url'] ?>/setting/account"><?php echo language(sciomio_word_nav_account); ?></a></li>
			</ul>
		</div>
	</div>
</div>

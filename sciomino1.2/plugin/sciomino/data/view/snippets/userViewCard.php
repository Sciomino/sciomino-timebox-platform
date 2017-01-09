<div class="puu-card_wrapper">
	<div class="puu-card">

		<div class="puu-static">
			<h3 class="fn n"><a class='url' href='<?php echo $XCOW_B['url'] ?>/view?user=<?php echo $session['response']['param']['user']['Id'] ?>'><?php echo $session['response']['param']['user']['FirstName']?> <?php echo $session['response']['param']['user']['LastName']?></a></h3>

			<?php
			$displayOrganization = $session['response']['param']['user']['Organization'][get_id_from_multi_array($session['response']['param']['user']['Organization'], 'Name', 'Current')]['division'];
			if ($displayOrganization == "") { $displayOrganization = $session['response']['param']['user']['Organization'][get_id_from_multi_array($session['response']['param']['user']['Organization'], 'Name', 'Current')]['company']; }
			?>
			<p class="role"><?php echo $session['response']['param']['user']['Organization'][get_id_from_multi_array($session['response']['param']['user']['Organization'], 'Name', 'Current')]['role'] ?> - <?php echo $displayOrganization ?></p>

			<a class="email" href="mailto:<?php echo $session['response']['param']['user']['Contact'][get_id_from_multi_array($session['response']['param']['user']['Contact'], 'Name', 'Work')]['email'] ?>"><?php echo $session['response']['param']['user']['Contact'][get_id_from_multi_array($session['response']['param']['user']['Contact'], 'Name', 'Work')]['email'] ?></a>

			<!--
			<div class='tel'><?php echo $session['response']['param']['user']['Contact'][get_id_from_multi_array($session['response']['param']['user']['Contact'], 'Name', 'Work')]['telExtern'] ?></div>
			-->

			<div class="twitter">
			<?php
			if (isset($session['response']['param']['twitterAccount'])) {
				echo "<a href='".$XCOW_B['url']."/snippet/tweet-new-form?user=".$session['response']['param']['twitterAccount']."' class='modalflex tinyicon tinyicon-twitter userlink'>".$session['response']['param']['twitterAccount']."</a>";
			}
			else {
				echo "&nbsp;";
			}
			?>
			</div>

			<div class="locality"><?php echo $session['response']['param']['user']['Address'][get_id_from_multi_array($session['response']['param']['user']['Address'], 'Name', 'Work')]['city'] ?></div>
		</div>
	</div>
</div>

<?php
if ($session['response']['param']['mode'] != "update") {
	echo "<fieldset class='message item'>";
}
?>
	<form method="post" class="saveable">
		<input class='form_input' type='hidden' name='title' value="motd">
		<input class='form_input' type='hidden' name='redirect' value="<?php echo $XCOW_B['url'] ?>/snippet/motd-list?mode=update">
			<?php
				if ($session['response']['param']['firstMotd'] == 0) {
					$motd = $session['response']['param']['motd'];
					$timeString = timeDiff2($motd['Timestamp']);
					$textarea = "<textarea name='description' class='current-message' maxlength='140'>".$motd['Description']."</textarea>";
				}
				else {
					$timeString = language('sciomio_text_motd_noMessage');
					$textarea = "<textarea name='description' placeholder='".language('sciomio_text_motd_defaultMessage')."' maxlength='140'>".language('sciomio_text_motd_defaultMessage')."</textarea>";
				}

				echo "<div class='count'>".$timeString."</div>";
				echo "<div class='inputset'>";
				echo $textarea;
			?>

			<span class="message-counter" id="MsgCount">140</span>
			<div class="interact">
				<a class="edit" href="#">e</a>
				<a class="tinybutton save" href="<?php echo $XCOW_B['url'] ?>/snippet/activity-new"><?php echo language('sciomio_word_share'); ?></a>
				<!--
				<div class="cancelbox">
				    <?php echo language('sciomio_word_or'); ?> <a class="cancel" href="#"><?php echo language('sciomio_word_reset'); ?></a>
				</div>
				-->
                
			</div>
		</div>
		<div class="helpers fieldset-info">
			<p><?php echo language('sciomio_text_motd_standardMessage'); ?></p>
			<ul>
				<li><a class="add-message fill" href="#"><?php echo language('sciomio_text_motd_message_1'); ?></a></li>
				<li><a class="add-message fill" href="#"><?php echo language('sciomio_text_motd_message_2'); ?></a></li>
				<li><a class="add-message fill" href="#"><?php echo language('sciomio_text_motd_message_3'); ?></a></li>
			</ul>
		</div>
	</form>
<?php
if ($session['response']['param']['mode'] != "update") {
	echo "</fieldset>";
}
?>

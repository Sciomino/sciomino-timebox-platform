<form action="<?php echo $XCOW_B['url'] ?>/snippet/hobby-new-form" method="post">
    <input value="<?php echo htmlTokens($session['response']['param']['fill']); ?>" type="text" class="text autocomplete" data-results="<?php echo $XCOW_B['url'] ?>/snippet/suggest-local?type=hobby" name="com_field" id="com_field" maxlength="32" />
    <div class="interact">
        <!-- <a href="/snippet/hobby-delete?hobbyId=<?php echo $session['response']['param']['hobbyId']; ?>" title="verwijder" class="remove">x</a> -->
        <a href="<?php echo $XCOW_B['url'] ?>/snippet/hobby-new-form" title="opslaan" class="tinybutton save"><?php echo language('sciomio_word_ok'); ?></a>
        <div class="cancelbox">
            <?php echo language('sciomio_word_or'); ?> <a href="#" title="annuleren" class="cancel-new"><?php echo language('sciomio_word_reset'); ?></a>
        </div>
    </div>
</form>

<?php
if ($session['response']['param']['status'] == "Same Same") {
    echo "<script>";
    echo "sc.displayMessage({message : '".language('sciomio_text_hobby_save_error')."', type : 'error', displayTime : 2000});";
    echo "</script>";
}

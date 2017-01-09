<div class="inputset item">
</div>
<?php
foreach ($session['response']['param']['blogList'] as $blog) {
	echo "<div class='inputset item'>";
	echo "<form action='".$XCOW_B['url']."/snippet/blog-edit-form' method='post'>";
    	echo "<input class='form_input' type='hidden' name='com_title' value='".htmlTokens($blog['title'])."'>";
    	echo "<input class='form_input' type='hidden' name='blogId' value='{$blog['Id']}'>";
	$languageString = "sciomio_text_blog_".$blog['title'];
	echo "<label class='icon icon-{$blog['title']}' for='blog-{$blog['Id']}'>".language($languageString)."</label>";
	echo "<input type='text' id='blog-{$blog['Id']}' name='com_relation-other' class='text' value='".htmlTokens($blog['relation-other'])."' maxlength='256'/>";
        echo "<div class='interact'>";
        echo "<a href='".$XCOW_B['url']."/snippet/blog-delete?blogId={$blog['Id']}' title='verwijder' class='remove'>x</a>";
        echo "<a href='".$XCOW_B['url']."/snippet/blog-edit-form' title='opslaan' class='tinybutton save'>".language('sciomio_word_ok')."</a>";
        echo "<div class='cancelbox'>";
        echo language('sciomio_word_or')." <a href='#' title='Annuleren' class='cancel'>".language('sciomio_word_reset')."</a>";
        echo "</div>";
        echo "</div>";
        echo "</form>";
	echo "</div>";
}
?>
        <div class="inputset item">
            <form action="<?php echo $XCOW_B['url'] ?>/snippet/blog-new-form" method="post">
    		<input class='form_input' type='hidden' name='com_title' value='blogger'>
                <label class="icon icon-blogger" for="blog-0"><?php echo language('sciomio_text_blog_blogger'); ?></label>
                <input class="text" type="text" name="com_relation-other" id="blog-0" value="" maxlength="256" />
                <div class="interact">
                    <a href="<?php echo $XCOW_B['url'] ?>/snippet/blog-new-form" title="opslaan" class="tinybutton save"><?php echo language('sciomio_word_ok'); ?></a>
               </div>
            </form>
        </div>
        <div class="inputset item">
            <form action="<?php echo $XCOW_B['url'] ?>/snippet/blog-new-form" method="post">
    		<input class='form_input' type='hidden' name='com_title' value='wordpress'>
                <label class="icon icon-wordpress" for="blog-0"><?php echo language('sciomio_text_blog_wordpress'); ?></label>
                <input class="text" type="text" name="com_relation-other" id="blog-0" value="" maxlength="256" />
                <div class="interact">
                    <a href="<?php echo $XCOW_B['url'] ?>/snippet/blog-new-form" title="opslaan" class="tinybutton save"><?php echo language('sciomio_word_ok'); ?></a>
               </div>
            </form>
        </div>
        <div class="inputset item">
            <form action="<?php echo $XCOW_B['url'] ?>/snippet/blog-new-form" method="post">
    		<input class='form_input' type='hidden' name='com_title' value='tumblr'>
                <label class="icon icon-tumblr" for="blog-0"><?php echo language('sciomio_text_blog_tumblr'); ?></label>
                <input class="text" type="text" name="com_relation-other" id="blog-0" value="" maxlength="256" />
                <div class="interact">
                    <a href="<?php echo $XCOW_B['url'] ?>/snippet/blog-new-form" title="opslaan" class="tinybutton save"><?php echo language('sciomio_word_ok'); ?></a>
               </div>
            </form>
        </div>
        <div class="inputset item">
            <form action="<?php echo $XCOW_B['url'] ?>/snippet/blog-new-form" method="post">
    		<input class='form_input' type='hidden' name='com_title' value='posterous'>
                <label class="icon icon-posterous" for="blog-0"><?php echo language('sciomio_text_blog_posterous'); ?></label>
                <input class="text" type="text" name="com_relation-other" id="blog-0" value="" maxlength="256" />
                <div class="interact">
                    <a href="<?php echo $XCOW_B['url'] ?>/snippet/blog-new-form" title="opslaan" class="tinybutton save"><?php echo language('sciomio_word_ok'); ?></a>
               </div>
            </form>
        </div>
        <div class="inputset item">
            <form action="<?php echo $XCOW_B['url'] ?>/snippet/blog-new-form" method="post">
    		<input class='form_input' type='hidden' name='com_title' value='blog'>
                <label class="icon icon-blog" for="blog-0"><?php echo language('sciomio_text_blog_blog'); ?></label>
                <input class="text" type="text" name="com_relation-other" id="blog-0" value="" maxlength="256" />
                <div class="interact">
                    <a href="<?php echo $XCOW_B['url'] ?>/snippet/blog-new-form" title="opslaan" class="tinybutton save"><?php echo language('sciomio_word_ok'); ?></a>
               </div>
            </form>
        </div>
        <div class="inputset add-container">
            <form action="<?php echo $XCOW_B['url'] ?>/snippet/blog-new-form" method="post">
                <label><?php echo language('sciomio_text_blog_toevoegen'); ?></label>
                <ul class="icon-buttons">
                   <li><a href="<?php echo $XCOW_B['url'] ?>/snippet/blog-new-form?fillTitle=blogger" title="blogger" class="add"><span class="icon icon-blogger">B</span></a></li>
                   <li><a href="<?php echo $XCOW_B['url'] ?>/snippet/blog-new-form?fillTitle=wordpress" title="wordpress" class="add"><span class="icon icon-wordpress">W</span></a></li>
                   <li><a href="<?php echo $XCOW_B['url'] ?>/snippet/blog-new-form?fillTitle=tumblr" title="tumblr" class="add"><span class="icon icon-tumblr">T</span></a></li>
                   <li><a href="<?php echo $XCOW_B['url'] ?>/snippet/blog-new-form?fillTitle=posterous" title="posterous" class="add"><span class="icon icon-posterous">P</span></a></li>
                   <li><a href="<?php echo $XCOW_B['url'] ?>/snippet/blog-new-form?fillTitle=blog" title="blog" class="add"><span class="icon icon-blog">Bl</span></a></li>
                </ul>
            </form>
        </div>



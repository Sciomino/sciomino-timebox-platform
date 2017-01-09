<?php
    $page = 'personen';
    $mijnprofiel = 'KenE';

?>
<!DOCTYPE html>
<html lang="nl" class="no-js">
<head>
	<meta charset="UTF-8" />

	<title><?php echo language('sciomio_title_user'); ?></title>

	<?php include("includes/headers.php"); ?>

        <?php include 'skin/'.$session['response']['param']['skin'].'/css.php'; ?>

</head>

<body>

        <?php include 'skin/'.$session['response']['param']['skin'].'/header.php'; ?>

        <div id="Header">
            <div class="page">

		<div class="nav">

		    <?php include 'includes/search.php' ?>

                    <?php include 'includes/nav.php'; ?>

		    <div id="sessionView">
		    </div>

		</div>

            </div>

	    <?php include 'includes/nav-user.php'; ?>

        </div>
	
	<div id="Content">
			<div style="height:1px;"></div>
		<div class="page">
			<div class="unit unit2-3">

				<div class="section form" id="Form-profile">

				    <div class="hgroup">
					<h2><?php echo language('sciomio_header_user_knowledge'); ?></h2>
					<h3><?php echo language('sciomio_text_user_knowledge'); ?></h3>
				    </div>

				    <fieldset class="user-defined divider interactive-set">

					<!--<h3 class="legend col colinput"><?php echo language('sciomio_header_user_knowledge_knowledge'); ?></h3> -->

					<?php
					if (count($session['response']['param']['knowledgeList']) > 0) {
						echo "<div class='fieldset-info highlight'>";
					    	echo "<p>".language('sciomio_text_user_knowledge_suggest');
						foreach ($session['response']['param']['knowledgeList'] as $knowledge => $count) {
							echo "<a class='tag add' href='".$XCOW_B['url']."/snippet/knowledge-new-form?fill=".urlencode($knowledge)."'>$knowledge</a>";
						}
						echo "</p>";
						echo "</div>";
					}
					?>

					<div id="publicationLinkedinSkillsWindow">
						<img src="<?php echo $XCOW_B['url'] ?>/gfx/ajax-loader-circle.gif">
					</div>

					<h3 class="colheader"><?php echo language('sciomio_text_user_knowledge_knowledgeColumn'); ?> <span class="info"><?php echo language('sciomio_text_user_knowledge_knowledgeColumnInfo'); ?></span></h3>
					<h3 class="colheader"><?php echo language('sciomio_text_user_knowledge_levelColumn'); ?></h3>

					<div id="knowledgeListWindow">
						<img src="<?php echo $XCOW_B['url'] ?>/gfx/ajax-loader-circle.gif">
					</div>

				    </fieldset>

				    <fieldset class="user-defined interactive-set">
					<!--<h3 class="legend col colinput"><?php echo language('sciomio_header_user_knowledge_hobby'); ?></h3> -->
					<h3 class="colheader"><?php echo language('sciomio_text_user_knowledge_hobbyColumn'); ?></h3>

					<div id="hobbyListWindow">
						<img src="<?php echo $XCOW_B['url'] ?>/gfx/ajax-loader-circle.gif">
					</div>

				    </fieldset>

				    <fieldset class="final">
					<div class="inputset buttons disabled">
					    <input class="submit button-saveall" type="submit" value="<?php echo language('sciomio_text_knowledge_all_toevoegen'); ?>" />
					    <div class="cancelbox">
						<?php echo language('sciomio_word_or'); ?> <a class="resetall" href="<?php echo $XCOW_B['url'] ?>/user/knowledge"><?php echo language('sciomio_word_resetAll'); ?></a>
					    </div>
					</div>
				    </fieldset>

				</div>
				
			</div>
		</div>
	</div>

        <div id="Footer">
            <div class="page">

                <?php include 'includes/footer.php'; ?>

            </div>
        </div>

 	<div id="sessionPopup" style="display:none">
		<div id="sessionPopupMenu">
		     <a href="javascript:Session.Window.close();"><?php echo language('sciomio_word_close'); ?></a>
		</div>
		<div id="sessionPopupData">
		</div>
	</div>

	<?php include 'includes/scripts.php'; ?>
	<script type="text/javascript">
		addLoadEvent(function() {Session.View.load();});
		addLoadEvent(function() {ScioMino.KnowledgeList.load();});
		addLoadEvent(function() {ScioMino.HobbyList.load();});
		addLoadEvent(function() {ScioMino.Connect.loadLinkedinSkills();});
	</script>

</body>
</html>

<?php
$page = 'kennis';
?>
<!DOCTYPE html>

<html lang="nl" class="no-js">
<head>
	<meta charset="UTF-8" />

	<title><?php echo language('sciomio_title_browse'); ?></title>

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

        </div>

	<div id="Content">
			<div style="height:1px;"></div>
		<div class="page">
                        <?php
                        $languageTemplate = array();
                        $languageTemplate['count'] = $session['response']['param']['stats']['UserCount'];
                        echo "<h1>".language_template('sciomio_header_browse_top', $languageTemplate)."</h1>";
                        ?>

			<div class="group divide div1-2">
				
				<div class="unit unit1-2 ">

					<div class="section softdivide solo">
						<h2><?php echo language('sciomio_header_browse_knowledge'); ?></h2>

						<?php
						/*
							echo "<ul class='pager alphabet'>";

							echo "<li><a class='active' href='javascript:ScioMino.ListKnowledgeFields.load(50,\"\");'>Top50</a></li>";
							$alphabet="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
							for($i=0; $i<strlen($alphabet); $i++) {
								echo "</li><a href='javascript:ScioMino.ListKnowledgeFields.loadAlphabet(\"".$alphabet[$i]."\",50);'>$alphabet[$i]</a></li>";
							}
							$cijfers="0123456789";
							for($i=0; $i<strlen($cijfers); $i++) {
								echo "</li><a class='active' href='javascript:ScioMino.ListKnowledgeFields.loadAlphabet(\"".$cijfers[$i]."\",50);'>$cijfers[$i]</a></li>";
							}

							echo "</ul>";
						*/
						?>

						<div>
							<!--
							<input class="text" type="search" onkeyup="javascript:ScioMino.ListKnowledgeFields.loadQuery(50);" name="query" id="searchKnowledgeBox">
							<span class='sectionhead'><?php echo language('sciomio_header_browse_knowledge_knowledge'); ?></span>
							-->
							<form>
							<input autocomplete="off" onkeyup="javascript:ScioMino.ListKnowledgeFields.loadQuery(50);" class="text placeholder" type="text" name="query" id="searchKnowledgeBox" value="<?php echo language('sciomio_text_browse_choose_knowledge'); ?>" placeholder="<?php echo language('sciomio_text_browse_choose_knowledge'); ?>" maxlength="32"/>
							</form>
						</div>

						<div id="knowledgeListWindow">
							<img src="<?php echo $XCOW_B['url'] ?>/gfx/ajax-loader-circle.gif">
						</div>
					</div>

					<div class="section solo">
						<div>
							<span class='sectionhead'><?php echo language('sciomio_header_browse_knowledge_hobby'); ?></span>
							<form>
								<input autocomplete="off" onkeyup="javascript:ScioMino.ListHobbyFields.loadQuery(20);" class="text placeholder" type="text" name="query" id="searchHobbyBox" value="<?php echo language('sciomio_text_browse_choose_hobby'); ?>" placeholder="<?php echo language('sciomio_text_browse_choose_hobby'); ?>" maxlength="32"/>
							</form>
						</div>

						<div id="hobbyListWindow">
							<img src="<?php echo $XCOW_B['url'] ?>/gfx/ajax-loader-circle.gif">
						</div>
					</div>

					<div class="section solo">
						<div>
							<span class='sectionhead'><?php echo language('sciomio_header_browse_knowledge_tag'); ?></span>
							<form>
								<input autocomplete="off" onkeyup="javascript:ScioMino.ListTagNames.loadQuery(20);" class="text placeholder" type="text" name="query" id="searchTagBox" value="<?php echo language('sciomio_text_browse_choose_tag'); ?>" placeholder="<?php echo language('sciomio_text_browse_choose_tag'); ?>" maxlength="32"/>
							</form>
						</div>

						<div id="tagListWindow">
							<img src="<?php echo $XCOW_B['url'] ?>/gfx/ajax-loader-circle.gif">
						</div>
					</div>

				</div>
				
				<div class="unit unit1-2">
					<div class="section softdivide solo">
						<h2><?php echo language('sciomio_header_browse_experience'); ?></h2>

						<div id="experienceListWindow">
							<img src="<?php echo $XCOW_B['url'] ?>/gfx/ajax-loader-circle.gif">
						</div>
					</div>
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
		addLoadEvent(function() {ScioMino.ListKnowledgeFields.load(20,"");});
		addLoadEvent(function() {ScioMino.ListHobbyFields.load(20);});
		addLoadEvent(function() {ScioMino.ListTagNames.load(20);});
		addLoadEvent(function() {ScioMino.ListExperienceFields.load(20,"");});
	</script>

</body>
</html>


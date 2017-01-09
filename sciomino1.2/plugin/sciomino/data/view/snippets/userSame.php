					<div class="section">
						<?php
						$languageTemplate = array();
						$languageTemplate['user'] = $session['response']['param']['user']['FirstName'];
						echo "<h2>".language_template('sciomio_header_view_same', $languageTemplate)."</h2>";
						?>
						<p><?php echo language('sciomio_text_view_same'); ?></p>
						<ul class="featured">
						<?php
						foreach ($session['response']['param']['sameUser'] as $sameUser) {
							if ($sameUser['Id'] != $session['response']['param']['view']) {
								echo "<li class='img-item'>\n";
								if (! isset($sameUser['photo'])) { $sameUser['photo'] = "/ui/gfx/photo.jpg"; }
								else { $sameUser['photo'] = str_replace("/upload/","/upload/48x48_",$sameUser['photo']); }
								echo "<div class='img'><a href='".$XCOW_B['url']."/view?user=".$sameUser['Id']."'><img src='".$XCOW_B['url'].$sameUser['photo']."' width='48' height='48' alt='' /></a></div>\n";

								echo "<ul class='bd'>\n";
								$me = "";
								if ($session['response']['param']['meUser'] == $sameUser['Id']) {
									$me = "<span class='you-label'>".language('sciomio_word_you')."</span>";
								}
								echo "<li><a class='name' href='".$XCOW_B['url']."/view?user=".$sameUser['Id']."'>".$sameUser['FirstName']." ".$sameUser['LastName']."</a>".$me."</li>\n";
								$displayOrganization = $sameUser['Organization'][get_id_from_multi_array($sameUser['Organization'], 'Name', 'Current')]['division'];
								if ($displayOrganization == "") { $displayOrganization = $sameUser['Organization'][get_id_from_multi_array($sameUser['Organization'], 'Name', 'Current')]['company']; }
								echo "<li>".$sameUser['Organization'][get_id_from_multi_array($sameUser['Organization'], 'Name', 'Current')]['role']." - ".$displayOrganization."</li>\n";
								echo "<li>\n";
								$first = 1;
								$count = 1;
								foreach ($sameUser['knowledgefield'] as $sameKnowledge) {
									if ($first) {$first = 0;}
									else { echo ", "; }
									echo "<a href='".$XCOW_B['url']."/browse/knowledge?k=".urlencode($sameKnowledge['field'])."'>".$sameKnowledge['field']."</a>";
									if ($count > 6) {
										break;
									}
									$count++;
								}
								echo "</li>\n";
								echo "</ul>\n";

								echo "</li>\n";
							}
						}
						?>
						</ul>
					</div>

					<div class="section">
						<ul>
							<li class="article">
								<div class="img-item">
									<div class="img"><img src="<?php echo $XCOW_B['url'] ?>/ui/gfx/logo_wikipedia.png" width="29" height="36" alt="Logo Wikipedia" /></div>
									<div class="bd">
										<h3><?php echo language('sciomio_header_browse_knowledge_external'); ?></h3>
										<?php 
										if ($session['response']['param']['externalContent'] != "niet gevonden") {
											echo "<p>".$session['response']['param']['externalContent']."</p>";
											echo "<a target='_blank' href='".$session['response']['param']['externalContentUrl']."' class='more'>".language('sciomio_word_browse_knowledge_external_more')."</a>";
										}
										else {
											echo language('sciomio_text_browse_knowledge_notfound');
										}
										?>
									</div>
								</div>
							</li>
						</ul>
					</div>

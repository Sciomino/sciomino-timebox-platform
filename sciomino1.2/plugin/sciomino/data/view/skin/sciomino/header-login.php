<div class="branded2" id="Sciomino2">
	<div class="page"  style="margin-top:20px; background-color: white;">

		<div class="puu-hdr">
			<?php echo "<a class='puu-lgo' href='".$XCOW_B['url']."/'><img height='51px' widht='200px' src='".$XCOW_B['url']."/ui/skin/sciomino/gfx/logo_sciomino.png' alt='Sciomino' border='0' /></a>"; ?>
			
			<div class="puu-nav">
					<ul>
						<li><a  href="http://about.sciomino.com/<?php echo $session['response']['language']?>">About</a></li>
						<li><a  href="http://discover.sciomino.com/<?php echo $session['response']['language']?>">Discover</a></li>
						<li><a  href="http://developer.sciomino.com/<?php echo $session['response']['language']?>">Developer</a></li>
						<li><a  href="http://business.sciomino.com/<?php echo $session['response']['language']?>">Business</a></li>
						<li><a  href="http://support.sciomino.com/<?php echo $session['response']['language']?>">Support</a></li>
						<li class="puu-login"><span><?php if ($session['request']['language'] == "nl") { echo "<a href='/en/".$session['request']['path_info']."'><img alt='en' src='/ui/skin/sciomino/gfx/login/ico_en.png' width='32' height='32' border='0'></a>"; } else { echo "<a href='/nl/".$session['request']['path_info']."'><img alt='nl' src='/ui/skin/sciomino/gfx/login/ico_nl.png' width='32' height='32' border='0'></a>"; } ?></span></li>
					</ul>
			</div>
		</div>
		
	</div>
</div>

<form>
	<input onClick="ScioMino.List.check(<?php echo $session['response']['param']['user']; ?>,<?php echo $session['response']['param']['listId']; ?>,event)" type="checkbox" class="checkbox" id="<?php echo $session['response']['param']['listName']; ?>" name="<?php echo htmlTokens($session['response']['param']['listName']); ?>">
	<label for="<?php echo $session['response']['param']['listName']; ?>"><?php echo $session['response']['param']['listName']; ?></label>
</form>



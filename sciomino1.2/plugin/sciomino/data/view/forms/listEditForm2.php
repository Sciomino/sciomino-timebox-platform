<form>
	<a class="listname" href="<?php echo $XCOW_B['url'] ?>/search?l[<?php echo urlencode($session['response']['param']['listName']); ?>]"><?php echo $session['response']['param']['listName']; ?></a>
	<span class="interact">
		<a class="edit" title="wijzig" href="<?php echo $XCOW_B['url'] ?>/snippet/list-edit-form?listId=<?php echo $session['response']['param']['listId']; ?>">e</a>
		<a class="remove" title="verwijder" href="<?php echo $XCOW_B['url'] ?>/snippet/list-delete?listId=<?php echo $session['response']['param']['listId']; ?>">x</a>
	</span>
</form>


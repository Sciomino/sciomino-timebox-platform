<?

class shortcutList extends control {

    function Run() {

        global $XCOW_B;

	# different views depending on local or remote profile infomration
	if ($XCOW_B['sciomino']['shortcut-view'] == 'local') {
      	    	$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/snippets/shortcutListLocal.php';
	}
        
     }

}

?>

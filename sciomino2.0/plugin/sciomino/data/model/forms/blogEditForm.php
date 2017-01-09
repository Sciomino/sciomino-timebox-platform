<?

class blogEdit extends control {

    function Run() {

        global $XCOW_B;

	$this->blog = array();

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);


	// params
	# $this->userId = $this->ses['request']['param']['userId'];

 	$this->blogId = makeIntString($this->ses['request']['param']['blogId']);

	$this->blog['title'] = $this->ses['request']['param']['com_title'];
	#$this->blog['description'] = $this->ses['request']['param']['com_description'];
	#$this->blog['relation-self'] = $this->ses['request']['param']['com_relation-self'];
	$this->blog['relation-other'] = $this->ses['request']['param']['com_relation-other'];

	//
	// check fields?
	//
	$input = array($this->blog['title']);
	if (! noEmptyInput($input) ) {
		$this->status = "Input Error";
	}
	$this->blog['relation-other'] = urlCompletion($this->blog['relation-other']);

	// allow update?
	if (! array_key_exists($this->blogId, ScioMinoApiGetBlog($this->userId, $this->blogId) )) {
		$this->status = "Access denied";
	}

        //
        // if the fields are checked, go for it
        // otherwise proceed to the view and show a form where a new product can be entered
        //
        if (! $this->status) {

		// save
		$blogId = ScioMinoApiUpdateBlog($this->blog, $this->userId, $this->blogId);

		if ($blogId != 0) {
			$this->status = "De blog is bewerkt.";
		}
		else{
			$this->status = "De blog kon niet bewerkt worden.";
		}

		$this->ses['response']['param']['blogId'] = $blogId;
		$this->ses['response']['param']['blogTitle'] = $this->blog['title'];
		$this->ses['response']['param']['blogRelation-other'] = $this->blog['relation-other'];

      	    	$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/forms/blogEditForm2.php';
        }
        
	// show the form
        else {
		$this->blog = ScioMinoApiGetBlog($this->userId, $this->blogId);
		$this->ses['response']['param']['blog'] = $this->blog[$this->blogId];
		$this->ses['response']['param']['blogId'] = $this->blogId;
        }

        $this->ses['response']['param']['status'] = $this->status;

    }

}

?>

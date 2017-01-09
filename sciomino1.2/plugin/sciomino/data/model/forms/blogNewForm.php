<?

class blogNew extends control {

    function Run() {

        global $XCOW_B;

	$blogId = 0;
	$this->blog = array();

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);


	// params
	# $this->userId = $this->ses['request']['param']['userId'];

	$this->blog['title'] = $this->ses['request']['param']['com_title'];
	#$this->blog['description'] = $this->ses['request']['param']['com_description'];
	#$this->blog['relation-self'] = $this->ses['request']['param']['com_relation-self'];
	$this->blog['relation-other'] = $this->ses['request']['param']['com_relation-other'];
	$this->fillTitle = $this->ses['request']['param']['fillTitle'];
	$this->fill = $this->ses['request']['param']['fill'];

	//
	// check fields?
	//
	$input = array($this->blog['title']);
	if (! noEmptyInput($input) ) {
		$this->status = "Input Error";
	}
	$this->blog['relation-other'] = urlCompletion($this->blog['relation-other']);

        //
        // if the fields are checked, go for it
        // otherwise proceed to the view and show a form where a new product can be entered
        //
        if (! $this->status) {

		// save
		$blogId = ScioMinoApiSaveBlog ($this->blog, $this->userId, '1'); 

		if ($blogId != 0) {
			$this->status = "De blog is toegevoegd.";
		}
		else{
			$this->status = "De blog kon niet toegevoegd worden.";
		}

		$this->ses['response']['param']['blogId'] = $blogId;
		$this->ses['response']['param']['blogTitle'] = $this->blog['title'];
		$this->ses['response']['param']['blogRelation-other'] = $this->blog['relation-other'];

      	    	$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/forms/blogNewForm2.php';
        }
        
	// show the form
        else {
		#$this->ses['response']['param']['productId'] = $this->productId;
		$this->ses['response']['param']['fillTitle'] = $this->fillTitle;
		$this->ses['response']['param']['fill'] = $this->fill;
        }

        $this->ses['response']['param']['status'] = $this->status;

    }

}

?>

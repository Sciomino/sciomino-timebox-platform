<?

class blogDelete extends control {

    function Run() {

        global $XCOW_B;

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);
        
	// init
 	$this->blogId = makeIntString($this->ses['request']['param']['blogId']);

	// allow delete?
	$this->blog = ScioMinoApiGetBlog($this->userId, $this->blogId);
	if (array_key_exists($this->blogId, $this->blog)) {
		// delete 
		if (ScioMinoApiDeleteBlog($this->userId, $this->blogId) != 0) {
			$this->status = "De blog is verwijderd.";
		}
		else{
			$this->status = "De blog kon niet verwijderd worden.";
		}
	}

	// status
        $this->ses['response']['param']['status'] = $this->status;

     }

}

?>

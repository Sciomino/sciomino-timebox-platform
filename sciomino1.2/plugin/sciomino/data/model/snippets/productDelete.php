<?

class productDelete extends control {

    function Run() {

        global $XCOW_B;

	// who?
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);
        
	// init
 	$this->productId = makeIntString($this->ses['request']['param']['productId']);

	// allow delete?
	$this->product = ScioMinoApiGetProduct($this->userId, $this->productId);
	if (array_key_exists($this->productId, $this->product)) {
		// delete 
		if (ScioMinoApiDeleteProduct($this->userId, $this->productId) != 0) {
			$this->status = "De productervaring is verwijderd.";

			// verwijder file
			if ($this->product[$this->productId]['relation-image'] != "") {
				unlink($XCOW_B['upload_destination_dir']."/".basename($this->product[$this->productId]['relation-image']));
			}

		}
		else{
			$this->status = "De productervaring kon niet verwijderd worden.";
		}
	}

	// status
        $this->ses['response']['param']['status'] = $this->status;

     }

}

?>

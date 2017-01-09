<?

class mailblockSave extends control {

    function Run() {

		global $XCOW_B;

		#
		# init
		#
		$mailblockId = 0;

		#
		# get params
		#
		$this->act = $this->ses['request']['param']['act'];
		$this->reference = $this->ses['request']['param']['reference'];

		#
		# check reference
		#
		if (! isset($this->reference) ) {
			$this->reference = "";
		}

		#
		# NEW
		#
		if (! $this->status) {

			$mailblockId = MailblockInsert($this->act, $this->reference);

			if ($mailblockId == 0) {
				$this->status = "500 Internal Error";
			}

		}

		#
		# Content
		#
        $this->ses['response']['param']['mailblockId'] = $mailblockId;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }

}

?>

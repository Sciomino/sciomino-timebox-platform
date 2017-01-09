<?

class mailblockDelete extends control {

    function Run() {

        global $XCOW_B;

		#
		# init
		#
		$mailblocks = array();

		#
		# get params
		# - mailblock/ID/delete
		# - mailblock/delete?mailblock[ID1]&mailblock[ID2]
		#
		$this->mailblockId = $this->ses['request']['REST']['param'];
		$this->mailblockIdList = $this->ses['request']['param']['mailblock'];

		#
		# create mailblock list
		#
        if (isset ($this->mailblockId)) {
			$mailblocks[] = $this->mailblockId;
        }

        if (isset ($this->mailblockIdList)) {
			foreach (array_keys($this->mailblockIdList) as $aKey) {
				$mailblocks[] = $aKey;
			}
        }

		#
		# DELETE
		#
		if (! $this->status) {

			$this->status = MailblockDelete($mailblocks);

		}

		#
		# Content
		#
        $this->ses['response']['param']['mailblocks'] = $mailblocks;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }

}

?>

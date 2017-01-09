<?php

class userPersonaliaList extends control {

    function Run() {

        global $XCOW_B;
		//
		// who?
		//
		$this->id = $this->ses['id'];
		$this->userId = UserApiGetUserFromReference($this->id, "SC_UserApiGetUserFromReference_".$this->id);
		# do not cache, because this is the viewer himself
		#$this->userInfo = current(UserApiListUserWithQuery("reference=".$this->id."&format=long", "SC_UserApiListUserWithQuery_".$this->id."_long"));

		// param

		// init
		$userList = array();

		// go
		// - get the user list of the current user
		$userList = UserApiListUserWithQuery("reference=".$this->id."&format=short");

		// include photo
		foreach ($userList as $id => $user) {
			if (isset($user['photo'])) {
				$user['photo'] = str_replace("/upload/","96x96_",$user['photo']);
				$userList[$id]['photoStream'] = base64_encode(file_get_contents($XCOW_B['upload_destination_dir']."/".$user['photo']));
			}
		}

		// content
		$this->ses['response']['param']['user'] = $this->userId;
		$this->ses['response']['param']['userList'] = $userList;
		
		// allow resource, for testing only!!!
		$this->ses['response']['header'] = "Access-Control-Allow-Origin:*"; 

     }

    function GetHeader() {
        #$this->ses['response']['header'] = header ("Content-Type: application/json\n\n");
        return ($this->ses['response']['header']);
    }

}

?>

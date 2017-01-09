<?

class profileDelete extends control{

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$profiles = array();

	#
	# get params
	# - profile/ID/delete
	# - profile/delete?profile[ID1]&profile[ID2]
	#
	$this->profileId = $this->ses['request']['REST']['param'];
	$this->profileIdList = $this->ses['request']['param']['profile'];

        $this->object = $this->ses['request']['param']['object'];

	#
	# create profile list
	#
        if (isset ($this->profileId)) {
                $profiles[] = $this->profileId;
        }

        if (isset ($this->profileIdList)) {
                foreach (array_keys($this->profileIdList) as $aKey) {
                        $profiles[] = $aKey;
                }
        }

	#
	# DELETE
	#
	if (! $this->status) {

		# check user before delete, could be losing annotations...
                $userId = userProfileGetUserId($this->object, $profiles[0]);
                if ($userId != 0) {
                        setQueueEntry($userId, $userId);
                }

		$this->status = UserProfileDelete($profiles, $this->object);

        }

	#
	# Content
	#
        $this->ses['response']['param']['profiles'] = $profiles;

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }

}

?>

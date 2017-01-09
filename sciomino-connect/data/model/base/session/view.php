<?

class sessionView extends control {

    function Run() {

        global $XCOW_B;
        
        $id = $this->ses['id'];

	if (isset($id) ) {
		$this->ses['response']['param']['id'] = $id;

                $name = getUserDisplayNameFromUserId($id);
                if ($name == '') { $name = getUserNameFromUserId($id);}
                $this->ses['response']['param']['user'] = $name;
	}
	else {
		$this->ses['response']['param']['id'] = 0;
	}

    }

}

?>

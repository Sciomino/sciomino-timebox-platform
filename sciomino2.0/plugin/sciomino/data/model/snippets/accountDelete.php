<?

class accountDelete extends control {

    function Run() {

        global $XCOW_B;

		// who?
		$this->id = $this->ses['id'];
		// userId
		$this->userId = UserApiGetUserFromReference($this->id);

		// params
		$this->go = $this->ses['request']['param']['go'];

		//
		// check fields?
		//
		if ($this->go != $this->ses['time']) {
			$this->status = "Intruder alert (CSRF)";
		}

        //
        // if the fields are checked, go for it
        // otherwise proceed to the view and show a form where a user can choose to logout
        //
        if (! $this->status) {
			# take care of id (in Session table from frontend) & userId (in User table from API)

			# user should be removed from index first!
			UserApiListListDelete($this->userId);
									
			# delete from answers api
			# - first get answers from user
			# - second: delete answers
			$answersList = AnswersApiListActWithQuery("reference=".$this->id);
			foreach (array_keys($answersList) as $actId) {
				AnswersApiDeleteAct($actId);
			}
			
			# delete from user api
			UserApiDeleteUser($this->userId);

			# deactivate OR delete from session
			# - for now... delete, there is not an 'activate session' implemented in this script
			#deactivateSession($userName);
			stopSession($this->id);
			registerDelete($this->id);					

			//$this->ses['response']['redirect'] = "/".$XCOW_B['url'];
			$this->status = "<a href='/'>".language('sciomio_word_ok')."</a>";
			$this->ses['response']['view'] = $XCOW_B['view_base'].'/web/sciomino/snippets/accountDelete2.php';
		}
		
		// show the form
		else {

		}

		$this->ses['response']['param']['status'] = $this->status;

	}

}

?>

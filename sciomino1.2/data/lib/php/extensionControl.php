<?

class extensionControl {

    public $ses;
    public $timestart;
    public $timestamp;
    public $status;

    function __construct($session) {

	#
	# session
	#
        $this->ses = $session;
 
        #
        # for statistics
        #
        $this->timestart = getMicrotime();
        $this->timestamp = time();
	$this->status = NULL;
	
    }

    function Run() {

	### Do your thing in the subclass

    }

    function GetExtensionParam() {

        return ($this->ses['response']['extension_param']);

    }

}

?>

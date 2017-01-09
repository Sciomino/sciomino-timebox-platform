<?

class control {

    public $ses;
    public $timestart;
    public $timeend;
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

	#
	# flush!
	#
    	@apache_setenv('no-gzip', 1);
    	@ini_set('zlib.output_compression', 0);
	if ($XCOW_B['use_flush'] == 1) {
		# geen flush voor ajax met jquery
    		@ini_set('implicit_flush', 1);
		# in sommige onmstandigheden werkt dit ook niet...
    		for ($i = 0; $i < ob_get_level(); $i++) { ob_end_flush(); }
    		ob_implicit_flush(1);
	}
	
    }

    function Run() {

	### Do your thing in the subclass

    }

    function Finish() {

        #
        # Statistics
        #
        $this->timeend = getMicrotime();
        if ( ($this->timeend - $this->timestart) > 2) {
                log2file("Slow Query [".getRequest($this->ses)."]: Time (>2): ".($this->timeend - $this->timestart));
        }

        #
        # Header
        #
        $this->ses['response']['stats']['time'] = $this->timeend - $this->timestart;
        $this->ses['response']['stats']['date'] = $this->timestamp;
        $this->ses['response']['stats']['request'] = getRequest($this->ses);
        $this->ses['response']['stats']['status'] = getStatus($this->status);

    }

    function GetView() {

        return ($this->ses['response']['view']);

    }

    function GetHeader() {

        return ($this->ses['response']['header']);

    }

    function GetRedirect() {

        return ($this->ses['response']['redirect']);

    }

    function GetParam() {

        return ($this->ses['response']['param']);

    }

    function GetStats() {

        return ($this->ses['response']['stats']);

    }

}

?>

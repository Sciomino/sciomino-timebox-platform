<?

class myxmlView extends control {

    function Run() {

	$this->param = $this->ses['request']['REST']['param'];
        
	$this->ses['response']['param']['restparam'] = $this->param;

    }

}

?>

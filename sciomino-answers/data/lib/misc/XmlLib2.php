<?php
/*
 * This library contains functions for handling of xml files
 *
 *
 * The library implements the following classes
 * - Xml2Php
 * - Php2Xml
 *
 * @author      Herman van Dompseler <herman@dompseler.nl>
 * @Date        29 Januari 2004
 *
 * version 2	28 november 2007
 * changes	- gaat nu goed om met elementen die herhaald worden
 *        	- attributen gaan ook mee
 *        	- data wordt gecodeerd alvorens de array door 'eval' te halen
 *        	- support for data entries that are split in multiple fields
 * 
 * version 3	7 juli & 16 november 2010
 * changes	- changed string functions in mb_string functions
 * 		- fix voor attributen
 *
 */

/*
 * Xml2Php converter
 *
 * This class converts a Xml file to a Php hash using the build in xml_parser.
 *
 * input: $file: the file with xml data
 * output: $php: the php array
 *
 * the converter treats a tag as an array of objects if it contains other tags.
 * if a tag only contains data is is treated as a string.
 *
 * limitations:
 * - tags contain other tags OR data, NOT a combination
 *
 */

class Xml2Php2 {

    var $attr_name = "Attributes";

    var $parser    = "";
    var $php       = "";
    var $php_element = "";

    var $path      = "";
    var $path_prev = array();
    var $path_count = array();
    var $path_list = 0;
    var $path_attrs = array();

    var $count     = 0;
    var $depth     = 0;
    var $last      = 0;

    var $IsData    = 0;

    function Xml2Php2 ()  {

        #
        # init parser
        #
        $this->parser = xml_parser_create();
        xml_set_object($this->parser, $this);
        xml_parser_set_option( $this->parser, XML_OPTION_CASE_FOLDING, false );
        xml_parser_set_option( $this->parser, XML_OPTION_TARGET_ENCODING, "UTF-8");
        xml_set_element_handler($this->parser, "startElement", "endElement"); 
        xml_set_character_data_handler($this->parser, "characterData");

    }

    function startProcessing ($file, $isString)  {

        $fp   = "";
        $data = "";
        
        #
        # read xml and parse
        #
        if ($isString) {
            #xml_parse($this->parser, $file);
			if (!xml_parse($this->parser, $file)) {
				log2file (sprintf("XML error: %s at line %d",
					xml_error_string(xml_get_error_code($this->parser)),
					xml_get_current_line_number($this->parser)));

				$tempNr = xml_get_current_line_number($this->parser);
				$tempFile = explode("\n", $file);
				log2file ("XML line ".$tempNr.": ".$tempFile[$tempNr-1]);

				$tempString = "";
				for ($i = 0; $i < 10; $i++) {
					$tempString .= ($i+1).": ".$tempFile[$i]."\n";
				}
				log2file ("XML info (first 10 lines): \n".$tempString);
			}
        }
        else {
            if (!($fp = fopen($file, "r"))) {
                die("could not open XML input: ".$file);
            }

            while ($data = fread($fp, 4096)) {
                if (!xml_parse($this->parser, $data, feof($fp))) {
                    die (sprintf("XML error: %s at line %d",
                        xml_error_string(xml_get_error_code($this->parser)),
                        xml_get_current_line_number($this->parser)));
                }
            }
        }

        #
        # clean
        #
        xml_parser_free($this->parser); 

    }

    function startElement($parser, $name, $attrs) {

	#
	# count xml elements
	#
	#if ($this->depth == 1) {
	#	$this->count++;
        #	log2file("$this->count");
	#}

        $this->path .= "/".$name;

	#
	# $this->path_count[] houdt bij hoeveel dezelfe elementen voorkomen in de XML 
	# - dit is hetzelfde als de KEY van de items, deze wordt dus ook gebruikt voor de KEY van de attributen
        # $this->path_prev[] houdt bij welk element op ditzelfde niveau hiervoor was.
	# $this->path_list geeft aan of het een herhalend element betreft
	#
	# Er is sprake van een herhaald element 
	# 1. als het pad al eerder was voorgekomen 
	# 2. het vorige element hetzelfde was als deze
	#
	# in bijvoorbeeld:
	# <xml>
	#   <a>
	#	<b>1</b>
	#	<c>2></c>
	#   </a>
	#   <a>
	#	<b>3</b>
	#	<c>4></c>
	#   </a>
	# </xml>
	#
	# In dit voorbeeld wordt element <a> herhaald. (maar <b> dus niet)
	#
        if (array_key_exists($this->path, $this->path_count) &&  $this->path_prev[$this->depth] == $this->path) {
		$this->path_count[$this->path] = $this->path_count[$this->path] + 1;
		$this->path_list = 1;
	}
	else {
		$this->path_count[$this->path] = 0;
		$this->path_list = 0;
	}

	# echo "<BR><BR>\n\nSTART: path: ".$this->path.", count:".$this->path_count[$this->path].", prev:".$this->path_prev[$this->depth];


        #
        # if we encountered closing tags
        # then the new opening tag is the next in the array
        #
        if ($this->depth < $this->last) {
		if (! $this->path_list ) {
            		$this->php_element .= "),";
		}
		else {
            		$this->php_element .= ",";
		}
        }

        #
        # include attributes
        #
	# here we create the attrs array and store it for this path in '$this->path_attrs[]'
	# the name of the attributes array is [KEY:Attributes]
	#
	# Voorbeeld van herhaling van elementen met attributen
	# xml:
	# <xml>
	#   <a att1/>
	#   <a att2/>
	# </xml>
	#
	# php: 
	# a -> 
	# 	[0] ->
	# 	[0:attr] -> att1
	# 	[1] ->
	# 	[1:attr] -> att2
	#
	if ($attrs) {
        	$my_attrs = '"'.$this->path_count[$this->path].":".$this->attr_name.'" => array(';
        	foreach ($attrs as $key => $value) {
                	$my_attrs .= '"'.$key.'" => "'.$value.'",';
        	}
		$my_attrs .= ')';
		$this->path_attrs[$this->path] = $my_attrs;
        }
	else {
		$this->path_attrs[$this->path] = '';
	}

        #
        # assume everything is an array
        #
        #$this->php_element .= '"'.$name.'" => array(';
	if (! $this->path_list ) {
        	$this->php_element .= '"'.$name.'" => array('.'"'.$this->path_count[$this->path].'" => array(';
	}
	else {
        	$this->php_element .= '"'.$this->path_count[$this->path].'" => array(';
	}

        #
        # keep up
        #
	$this->path_prev[$this->depth] = $this->path;
        $this->depth++;
        $this->last = $this->depth;

    }

    function endElement($parser, $name) {

	$my_attrs = $this->path_attrs[$this->path];

        $this->path = mb_substr($this->path, 0, mb_strrpos($this->path, "/"));

	# echo "<BR>\nEND: path: ".$this->path;

	#
	# an empty element is not an empty array, but empty data
	#
	if (mb_strrpos($this->php_element, "array(") == mb_strlen($this->php_element) - 6) {
            	$this->php_element = mb_substr($this->php_element, 0, mb_strlen($this->php_element) - 6);
                $this->php_element .= '""';
		$this->IsData = 1;
        }

        #
        # If the previous match was data
        # then we do not end an array
        #
	# at the end we include the attributes if they exist
	#
        if (! $this->IsData ) {
		if ($my_attrs != '') {
            		$this->php_element .= "),".$my_attrs.")";
		}
		else {
            		$this->php_element .= "))";
		}
        }
        else {
            	$this->IsData = 0;
		if ($my_attrs != '') {
            		$this->php_element .= ",".$my_attrs;
		}
        }

        #
        # keep up
        #
	$this->php .= $this->php_element;
	$this->php_element = "";

	$this->path_prev[$this->depth] = "";
        $this->depth--;

    }

    function characterData($parser, $data) {

	# echo "<BR>\nDATA: ".$data;

	#
	# Note that data entries can be split over more fields.
	#

        if ($this->IsData == 1 || trim($data) != "") {

            #
            # remove the array opening for data: "array("
	    # and (1) put a new data entry
            #
	    if (mb_strrpos($this->php_element, "array(") == mb_strlen($this->php_element) - 6) {
            	$this->php_element = mb_substr($this->php_element, 0, mb_strlen($this->php_element) - 6);
                $this->php_element .= '"'.$this->encryptCharacterData($data).'"';
	    }
            #
            # remove the trailing " from the data entry
	    # and (2) append a data entry
            #
	    else {
            	$this->php_element = mb_substr($this->php_element, 0, mb_strlen($this->php_element) - 1);
                $this->php_element .= $this->encryptCharacterData($data).'"';
	    }


            $this->IsData = 1;

        }

    }

    function getPhpArray() {

	# save memory? put htis in the 'eval' statement below
        # $this->php = "array(".$this->php.") );";

	# echo "<BR>\n ARRAY: ".$this->php;

        if ( eval ("\$this->php = array($this->php) );") === false) {
		$this->php = array();
	}

	$this->php = $this->decryptCharacterData($this->php);

        # $output = print_r ($this->php, 'TRUE');
        # log2file("DEBUG XMLlib 2: \n$output\n");

        return $this->php;

    }

    function encryptCharacterData($data) {
	#return ($data);
	return toUnicode($data);
    }

    function decryptCharacterData($php_array) {
	
        foreach ($php_array as $key => $value) {
		if ( is_array($php_array[$key]) ) {
			$php_array[$key] = $this->decryptCharacterData($php_array[$key]);
            	}
		else {
			#$php_array[$key] = $value;
			$php_array[$key] = fromUnicode($value);
		}
        }
	
	return ($php_array);

    }

    function getPhpText() {

        return $this->php;

    }

}

/*
 * Php2Xml converter
 *
 * This class converts a Php hash to a Xml file and writes the xml to disk
 *
 * input: $file: the file with xml data
 * input: $php: the php array
 * input: $attribute: the attribute which identifies the tag
 * output: $xml
 *
 */

class Php2Xml2 {

    var $attr_name = "Xml2Php:Attributes";
    var $attribute = array();

    var $list_tag    = array();

    var $xml         = "";

    var $path        = "";
    var $depth       = 0;
    var $IsData      = 0;
    var $IsMaster    = array();

    function Php2Xml2 ()  {

	# nothing to init

    }

    function startProcessing ($php)  {

        $this->php_parse($php);

    }

    function php_parse($php) {

        foreach ($php as $key => $value) {

            	#
            	# Open Tag
            	#
            	if(is_array($value)) {
			# numeric indexes are assumed to be introduced by path_list from Xml2Php
			# - do not show them here
			if (is_int($key)) {
            			for ($i = 0; $i < $this->depth - 1; $i++) {
                			$this->xml .= "\t";
            			}

				# show the previous tag
				# !!!SHOULD INCLUDE ATTRIBUTES!!!
            			$this->xml .= "<".$this->list_tag[$this->depth - 1].">\n";

                		# recurse the index
                		$this->php_parse ($value);

                                $this->IsData = 0;

			}
			else {
            			for ($i = 0; $i < $this->depth; $i++) {
                			$this->xml .= "\t";
            			}

				# store this tag
				$this->list_tag[$this->depth] = "$key";

	    			# exclude the attribute key, this is already been taken care of in the value (below)
	    			if ($key !== $this->attr_name) {
            		
					# do not show the master of the index, the index is shown later
					if (! is_array($value['0'])) {
            					# if we match the attribute name in the value, we introduce the attributes.
            					if (array_key_exists($this->attr_name, $value)) {
            						$this->xml .= "<".$key;
							foreach ($value[$this->attr_name] as $key2 => $val2) {
            							$this->xml .= " ".$key2."=\"".$val2."\"";
							}
            						$this->xml .= ">";
            					}
	    					else {
            						$this->xml .= "<".$key.">";
            					}
          
                				$this->xml .= "\n";
						$this->IsMaster[$this->depth] = 0;
            				}
            				else {
						$this->IsMaster[$this->depth] = 1;
            				}

                			$this->path .= "/".$key;
                			$this->depth++;

                			# recurse
                			$this->php_parse ($value);

                			$this->path = substr($this->path, 0, strrpos($this->path, "/"));
                			$this->depth--;

                			$this->IsData = 0;
            			}
            		}
            	}

                #
                # Cdata
                #
            	else {
            		for ($i = 0; $i < $this->depth; $i++) {
                		$this->xml .= "\t";
            		}
                	$this->xml .= $value;

                	$this->IsData = 1;
            	}

            	#
            	# Close Tag
            	#
            	if (! $this->IsData) {
			# different behaviour for lists and 'normal' tags
			if (is_int($key)) {
                		for ($i = 0; $i < $this->depth - 1; $i++) {
                    			$this->xml .= "\t";
                		}
            			$this->xml .= "</".$this->list_tag[$this->depth - 1].">\n";
                	}
			else {
				# do not show the master of a list
				if (! $this->IsMaster[$this->depth]) {
                			$this->xml .= "\n";
                			for ($i = 0; $i < $this->depth; $i++) {
                    				$this->xml .= "\t";
                			}
            				$this->xml .= "</".$key.">\n";
				}
			}
            	}
        }
    }

    function getXml() {

        return $this->xml;

    }

    function getXmlText() {

        $patterns[0] = "/</";
        $patterns[1] = "/>/";

        $replacements[0] = "&lt;";
        $replacements[1] = "&gt;";

        return preg_replace($patterns, $replacements, $this->xml);

        return $this->xml;

    }

    function saveXml( $file ) {

        $status = 0;

        #
        # Backup the old xml file
        #
        if ( is_file($file) && !copy($file, $file.'.bak')) {
            print ("failed to copy $file...\n");
        }

        $status = $this->overwriteFile($file);

        return $status;

    }

    function overwriteFile( $file ) {

        #
        # overwrite the $file
        #
        if (!file_exists($file) || is_writable($file)) {
            if (!$fh = fopen($file, "w")) {
                print "Cannot open file ($file)";
                return 2;
            }

            if (!fwrite($fh, $this->xml, strlen($this->xml))) {
                print "Cannot write to file ($file)";
                return 3;
            }

            fclose($fh);
            return 0;

        }
        else {
            return 1;
        }

    }

}

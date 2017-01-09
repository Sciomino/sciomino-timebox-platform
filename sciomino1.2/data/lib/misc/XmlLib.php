<?php
/*
 * This library contains functions for handling of xml files
 *
 *
 * The library implements the following classes
 * - Xml2Php
 * - Php2Xml
 *
 * @author      Herman van Dompseler <herman@info.nl>
 * @Date        29 Januari 2004
 *
 */

/*
 * Xml2Php converter
 *
 * This class converts a Xml file to a Php hash using the build in xml_parser.
 *
 * input: $file: the file with xml data
 * input: $attribute: the attribute which identifies the tag
 * output: $php: the php array
 *
 * the converter treats a tag as an array of objects if it contains other tags.
 * if a tag only contains data is is treated as a string.
 *
 * limitations:
 * - tags contain other tags OR data, NOT a combination
 * - attributes are only used to identify tags in the php array 
 *   (other attributes are ignored)
 *
 */

class Xml2Php {

    var $attr      = "";
    var $attr_path = "";
    var $attr_key  = "";

    var $parser    = "";
    var $php       = "";

    var $path      = "";
    var $depth     = 0;
    var $last      = 0;
    var $IsData    = 0;

    function Xml2Php ()  {

    }

    function startProcessing ($file, $isString)  {

        $this->Xml22Php($file, $isString, null);

    }

    function StartProcessingWithAttribute ($file, $isString, $attribute)  {

        $this->Xml22Php($file, $isString, $attribute);

    }

    function Xml22Php ($file, $isString, $attribute)  {

        $fp   = "";
        $data = "";

        #
        # parse attribute
        #
        # attribute example:
        # attr      => /factories/factory@id
        # attr_path => /factories/factory
        # attr_key  => id
        #
        $this->attr = $attribute;
        if ($this->attr) {
            preg_match("/([^\@]*)\@(.*)/", $attribute, $matches);
            $this->attr_path = $matches[1];
            $this->attr_key = $matches[2];
        }

        #
        # init parser
        #
        $this->parser = xml_parser_create();
        xml_set_object($this->parser, &$this);
        xml_parser_set_option( $this->parser, XML_OPTION_CASE_FOLDING, false );
        xml_set_element_handler($this->parser, "startElement", "endElement"); 
        xml_set_character_data_handler($this->parser, "characterData");
        
        #
        # read xml and parse
        #
        if ($isString) {
            xml_parse($this->parser, $file);
        }
        else {
            if (!($fp = fopen($file, "r"))) {
                die("could not open XML input");
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

        $this->path .= "/".$name;

        #
        # if the specified attribute is found we use the value
        # instead of the tag name
        #
        if ($this->attr) {
            if ( ($this->attr_path == $this->path) && array_key_exists($this->attr_key, $attrs) ) {
                $name = $attrs[$this->attr_key];
            }
        }

        #
        # if we encountered closing tags
        # then the new opening tag is the next in the array
        #
        if ($this->depth < $this->last) {
            $this->php .= ",";
        }

        #
        # include attributes
        #
        foreach ($attrs as $key => $value) {
            if (($this->attr && $key != $this->attr_key) || ! $this->attr) {
                $this->php .= '"'.$key.'" => "'.$value.'",';
            }
        }

        #
        # assume everything is an array
        #
        $this->php .= '"'.$name.'" => array(';

        #
        # keep up
        #
        $this->depth++;
        $this->last = $this->depth;

    }

    function endElement($parser, $name) {

        $this->path = substr($this->path, 0, strrpos($this->path, "/"));

        #
        # If the previous match was data
        # then we do not end an array
        #
        if (! $this->IsData ) {
            $this->php .= ")";
        }
        else {
            $this->IsData = 0;
        }

        #
        # keep up
        #
        $this->depth--;

    }

    function characterData($parser, $data) {

        if (trim($data) != "") {

            #
            # remove the array opening for data: "array("
            #
            $this->php = substr($this->php, 0, strlen($this->php) - 7);

            $this->php .= '"'.trim($data).'"';

            $this->IsData = 1;

        }

    }

    function getPhpArray() {

        $this->php = "array(".$this->php.");";
        eval ("\$this->php = $this->php");

        return $this->php;

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

class Php2Xml {

    var $attr        = "";
    var $attr_path   = "";
    var $attr_key    = "";
    var $attr_prefix = "";
    var $attr_value  = "";

    var $xml         = "";

    var $path        = "";
    var $depth       = 0;
    var $IsData      = 0;

    function Php2Xml ($php)  {

        $this->Php22Xml($php, null);

    }

    function Php22Xml ($php, $attribute)  {

        $fp   = "";
        $data = "";

        #
        # parse attribute
        #
        # attribute example:
        # attr        => /factories/factory@id
        # attr_path   => /factories/factory
        # attr_key    => id
        # attr_prefix => /factories
        # attr_value  => factory
        #
        $this->attr = $attribute;
        if ($this->attr) {
            preg_match("/([^\@]*)\@(.*)/", $attribute, $matches);
            $this->attr_path = $matches[1];
            $this->attr_key = $matches[2];

            $this->attr_prefix = substr($this->attr_path, 0, strrpos($this->attr_path, "/"));
            $this->attr_value = substr(strrchr($this->attr_path, "/"), 1);
        }

        #
        # parse
        #
        $this->php_parse($php);

    }

    function php_parse($php) {

        foreach ($php as $key => $value) {

            #
            # Open Tag
            #
            # if we match the attribute prefix, we introduce the attribute tag.
            #
            for ($i = 0; $i < $this->depth; $i++) {
                $this->xml .= "\t";
            }
            if ($this->path == $this->attr_prefix) {
                $this->xml .= '<'.$this->attr_value.' '.$this->attr_key.'="'.$key.'">';
            }
            else {
                $this->xml .= "<".$key.">";
            }
          
            if(is_array($value)) {
                $this->xml .= "\n";

                $this->path .= "/".$key;
                $this->depth++;

                #
                # recurse
                #
                $this->php_parse ($value);

                $this->path = substr($this->path, 0, strrpos($this->path, "/"));
                $this->depth--;

                $this->IsData = 0;
            }
            else {
                #
                # Cdata
                #
                $this->xml .= $value;

                $this->IsData = 1;
            }

            #
            # Close Tag
            #
            if (! $this->IsData) {
                for ($i = 0; $i < $this->depth; $i++) {
                    $this->xml .= "\t";
                }
            }
            if ($this->path == $this->attr_prefix) {
                $this->xml .= "</".$this->attr_value.">\n";
            }
            else {
                $this->xml .= "</".$key.">\n";
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

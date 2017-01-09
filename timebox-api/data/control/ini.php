<?php

# rewrite ini
require $_SERVER['DOCUMENT_ROOT']."/../data/control/rewrite.ini";

# base controller ini
require $_SERVER['DOCUMENT_ROOT']."/../data/control/base.ini";

# read ini files from directories
readIni($_SERVER['DOCUMENT_ROOT']."/../data/control/web");
readIni($_SERVER['DOCUMENT_ROOT']."/../data/control/ext");
readIni($_SERVER['DOCUMENT_ROOT']."/../data/control/api");

function readIni($dir) {

    if ($openDir = opendir($dir)) {

        while (false !== ($file = readdir($openDir))) {

            // skip hidden files and . and .. directories
            if ($file[0]==".") {
                continue;
            }

            // do not follow links
            // if (is_link($dir."/".$file)) {
            //     continue;
            // }

            // add file
            if (is_file($dir."/".$file)) {
                require $dir."/".$file;
            }

        }

        closedir($openDir);

    }

}

?>

<?php

# base config
require $_SERVER['DOCUMENT_ROOT']."/../data/etc/xcow_base.ini";

# read config files from directories
readConf($_SERVER['DOCUMENT_ROOT']."/../data/etc/web");
readConf($_SERVER['DOCUMENT_ROOT']."/../data/etc/ext");
readConf($_SERVER['DOCUMENT_ROOT']."/../data/etc/api");

function readConf($dir) {

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

            // recurse directories
            if (is_dir($dir."/".$file)) {
                readLib($dir."/".$file);
            }

            // add file
            if (is_file($dir."/".$file)) {
                require $dir."/".$file;
            }

        }

        closedir($openDir);

    }

}

?>

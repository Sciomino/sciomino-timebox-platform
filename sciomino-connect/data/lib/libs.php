<?php

# base classes
require $_SERVER['DOCUMENT_ROOT']."/../data/lib/php/control.php";
require $_SERVER['DOCUMENT_ROOT']."/../data/lib/php/extensionControl.php";
require $_SERVER['DOCUMENT_ROOT']."/../data/lib/php/rewrite.php";

# base libs
require $_SERVER['DOCUMENT_ROOT']."/../data/lib/base/mail.php";
require $_SERVER['DOCUMENT_ROOT']."/../data/lib/base/upload.php";
require $_SERVER['DOCUMENT_ROOT']."/../data/lib/base/session.php";
require $_SERVER['DOCUMENT_ROOT']."/../data/lib/base/Queue.php";
require $_SERVER['DOCUMENT_ROOT']."/../data/lib/base/oauth.php";
require $_SERVER['DOCUMENT_ROOT']."/../data/lib/base/mobile.php";

# misc libs
# require $_SERVER['DOCUMENT_ROOT']."/../data/lib/misc/XmlLib.php";
require $_SERVER['DOCUMENT_ROOT']."/../data/lib/misc/XmlLib2.php";
require $_SERVER['DOCUMENT_ROOT']."/../data/lib/misc/HttpConnect.php";
require $_SERVER['DOCUMENT_ROOT']."/../data/lib/misc/InputOutput.php";
require $_SERVER['DOCUMENT_ROOT']."/../data/lib/misc/Mysql.php";
require $_SERVER['DOCUMENT_ROOT']."/../data/lib/misc/gfx.php";

# misc-other libs
require $_SERVER['DOCUMENT_ROOT']."/../data/lib/misc-other/oauthClient/OAuth.php";

# read libs files from directories
readLib($_SERVER['DOCUMENT_ROOT']."/../data/lib/web");
readLib($_SERVER['DOCUMENT_ROOT']."/../data/lib/ext");
readLib($_SERVER['DOCUMENT_ROOT']."/../data/lib/api");

function readLib($dir) {

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

#!/bin/sh

APPDIR=APP

##############
### WEBAPP ###
##############

# preload model
rm htdocs/js/model/base/model.concat.js 
cat htdocs/js/model/base/*.js > model.concat.js
#echo "MCOW.Model.last = { }" >> model.concat.js
mv model.concat.js htdocs/js/model/base/model.concat.js

# preload view
rm htdocs/js/view/base/view.concat.js 
echo "MCOW.View.preload = {" > view.concat.js

# put each view in a base64 string
VIEWS=htdocs/js/view/base/*.js
for view in $VIEWS
do
        KEY=`basename -s .js $view`
        VAL=`base64 -w 0 $view`

        echo "$KEY : \"$VAL\"," >> view.concat.js
done

echo "last:\"last\"" >> view.concat.js
echo "}" >> view.concat.js
mv view.concat.js htdocs/js/view/base/view.concat.js


################
### PHONEGAP ###
################

# finally, copy webapp to phonegap
rm -r $APPDIR/mcow/www/*
cp -a htdocs/* $APPDIR/mcow/www

# and add phonegap specific stuff
cp phonegap/mcow/index.html $APPDIR/mcow/www
cp phonegap/mcow/config.js $APPDIR/mcow/www/js/etc
cp -a phonegap/icons $APPDIR/mcow/www/gfx
cp -a phonegap/splash $APPDIR/mcow/www/gfx
cp phonegap/splash/ldpi.png $APPDIR/mcow/www/splash.png

# config for cordova
cp phonegap/config.xml $APPDIR/mcow

# config for phonegap build
cp phonegap/config-pgb.xml $APPDIR/mcow/www/config.xml

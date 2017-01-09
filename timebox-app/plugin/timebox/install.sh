#!/bin/sh

APPDIR=../../APP

##############
### WEBAPP ###
##############

# first, OVERWRITE main files
# - index.html
# - config.js
# - control.js
# - cron.js
cp index.html ../../htdocs/index.html
cp js/etc/config.js ../../htdocs/js/etc/config.js
cp js/control/control.js ../../htdocs/js/control/control.js
cp js/control/cron.js ../../htdocs/js/control/cron.js

# second, ADD plugin components
# - js:lib, model & view
# - css

# lib
mkdir -p ../../htdocs/js/lib/timebox
cat js/lib/*.js > ../../htdocs/js/lib/timebox/lib.concat.js
# uglify lib
/usr/bin/uglifyjs ../../htdocs/js/lib/timebox/lib.concat.js -c -m > ../../htdocs/js/lib/timebox/lib.concat.min.js 
mv ../../htdocs/js/lib/timebox/lib.concat.min.js ../../htdocs/js/lib/timebox/lib.concat.js

# model
mkdir -p ../../htdocs/js/model/timebox
cat js/model/*.js > ../../htdocs/js/model/timebox/model.concat.js
# uglify model
/usr/bin/uglifyjs ../../htdocs/js/model/timebox/model.concat.js -c -m > ../../htdocs/js/model/timebox/model.concat.min.js
mv ../../htdocs/js/model/timebox/model.concat.min.js ../../htdocs/js/model/timebox/model.concat.js

# view
mkdir -p ../../htdocs/js/view/timebox
rm ../../htdocs/js/view/timebox/*.js
#cp js/view/*.js ../../htdocs/js/view/timebox/

######
# start preload view data
echo "MCOW.View.Timebox.preload = {" > ../../htdocs/js/view/timebox/view.concat.js

# put each view in a base64 string
VIEWS=js/view/*.js
for view in $VIEWS
do
        KEY=`basename -s .js $view`
        VAL=`base64 -w 0 $view`

        echo "$KEY : \"$VAL\"," >> ../../htdocs/js/view/timebox/view.concat.js
done

# stop preload of view data
echo "last:\"last\"" >> ../../htdocs/js/view/timebox/view.concat.js
echo "}" >> ../../htdocs/js/view/timebox/view.concat.js
######

# css
mkdir -p ../../htdocs/css/timebox
cat css/*.css > ../../htdocs/css/timebox/css.concat.css
# cleancss 
/usr/bin/cleancss ../../htdocs/css/timebox/css.concat.css > ../../htdocs/css/timebox/css.concat.min.css
mv ../../htdocs/css/timebox/css.concat.min.css ../../htdocs/css/timebox/css.concat.css

# third, don't forget the graphics & fonts

# png, jpg & gif gfx
mkdir -p ../../htdocs/gfx/timebox
rm ../../htdocs/gfx/timebox/*.png
cp gfx/*.png ../../htdocs/gfx/timebox/
rm ../../htdocs/gfx/timebox/*.jpg
cp gfx/*.jpg ../../htdocs/gfx/timebox/
rm ../../htdocs/gfx/timebox/*.gif
cp gfx/*.gif ../../htdocs/gfx/timebox/

# create images directory for frontend
if [ ! -L ../../htdocs/images ]
	then ln -s gfx/timebox ../../htdocs/images
fi

# fonts
mkdir -p ../../htdocs/fonts/timebox
rm ../../htdocs/fonts/timebox/*
cp fonts/* ../../htdocs/fonts/timebox/

################
### PHONEGAP ###
################

# finally, copy webapp to phonegap
rm -r $APPDIR/sciomino-timebox/www/*
cp -a ../../htdocs/* $APPDIR/sciomino-timebox/www

# and add phonegap specific stuff
cp phonegap/mcow/index.html $APPDIR/sciomino-timebox/www
cp phonegap/mcow/config.js $APPDIR/sciomino-timebox/www/js/etc
cp -a phonegap/icons $APPDIR/sciomino-timebox/www/gfx/timebox
cp -a phonegap/splash $APPDIR/sciomino-timebox/www/gfx/timebox
cp phonegap/splash/ldpi.png $APPDIR/sciomino-timebox/www/splash.png

# config for cordova
cp phonegap/config.xml $APPDIR/sciomino-timebox

# config for phonegap build
cp phonegap/config-pgb.xml $APPDIR/sciomino-timebox/www/config.xml

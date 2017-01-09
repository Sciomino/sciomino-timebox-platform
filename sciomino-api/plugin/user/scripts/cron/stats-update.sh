#!/bin/bash

URL1="http://sciomino-api/stats/generate?user_api_id=sciomino12&user_api_nonce=60de03bea6549cb8cffa2b22ffe157c3&user_api_key=715cac14dc58e72c84036fcc0505874321410229";
URL2="http://sciomino-api/stats/generate?user_api_id=sciomino20&user_api_nonce=08858edacc031ae190ece501e4efe487&user_api_key=3d3d92a92e85edb7dd334c39c7d7d0d39f635f69";

OUTFILE="/var/www-virtual/sciomino/sciomino-api/plugin/user/scripts/cron/stats-update.html"
LOGFILE="/var/www-virtual/sciomino/sciomino-api/plugin/user/scripts/cron/stats-update.log"

/usr/bin/wget --timeout=3600 -O $OUTFILE -o $LOGFILE "$URL1"
/usr/bin/wget --timeout=3600 -O $OUTFILE -o $LOGFILE "$URL2"

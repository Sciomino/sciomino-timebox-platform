#!/bin/bash

URL1="http://api.sciomino.com/group/updateType?type=manager&user_api_id=alliander&user_api_nonce=08858edacc031ae190ece501e4efe487&user_api_key=9c1f1953c155785ce684ec4cad37e470c8253af6";

OUTFILE="/home/sciomino/html/api.sciomino.com/plugin/user/scripts/cron/manager-list-update.html"
LOGFILE="/home/sciomino/html/api.sciomino.com/plugin/user/scripts/cron/manager-list-update.log"

/usr/bin/wget --timeout=3600 -O $OUTFILE -o $LOGFILE "$URL1"

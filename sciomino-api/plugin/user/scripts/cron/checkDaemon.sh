#!/bin/bash

DAEMON="/var/www-virtual/sciomino/sciomino-api/plugin/user/scripts/cron/indexDaemon.sh"

STATUS=$($DAEMON stat)

if [ "$STATUS" == "indexDaemon.sh is not running" ]; then
   RESTART=$($DAEMON start)
   echo $RESTART
fi

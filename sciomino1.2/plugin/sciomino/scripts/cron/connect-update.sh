#!/bin/bash

URL="http://sciomino1.2/import/connect-update"

OUTFILE="/var/www-virtual/sciomino/sciomino1.2/plugin/sciomino/scripts/cron/connect-update.html"
LOGFILE="/var/www-virtual/sciomino/sciomino1.2/plugin/sciomino/scripts/cron/connect-update.log"

/usr/bin/wget --timeout=3600 -O $OUTFILE -o $LOGFILE "$URL"

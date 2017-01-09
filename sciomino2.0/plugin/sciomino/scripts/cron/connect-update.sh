#!/bin/bash

URL="http://sciomino2.0/import/connect-update"

OUTFILE="/var/www-virtual/sciomino/sciomino2.0/plugin/sciomino/scripts/cron/connect-update.html"
LOGFILE="/var/www-virtual/sciomino/sciomino2.0/plugin/sciomino/scripts/cron/connect-update.log"

/usr/bin/wget --timeout=3600 -O $OUTFILE -o $LOGFILE "$URL"

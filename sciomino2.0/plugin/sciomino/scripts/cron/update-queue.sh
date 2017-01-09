#!/bin/bash

URL="http://sciomino2.0/import/update?mode=update&source=queue"

OUTFILE="/var/www-virtual/sciomino/sciomino2.0/plugin/sciomino/scripts/cron/update-queue.html"
LOGFILE="/var/www-virtual/sciomino/sciomino2.0/plugin/sciomino/scripts/cron/update-queue.log"

/usr/bin/wget --timeout=3600 -O $OUTFILE -o $LOGFILE "$URL"


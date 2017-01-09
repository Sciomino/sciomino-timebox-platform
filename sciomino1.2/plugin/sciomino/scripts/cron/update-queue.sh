#!/bin/bash

URL="http://sciomino1.2/import/update?mode=update&source=queue"

OUTFILE="/var/www-virtual/sciomino/sciomino1.2/plugin/sciomino/scripts/cron/update-queue.html"
LOGFILE="/var/www-virtual/sciomino/sciomino1.2/plugin/sciomino/scripts/cron/update-queue.log"

/usr/bin/wget --timeout=3600 -O $OUTFILE -o $LOGFILE "$URL"


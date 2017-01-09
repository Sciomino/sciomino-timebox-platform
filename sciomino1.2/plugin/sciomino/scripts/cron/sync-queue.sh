#!/bin/bash

URL="http://sciomino1.2/import/sync?mode=all&stamp=queue&entries=3"

OUTFILE="/var/www-virtual/sciomino/sciomino1.2/plugin/sciomino/scripts/cron/sync-queue.html"
LOGFILE="/var/www-virtual/sciomino/sciomino1.2/plugin/sciomino/scripts/cron/sync-queue.log"

/usr/bin/wget --timeout=300 -O $OUTFILE -o $LOGFILE "$URL"

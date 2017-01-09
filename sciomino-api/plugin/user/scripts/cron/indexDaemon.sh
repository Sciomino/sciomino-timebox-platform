#!/bin/bash

function payload() {

  # where is the queue?
  DIR1="/var/www-virtual/sciomino/sciomino-api/save/queue/sciomino12"
  DIR2="/var/www-virtual/sciomino/sciomino-api/save/queue/sciomino20"

  # this is the url to fetch
  URL1="http://sciomino-api/index/generate?action=queue&user_api_id=sciomino12&user_api_nonce=60de03bea6549cb8cffa2b22ffe157c3&user_api_key=715cac14dc58e72c84036fcc0505874321410229";
  URL2="http://sciomino-api/index/generate?action=queue&user_api_id=sciomino20&user_api_nonce=08858edacc031ae190ece501e4efe487&user_api_key=3d3d92a92e85edb7dd334c39c7d7d0d39f635f69";

  OUTFILE="/var/www-virtual/sciomino/sciomino-api/plugin/user/scripts/cron/generateIndex.html"
  LOGFILE="/var/www-virtual/sciomino/sciomino-api/plugin/user/scripts/cron/generateIndex.log"

  while [ true ]; do
    checkforterm

    # DIR is not empty? Go for it
    if [ "$(ls -A $DIR1)" ]; then
        /usr/bin/wget --timeout=3600 -O $OUTFILE -o $LOGFILE "$URL1"
    fi
    if [ "$(ls -A $DIR2)" ]; then
        /usr/bin/wget --timeout=3600 -O $OUTFILE -o $LOGFILE "$URL2"
    fi

    # check every 6 seconds
    sleep 6
  done
}

source /var/www-virtual/sciomino/sciomino-api/plugin/user/scripts/cron/daemon-functions.sh

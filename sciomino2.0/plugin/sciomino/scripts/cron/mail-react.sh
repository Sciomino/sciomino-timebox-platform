#!/bin/bash

# IDurl for fetching id's, MAILurl for sending mail to the Id's
IDURL="http://sciomino2.0/import/getActIds"
MAILURL="http://sciomino2.0/import/mail-react"

# store the info of the mail send
MAILOUTFILE="/var/www-virtual/sciomino/sciomino2.0/plugin/sciomino/scripts/cron/mail-react.html"
MAILLOGFILE="/var/www-virtual/sciomino/sciomino2.0/plugin/sciomino/scripts/cron/mail-react.log"

LIMIT=100

# The last 10 minutes
TO="$(date +%s)"
FROM=`expr $TO - 600`

# fetch the $LIMIT number of next Id's that are inactive
CURURL="$IDURL?from=$FROM&limit=$LIMIT"

# get the Id string and transform it to an array
IDSTRING="`/usr/bin/wget --no-check-certificate -qO- $CURURL`"
IDARRAY=($(echo $IDSTRING | tr "," " "))
IDLEN=${#IDARRAY[@]}

# for each Id, do something
for ID in "${IDARRAY[@]}"
do
	CURMAILURL="$MAILURL?mode=send&id=$ID"
	/usr/bin/wget --no-check-certificate --timeout=300 -O $MAILOUTFILE -o $MAILLOGFILE "$CURMAILURL"
done

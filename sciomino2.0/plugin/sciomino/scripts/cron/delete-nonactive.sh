#!/bin/bash

# IDurl for fetching id's, MAILurl for sending mail to the Id's
# note: in this case MAIL user is DELETE user
IDURL="http://sciomino2.0/import/getIds"
MAILURL="http://sciomino2.0/import/delete"

# store the info of the mail send
MAILOUTFILE="/var/www-virtual/sciomino/sciomino2.0/plugin/sciomino/scripts/cron/delete-nonactive.html"
MAILLOGFILE="/var/www-virtual/sciomino/sciomino2.0/plugin/sciomino/scripts/cron/delete-nonactive.log"

OFFSET=0
LIMIT=10

MORE=1
while [ $MORE == 1 ]
do
	# fetch the $LIMIT number of next Id's that are inactive
	CURURL="$IDURL?mode=inactive&offset=$OFFSET&limit=$LIMIT"
	
	# get the Id string and transform it to an array
	IDSTRING="`/usr/bin/wget --no-check-certificate -qO- $CURURL`"
	IDARRAY=($(echo $IDSTRING | tr "," " "))
	IDLEN=${#IDARRAY[@]}
	
	# for each Id, do something
	for ID in "${IDARRAY[@]}"
	do
		# stop if ID=0 is returned, then no more Id's are left
		if [ $ID == 0 ]
		then 
			MORE=0
		else 
			CURMAILURL="$MAILURL?mode=delete&id=$ID"
			/usr/bin/wget --no-check-certificate --timeout=300 -O $MAILOUTFILE -o $MAILLOGFILE "$CURMAILURL"
		fi
	done
	
	# next set of Id's
	OFFSET=$[$OFFSET+$LIMIT]
	
	# sleep for the next bunch
	sleep 5
done

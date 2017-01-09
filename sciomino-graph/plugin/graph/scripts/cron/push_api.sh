#!/bin/bash

# urls:
# - IDurl for fetching id's, PUSHurl for pushing data to the API for the selected Id's
IDURL="http://sciomino-graph/import/getIds"
PUSHURL="http://sciomino-graph/import/push2api"
# IDURL="http://graph:ccAb8jan@test.graph.sciomino.com/import/getIds"
# PUSHURL="http://graph:ccAb8jan@test.graph.sciomino.com/import/push2api"

# files:
# - to store the info of the data send in .html & .log
# - for timestamp and locking .time & .lock
PUSHOUTFILE="/var/www-virtual/sciomino-graph/plugin/graph/scripts/cron/push_api.html"
PUSHLOGFILE="/var/www-virtual/sciomino-graph/plugin/graph/scripts/cron/push_api.log"
PUSHTIMEFILE="/var/www-virtual/sciomino-graph/plugin/graph/scripts/cron/push_api.time"
PUSHLOCKFILE="/var/www-virtual/sciomino-graph/plugin/graph/scripts/cron/push_api.lock"
# PUSHOUTFILE="/home/is/webserver/www/sciomino.graph.test/plugin/graph/scripts/cron/push_api.html"
# PUSHLOGFILE="/home/is/webserver/www/sciomino.graph.test/plugin/graph/scripts/cron/push_api.log"
# PUSHTIMEFILE="/home/is/webserver/www/sciomino.graph.test/plugin/graph/scripts/cron/push_api.time"
# PUSHLOCKFILE="/home/is/webserver/www/sciomino.graph.test/plugin/graph/scripts/cron/push_api.lock"

# 1.
# get parameters
if [ $# != 1 ]
then
	echo "Need one parameter: customer"
	exit
fi
	
CUSTOMER=$1

# 2.
# check lock file (per customer)
if [ -f "$PUSHLOCKFILE.$CUSTOMER" ]
then
	echo "Still running"
	exit
else 
	touch "$PUSHLOCKFILE.$CUSTOMER"
fi

# 3.
# get timestamp (per customer)
PREVTIMESTAMP=0
NEXTTIMESTAMP=`date +%s`

if [ -f "$PUSHTIMEFILE.$CUSTOMER" ]
then
	PREVTIMESTAMP=$(<"$PUSHTIMEFILE.$CUSTOMER")
else 
	# create file the first time
	echo -n $PREVTIMESTAMP > "$PUSHTIMEFILE.$CUSTOMER"
fi

# init
OFFSET=0
LIMIT=10

# go
MORE=1
while [ $MORE == 1 ]
do
	# fetch the $LIMIT number of next Id's
	CURURL="$IDURL?customer=$CUSTOMER&timestamp=$PREVTIMESTAMP&offset=$OFFSET&limit=$LIMIT"
	
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
			CURPUSHURL="$PUSHURL?customer=$CUSTOMER&id=$ID"
			/usr/bin/wget --no-check-certificate --timeout=300 -O $PUSHOUTFILE -o $PUSHLOGFILE "$CURPUSHURL"
		fi
	done
	
	# next set of Id's
	OFFSET=$[$OFFSET+$LIMIT]
	
	# sleep for the next bunch
	sleep 3
done

# end 3.
# write timestamp
echo -n $NEXTTIMESTAMP > "$PUSHTIMEFILE.$CUSTOMER"

# end 2.
# remove lockfile
rm "$PUSHLOCKFILE.$CUSTOMER"

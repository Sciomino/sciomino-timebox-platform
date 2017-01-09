plugin: sciomino graph
title: sciomino graph
description: graph for sciomino with different views on the api
version: 0.3
date: 18 mei 2015


---
update: 18 mei 2016

new process to push data to carerix
1. store credentials of carerix customers (in db & in graph.ini file)
2. cron starts push_api.sh (for each customer)
	2.1 it takes care of file locking and timestamps
3. push_api.sh starts:
	3.1. getIds (returns list of id's to be updated)
	3.2. push2api(updates carerix api for each id)
 
note the consequences with these new features... graph used to be simple... but now:
- uses db/AuthApp, the customer must be able to access the graph database and should have credentials for the timebox api
- uses etc/graph.ini, this must be configured for customers with credentials for the remote api
- uses /data/model/import, this must be secured by a user/password
- uses scripts/cron, this must be correctly configured and started by cron
- make sure the timestamps are enabled on the availability api!

#!/usr/bin/perl -w

$url = "http://xcow-base/PA/assignmentSync";

system("/usr/bin/wget --timeout=3600 -O /var/www-virtual/xcow-base/scripts/cron/sync.html -o /var/www-virtual/xcow-base/scripts/cron/sync.log $url");

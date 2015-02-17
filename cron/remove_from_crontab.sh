#!/bin/bash
CRON_TEMP=""
crontab -l > "$CRON_TEMP"

awk '$0!~/bisonsv2/cron/feeds/ { print $0 }' $CRON_TEMP >$CRON_NEW

crontab $CRON_NEW
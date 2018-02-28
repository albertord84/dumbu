#!/bin/sh

date=$(date +%Y%m%d)

now=$(date +"%T")

wget -O /home/dumbuo5/public_html/dumbu/worker/log/daily_report-${date}.log   http://dumbu.one/dumbu/worker/scripts/daily_report.php
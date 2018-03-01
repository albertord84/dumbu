#!/bin/sh

date=$(date +%Y%m%d)

now=$(date +"%T")

curl http://dumbu.one/dumbu/worker/scripts/index-do.php?id=1 > /home/dumbuo5/public_html/dumbu/worker/log/dumbo-worker1-${date}.log
#wget -O /home/dumbuo5/public_html/dumbu/worker/log/dumbo-worker1-${date}.log   http://dumbu.one/dumbu/worker/scripts/index-do.php?id=1

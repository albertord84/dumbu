#!/bin/sh

date=$(date +%Y%m%d)

now=$(date +"%T")

curl http://127.0.0.1:30080/dumbu/worker/scripts/index-do.php?id=5 >> /home/dumbuo5/public_html/dumbu/worker/log/dumbo-worker5-${date}.log
wget -O /home/dumbuo5/public_html/dumbu/worker/log/dumbo-worker5-${date}.log   http://127.0.0.1:30080/dumbu/worker/scripts/index-do.php?id=5
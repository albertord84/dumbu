#!/bin/sh

date=$(date +%Y%m%d)

now=$(date +"%T")

wget -O /home/dumbuo5/public_html/dumbu/worker/log/dumbo-worker5-${date}.log   http://dumbu.one/dumbu/worker/scripts/index-do.php?id=5
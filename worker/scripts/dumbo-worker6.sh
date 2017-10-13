#!/bin/sh

date=$(date +%Y%m%d)

now=$(date +"%T")

curl http://localhost/dumbu/worker/scripts/index-do.php > /opt/lampp/htdocs/dumbu/worker/log/dumbo-worker6-${date}.log
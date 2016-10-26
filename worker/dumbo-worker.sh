#!/bin/sh

date=$(date +%Y%m%d)

now=$(date +"%T")

curl http://localhost/dumbu/worker/index.php > /opt/lampp/htdocs/dumbu/worker/log/dumbo-worker-${date}.log

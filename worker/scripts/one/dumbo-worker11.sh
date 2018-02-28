#!/bin/sh

parent_path=$( cd "$(dirname "${BASH_SOURCE[0]}")" ; pwd -P )

cd "$parent_path"

date=$(date +%Y%m%d)

now=$(date +"%T")

output="../../dumbu/worker/log/dumbo-worker11-${date}.log"
#url="http://dumbu.one/dumbu/worker/scripts/index-do.php?id=1"
url="http://localhost/dumbu/worker/scripts/index-do.php?id=11"

#curl http://localhost/dumbu/worker/index.php > ../worker/log/dumbo-worker-${date}.log
wget -O $output $url
#curl http://localhost/dumbu/worker/scripts/index-do.php?id=1 > /opt/lampp/htdocs/dumbu/log/dumbo-worker1-${date}.log

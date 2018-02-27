#!/bin/sh

parent_path=$( cd "$(dirname "${BASH_SOURCE[0]}")" ; pwd -P )

cd "$parent_path"

date=$(date +%Y%m%d)

now=$(date +"%T")

output="../../dumbu/worker/log/unfollow-${date}.log"
url="http://localhost/dumbu/worker/scripts/unfollow.php"
wget -O $output $url
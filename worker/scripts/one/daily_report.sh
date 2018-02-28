#!/bin/sh

parent_path=$( cd "$(dirname "${BASH_SOURCE[0]}")" ; pwd -P )

cd "$parent_path"

date=$(date +%Y%m%d)

now=$(date +"%T")

output="../../dumbu/worker/log/daily_report-${date}.log"
url="http://localhost/dumbu/worker/scripts/daily_report.php"
wget -O $output $url

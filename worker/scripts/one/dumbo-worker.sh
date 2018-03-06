#!/bin/sh

parent_path=$( cd "$(dirname "${BASH_SOURCE[0]}")" ; pwd -P )

cd "$parent_path"

date=$(date +%Y%m%d)

now=$(date +"%T")

wget -O /home/dumbuo5/public_html/dumbu/worker/log/dumbo-worker-${date}.log http://dumbu.one/dumbu/worker/index.php


#!/bin/sh

date=$(date +%Y%m%d)

now=$(date +"%T")

wget -O /home/dumbuo5/public_html/dumbu/worker/log/unfollow-${date}.log   http://dumbu.one/dumbu/worker/scripts/unfollow.php

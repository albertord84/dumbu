#!/bin/sh

date=$(date +%Y%m%d)

now=$(date +"%T")

wget -O /home/dumbuo5/public_html/dumbu/src/logs/check-payment-${date}.log   http://dumbu.one/dumbu/src/index.php/payment/check_payment
#!/bin/bash
TODAY=`date +%c`
echo \ >> /var/www/engetecnica/log/automacoes.log
echo $TODAY >>  /var/www/engetecnica/log/automacoes.log
curl https://engetecnica.xyz/app/automacoes >> /var/www/engetecnica/log/automacoes.log
echo \ >> /var/www/engetecnica/log/automacoes.log
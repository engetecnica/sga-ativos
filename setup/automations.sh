#!/bin/bash
TODAY=`date +%c`
echo \ >> /var/www/engetecnica/log/automacoes.log
echo $TODAY >>  /var/www/engetecnica/log/automacoes.log
curl http://67.207.93.146/app/automacoes >> /var/www/engetecnica/log/automacoes.log
echo \ >> /var/www/engetecnica/log/automacoes.log
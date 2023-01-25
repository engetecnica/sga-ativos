#!/bin/bash
TODAY=`date +%c`
echo \ >> /var/www/sgaeng/log/automacoes.log
echo $TODAY >>  /var/www/sgaeng/log/automacoes.log
curl https://www.sga-e.eng.br/app/automacoes >> /var/www/sgaeng/log/automacoes.log
echo \ >> /var/www/sgaeng/log/automacoes.log
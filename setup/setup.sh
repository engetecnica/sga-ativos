#!/bin/bash
chown -R root:www-data assets/uploads
chmod 777 -R assets/uploads
chown -R root:www-data  application/cache
chmod 777 -R application/cache
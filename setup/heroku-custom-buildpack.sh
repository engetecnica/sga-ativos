#!/bin/bash
cp application/config/heroku.database.php application/config/database.php
cp application/config/heroku.config.php application/config/config.php
chmod -R 777 assets/uploads
#chown -R root:www-data assets/uploads

echo "Run heroku-custom-buildpack.sh"
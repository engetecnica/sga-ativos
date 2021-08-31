#!/bin/bash
cp application/config/heroku.database.php application/config/database.php
chmod -R 775 assets/uploads
#chown -R root:www-data assets/uploads
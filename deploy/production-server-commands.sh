#!/bin/bash
deploy_dir=$1
branch=$2
deployUser=$3
user=$4
destination=$5

echo "Extracting files"
unzip -oq ${deploy_dir}${branch}-release.zip -d ${deploy_dir}${branch}-release

echo "Fixing permissions"
chown -R $deployUser:$user $destination ${deploy_dir}${branch}-release

chgrp -R www-data ${destination}storage ${destination}bootstrap/cache
chmod -R ug+rwx ${destination}storage ${destination}bootstrap/cache

echo "Copying files to deployment directory (${deploy_dir})"
rsync -a ${deploy_dir}${branch}-release/. ${destination}/ --exclude ${deploy_dir}${branch}-release/node_modules/.cache

echo "Removing unneeded files from production"
rm -rf ${destination}/deploy ${destination}/.env.example ${destination}/.git* ${destination}/package* ${destination}/phpunit.xml ${destination}/webpack.mix.js

echo "Cleaning up"
rm -rf ${deploy_dir}${branch}-release.zip ${deploy_dir}${branch}-release

cd /home/${deployUser}/app/ && \
 php artisan config:clear --no-interaction && \
 php artisan cache:clear --no-interaction && \
 php artisan config:cache --no-interaction && \
 php artisan route:cache --no-interaction && \
 php artisan migrate --no-interaction

#echo "Restarting Lsyncd to ensure files transfer"
#sudo service lsyncd restart & > /dev/null 2>&1

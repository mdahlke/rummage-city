#!/bin/bash
deploy_dir=$1
test_branch=$2
deployUser=$3
user=$4
destination=$5

echo "Extracting files"
unzip -oq ${deploy_dir}${test_branch}-release.zip -d ${deploy_dir}${test_branch}-release

echo "Fixing permissions"
chown -R $deployUser:$user $destination ${deploy_dir}${test_branch}-release

chgrp -R www-data storage bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache

echo "Copying files to deployment directory (${deploy_dir})"
rsync -av --progress ${deploy_dir}${test_branch}-release/. $destination/ --exclude ${deploy_dir}${test_branch}-release/node_modules/.cache

echo "Removing unneeded files from production"
rm -rf ${destination}/deploy ${destination}/.env.example ${destination}/.git* ${destination}/.package* ${destination}/phpunit.xml ${destination}/webpack.mix.js

echo "Cleaning up"
rm -rf ${deploy_dir}${test_branch}-release.zip ${deploy_dir}${test_branch}-release

#echo "Restarting Lsyncd to ensure files transfer"
#sudo service lsyncd restart & > /dev/null 2>&1

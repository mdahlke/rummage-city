#!/bin/bash
deploy_dir=$1
test_branch=$2
user=$3
destination=$4

echo "Extracting files"
sudo unzip -oq ${deploy_dir}${test_branch}-release.zip -d ${deploy_dir}${test_branch}-release

echo "Fixing permissions"
sudo chown -R $user:$user $destination ${deploy_dir}${test_branch}-release

echo "Copying files to deployment directory (${deploy_dir})"
sudo cp -pr ${deploy_dir}${test_branch}-release/. $destination/

echo "Cleaning up"
sudo rm -rf ${deploy_dir}${test_branch}-release.zip ${deploy_dir}${test_branch}-release

#echo "Restarting Lsyncd to ensure files transfer"
#sudo service lsyncd restart & > /dev/null 2>&1

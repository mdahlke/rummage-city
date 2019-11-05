#!/bin/bash
user=rummagecity
deployUser=mdahlke
ip=159.203.101.203
echo -e 'Beginning deployment'
echo -e 'Pinging server'
ping_result=$(ping -c 1 $ip)

base_dir=/home/rummagecity/app/

BRANCH=$(git symbolic-ref --short HEAD)

echo $BRANCH;

test_branch="master"
destination=${base_dir}
lower_case_branch=$test_branch
vhost_filename=rummagecity.com.conf


echo -e $destination
if [[ $ping_result == *"ping: cannot resolve"* ]]; then
    echo 'Could not connect to host'
    exit 1
else
    echo -e "Server found"
    ssh -q $deployUser@$ip exit

    ssh_connection=$(ssh -o BatchMode=yes -o ConnectTimeout=5 $deployUser@$ip echo ok 2>&1)
    git_tag=$(git describe --abbrev=0 --tags 2>&1)

    echo $ssh_connection

    test_branch=$git_tag

    if [[ $ssh_connection == ok ]] ; then
        echo -e "Connection Successful"
        echo -e "Preparing files for deployment"

        echo -e "Creating Archive"
        git archive -o $test_branch-release.zip HEAD

        echo -e "Adding node_modules/, and vendor/ to archive"
        zip -urq $test_branch-release.zip node_modules vendor

        # we are purposely using the `rummagecity` ($user) user here and in the 'Extracting Files' section to avoid permission issues
        echo -e "Sending archive to production server"
        rsync -aP $test_branch-release.zip $user@$ip:/home/$user/deploy/

        echo -e "Extracting files"
        ssh -t $user@$ip "unzip -oq /home/$user/deploy/${test_branch}-release.zip -d /home/$user/deploy/${test_branch}-release"

        echo -e "Create destination directory: ${destination}"
        ssh -t $deployUser@$ip "sudo mkdir -p $destination && sudo chown -R $user:www-data $destination"
        echo -e "Fix ownership"
        ssh -t $deployUser@$ip "sudo chown -R $user:www-data $destination"
        echo -e "Copy deployment to production root"
        ssh -t $user@$ip "cp -r /home/$user/deploy/${test_branch}-release/. $destination/"

        echo -e "Fix ownership"
        ssh -t $deployUser@$ip "sudo chown -R $user:www-data $destination"

        echo -e "Fix templates 'tmp' directory owner permissions"
        ssh -t $deployUser@$ip "sudo chown -R www-data:www-data $destination/public/tmp"

        echo -e "Copying .htaccess file to $destination/public"
        ssh -t $user@$ip "cp $destination/deploy/templates/.htaccess.tmpl $destination/public/.htaccess"

        echo -e "Cleaning up"
        ssh -t $user@$ip "rm -rf /home/$user/deploy/${test_branch}-release.zip /home/$user/deploy/${test_branch}-release"
#        ssh -t $deployUser@$ip "sudo rm -rf $destination/Tests/ $destination/docs/ $destination/schema/ $destination/pre-commit-dev $destination/.pre-commit-php-unit $destination/.htaccess-* $destination/.env.* $destination/.git*"


        echo -e "Deployment complete. Production is on ${git_tag}"


    elif [[ $status == "Permission denied"* ]] ; then
        echo no_auth
        exit 1
    else
        echo $status
        exit 1
    fi

    exit 0

fi

#!/bin/bash
user=markey
ip=69.20.40.90
echo -e 'Beginning deployment'
echo -e 'Pinging server'
ping_result=$(ping -c 1 $ip)

base_dir=/var/www/vhosts/test/

BRANCH=$(git symbolic-ref --short HEAD)

echo $BRANCH;

branch_parts=(${BRANCH//\// })
branch_parts_length=${#branch_parts[@]};

echo -e $branch_parts_length;
echo -e ${branch_parts[0]};
echo -e ${branch_parts[1]};


if [ $branch_parts_length == 2 ]; then
    test_branch=${branch_parts[1]}
else
    test_branch=${branch_parts[0]}
fi

destination=$base_dir$test_branch
lower_case_branch="$(echo ${test_branch} | tr '[:upper:]' '[:lower:]')"
vhost_filename=lower_case_branch.my.markeyds.com.conf


echo -e $destination

if [[ $ping_result == *"ping: cannot resolve"* ]]; then
    echo 'Could not connect to host'
    exit 1
else
    echo -e "Server found"
    ssh -q $user@$ip exit

    ssh_connection=$(ssh -o BatchMode=yes -o ConnectTimeout=5 $user@$ip echo ok 2>&1)
    git_tag=$(git describe --abbrev=0 --tags 2>&1)


    if [[ $ssh_connection == ok ]] ; then
        echo -e "Connection Successful"
        echo -e "Preparing files for deployment"

        echo -e "Copy .env"
        cp -v .env .env.deploybackup
        mv -v .env{.cloud-server,}

        echo -e "Creating Archive"
        git archive -o $lower_case_branch-release.zip HEAD

        echo -e "Adding .env, node_modules/, and vendor/ to archive"
        zip -urq $lower_case_branch-release.zip node_modules vendor .env

        echo -e "Sending archive to production server"
        rsync -aP $lower_case_branch-release.zip $user@$ip:/home/$user/deploy/

        echo -e "Extracting files"
        ssh -tt $user@$ip "unzip -oq /home/$user/deploy/${lower_case_branch}-release.zip -d /home/$user/deploy/${lower_case_branch}-release"

        echo -e "Create destination directory: ${destination}"
        ssh -tt $user@$ip "sudo mkdir -p $destination && sudo chown -R markey:www-data $destination"
        echo -e "Fix ownership"
        ssh -tt $user@$ip "sudo chown -R markey:www-data $destination"
        echo -e "Copy deployment to production root"
        ssh -tt $user@$ip "cp -r /home/$user/deploy/${lower_case_branch}-release/. $destination/"

        echo -e "Fix ownership"
        ssh -tt $user@$ip "sudo chown -R markey:www-data $destination"

        echo -e "Fix templates 'tmp' directory owner permissions"
        ssh -tt $user@$ip "sudo chown -R www-data:www-data $destination/public/tmp"

        echo -e "Ensure tmp directories exists for \"Display\" and \"Admin\""
        ssh -tt $user@$ip "mkdir -p $destination/public/tmp/admin $destination/public/tmp/display"
        echo -e "Copying .htaccess file to $destination/public"
        ssh -tt $user@$ip "cp $destination/deploy/templates/.htaccess.tmpl $destination/public/.htaccess"

        echo -e "Updating .env for proper subdomain"
        ssh -tt $user@$ip "sed -i -e 's/{{ SUBDOMAIN_MY_REPLACE }}/${lower_case_branch}.my/g' $destination/.env"
        ssh -tt $user@$ip "sed -i -e 's/{{ SUBDOMAIN_DISPLAY_REPLACE }}/${lower_case_branch}.display/g' $destination/.env"


        echo -e "Cleaning up"
        ssh -tt $user@$ip "rm -rf /home/$user/deploy/${lower_case_branch}-release.zip /home/$user/deploy/${lower_case_branch}-release"
        ssh -tt $user@$ip "rm -rf $destination/Tests/ $destination/docs/ $destination/schema/ $destination/pre-commit-dev $destination/.pre-commit-php-unit $destination/.htaccess-* $destination/.env.* $destination/.git*"

        echo -e "Creating virtual host"
        ssh -tt $user@$ip "sed -i -e 's/{{ BRANCH }}/${lower_case_branch}/g' $destination/deploy/templates/vhost.conf"
        ssh -tt $user@$ip "sudo cp $destination/deploy/templates/vhost.conf /etc/apache2/sites-available/${vhost_filename}"
        ssh -tt $user@$ip "sudo a2ensite ${vhost_filename}"
        ssh -tt $user@$ip "sudo service apache2 reload"

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

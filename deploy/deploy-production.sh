#!/bin/bash
user=www-data
deployUser=mdahlke
ip=167.99.119.100
echo 'Beginning deployment'
echo 'Pinging server'
ping_result=$(ping -c 1 $ip)

## We are getting the fullpath to the directory the
## scripts reside in to avoid file not found when
## calling `production-server-commands.sh` below
SOURCE="${BASH_SOURCE[0]}"
while [ -h "$SOURCE" ]; do # resolve $SOURCE until the file is no longer a symlink
  DIR="$(cd -P "$(dirname "$SOURCE")" >/dev/null 2>&1 && pwd)"
  SOURCE="$(readlink "$SOURCE")"
  [[ $SOURCE != /* ]] && SOURCE="$DIR/$SOURCE" # if $SOURCE was a relative symlink, we need to resolve it relative to the path where the symlink file was located
done
DIR="$(cd -P "$(dirname "$SOURCE")" >/dev/null 2>&1 && pwd)"

base_dir=/home/rummagecity
destination=${base_dir}/app/
deploy_dir=${base_dir}/deploy/

BRANCH=$(git symbolic-ref --short HEAD)

CWD=$(pwd)

test_branch="master"
lower_case_branch=${test_branch}

if [[ $ping_result == *"ping: cannot resolve"* ]]; then
  echo 'Could not connect to host'
  exit 1
else
  echo "Server pinged successfully"
  echo "Finding Server..."
  ssh -q $deployUser@$ip exit

  ssh_connection=$(ssh -o BatchMode=yes -o ConnectTimeout=5 $deployUser@$ip echo ok 2>&1)
  git_tag=$(git describe --abbrev=0 --tags 2>&1)

  echo "Server Found: ${ssh_connection}"

  test_branch=$git_tag

  if [[ $ssh_connection == ok ]]; then
    echo -e "\nBranch:  ${BRANCH}"
    echo "Git Tag: ${git_tag}"
    echo "Current Working Directory:  ${CWD}"
    echo "IP Address:  ${ip}"
    echo "Deploy User: ${deployUser}"
    echo "Directory Owner: ${user}"
    echo "Branch Release: ${test_branch}"
    echo "Deploy Directory: ${deploy_dir}"
    echo -e "Destination Directory: ${destination}\n"

    echo "Connection Successful"
    echo "Preparing files for deployment"

    ls -alh public/ public/css public/js

    echo "Creating Archive"
    git archive -o ${test_branch}-release.zip HEAD

    echo "Adding commit.json, node_modules/, and vendor/ to archive"
    zip -urq ${test_branch}-release.zip node_modules vendor commit.json public/css/app.min.css public/css/fontawesome.css public/js/app.min.js

    echo "Sending archive to production server"
    rsync -aP ${test_branch}-release.zip $deployUser@$ip:$deploy_dir

    # Run all commands found in the provided file
    # this allows use to execute multiple SSH commands while avoiding rate limiting
    ## deploy_dir=$1
    ## test_branch=$2
    ## user=$3
    ## destination=$4
    ssh -t ${deployUser}@${ip} 'bash -s' $deploy_dir $test_branch $user $destination <${DIR}/production-server-commands.sh

    echo "Deployment complete. Production is on ${git_tag}"

  elif
    [[ $status == "Permission denied"* ]]
  then
    echo no_auth
    exit 1
  else
    echo $status
    exit 1
  fi

  exit 0

fi

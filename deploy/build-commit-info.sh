#!/bin/bash

hash=$(git rev-parse HEAD)
hashShort=$(git rev-parse --short HEAD)
message=$(git log -1 --pretty=%B)
branch=$(git symbolic-ref --short HEAD)
author=$(git log -1 --pretty=format:'%an')
date=$(git log -1 --pretty=format:'%ci')

echo "Building \`commit.json\` for debugging."

echo {\"branch\": \"$branch\", \"hash\": \"$hash\", \"hashShort\": \"$hashShort\", \"message\": \"$message\", \"author\": \"$author\", \"date\": \"$date\"} > commit.json

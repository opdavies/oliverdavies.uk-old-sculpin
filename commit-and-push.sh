#!/usr/bin/env bash

git add -A
git commit -m "$1"
git push


nodemon \
    --watch ./output_dev \
    --ext html \
    --exec './commit-and-push.sh "Live blogging from SymfonyLive London 2019"'

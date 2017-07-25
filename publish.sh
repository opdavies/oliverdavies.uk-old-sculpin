#!/usr/bin/env bash

set -e

SITE_ENV="prod"
REPO=`git config remote.origin.url`
SSH_REPO=${REPO/https:\/\/github.com\//git@github.com:}
SHA=`git rev-parse --verify HEAD`
BUILD_DIR=".build"

# Prepare the build directory.
mkdir -p $BUILD_DIR
pushd $BUILD_DIR
cp -R ../.git .
git fetch
git checkout master || git checkout --orphan master
popd
rm -rf ${BUILD_DIR}/**/* || exit 0

# Re-generate the site.
npm run prod
vendor/bin/sculpin generate --no-interaction --clean --env=prod
touch output_prod/.nojekyll

# Add, commit and push the changes.
pushd $BUILD_DIR
mv ../output_prod/* .
git config --local user.email oliver@oliverdavies.uk
git add -A .
git commit -m "Re-generate site: $SHA"
git push $SSH_REPO master
popd

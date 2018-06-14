#!/usr/bin/env bash
set -e
trap '>&2 echo Error: Command \`$BASH_COMMAND\` on line $LINENO failed with exit code $?' ERR

## backup and disable xdebug
cp ~/.phpenv/versions/$(phpenv version-name)/etc/conf.d/xdebug.ini ~/.phpenv/versions/$(phpenv version-name)/xdebug.ini.bak
echo > ~/.phpenv/versions/$(phpenv version-name)/etc/conf.d/xdebug.ini
phpenv rehash

# create directories for the tests
mkdir -p "$HOME/.php-cs-fixer"

composer self-update --profile
git config --global user.name travis-ci
git config --global user.email travis@webproject.xyz
if [[ $RUN_WITH_COVERAGE == 'true' ]]; then curl -L https://codeclimate.com/downloads/test-reporter/test-reporter-latest-linux-amd64 > ./cc-test-reporter; fi
if [[ $RUN_WITH_COVERAGE == 'true' ]]; then chmod +x ./cc-test-reporter; fi
if [[ $RUN_WITH_COVERAGE == 'true' ]]; then ./cc-test-reporter before-build; fi

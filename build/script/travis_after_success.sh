#!/usr/bin/env bash
set -e
trap '>&2 echo Error: Command \`$BASH_COMMAND\` on line $LINENO failed with exit code $?' ERR

## coverage reports
php vendor/bin/codacycoverage clover tests/_output/coverage.xml
if [[ $RUN_WITH_COVERAGE == 'true' && "$TRAVIS_PULL_REQUEST" == "false" ]]; then ./cc-test-reporter after-build -t=clover build/logs/clover.xml --exit-code $TRAVIS_TEST_RESULT ; fi

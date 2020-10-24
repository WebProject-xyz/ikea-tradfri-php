#!/usr/bin/env bash
set -e
trap '>&2 echo Error: Command \`$BASH_COMMAND\` on line $LINENO failed with exit code $?' ERR

# run static php-cs-fixer code analysis
./vendor/bin/php-cs-fixer fix --dry-run --diff --verbose

## prepare test run
./vendor/bin/codecept build
## run the tests with no coverage
if [[ $RUN_WITH_COVERAGE != 'true' ]]; then ./vendor/bin/codecept run --colors; fi
## run the tests with with coverage
if [[ $RUN_WITH_COVERAGE == 'true' ]]; then mv ~/.phpenv/versions/$(phpenv version-name)/xdebug.ini.bak ~/.phpenv/versions/$(phpenv version-name)/etc/conf.d/xdebug.ini; fi
if [[ $RUN_WITH_COVERAGE == 'true' ]]; then composer build-coverage; fi
# codecov report
if [[ $RUN_WITH_COVERAGE == 'true' ]]; then bash <(curl -s https://codecov.io/bash) -Z ; fi

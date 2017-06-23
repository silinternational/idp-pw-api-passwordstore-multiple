#!/usr/bin/env bash

# Try to install composer dev dependencies.
composer install --no-interaction --no-scripts

# If that failed, exit.
rc=$?; if [[ $rc != 0 ]]; then exit $rc; fi

# Run the feature tests
./vendor/bin/behat --config=features/behat.yml --strict

# If they failed, exit.
rc=$?; if [[ $rc != 0 ]]; then exit $rc; fi

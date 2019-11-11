#!/bin/bash

set -e

if [ -n "$POSTGRES_TEST_DB" ]; then
	echo "Creating test database: $POSTGRES_TEST_DB"

	psql=( psql -v ON_ERROR_STOP=1 )

    "${psql[@]}" --username $POSTGRES_USER <<-EOSQL
        CREATE DATABASE "$POSTGRES_TEST_DB" TEMPLATE template1;
EOSQL

fi

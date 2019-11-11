#!/bin/bash

set -e

if [ -n "$POSTGRES_LOG_DB" ]; then
	echo "Creating test database: $POSTGRES_LOG_DB"

	psql=( psql -v ON_ERROR_STOP=1 )

    "${psql[@]}" --username $POSTGRES_USER <<-EOSQL
        CREATE DATABASE "$POSTGRES_LOG_DB" TEMPLATE template1;
EOSQL

fi

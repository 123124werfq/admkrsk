#!/bin/bash

set -e

# Load extensions into both template1
for DB in template1; do
	echo "Loading extensions into $DB"

	psql=( psql -v ON_ERROR_STOP=1 )

	"${psql[@]}" --username $POSTGRES_USER -d $DB <<-EOSQL
		CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
		CREATE EXTENSION pg_trgm;
EOSQL

done

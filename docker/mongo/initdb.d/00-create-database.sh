#!/bin/bash

set -e

if [ "$MONGO_USER" ] && [ "$MONGO_PASSWORD" ]; then
    "${mongo[@]}" "$MONGO_DB" <<-EOJS
        db.createUser({
            user: $(_js_escape "$MONGO_USER"),
            pwd: $(_js_escape "$MONGO_PASSWORD"),
            roles: [ { role: 'readWrite', db: $(_js_escape "$MONGO_DB") } ]
        })
EOJS

fi

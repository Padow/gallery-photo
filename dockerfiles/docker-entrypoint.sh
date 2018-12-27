#!/bin/sh
set -e

mkdir -p /var/www/html/config

cat > /var/www/html/config/sql.json <<EOF
{
	"sql":{
		"driver":"$DATABASE_DRIVER",
		"host":"$DATABASE_HOST",
		"user":"$DATABASE_USER",
		"password":"$DATABASE_PASSWORD",
		"database":"$DATABASE_NAME"
	}
}
EOF

cat > /var/www/html/config/admin.json <<EOF
{
	"admin":{
		"login":"$ADMIN_USER",
		"password":"$ADMIN_PASSWORD"
	}
}
EOF

cat > /var/www/html/config/param.json <<EOF
{
	"header":{
		"pagename":"$PAGENAME"
	},
	"content":{
		"title":"$TITLE",
		"subtitle":"$SUBTITLE"
	},
	"footer":{
		"contact":"$CONTACT"
	}
}
EOF

# Extracted from https://github.com/docker-library/php/blob/master/5.6/alpine3.8/fpm/docker-php-entrypoint
#
# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi

exec "$@"

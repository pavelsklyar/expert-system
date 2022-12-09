set -e

psql -v ON_ERROR_STOP=1 --username "app" --dbname "app" <<-EOSQL
	SELECT 'CREATE DATABASE app_test'
  WHERE NOT EXISTS (SELECT FROM pg_database WHERE datname = 'app_test')\gexec
	GRANT ALL PRIVILEGES ON DATABASE app_test TO app;
EOSQL
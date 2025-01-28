to deploy:
1. `composer update`
2. rename `.env.exmaple` to `.env`
3. (only if you don't have mysql on your host) uncomment `mysql-db` service in `docker-compose.yml` and configure db by your own (including `db.php`)
4. `docker compose up -d --build`
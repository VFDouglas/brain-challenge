docker compose exec php composer install --no-dev
docker compose exec php composer update --no-dev
docker compose exec php composer dump-autoload -o
docker compose exec php php artisan config:cache
docker compose exec php php artisan route:cache
docker compose exec php php artisan view:cache
docker compose exec php npm install
docker compose exec php npm run build
docker compose restart nginx php

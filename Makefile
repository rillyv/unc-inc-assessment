setup:
	cd api && cp .env.example .env && \
	cd ../nlp_service && cp .env.example .env && \
	cd ../api && composer install

up:
	docker compose up -d
	sleep 3
	docker compose exec api php artisan migrate --force
	docker compose exec -d api php artisan queue:work --sleep=1 --tries=3

down:
	docker compose down

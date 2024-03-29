.PHONY: up down stop start install cli uli test
include .env
up:
	docker-compose up -d
down:
	docker-compose down
stop:
	docker-compose stop
start:
	docker-compose start
install: up
	docker-compose exec -T php composer install --no-interaction
	docker-compose exec -T php bash -c "drush site:install --existing-config --db-url=mysql://$(MYSQL_USER):$(MYSQL_PASS)@$(MYSQL_HOST):$(MYSQL_PORT)/$(MYSQL_DB_NAME) -y"
	docker-compose exec -T php bash -c "drush user:create yevhen --mail=\"drupal@example.com\" --password=\"dbrf23\""
	docker-compose exec -T php bash -c "drush user:role:add administrator yevhen"
	docker-compose exec -T php bash -c "drush user:block admin"
	docker-compose exec -T php bash -c 'mkdir -p "drush" && echo -e "options:\n  uri: http://$(PROJECT_BASE_URL)" > drush/drush.yml'
cli:
	docker-compose exec php bash
node-cli:
	docker-compose exec node bash
uli:
	docker-compose exec php bash -c "drush uli"
test:
	docker-compose exec -T php curl 0.0.0.0:80 -H "Host: $(PROJECT_BASE_URL)"

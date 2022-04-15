.PHONY: up down stop start install cli
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
	docker-compose exec php bash -c "drush site:install --db-url=mysql://$(MYSQL_USER):$(MYSQL_PASS)@$(MYSQL_HOST):$(MYSQL_PORT)/$(MYSQL_DB_NAME) -y && drush user:create jeka --mail=\"drupal@example.com\" --password=\"dbrf23\" && drush user:role:add administrator jeka && drush user:block admin"
	@mkdir "drush"
	@echo "options:\n  uri: 'http://$(PROJECT_BASE_URL)'" > drush.yml
cli:
	docker-compose exec php bash
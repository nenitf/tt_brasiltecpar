up:
	@docker-compose --env-file .env.local up -d

down:
	@docker-compose --env-file .env.local down

install:
	@docker-compose --env-file .env.local exec app composer install

createdb:
	@docker-compose --env-file .env.local exec app php bin/console doctrine:database:create

migrate:
	@docker-compose --env-file .env.local exec app php bin/console doctrine:migrations:migrate

test:
	@docker-compose --env-file .env.local exec app php bin/phpunit

bash:
	@echo "Saia do container com \033[42mexit\033[0m"
	@docker-compose --env-file .env.local exec app bash

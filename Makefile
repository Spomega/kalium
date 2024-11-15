run:
	docker compose up -d

check-running-containers:
	docker compose ps

logs:
	docker compose logs

down:
	docker compose down

create-migration-entity:
	symfony console make:migration

migrate:
	symfony console doctrine:migration:migrate

symfony-up:
	symfony server:start -d

symfony-down:
	symfony server:stop

fix-code-violations:
	vendor/bin/phpcbf --standard=ruleset.xml --extensions=php --tab-width=4 -sp src tests

code-sniffer:
	vendor/bin/phpcs --standard=ruleset.xml --extensions=php --tab-width=4 -sp src tests

load-fixture:
	symfony console doctrine:fixtures:load --env=test

tests:
	symfony console doctrine:database:drop --force --env=test || true
	symfony console doctrine:database:create --env=test
	symfony console doctrine:migrations:migrate -n --env=test
	symfony console doctrine:fixtures:load -n --env=test
	symfony php bin/phpunit $(MAKECMDGOALS)



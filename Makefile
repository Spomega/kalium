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
	symfony server-stop

fix-PSR12-code-violations:
	vendor/bin/phpcbf --standard=ruleset.xml --extensions=php --tab-width=4 -sp src tests

code-sniffer:
	vendor/bin/phpcs --standard=ruleset.xml --extensions=php --tab-width=4 -sp src tests





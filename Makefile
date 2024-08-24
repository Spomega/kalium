run:
	docker compose up -d

check-running-containers:
	docker compose ps

logs:
	docker compose logs

down:
	docker compose down

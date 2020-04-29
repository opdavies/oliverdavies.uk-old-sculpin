tests:
	docker-compose exec app /app/bin/phpunit -c /app ${ARGS}

.PHONY: tests

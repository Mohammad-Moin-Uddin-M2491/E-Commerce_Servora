.DEFAULT_GOAL := run

.PHONY: run setup test

run:
	php tools/start.php run

setup:
	php tools/start.php setup

test:
	php artisan test

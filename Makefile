.DEFAULT_GOAL := help

help:  ## Display this help
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n  make \033[36m<target>\033[0m\n"} /^[a-zA-Z_-]+:.*?##/ { printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST)

##@ Application commands
create: build start # Create whole application with building images

build: # build local images
	docker compose build
start: # start stopped containers
	docker compose up -d
stop: # stop all containers (without removing them and they volumes)
	docker compose stop
destroy: # Kill all containers, remove volumes and networks
	docker compose down

install: # install composer dependencies using app service
	docker compose run --rm --user $(shell id -u):$(shell id -g) php composer install

upgrade: # updates composer dependencies using app service
	docker compose run --rm --user $(shell id -u):$(shell id -g) php composer upgrade

app: # attach to running app service with bash shell
	docker compose exec --user $(shell id -u):$(shell id -g) php bash

test: # Run phpunit tests
	docker compose run --rm --user $(shell id -u):$(shell id -g) php ./bin/phpunit

phpstan: # Run phpstan
	docker compose run --rm php ./vendor/bin/phpstan analyse

phpcs: # Run phpcs
	docker compose run --rm php ./vendor/bin/phpcs

phpcbf: # Run phpcbf
	docker compose run --rm --user $(shell id -u):$(shell id -g) php ./vendor/bin/phpcbf

grumphp: # Run grumphp
	docker compose run --rm --user $(shell id -u):$(shell id -g) php ./vendor/bin/grumphp run

grumphp-init: # Run grumphp init
	docker compose run --rm --user $(shell id -u):$(shell id -g) php ./vendor/bin/grumphp git:init
	docker compose run --rm --user $(shell id -u):$(shell id -g) php ./vendor/bin/grumphp git:pre-commit

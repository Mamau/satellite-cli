work-dir=$(shell pwd)
home-dir=$(shell echo $$HOME)
username=
token=
composer-repository=
node-v=10
user_id=$(shell id -u)
composer-v=2

DEFAULT_GOAL := help
help:
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n  make \033[36m<target>\033[0m\n"} /^[a-zA-Z0-9_-]+:.*?##/ { printf "  \033[36m%-27s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST)

guard-%:
	@ if [ "${${*}}" = "" ]; then \
		echo "need argument $*. Try something like this $*=value"; \
	        exit 1; \
	fi

bower: ## Install bower dependencies
	docker run -ti -u $(user_id) -v $(work-dir):/home/node mamau/bower install

yarn: ## Install yarn dependencies
	docker run -ti -u $(user_id) --workdir=/home/node -v $(work-dir):/home/node node:$(node-v) /bin/bash -c "yarn"

watch: ## Run watch
	docker run -ti -u $(user_id) --workdir=/home/node -v $(work-dir):/home/node node:$(node-v) /bin/bash -c "yarn watch"

composer: ## Install composer dependencies
	## add specific repository, need modify bin/bahs command: "composer config $(composer-repository) $(username) $(token); composer i --ignore-platform-reqs"
	docker run -ti -u $(user_id) --workdir=/home/www-data -v /etc/ssl/certs:/etc/ssl/certs -v $(work-dir):/home/www-data composer:$(composer-v) /bin/bash -c "composer i --ignore-platform-reqs"

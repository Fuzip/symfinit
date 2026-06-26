.DEFAULT_GOAL = help

## —— Project 📁 ————————————————————————————————————————————————————————————————
.PHONY: help install

help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9\./_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

install: ## Install the composer dependencies
	@composer install --no-interaction --no-progress

phpstan: ## Run phpstan
	@vendor/bin/phpstan -vv

csfixer: ## Fix with php-cs-fixer
	@vendor/bin/php-cs-fixer fix --using-cache=no --verbose

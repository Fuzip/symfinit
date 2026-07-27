.DEFAULT_GOAL = help

DIST ?= dist
TARGET ?= $(DIST)/symfinit
PHAR := $(DIST)/symfinit.phar

## —— Project 📁 ————————————————————————————————————————————————————————————————
.PHONY: help install phpstan csfixer

help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9\./_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

install: ## Install the composer dependencies
	@composer install --no-interaction --no-progress

phpstan: ## Run phpstan
	@vendor/bin/phpstan -vv

csfixer: ## Fix with php-cs-fixer
	@vendor/bin/php-cs-fixer fix --using-cache=no --verbose

## —— Project 🐘 ————————————————————————————————————————————————————————————————
.PHONY: build clean

build: ## Build a static binary using crazywhalecc/static-php-cli (require spc https://static-php.dev/
	@mkdir -p $(DIST)
	@composer install --no-dev --no-interaction --no-progress --optimize-autoloader
	@php -d phar.readonly=0 scripts/build-phar.php $(PHAR)
	@composer install --no-interaction --no-progress
	@spc craft
	@spc micro:combine $(PHAR) -O $(TARGET)
	@rm -f $(PHAR)

clean: ## Clean phar build
	@rm -rf $(DIST)

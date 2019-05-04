COMPOSER ?= composer
NPM ?= npm
GIT ?= git
MKDIR ?= mkdir
PHP ?= php
RM ?= rm

all: update clean-cache

composer:
	@echo "Installing PHP dependencies"
	@echo "---------------------------"
	@echo

	${COMPOSER} install

npm:
	@echo "Installing CSS/JS dependencies"
	@echo "------------------------------"
	@echo

	${NPM} install
	${NPM} update

update:
	@echo "Updating application's dependencies"
	@echo "-----------------------------------"
	@echo

	${GIT} pull origin master
	${MKDIR} -p data/git
	${MKDIR} -p data/cache
	${COMPOSER} update
	${NPM} install
	${NPM} update

clean-cache:
	@echo "Removing cache"
	@echo "--------------"
	@echo

	${RM} -fr cache/*

run:
	@echo "Run development server"
	@echo "----------------------"
	@echo

	${PHP} -S 127.0.0.1:8080 -t web

propel:
	@echo "Propel migration"
	@echo "----------------"
	@echo

	./vendor/propel/propel/bin/propel config:convert
	./vendor/propel/propel/bin/propel model:build --recursive
	./vendor/propel/propel/bin/propel migration:diff --recursive
	./vendor/propel/propel/bin/propel migration:migrate --recursive

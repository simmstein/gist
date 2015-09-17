COMPOSER ?= composer
BOWER ?= bower
GIT ?= git
MKDIR ?= mkdir
PHP ?= php

all: update

composer:
	@echo "Installing application's dependencies"
	@echo "-------------------------------------"
	@echo 

	$(COMPOSER) install $(COMPOSER_INSTALL_FLAGS)
bower:
	@echo "Installing application's dependencies"
	@echo "-------------------------------------"
	@echo 

	$(BOWER) install

optimize:
	@echo "Optimizing Composer's autoloader, can take some time"
	@echo "----------------------------------------------------"
	@echo 

	$(COMPOSER) dump-autoload --optimize

update:
	@echo "Updating application's dependencies"
	@echo "-----------------------------------"
	@echo 

	$(GIT) pull origin master
	${MKDIR} -p data/git
	$(COMPOSER) update
	$(BOWER) install

run:
	@echo "Run development server"
	@echo "----------------------"
	@echo 

	$(PHP) -S 127.0.0.1:8080 -t web

propel:
	@echo "Propel migration"
	@echo "----------------"
	@echo 
	
	./vendor/propel/propel/bin/propel config:convert
	./vendor/propel/propel/bin/propel model:build --recursive
	./vendor/propel/propel/bin/propel migration:diff --recursive
	./vendor/propel/propel/bin/propel migration:migrate --recursive
	./vendor/propel/propel/bin/propel model:build --recursive

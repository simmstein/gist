COMPOSER ?= composer
BOWER ?= bower
GIT ?= git
MKDIR ?= mkdir

all: update

composer:
	@echo
	#
	# Installing application's dependencies.
	#
	$(COMPOSER) install $(COMPOSER_INSTALL_FLAGS)
bower:
	@echo
	#
	# Installing application's dependencies.
	#
	$(BOWER) install

optimize:
	@echo
	#
	# Optimizing Composer's autoloader, can take some time.
	#
	$(COMPOSER) dump-autoload --optimize

update:
	@echo
	#
	# Updating application's dependencies.
	#
	$(GIT) pull origin master
	${MKDIR} -p data/git
	$(COMPOSER) update
	$(BOWER) install

propel:
	@echo
	#
	# Propel migration.
	#
	./vendor/propel/propel/bin/propel config:convert
	./vendor/propel/propel/bin/propel model:build --recursive
	./vendor/propel/propel/bin/propel migration:diff --recursive
	./vendor/propel/propel/bin/propel migration:migrate --recursive
	./vendor/propel/propel/bin/propel model:build --recursive

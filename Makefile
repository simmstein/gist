COMPOSER ?= composer
BOWER ?= bower

all: composer
all: bower

prod: COMPOSER_INSTALL_FLAGS += --no-dev
prod: all optimize

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
	# Updating application's depencies.
	#
	$(COMPOSER) update
	$(BOWER) install

GIST
====

GIST is an open-source application to share code.

Requirements
------------

* PHP >= 5.4
* GIT
* Composer
* Bower

Installation
------------

	$ git clone https://gitlab.deblan.org/deblan/gist.deblan.org.git
	$ cd gist.deblan.org.git
	$ make

Composer
--------

Composer can maybe be downloaded from your system's repositories.
Else, follow the next instructions:

### Download

    # With cURL
    curl -sS https://getcomposer.org/installer | php

    # With Wget
    wget -O - -q https://getcomposer.org/installer | php

You can now use it with `php composer.phar [arguments]`.

### Executable

    mv composer.phar composer
    chmod +x composer

Use it with `./composer [arguments]`.

### Install

Assuming `~/bin` exists ans is in `$PATH`.

    mv composer ~/bin

### Dependencies Installation (from `composer.lock`)

    composer install

### Dependencies Update (will change `composer.lock`)

    composer update

Bower
-----

### Install

	npm install -g bower

### Dependencies Installation (from `bower.json`)

    bower install

### Dependencies Update (will change `bower.json`)

    bower update


Makefile
--------

A Makefile is provided to automate some tasks.

* `make` will install application's dependencies via Composer,
* `make prod` will install dependencies without developmenent requirements
  and run `make optimize`,
* `make optimize` will run Composer's autoloader dump script with classmap
  only, without dynamic autoload rules,

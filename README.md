GIST
====

GIST is an open-source application to share code.

Requirements
------------

* PHP >= 5.4
* GIT
* MySQL, PostgreSQL or SQLite
* Composer (php)
* Bower (node)

Installation
------------

	$ git clone https://gitlab.deblan.org/deblan/gist
	$ cd gist
	$ make
	$ mv propel-dist.yaml propel.yaml
	$ # EDIT propel.yml (dsn)
	$ make propel

### Git

Git can maybe be downloaded from your system's repositories.

	$ git config --global user.email "you@example.com"
	$ git config --global user.name "Your Name"

### Composer

Composer can maybe be downloaded from your system's repositories.
Else, follow the next instructions:

#### Download

    # With cURL
    curl -sS https://getcomposer.org/installer | php

    # With Wget
    wget -O - -q https://getcomposer.org/installer | php

You can now use it with `php composer.phar [arguments]`.

#### Executable

    mv composer.phar composer
    chmod +x composer

Use it with `./composer [arguments]`.

#### Install

Assuming `~/bin` exists ans is in `$PATH`.

    mv composer ~/bin

#### Dependencies Installation (from `composer.lock`)

    composer install

#### Dependencies Update (will change `composer.lock`)

    composer update

### Bower


#### Install

	npm install -g bower

#### D#ependencies Installation (from `bower.json`)

    bower install

#### De#pendencies Update

    bower inxtall


Makefile
--------

A Makefile is provided to automate some tasks.

* `make` will install application's dependencies via Composer and Bower,
* `make optimize` will run Composer's autoloader dump script with classmap
* `make update` will update the application
* `make propel` will generate propel's files

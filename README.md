Table of Contents
=================

  * [GIST](#gist)
    * [Requirements](#requirements)
      * [Git](#git)
      * [Composer](#composer)
      * [Bower](#bower)
    * [Installation](#installation)
    * [Upgrade](#upgrade)
    * [Makefile](#makefile)
    * [API](#api)
      * [Create a new gist](#create-a-new-gist)
      * [Update an existing Gist](#update-an-existing-gist)
    * [Console](#console)
      * [Create and update gists](#create-and-update-gists)
      * [Create user](#create-user)
      * [Show stats](#show-stats)
    * [Configuration](#configuration)
      * [API](#api-1)
      * [Authentication](#authentication)
      * [Debug](#debug)
    * [Deployment](#deployment)
    * [Contributors](#contributors)

GIST
====

GIST is an open-source application to share code.
https://www.deblan.io/post/517/gist-est-dans-la-place

![Gist](https://upload.deblan.org/u/2015-11/565b93a5.png "Gist")

![Gist](https://upload.deblan.org/u/2016-06/57655dec.png "Gist")

Requirements
------------

* PHP >= 5.4
* GIT
* MySQL, PostgreSQL or SQLite
* Composer (php)
* Bower (node)

### Git

Git can maybe be downloaded from your system's repositories.

	$ git config --global user.email "you@example.com"
	$ git config --global user.name "Your Name"

### Composer

Composer can maybe be downloaded from your system's repositories.
Else, follow the next instructions:

	# With cURL
	$ curl -sS https://getcomposer.org/installer | php

	# With Wget
	$ wget -O - -q https://getcomposer.org/installer | php

	$ chmod +x composer.phar

	# For a local installation and if the envvar PATH contains "$HOME/bin/"
	$ mv composer.phar ~/bin/composer

	# For a global installation
	$ sudo mv composer.phar /usr/local/bin/composer

### Bower

	$ sudo apt-get install npm
	$ sudo npm install -g bower

Installation
------------

	$ cd /path/to/www/
	$ git clone https://gitnet.fr/deblan/gist
	$ cd gist
	$ make
	$ mv propel-dist.yaml propel.yaml
	
Edit `propel.yaml`. **Use spaces instead of tabulations**.

**MySQL**

    propel:
        database:
            connections:
                default:
                    adapter: mysql
                    # http://www.php.net/manual/en/ref.pdo-mysql.connection.php
                    dsn: "mysql:host=DATABASE_SERVER;dbname=DATABASE_NAME"
                    user: DATEBASE_USER
                    password: DATEBASE_PASSWORD
                    settings:
                        charset: utf8
                        queries:
                            utf8: "SET NAMES utf8 COLLATE utf8_unicode_ci, COLLATION_CONNECTION = utf8_unicode_ci, COLLATION_DATABASE = utf8_unicode_ci, COLLATION_SERVER = utf8_unicode_ci"
    
    [...]

**SQLITE**

    propel:
        database:
            connections:
                default:
                    adapter: sqlite
                    # http://www.php.net/manual/en/ref.pdo-sqlite.connection.php
                    dsn: "sqlite:/PATH/TO/gist.sqlite"
                    user: ~
                    password: ~
    
    [...]
	
Then `$ make propel`.

Edit `app/bootstrap.php.d/70-security.php` and modify the value of `$app['token']` with a strong secret phrase.

The web server must have permission to write into `data`.

	$ sudo chown -R www-data:www-data data

Your webserver must be configured to serve `web/` as document root. If you use nginx, all virtual paths must be rooted with `web/index.php` or `web/app_dev.php` ([documentation](https://www.nginx.com/resources/wiki/start/topics/recipes/symfony/)).

Upgrade
-------
	
	$ make update
	$ make propel

Makefile
--------

A Makefile is provided to automate some tasks.

* `make` will install application's dependencies via Composer and Bower,
* `make optimize` will run Composer's autoloader dump script with classmap
* `make update` will update the application
* `make propel` will generate propel's files
* `make run` will run development server on http://127.0.0.1:8080/

API
---

### Create a new gist

**POST** /{locale}/api/create
Params:

* `form[title]`: String (required, can be empty)
* `form[type]`: String (required)
  Values: html, css, javascript, php, sql, xml, yaml, perl, c, asp, python, bash, actionscript3, text
* `form[content]`: String (required)

**Responses:**

* Code `200`: A json which contains gist's information. Example:
  ```javascript
{
    "url": "https:\/\/gist.deblan.org\/en\/view\/55abcfa7771e0\/f4afbf72967dd95e3461490dcaa310d728d6a97d",
    "gist": {
        "Id": 66,
        "Title": "test prod",
        "Cipher": false,
        "Type": "javascript",
        "File": "55abcfa7771e0",
        "CreatedAt": "2015-07-19T16:26:15Z",
        "UpdatedAt": "2015-07-19T16:26:15Z"
    }
}
  ```
* Code `405`: Method Not Allowed
* Code `400`: Bad Request

### Update an existing Gist

**POST** /{locale}/api/update/{id}
Params:

* `{id}`: Gist Id (required)
* `form[content]`: String (required)

**Responses:**

* Code `200`: A json which contains gist's information. Example:
  ```javascript
{
    "url": "https:\/\/gist.deblan.org\/en\/view\/55abcfa7771e0\/abcgi72967dd95e3461490dcaa310d728d6adef",
    "gist": {
        "Id": 66,
        "Title": "test prod",
        "Cipher": false,
        "Type": "javascript",
        "File": "55abcfa7771e0",
        "CreatedAt": "2015-07-19T16:26:15Z",
        "UpdatedAt": "2015-07-19T16:30:15Z"
    }
}
  ```
* Code `405`: Method Not Allowed
* Code `400`: Bad Request

Console
-------

### Create and update gists

```
$ app/console --help create
$ app/console --help update
```

### Create user

```
$ app/console --help user:create
```

### Show stats

```
$ app/console --help stats
```

Configuration
-------------

### API

**Personal instance**

If you install Gist on your server, you have to modify the `base_uri` of the API.
Edit `app/bootstrap.php.d/60-api.php` and replace `https://gist.deblan.org/`.

### Authentication

**Disabling login**

Edit `app/bootstrap.php.d/70-security.php` and modify the value of `$app['enable_login']` with `false`.

**Disabling registration**

Edit `app/bootstrap.php.d/70-security.php` and modify the value of `$app['enable_registration']` with `false`.

**Login required to edit a gist**

Edit `app/bootstrap.php.d/70-security.php` and modify the value of `$app['login_required_to_edit_gist']` with `true`.

**Login required to view a gist**

Edit `app/bootstrap.php.d/70-security.php` and modify the value of `$app['login_required_to_view_gist']` with `true`.

**Login required to view an embeded gist**

Edit `app/bootstrap.php.d/70-security.php` and modify the value of `$app['login_required_to_view_embeded_gist']` with `true`.

### Debug

`app_dev.php` is the development router. Access is granted for an IP range defined in the same file.

Deployment
----------

Gist uses [Magallanes](http://magephp.com/) to manage deployment. 

**Global installation**

	$ composer global require andres-montanez/magallanes
	# if the envvar PATH contains "$HOME/bin/"
	$ ln -s ~/.composer/vendor/bin/mage ~/bin/mage

**Local installation**
	
	$ composer require andres-montanez/magallanes

There is an example of the configuration of an environment in `.mage/config/environment/prod.yml-dist`.

	# global installation
	$ mage deploy to:prod

	# local installation
	$ ./vendor/andres-montanez/magallanes/bin/mage deploy to:prod

Contributors
------------

**Developers**

	* Simon Vieille <contact@deblan.fr>

**Translators**

	* Simon Vieille <contact@deblan.fr>
	* Marion Sanchez
	* Marjorie Da Silva

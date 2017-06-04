Table of Contents
=================

  * [GIST](#gist)
    * [Requirements](#requirements)
      * [Git](#git)
      * [Composer](#composer)
      * [Bower](#bower)
    * [Installation](#installation)
    * [Upgrade](#upgrade)
    * [Configuration](#configuration)
    * [Makefile](#makefile)
    * [API](#api)
    * [Console](#console)
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
	$ cp propel-dist.yaml propel.yaml

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

**Versions >= 1.4.4 only**: `$ cp app/config/config.yml.dist app/config/config.yml`

See the [configuration section](#configuration) for more information about configuration.

---

The web server must have permission to write into `data`.

	$ sudo chown -R www-data:www-data data

Your webserver must be configured to serve `web/` as document root. If you use nginx, all virtual paths must be rooted with `web/index.php` or `web/app_dev.php` ([documentation](https://www.nginx.com/resources/wiki/start/topics/recipes/symfony/)). If you use apache, you must enable the `rewrite` module and restart:

	$ sudo a2enmod rewrite
	$ sudo service apache2 restart

`app_dev.php` is the development router. Access is granted for an IP range defined in the same file.

Upgrade
-------

If your version is less than v1.4.2, run: `test -d app && git add app && git commit -m "Configuration"`.

	$ make update
	$ make propel

If you upgrade to v1.4.1, run: `app/console migrate:to:v1.4.1`.

If you upgrade to v1.4.4 or more, the configuration is moved to a `app/config/config.yml`: `$ cp app/config/config.yml.dist app/config/config.yml` and see the [configuration section](#configuration) for more information.

Configuration
-------------

### Version < 1.4.4

Edit `app/bootstrap.php.d/70-security.php`.

* `$app['token']`: the securty token (a strong passphrase).
* `$app['enable_registration']`: defines if the registration is allowed (`true` or `false`)
* `$app['enable_login']`: defines if the login is allowed (`true` or `false`)
* `$app['login_required_to_edit_gist']`: defines if the user must be logged to create or clone a Gist (`true` or `false`)
* `$app['login_required_to_view_gist']`: defines if the user must be logged to view a Gist (`true` or `false`)
* `$app['login_required_to_view_gist']`: defines if the user must be logged to view an embeded Gist (`true` or `false`)

If you install Gist on your server, you have to modify the `base_uri` of the API.
Edit `app/bootstrap.php.d/60-api.php` and replace `https://gist.deblan.org/`.

### Version >= 1.4.4

Edit `app/config/config.yml`.

* `security.token`: the securty token (a strong passphrase)
* `security.enable_registration`: defines if the registration is allowed (`true` or `false`)
* `security.enable_login`: defines if the login is allowed (`true` or `false`)
* `security.login_required_to_edit_gist`: defines if the user must be logged to create or clone a Gist (`true` or `false`)
* `security.login_required_to_view_gist`: defines if the user must be logged to view a Gist (`true` or `false`)
* `security.login_required_to_view_gist`: defines if the user must be logged to view an embeded Gist (`true` or `false`)
* `api.base_uri`: The url of your instance.
* `data.path`: the path where the files are saved.
* `git.path`: The path of `git`.
* `theme.name`: the name of the theme (`dark` or `light`)

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

* Code `405`: Method Not Allowed
* Code `400`: Bad Request
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

### Update an existing gist

**POST** /{locale}/api/update/{id}
Params:

* `{id}`: Gist Id (required)
* `form[content]`: String (required)

**Responses:**

* Code `405`: Method Not Allowed
* Code `400`: Bad Request
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

Console
-------

* **Create a gist**: `$ app/console --help create`
* **Update a gist**: `$ app/console --help update`
* **Create user**: `app/console --help user:create`
* **Show stats**: `$ app/console --help stats`

Deployment
----------

Gist uses [Magallanes](http://magephp.com/) to manage deployment.

**Global installation**

	$ composer global require andres-montanez/magallanes
	# if the envvar PATH contains "$HOME/bin/"
	$ ln -s ~/.composer/vendor/bin/mage ~/bin/mage

**Local installation**

	$ composer require andres-montanez/magallanes

There is an example of the configuration of an environment in `.mage/config/environment/prod.yml.dist`.

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
* Mélanie Chanat
* Showfom

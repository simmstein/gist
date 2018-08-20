Table of Contents
=================

  * [GIST](#gist)
    * [Requirements](#requirements)
      * [Git](#git)
      * [Composer](#composer)
      * [NPM](#npm)
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
* MySQL or SQLite (PostgreSQL should works)
* Composer (php)
* NPM (nodejs)

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

### NPM

    $ sudo apt-get install npm

Installation
------------

    $ cd /path/to/www/
    $ git clone https://gitnet.fr/deblan/gist
    $ cd gist
    $ make

An interactive shell will start. If you want to perform a manual configuration, follow these instructions.

If you want to use `MySQL`:

    $ cp app/config/propel.yaml.dist-mysql propel.yaml

If you want to use `SQLite`:

    $ cp app/config/propel.yaml.dist-sqlite propel.yaml

Then edit `propel.yaml` and replace the values of `dsn`, `user`, `password` by considering your environment
and run `$ make propel`.

If you want to run the interactive shell manually, run:

    $ composer gist-scripts

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

If you upgrade to v1.4.1 or more: `app/console migrate:to:v1.4.1`.

If you upgrade to v1.4.4 or more, the configuration is moved to a `app/config/config.yml`: `$ cp app/config/config.yml.dist app/config/config.yml` and see the [configuration section](#configuration) for more information.

If you upgrade to v1.7.0 or more, see the [configuration section](#configurationh) for more information about new options.

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

### Version >= v1.7.0

* `api.enabled`: defines if the API is enabled (`true` or `false`)
* `api.api_key_required`: defines if the API key is required to access the API (`true` or `false`)
* `api.client.api_key`: defines the client API key (`string`)

Makefile
--------

A Makefile is provided to automate some tasks.

* `make` will install dependencies via composer and NPM
* `make composer` will install PHP dependencies via composer
* `make npm` will install CSS/JS dependencies via NPM
* `make update` will update the application
* `make propel` will generate propel migrations (database and files)
* `make run` will run development server on http://127.0.0.1:8080/

By default, `composer`, `npm`, `git`, `mkdir` and `php` binaries must be in your `$PATH`. You can override it by using these envars:

* `COMPOSER`
* `NPM`
* `GIT`
* `MKDIR`
* `PHP`

For example:

    $ export COMPOSER=/path/to/composer
    $ make composer

API
---

### Version < v1.7.0

#### Create a new gist

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

#### Update an existing gist

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

### Version >= v1.7.0

Invalid response codes:

* Code `401`: Unauthorized
* Code `403`: API not enabled
* Code `405`: Method Not Allowed
* Code `400`: Bad Request

#### List gists

**GET** /{locale}/api/list/{apiToken}

Response example:

```javascript
[
    {
        "id": 66,
        "title": "test prod",
        "cipher": false,
        "type": "javascript",
        "file": "55abcfa7771e0",
        "createdAt": "2015-07-19T16:26:15Z",
        "updatedAt": "2015-07-19T16:30:15Z"
        "url": "https:\/\/gist.deblan.org\/en\/view\/55abcfa7771e0\/abcgi72967dd95e3461490dcaa310d728d6adef",
    },
    {
        "id": 67,
        "title": "test prod 2",
        "cipher": false,
        "type": "javascript",
        "file": "xyzbcfa7771e0",
        "createdAt": "2015-08-19T16:26:15Z",
        "updatedAt": "2015-08-19T16:30:15Z"
        "url": "https:\/\/gist.deblan.org\/en\/view\/5byzbcfa7771e0\/def72967dd95e346koq0dcaa310d728d6artu",
    },
    ...
]
```

#### Create a new gist

**POST** /{locale}/api/create/{apiToken}
Params:

* `form[title]`: String (required, can be empty)
* `form[type]`: String (required)
  Values: html, css, javascript, php, sql, xml, yaml, perl, c, asp, python, bash, actionscript3, text
* `form[content]`: String (required)

Response example:

```javascript
{
    "url": "https:\/\/gist.deblan.org\/en\/view\/55abcfa7771e0\/f4afbf72967dd95e3461490dcaa310d728d6a97d",
    "gist": {
        "id": 66,
        "title": "test prod",
        "cipher": false,
        "type": "javascript",
        "file": "55abcfa7771e0",
        "createdAt": "2015-07-19T16:26:15Z",
        "updatedAt": "2015-07-19T16:26:15Z"
    }
}
```

#### Update an existing gist

**POST** /{locale}/api/update/{id}/{apiToken}
Params:

* `{id}`: Gist Id (required)
* `form[content]`: String (required)

Response example:

```javascript
{
    "url": "https:\/\/gist.deblan.org\/en\/view\/55abcfa7771e0\/abcgi72967dd95e3461490dcaa310d728d6adef",
    "gist": {
        "id": 66,
        "title": "test prod",
        "cipher": false,
        "type": "javascript",
        "file": "55abcfa7771e0",
        "createdAt": "2015-07-19T16:26:15Z",
        "updatedAt": "2015-07-19T16:30:15Z"
    }
}
```

#### Delete an existing gist

**POST** /{locale}/api/delete/{id}/{apiToken}

Response code `200`:

```javascript
{"error":false}
```

Response code `400`:

```javascript
{"message":"Invalid Gist", "error":true}
```

Console
-------

* **Create a gist**: `$ app/console --help create`
* **Update a gist**: `$ app/console --help update`
* **Create user**: `$ app/console --help user:create`
* **Show stats**: `$ app/console --help stats`

### Version >= v1.7.0

* **List your gists**: `$ app/console --help gists`
* **Delete a gist**: `$ app/console --help delete`

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
* Tavin

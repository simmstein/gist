GIST
====

GIST is an open-source application to share code.
https://www.deblan.io/post/517/gist-est-dans-la-place

![Gist](https://upload.deblan.org/u/2015-11/5654aa86.png "Gist")

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
	$ # EDIT propel.yaml (dsn)
	$ make propel

Screencast: https://asciinema.org/a/19814

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

#### Dependencies Installation (from `bower.json`)

    bower install

#### Dependencies Update

    bower install

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

* ```form[title]```: String (required, can be empty)
* ```form[type]```: String (required)
  Values: html, css, javascript, php, sql, xml, yaml, perl, c, asp, python, bash, actionscript3, text
* ```form[content]```: String (required)

#### Responses:

* Code ```200```: A json which contains gist's information. Example:
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
* Code ```405```: Method Not Allowed
* Code ```400```: Bad Request

### Update an existing Gist

**POST** /{locale}/api/update/{id}
Params:

* ```{id}```: Gist Id (required)
* ```form[content]```: String (required)

#### Responses:

* Code ```200```: A json which contains gist's information. Example:
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
* Code ```405```: Method Not Allowed
* Code ```400```: Bad Request

Console
-------

### Create and update gists:

```
$ app/console --help create
$ app/console --help update
```

### Create user

```
$ app/console stats
```

### Show stats

```
$ app/console --help stats
```

Configuration
-------------

### API

#### Personal instance

If you install Gist on your server, you have to modify the ```base_uri``` of the API.
Edit ```app/bootstrap.php.d/60-api.php``` and replace ```https://gist.deblan.org/```.

### Authentication

#### Disabling login

Edit `app/bootstrap.php.d/70-security.php` and modify the value of `$app['enable_login']` with `false`.

#### Disabling registration

Edit `app/bootstrap.php.d/70-security.php` and modify the value of `$app['enable_registration']` with `false`.

### Debug

`app_dev.php` is the development router. Access is granted for an IP range defined in the same file.

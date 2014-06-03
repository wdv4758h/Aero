DB project
================================================================================

DB project written by Chiu-Hsiang Hsu & Pu-Hsuan Wu

This is a homework which contain scary, terrible, horrible code.

Contribute
----------------------------------------

- Chiu-Hsiang Hsu (wdv4758h@gmail.com)

    * Server Part
    * Javasript
    * PHP

- Pu-Hsuan Wu (hwupu.cs01@nctu.edu.tw)

    * CSS
    * Javasript
    * PHP

Using
----------------------------------------

- OS

    * Arch Linux

- Database

    * MariaDB

- Web Server

    * Apache

- Version Control

    * Git

- Language

    * HTML
    * CSS
    * Javascript
        + AngularJS
        + alertify.js
    * PHP
        + Composer (PHP package manager)
        + Twig (PHP template engine)

Composer
----------------------------------------

- Install Composer

    ```bash
    curl -sS https://getcomposer.org/installer | php    # generate composer.phar
    ```

    If you want to put it to global environment, then `mv composer.phar /usr/bin/composer`.
    Now you can use composer by the command **composer**.

- Composer Self Update

    ```bash
    composer self-update
    ```

- Update Packages

    ```bash
    composer update
    ```

- Install Twig

    ```bash
    composer require twig/twig:1.*
    composer install
    ```

    Add to your PHP files

    ```php
    // Composer autoload
    require_once "vendor/autoload.php";
    ```

Gitweb
----------------------------------------

- [Gitweb - ArchWiki](https://wiki.archlinux.org/index.php/gitweb)
- For Apache 2.4 you need to install mod_perl
- Apache config

    * loadmodule

        ```
        LoadModule perl_module modules/mod_perl.so
        ```

    * gitweb config

        ```
        <IfModule mod_perl.c>
            Alias /gitweb "/usr/share/gitweb"
            <Directory "/usr/share/gitweb">
                DirectoryIndex gitweb.cgi
                Require all granted
                Options ExecCGI
                AddHandler perl-script .cgi
                PerlResponseHandler ModPerl::Registry
                PerlOptions +ParseHeaders
                SetEnv  GITWEB_CONFIG  /srv/http/gitweb/gitweb.conf
            </Directory>
        </IfModule>
        ```

HTTPS
----------------------------------------

We have Self-signed certificate (as it's just a homework) and force user to use HTTPS by redirect.

```apache
<VirtualHost *:80>
    ServerName www.example.com
    Redirect permanent / https://www.example.com/
</VirtualHost>
```

Code Explain
----------------------------------------

### PHP

#### include

- base.php

    * session start
    * php packages autoload (Composer)
    * function used by other php files

        + render(template name, array to pass into)

- db.php

    * Aero class

        + sql : sql syntax
        + execute : function to execute the sql
        + query : the query result

#### api

The PHP files in this directory will return a JSON files,
some may need to pass in parameters to get the data (like **search**)

### HTML/AngularJS

All the HTML is in the **template** directory,
and the HTML files will be processed by template engine before output to the users.

In each HTML files, it may contain some attributes that have **data-** prefix,
this prefix is the standard of **Custom Data Attributes** in **HTML5**,
and if there is a **ng-** after **data-** (which means **data-ng-**)
means it is the custom data attributes defined by AngularJS.

### CSS

All the CSS files are put in static/css/ directory.

### Javascript

All the Javascript files are put in static/js/ directory.

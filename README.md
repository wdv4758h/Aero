DB project
================================================================================

DB project written by Chiu-Hsiang Hsu & Pu-Hsuan Wu

This is a homework which contain scary, terrible, horrible code.

contribute
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

include
----------------------------------------

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

gitweb
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

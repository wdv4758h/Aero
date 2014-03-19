DB project
================================================================================

DB project written by Chiu-Hsiang Hsu & Pu-Hsuan Wu

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

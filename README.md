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

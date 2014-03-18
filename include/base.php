<?php

// start session if it is not started before
if (session_id() == "")
    session_start();

// Composer autoload
require_once './vendor/autoload.php';

// twig
function render($template, $parameter){
    $loader = new Twig_Loader_Filesystem('./template');
    $twig = new Twig_Environment($loader);

    //$parameter['session'] = $_SESSION;
    echo $twig->render($template, $parameter);
    return;
}

?>

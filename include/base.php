<?php

// start session if it is not started before
if (session_id() == "")
    session_start();

$root = realpath($_SERVER["DOCUMENT_ROOT"]).'/db_project';

// Composer autoload
require_once "$root/vendor/autoload.php";

// twig
function render($template, $parameter){
    global $root;
    $loader = new Twig_Loader_Filesystem("$root/template");
    $twig = new Twig_Environment($loader);

    //$parameter['session'] = $_SESSION;
    echo $twig->render($template, $parameter);
    return;
}

?>

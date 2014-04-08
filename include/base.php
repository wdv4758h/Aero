<?php

class Credential {
    const isAdmin = 1;
}

function checkCredential($level = 0) {
    if(!isset($_SESSION['id'])) {
        //echo "not login";
        header('location: /login');
        exit();
    }
    if($level==Credential::isAdmin && !$_SESSION['is_admin']) {
        //echo "not admin";
        header('location: /');
        exit();
    }
    require_once 'aero.php';
    $user = new User;
    $user -> get($_SESSION['id']);
    if($user->id == null) {
        //echo "not in databas";
        header('location: /login');
        exit();
    }
    $_SESSION['is_admin'] = $user -> is_admin;
}


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

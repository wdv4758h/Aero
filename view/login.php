<?php
require_once 'twig.php';
$template = $twig->loadTemplate('login.html');
echo $template->render();
?>
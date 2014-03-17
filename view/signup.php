<?php
require_once 'twig.php';
$template = $twig->loadTemplate('signup.html');
echo $template->render();
?>
<?php
require_once('../include/aero.php');
require_once('../include/base.php');
checkCredential(Credential::isAdmin);

// Render...
render('country_list.html', compact('_SESSION'));

?>

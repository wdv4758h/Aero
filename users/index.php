<?php
require_once('../include/aero.php');
require_once('../include/base.php');
checkCredential(Credential::isAdmin);

// Render...
render('users_list.html', compact('_SESSION'));

?>
<?php
require_once('../include/base.php');

if ( isset($_SESSION['id']) && $_SESSION['is_admin'])
    render('ticket.html', compact('_SESSION'));
else
    render('tickets_list_ng@newAERO.html', compact('_SESSION'));

?>

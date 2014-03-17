<?php
session_start();

// If is not POST connection... Render Login Page
if(!isset($_POST['username']) || trim($_POST['password'])=='') {
    require_once 'twig.php';
    $template = $twig->loadTemplate('login.html');
    echo $template->render(array());
    exit();
}

// Processing POST connection...
$username = $_POST['username'];
$password = $_POST['password'];

require_once('include/db.php');

try {
    $db = new PDO($dsn, $db_user, $db_password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM users WHERE username = ?";
    $query = $db -> prepare($sql);
    $query -> execute(array($username));
    $result = $query->fetchObject();
} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
    exit();
}

// Check
if (!$result) {
    echo "NOT FOUND";
    exit();
}

// Create password hash...
$password = sha1('mightySalt'.$password);


if ($password != $result->password) {
    session_destroy();
    
    // Render Login Page
    require_once 'twig.php';
    $template = $twig->loadTemplate('login.html');
    echo $template->render(array());
    
} else {
    
    // Authorized User... Redirecting...
    session_regenerate_id();
    $_SESSION['id'] = $result->id;
    $_SESSION['username'] = $result->username;
    $_SESSION['is_admin'] = $result->is_admin;
    session_write_close();
    header('location: main.php');
}

?>
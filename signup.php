<?php
session_start();

if($_POST['username'] && $_POST['password'] && $_POST['password2']) {
    
    require_once('include/db.php');
    
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];
    $is_admin = $_POST['is_admin']?1:0;

    if ($password != $password2) {
        echo "diff password";
        exit();
    }
    
    $password = sha1('mightySalt'.$password);
    
    try {
        $db = new PDO($dsn, $db_user, $db_password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT username FROM `phwu_cs`.`users` WHERE username = ?";
        $query = $db -> prepare($sql);
        $query -> execute(array($username));
        $result = $query->fetchObject();
        if ($result) {
            echo "repeated";
            exit();
        }
    } catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
        exit();
    }
    
    try {
        $db = new PDO($dsn, $db_user, $db_password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'INSERT INTO `phwu_cs`.`users` (`id`, `username`, `password`, `is_admin`) VALUES (NULL, ?, ?, ?)';
        $query = $db -> prepare($sql);
        $query -> execute(array($username,$password, $is_admin));
    } catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
        exit();
    }
    
    header('location: main.php');
    

} else {

    require_once 'twig.php';
    $template = $twig->loadTemplate('signup.html');
    echo $template->render(array());

}
?>
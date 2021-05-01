<?php
require_once "config.php";
if (isset($_GET["approuver"]) && !empty($_GET["approuver"])) {
    // Prepare an update statement
    $dsn = "mysql:host=$db_server;dbname=$db_name;charset=utf8mb4";
    $options = [
        PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //make the default fetch be an associative array
    ];
    try {
        $pdo = new PDO($dsn, $db_user, $db_password, $options);
    } catch (Exception $e) {
        error_log($e->getMessage());
        exit('Something weird happened');
    }

    // Get URL parameter
    $id =  trim($_GET["approuver"]);
    $stmt = $pdo->prepare("UPDATE utilisateur SET approuver=? WHERE id=?");

    if (!$stmt->execute(['1', $id])) {
        echo "Un problème est survenu. Veuillez réessayer plus tard.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: utilisateur-read.php?id=$id");
    }
} elseif (isset($_GET["desapprouver"]) && !empty($_GET["desapprouver"])) {
    // Get URL parameter
    // Prepare an update statement
    $dsn = "mysql:host=$db_server;dbname=$db_name;charset=utf8mb4";
    $options = [
        PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //make the default fetch be an associative array
    ];
    try {
        $pdo = new PDO($dsn, $db_user, $db_password, $options);
    } catch (Exception $e) {
        error_log($e->getMessage());
        exit('Something weird happened');
    }
    $id =  trim($_GET["desapprouver"]);
    $stmt = $pdo->prepare("UPDATE utilisateur SET approuver=? WHERE id=?");

    if (!$stmt->execute(['0', $id])) {
        echo "Un problème est survenu. Veuillez réessayer plus tard.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: utilisateur-read.php?id=$id");
    }
}

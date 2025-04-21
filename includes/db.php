<?php
// simple function to get db connection
function getdb() {
    // use static var so connection only made once per request
    static $pdo = null;
    if ($pdo === null) {
        // DSN data source name string
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS);
            // tell pdo give errors makes debug easier
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // fetch results as associative arrays easier to use
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // cant connect game over man
            die("db connection failed bro: " . $e->getMessage());
        }
    }
    return $pdo; // return the connection object
}
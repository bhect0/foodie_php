<?php

require_once 'config.php';

$DSN = '';
global $CONN;

try {
    $DSN = 'mysql:host='.$db_host.';dbname='.$db_name;
    $CONN = new PDO($DSN, $db_user, $db_pass, $db_options);

} catch(PDOException $error) {
    echo $error->getMessage();
}
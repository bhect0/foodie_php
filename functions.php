<?php

require_once 'config/init.php';

/***
 *
 * @param $html
 * @return string
 *
 *
 */
function escapar($html) {
    return htmlspecialchars($html, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}

function csrf() {

    session_start();

    if (empty($_SESSION['csrf'])) {
        if (function_exists('random_bytes')) {
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        } else if (function_exists('mcrypt_create_iv')) {
            $_SESSION['csrf'] = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
        } else {
            $_SESSION['csrf'] = bin2hex(openssl_random_pseudo_bytes(32));
        }
    }
}

function exec_query($sql, $r = []) {
    global $CONN;
    $ps = $CONN->prepare($sql);
    foreach ($r as $nombre => $valor) {
        $ps->bindValue(":".$nombre, $valor);
    }
    return $ps->execute();
}

function get_record($sql) {
    global $CONN;
    $ps = $CONN->prepare($sql);
    $ps->execute();
    return $ps->fetch(PDO::FETCH_ASSOC);

}

function get_records($sql) {
    global $CONN;
    $ps = $CONN->prepare($sql);
    $ps->execute();
    return $ps->fetchAll();

}
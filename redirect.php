<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/Config/config.php");
    require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/Config/tracking.php");

    if(!preg_match('|^[0-9a-zA-Z]{4,6}$|', $_REQUEST['url'])) {
        die("Not a valid redirect url");
    }

    $short = $mysql->real_escape_string($_REQUEST['url']);

    if (CACHE) {
        // Something about cache here
    } else {
        $result = $mysql->query(sprintf(SELECT_LONG_URL, $short))->fetch_assoc();
        $fullURL = $result['full'];
    }

    header("HTTP/1.1 301 Moved Permanently");
    header("Location: " . $fullURL);
    exit;
?>
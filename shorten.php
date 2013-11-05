<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/Config/config.php");

    echo $shorten->url($_REQUEST['url']);
?>
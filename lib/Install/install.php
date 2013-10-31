<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/Config/config.php");

    // Creates the links table
    $createTable = "CREATE TABLE IF NOT EXISTS links (
        id int not null auto_increment,
        short varchar(10),
        full varchar(500),
        primary key(id)
    )
    engine=myisam
    CHARACTER SET utf8
    COLLATE utf8_general_ci";

    // Verifies that the table was created
    if ($mysql->query($createTable) === FALSE) {
        die("Failed to create table; Please check database settings in" . $_SERVER['DOCUMENT_ROOT'] . "/lib/Config/config.php");
    }

    // Creates the installed file to prevent the script from trying to install again
    $fh = fopen($_SERVER['DOCUMENT_ROOT'] . "/lib/Install/installed", "w");

    if ($fh === FALSE) {
        die("Failed to create installed file; Please check user permissions");
    }

    fclose($fh);

    echo "Install Complete!";
?>
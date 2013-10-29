<?php
    require_once("./lib/Config/config.php");

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
        die("Failed to create table; Please check database settings in ./lib/Config/config.php");
    }

    // Attempts to make the CACHE_DIR
    // Could put this in the actual build but it helps test for file/dir creation permissions
    if (!mkdir("./cache")) {
        die("Failed to create cache folder; Please check user permissions");
    }

    // Creates the installed file to prevent the script from trying to install again
    $fh = fopen("./lib/Install/installed", "w");

    if ($fh === FALSE) {
        die("Failed to create installed file; Please check permissions");
    }

    fclose($fh);
?>
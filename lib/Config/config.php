<?php
    ini_set('max_execution_time',0);
    ini_set('display_errors', '1');
    ini_set('error_reporting', NULL);
    date_default_timezone_set('America/Toronto');

    require_once("./lib/Hashids/Hashids.php");

    /**
    * Defines mutiple objects
    * 
    * @param mixed $key Key to define or Array of keys to define
    * @param mixed $value Value to corespond with single key or FALSE
    * @return bool
    */
    function multiDefine($key, $value = FALSE) {
        if (is_null($key)) return FALSE;

        if (is_array($key))
            foreach ($key as $k => $v)
                if (!defined($k)) define($k, $v);
        else
            if (!defined($key)) define($key, $value);

        return TRUE;
    }

    // Dev DB Info
    // Can Ignore
    if (file_exists("./lib/Config/dbconfig.php"))
        require_once("./lib/Config/dbconfig.php");

    $config = array(
                    "MIN_NAME_LENGTH", 4,
                    "DB_NAME" => "YOUR_DB_NAME",
                    "DB_USER" => "YOUR_DB_USER",
                    "DB_PASS" => "YOUR_DB_PASS",
                    "DB_HOST" => "YOUR_DB_HOST",
                    "CACHE" => FALSE,
                    "CACHE_DIR" => dirname(__FILE__) . '/cache/',
                    "CHECK" => FALSE,
                    "BASE_URL" => "http://" . $_SERVER['HTTP_HOST'] . "/",
                    "SELECT_URL_CHECK" => "select short from links where full='%s'",
                    "SELECT_MAX_ID" => "insert into links () values()",
                    "SELECT_LONG_URL" => "select full from links where short='%s'",
                    "INSERT_SHORT_URL" => "insert into links (short, full) values('%s', '%s')",
                    "UPDATE_SHORT_URL" => "update links set short='%s', full='%s' where id=%s",
                    "INSTALLED_FILE" => "./lib/Install/installed", /* DO NOT REMOVE */
    			);

    multiDefine($config);

    $mysql = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($mysql->connect_errno)
        die("mysql connection error: " . $mysql->connect_error);
?>
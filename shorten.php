<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/Config/config.php");

    /**
     * Returns the DBs Max ID + 1 for hashing
     * @param  mysqli $c mysqli connection
     * @return int    value that is to be hashed
     */
    function getMaxID($c) {
        $result = $c->query(SELECT_MAX_ID);
        return $c->insert_id;
    }

    $url = trim($_REQUEST['url']);

    if (!empty($url) && preg_match('|^https?://|', $url)) {
        $hashids = new Hashids\Hashids('', MIN_NAME_LENGTH);

        $url = $mysql->real_escape_string($url);

        if (CHECK) {
            // verify that the page doesn't 404
        }

        $alreadyShort = $mysql->query(sprintf(SELECT_URL_CHECK, $url));

        // Will return the already shortened URL
        if ($alreadyShort->num_rows > 0) {
            $short = $alreadyShort->fetch_assoc();
            echo BASE_URL . $short['short'];
            exit;
        }

        // Creates the id
        $id = getMaxID($mysql);
        // Encrypts the hash
        $hash = $hashids->encrypt($id);
        // Updates the table
        $mysql->query(sprintf(UPDATE_SHORT_URL, $hash, $url, $id));

        echo BASE_URL . $hash;
        exit;
    }

    echo "Not a valid URL";
?>
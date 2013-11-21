<?php
class ShortenURL {
    private static $mysql;

    public function __construct($c) {
        self::$mysql = $c;
    }

    /**
     * Returns the DBs Max ID + 1 for hashing
     * @param  mysqli $c mysqli connection
     * @return int    value that is to be hashed
     */
    public static function getMaxID($c) {
        $result = $c->query(SELECT_MAX_ID);
        return $c->insert_id;
    }

    public static function url($u) {
        $url = trim($u);

        if (!empty($url) && preg_match('/(pnt2\.ca)/', $url)) {
            return "Cannot shorten Pnt2.ca links";
        } else if (!empty($url) && preg_match('/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?/', $url)) {
            $hashids = new Hashids\Hashids(HASH_SALT, MIN_NAME_LENGTH);

            $url = self::$mysql->real_escape_string($url);

            if (preg_match('/^(https?:\/\/)/', $url) == FALSE) {
                $url = "http://" . $url;
            }

            if (CHECK) {
                // verify that the page doesn't 404
            }

            $alreadyShort = self::$mysql->query(sprintf(SELECT_URL_CHECK, $url));

            // Will return the already shortened URL
            if ($alreadyShort->num_rows > 0) {
                $short = $alreadyShort->fetch_assoc();
                return BASE_URL . $short['short'];
            }

            // Creates the id
            $id = ShortenURL::getMaxID(self::$mysql);
            // Encrypts the hash
            $hash = $hashids->encrypt($id);
            // Updates the table
            self::$mysql->query(sprintf(UPDATE_SHORT_URL, $hash, $url, $id));

            return BASE_URL . $hash;
        }

        return "Not a valid URL";
    }
}
?>
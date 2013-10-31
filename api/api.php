<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/Config/config.php");
    require_once($_SERVER['DOCUMENT_ROOT'] . "/api/RestUtils.php");
    require_once($_SERVER['DOCUMENT_ROOT'] . "/api/RestRequest.php");

    /**
     * Returns the DBs Max ID + 1 for hashing
     * @param  mysqli $c mysqli connection
     * @return int    value that is to be hashed
     */
    function getMaxID($c) {
        $result = $c->query(SELECT_MAX_ID);
        return $c->insert_id;
    }

    $request = RestUtils::processRequest();
    $data = $request->getData();

    if (!isset($data['urls'])) {
        RestUtils::sendResponse(400, json_encode('{"error":"Invalid JSON"}'), 'application/json');
    }
    //$data = json_decode('{"urls":[{"url":"nig"},{"url":"http://pnt2.ca/2"},{"url":"http://pnt2.ca/3"}]}',true);

    $return = array('urls' => array());

    foreach ($data['urls'] as $urls) {
        $url = $urls['url'];

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
                $return['urls'][] = array('url' => BASE_URL . $short['short']);
            } else {
                // Creates the id
                $id = getMaxID($mysql);
                // Encrypts the hash
                $hash = $hashids->encrypt($id);
                // Updates the table
                $mysql->query(sprintf(UPDATE_SHORT_URL, $hash, $url, $id));

                $return['urls'][] = array('url' => BASE_URL . $hash);
            }
        } else {
            $return['urls'][] = array('url' => "Not a valid URL");
        }
    }

    RestUtils::sendResponse(200, json_encode($return), 'application/json');
?>
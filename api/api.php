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

    if ($request->getMethod() == "post" and !isset($data['urls'])) {
        RestUtils::sendResponse(400, json_encode('{"error":"Invalid JSON"}'), 'application/json');
    } elseif ($request->getMethod() == "get") {
        RestUtils::sendResponse(200, $shorten->url($data));
    } else {
        $return = array('urls' => array());

        foreach ($data['urls'] as $urls) {
            $url = $urls['url'];

            $return['urls'][] = $shorten->url($url);
        }

        RestUtils::sendResponse(200, json_encode($return), 'application/json');
    }
?>
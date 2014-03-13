<?php
    require('vendor/autoload.php');
    require($_SERVER['DOCUMENT_ROOT'] . "/lib/Config/config.php");

    $app = new \Slim\Slim();

    $app->get('/', 'index');
    $app->get('/:url', 'redirect');

    $app->group('/api', function() use ($app) {
        $app->post('/shorten', 'apiPostShortenURL');
    });

    $app->run();

    function index() {
        $app = \Slim\Slim::getInstance();
        $app->render('index.php');
    }

    function redirect($url) {
        $app = \Slim\Slim::getInstance();
        
        if (!preg_match('|^[0-9a-zA-Z]{4,6}$|', $url)) {
            $app->view()->setData(array('error' => sprintf("http://pnt2.ca/%s is not a valid url", $url)));
            $app->render('index.php');
        } else {
            $db = _buildConnection();
            $url = $db->real_escape_string($url);
            $result = $db->query(sprintf(SELECT_LONG_URL, $url))->fetch_assoc();
            $fullURL = $result['full'];
            $app->response->redirect($fullURL, 301);
            $app->response;
        }
    }

    function apiPostShortenURL() {
        $app = \Slim\Slim::getInstance();
        $request = $app->request();
        $data = json_decode($request->getBody(), true);

        if (!isset($data['urls'])) {
            $app->response->setStatus(400);
            $app->response->setBody(json_encode(array('error' => 'Invalid JSON')));
            $app->response->headers->set('Content-Type', 'application/json');
            $app->response;
        } else {
            //curl -X POST -H "Content-Type: application/json" -d '{"urls":[{"url":"http://google.ca"},{"url":"http://pnt2.ca"},{"url":"http://careers.stackoverflow.com/jobs?location=canada&range=20&distanceUnits=Miles"}]}' http://wstratto.ca/api/shorten
            $return = array('urls' => array());

            foreach ($data['urls'] as $url) {
                $return['urls'][] = _shortenURL($url['url']);
            }

            $app->response->setStatus(200);
            $app->response->setBody(json_encode($return, JSON_UNESCAPED_SLASHES));
            $app->response->headers->set('Content-Type', 'application/json');
            $app->response;
        }
    }

    function _buildConnection() {
        $mysql = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($mysql->connect_errno)
            die("mysql connection error: " . $mysql->connect_error);

        return $mysql;
    }

    function _shortenURL($url) {
        if (!isset($url) || empty($url) || preg_match('/(pnt2\.ca)/', $url)) {
            return "Cannot shorten Pnt2.ca links";
        } elseif (preg_match('/^(https?:\/\/)?([\da-zA-Z\.-]+)\.([a-zA-Z\.]{2,6})([\/\w \.-]*)*\/?/', $url)) {
            if (preg_match('/^(https?:\/\/)/', $url) == FALSE) {
                $url = "http://" . $url;
            }

            $db = _buildConnection();
            $url = $db->real_escape_string($url);

            // Checks for already short URL
            $alreadyShort = $db->query(sprintf(SELECT_URL_CHECK, $url));
            // Will return the already shortened URL
            if ($alreadyShort->num_rows > 0) {
                $short = $alreadyShort->fetch_assoc();
                return BASE_URL . $short['short'];
            } else {
                $db->query(SELECT_MAX_ID);
                $id = $db->insert_id;

                $hashids = new Hashids\Hashids(HASH_SALT, MIN_NAME_LENGTH);
                $hash = $hashids->encrypt($id);
                $db->query(sprintf(UPDATE_SHORT_URL, $hash, $url, $id));

                return BASE_URL . $hash;
            }
        } else {
            return "Not a valid URL";
        }
    }
?>
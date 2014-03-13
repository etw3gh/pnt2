PNT2
====

PHP based URL Shortener with JSON RESTFUL API built on Slim Framework

Try on-line @ [http://pnt2.ca](http://pnt2.ca).

Usage
=

Request
-
    POST /api/shorten
    Accept: application/json
    Content-Type: application/json
    Content-Length: 91

    {
        "urls":
        [
            {"url":"http://google.ca"},
            {"url":"http://pnt2.ca"},
            {"url":"http://github.com"}
        ]
    }

Response
-
    HTTP/1.1 200 OK
    Content-Type: application/json
    Content-Length: 93

    {
        "urls":
        [
        "http://pnt2.ca/enRe",
        "Cannot shorten Pnt2.ca links",
        "http://pnt2.ca/7yxw"
        ]
    }
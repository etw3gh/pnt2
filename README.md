PNT2
====

PHP based URL Shortener with REST API

Try on-line @ [http://pnt2.ca](http://pnt2.ca).

Usage
=====

Request:
POST /api
Accept: application/json

{
    "urls":
    [
        {"url":"http://google.ca"},
        {"url":"http://pnt2.ca"},
        {"url":"http://github.com"}
    ]
}

Response:
HTTP/1.1 200 OK
Content-Type: application/json

{
    "urls":
    [
        {"url":"http:\/\/pnt2.ca\/bq2d"},
        {"url":"http:\/\/pnt2.ca\/el5a"},
        {"url":"http:\/\/pnt2.ca\/erEd"}
    ]
}
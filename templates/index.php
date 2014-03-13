<!DOCTYPE html>
<html lang="en-us">
<head>
    <meta charset="utf-8">

    <title>Pnt2.ca</title>
    <meta name="description" content="URL Shortener">

    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Karla:400,700">
    <link rel="stylesheet" href=<?php $_SERVER['DOCUMENT_ROOT'] . "/" ?>"css/pnt2.css" media="screen"/>
</head>
<body>
    <?php
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/lib/Config/tracking.php"))
            include_once($_SERVER['DOCUMENT_ROOT'] . "/lib/Config/tracking.php"); 

        if (isset($this->data['error'])) {
    ?>
        <div class="isa_error"><?php echo $this->data['error']; ?></div>
    <?php
        } // Close if
    ?>
    <div class="logo">Pnt2</div>
    <div class="row">
        <form id="frmShorten">
            <input autofocus type="text" class="full" id="shorten">
            <div class="button" id="btnShorten">Shorten URL</div>
        </form>
    </div>
    <div class="github">View on GitHub <a href="http://pnt2.ca/bk5e">HERE</a></div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#frmShorten").submit(function(e) {
                e.preventDefault();
                shortenURL();
            });

            $("#btnShorten").click(function() {
                shortenURL();
            });

            function shortenURL() {
                $.ajax({
                    type: "POST",
                    url: "/api/shorten",
                    dataType: 'json',
                    data: '{"urls":[{"url":"' + $('#shorten').val() + '"}]}',
                    success: function(html) {
                        console.log(html);
                        if(html) {
                            document.getElementById('shorten').value = html.urls[0];
                        }
                    },
                    error: function(html) {
                        console.log(html);
                        if(html) {
                            obj = JSON.parse(html.responseText);
                            document.getElementById('shorten').value = obj.error;
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>
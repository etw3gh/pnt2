<!DOCTYPE html>
<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/Config/config.php");

    if (!file_exists(INSTALLED_FILE)) {
        require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/Install/install.php");
        exit;
    }
?>
<html lang="en-us">
<head>
    <meta charset="utf-8">

    <title>Pnt2</title>

    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Karla:400,700">
    <link rel="stylesheet" href="css/pnt2.css" media="screen"/>
</head>
<body>
    <?php
        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/lib/Config/tracking.php"))
            include_once($_SERVER['DOCUMENT_ROOT'] . "/lib/Config/tracking.php"); 
    ?>
    <div class="logo">pnt2.ca</div>
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
                $.ajax({
                    type: "POST",
                    url: "shorten.php",
                    data: { url : $('#shorten').val() },
                    success: function(html) {
                        if(html) {
                            document.getElementById('shorten').value = html;
                        }
                    }
                });
            });

            $("#btnShorten").click(function() {
                $.ajax({
                    type: "POST",
                    url: "shorten.php",
                    data: { url : $('#shorten').val() },
                    success: function(html) {
                        if(html) {
                            document.getElementById('shorten').value = html;
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
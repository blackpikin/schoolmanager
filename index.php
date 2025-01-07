<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1" id="viewport-meta" />
    <script>
        // Store the meta element
        var viewport_meta = document.getElementById('viewport-meta');

        // Define our viewport meta values
        var viewports = {
            bydefault: viewport_meta.getAttribute('content'),
            landscape: 'width=990'
        };

        // Change the viewport value based on screen.width
        var viewport_set = function() {
            if ( screen.width > 768 )
                viewport_meta.setAttribute( 'content', viewports.landscape );
            else
                viewport_meta.setAttribute( 'content', viewports.bydefault );
        };

        // Set the correct viewport value on page load
        viewport_set();

        // Set the correct viewport after device orientation change or resize
        window.onresize = function() {
            viewport_set();
        }
    </script>
    <title><?= 'ClassMaster Pro' ?></title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <link rel="stylesheet" href="fr/css/bootstrap.min.css" type="text/css" media="all">
    <link rel="stylesheet" href="fr/css/font-awesome.min.css" type="text/css" media="all">
    <link rel="stylesheet" href="fr/css/bootstrap-theme.min.css" type="text/css" media="all">
    <link rel="stylesheet" href="fr/css/style.css" type="text/css" media="all">
    <!--[if IE]>
    <link rel="stylesheet" type="text/css" href="./css/ie.css" />
    <![endif]-->
    <script type="text/javascript" src="fr/js/jquery-3.1.0.min.js"></script>
    <script type="text/javascript" src="fr/js/bootstrap.min.js"></script>
</head>
<body class="grey-body bkg-page-body" oncontextmenu="return false">
<div id="firstline" style="margin-top:75px;">
    <br>
    <div class="row">
        <div class="col-md-2 col-sm-2 hidden-xs">

        </div>
        <div class="col-md-8 col-sm-8 col-xs-12">
            
                <h4 id="label1" style="text-align:center;">Select the sub-system of Education</h4>
                <h4 id="label1" style="text-align:center;">Selectionner le sous-systeme</h4>
                <div class="curved-box" style="text-align:center;">
                <button onclick="Go('eng')" class="btn btn-primary" style="padding: 50px; font-size:20pt;">Anglosaxon</button>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button onclick="Go('fr')" class="btn btn-primary" style="padding: 50px; font-size:20pt;">Francophone</button>
                </div>
        </div>
        <div class="col-md-2 col-sm-2 hidden-xs">

        </div>
    </div>
</div>
<script >
    function Go(page){
        $.ajax({
            type: "POST",
            url: "fr/html/ajax.php",
            data: {lang:page, 'action':"ChangeLang"},
            dataType: 'html',
            success: function (data) {
                window.location = 'fr';
                console.log(data);
            },
            error: function () {
                console.log(Error().message);
            }
        });
    }
</script>
</body>
</html>
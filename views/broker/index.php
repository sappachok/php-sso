<!doctype html>
<html>
    <head>
        <title><?= $broker->broker ?> (Single Sign-On demo)</title>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <h1><?= $broker->broker ?> <small>(Single Sign-On demo)</small></h1>
            <h3>Logged in</h3>

            <pre><?= json_encode($user, JSON_PRETTY_PRINT); ?></pre>
            <a id="logout" class="btn btn-default" href="<?php echo base_url("broker/logout"); ?>">Logout</a>
        </div>
    </body>
</html>
<?php 
require 'system/connect.php'; 
require 'system/settings.php'; 
?>
<!DOCTYPE html>
<html class="no-js">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="css/jquery-ui-1.10.2.custom.min.css">
        <script src="js/vendor/modernizr-2.6.2.min.js"></script>
        
    </head>
    <body>
        <div class="main" role="main">
            <header>
                <h1>epub generator login</h1>
            </header>    
            <section>
              <form id="login_form" action="login_check.php" method="post">
                <label for="name">name</label>
                <input type="text" id="name" name="name" />
                <label for="password">password</label>
                <input type="password" name="password" id="password" />
                <input type="submit" />
              </form>
            </section> 
 

        </div>

        <section class="message">Set Message</section>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.9.1.min.js"><\/script>')</script>
        <script src="js/vendor/jquery-ui-1.10.2.custom.min.js"></script>
        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>
    </body>
</html>

<?php 
require 'connect.php'; 
require 'settings.php'; 
?>
<!DOCTYPE html>
<html class="no-js">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>add user</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="css/jquery-ui-1.10.2.custom.min.css">
        <script src="js/vendor/modernizr-2.6.2.min.js"></script>
        
    </head>
    <body>
        <div class="main" role="main">
          <section class="add-user">
            <h1>add user</h1>
            <form id="login_form" action="adduser.php" method="post">
              <label for="name">name</label>
              <input type="text" id="name" name="name" />
              <label for="e-mail">e-mail address</label>
              <input type="text" id="e-mail" name="e-mail" />
              <label for="password">password</label>
              <input type="password" name="password" id="password" />
              <input type="submit" value="Add user" />
            </form> 
          </section>       
        </div>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.9.1.min.js"><\/script>')</script>
        <script src="js/vendor/jquery-ui-1.10.2.custom.min.js"></script>
        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>
    </body>
</html>
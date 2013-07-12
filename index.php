<?php 
require 'system/connect.php'; 
require 'system/settings.php'; 
// if ($_SESSION['login']) {
?>
<!DOCTYPE html>
<html class="no-js">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>ePub Generator</title>
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
                <h1>epub generator</h1>
            </header>     
            <section class="actions">
              <ul>
                <li><a href="" class="action addSpread">add spread</a></li>
                <li><a href="system/generate.php" class="action generate">generate epub</a></li>
                <!-- <li><a href="system/user.php" class="action add-user">add user</a></li> -->
                <!-- <li><a href="settings.php" class="action settings">book settings</a></li> -->
              </ul>
            </section>
            <section class="content">
                <?php 
                $print = "";
                $spread_result = mysql_query("SELECT * FROM spread");
                $pageNumber = 0;
                if (mysql_num_rows($spread_result)) {
                    while($spread = mysql_fetch_assoc($spread_result)){
                        $print .= '<section class="spread" id="spread-'.$spread['id'].'"><a href="" class="action editPage">edit</a>'.PHP_EOL;
                        $piq = "SELECT * FROM spreadItem WHERE spreadId=".$spread['id'];
                        $pageId_result = mysql_query($piq);
                        if (mysql_num_rows($pageId_result)) {
                            while ($pageId = mysql_fetch_assoc($pageId_result)) {
                                $pageNumber++;
                               $pq = "SELECT * FROM item WHERE id=".$pageId['itemId'];
                               $checked = '';
                               $page_result = mysql_query($pq);
                               $page = mysql_fetch_assoc($page_result);
                               $print .= '<div class="page" id="page-'.$page['id'].'">'.PHP_EOL;
                               $print .= '      <div class="info">'.PHP_EOL;
                               $print .= '        <div class="page-number">'.$pageNumber.'</div>'.PHP_EOL;
                               $print .= '        <div class="title">'.$page['title'].'</div>'.PHP_EOL;
                               $print .= '      </div>'.PHP_EOL;
                               
                               $print .= '      <div class="edit hidden">'.PHP_EOL;
                               $print .= '          <div class="page-number">'.$pageNumber.'</div>'.PHP_EOL;
                               $print .= '          <div class="title"><input type="text" value="'.$page['title'].'" /></div>'.PHP_EOL;
                               $print .= '          <div class="content"><textarea> '.$page['content'].'</textarea></div>'.PHP_EOL;
                               if ( $page['backgroundImage'] ) { $checked .= ' checked'; }
                               $print .= '          <div class="image">image <input type="checkbox"'.$checked.' /></div>'.PHP_EOL;
                               $print .= '          <div class="added">created:<br>'.date("Y-m-d", $page['creationDate']).'</div>'.PHP_EOL;
                               $print .= '          <div class="changed">modified:<br>'.date("Y-m-d", $page['modificationDate']).'</div>'.PHP_EOL;
                               $print .= '          <div class="update"><a href="" class="action updatePage" update="'.$page['id'].'">update</a></div>'.PHP_EOL;
                               $print .= '      </div>'.PHP_EOL;
                               $print .= '      <div class="clearfix"></div>'.PHP_EOL;
                               $print .= '</div>'.PHP_EOL;
                            }
                        }

                        $print .= '<div class="clearfix"></div>'.PHP_EOL;
                        $print .= '</section>'.PHP_EOL;
                        
                    }
                    print $print;
                }
                ?>
                
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
<?php 
// } else {
//   header('Location: login.php');
// }

?>
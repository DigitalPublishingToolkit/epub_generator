<?php
require('connect.php');
require('helper.php');


$action = $_REQUEST['action'];
$return = array();
switch ($action) {

  case 'addSpread': 
    // add two items
    $time = time();
    mysql_query("INSERT INTO item (id, title, content, backgroundImage, creationDate, modificationDate) VALUES (NULL, '', '', '', '".$time."', '')");
    mysql_query("INSERT INTO item (id, title, content, backgroundImage, creationDate, modificationDate) VALUES (NULL, '', '', '', '".$time."', '')");
    mysql_query("INSERT INTO spread (id, title, creationDate, modificationDate) VALUES (NULL, '', '".$time."', '')");
    $spread_result = mysql_query("SELECT id FROM spread WHERE creationDate = ".$time);
    if (mysql_num_rows($spread_result) > 0) {
      $return['error'] = "no error";
      while ($spread = mysql_fetch_assoc($spread_result)) {
        $return["spreadId"] = $spread["id"];
        $page_result = mysql_query("SELECT id FROM item WHERE creationDate = ".$time);
        if (mysql_num_rows($page_result)) {
          $result['pageId'] = array();
          while ( $row = mysql_fetch_assoc($page_result) ) {
            mysql_query("INSERT INTO spreadItem (id, spreadId, itemId) VALUES ('', ".$spread['id'].", '".$row['id']."')");
            $return['pageId'][] = $row['id'];
          }
        }
      }
    } else {
      $return['error'] = "no spread found: ".mysql_error();
    }
    break;
  case 'updatePage':
    $page = array(  'id' => intval($_REQUEST['id']),
                    'title' => addslashes( htmlentities( $_REQUEST['title'], ENT_QUOTES, "UTF-8" ) ),
                    'content' => addslashes( htmlentities( $_REQUEST['content'], ENT_QUOTES, "UTF-8" ) ),
                    'modificationDate' => time(),
                    'hasImage' => 0
                  );
  if ($_REQUEST['hasImage'] == 'true') {
    $page['hasImage'] = 1;
  }

    $pageUpdateQ = "UPDATE  item SET title = '".$page['title']."', content='".$page['content']."', backgroundImage = '".$page['hasImage']."', modificationDate='".$page['modificationDate']."' WHERE id=".$page['id'];
    // $return['query'] = $pageUpdateQ;
    if ( mysql_query($pageUpdateQ) ) {
      $return['error'] = FALSE;
      $return['message'] = 'update successful';
      // $return['hasImage'] = page['hasImage'];
    } else {
      $return['error'] = 'update failed: '.mysql_error();
    }

    break;
  default:
    $return['content'] = $action;
    break;
}

print json_encode($return);
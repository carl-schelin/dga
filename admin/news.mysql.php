<?php

  include ('../login/check.php');
  include ('../function.php');

  $package = "news.mysql.php";

  if (check_userlevel(3)) {
    $formVars['news_id']       = clean($_POST['id'], 10);
    $formVars['news_title']    = clean($_POST['title'], 255);
    $formVars['news_start']    = clean($_POST['start'], 20);
    $formVars['news_end']      = clean($_POST['end'], 20);
    $formVars['news_text']     = clean($_POST['text'], 65535);
    $formVars['news_status']   = clean($_POST['status'], 10);

    if ($formVars['news_start'] == '') {
      $formVars['news_start'] = 0;      
    }
    if ($formVars['news_start'] == '') {
      $formVars['news_start'] = 0;      
    }
   
    $formVars['news_start']  = strtotime($formVars['news_start']);
    $formVars['news_end']    = strtotime($formVars['news_end']);

    if ($formVars['news_status'] == true) {
      $formVars['news_status'] = $_SESSION['uid'];
    } else {
      $formVars['news_status'] = 0;
    }
   
    $q_string = "select con_id from convention where con_active > 0";
    $q_convention = mysql_query($q_string) or die($q_string . ": " . mysql_error());
    $a_convention = mysql_fetch_array($q_convention);

    $q_string = 
      "news_title  = \"" . $formVars['news_title']  . "\"," . 
      "news_start  =   " . $formVars['news_start']  . "," . 
      "news_end    =   " . $formVars['news_end']    . "," . 
      "news_text   = \"" . $formVars['news_text']   . "\"," . 
      "news_status = \"" . $formVars['news_status'] . "\"," . 
      "news_conid  =   " . $a_convention['con_id'];

    if ($formVars['news_id'] > 0) {
      $query = "update news set " .$q_string . " where news_id = " . $formVars['news_id'];
      logaccess($_SESSION['username'], $package, "Updating news " . $formVars['news_id']);
    } else {
      $query = "insert into news set news_id = NULL," . $q_string;
      logaccess($_SESSION['username'], $package, "Adding news article");
    }

    $insert = mysql_query($query) or die($query . ": " . mysql_error());
  } else {
    logaccess($_SESSION['username'], $package, "Access Denied!");
  }

?>
<html>
<meta http-equiv="REFRESH" content="0; url=news.php">
</html>

<?php

  include('settings.php');
  include($Sitepath . '/login/check.php');
  include($Sitepath . '/function.php');

  $package = "image.mysql.php";

  if (check_userlevel(3)) {
    $formVars['img_id']     = clean($_POST['id'], 10);
    $formVars['img_title']  = clean($_POST['title'], 255);
    $formVars['img_file']   = clean($_POST['file'], 255);
    $formVars['img_status'] = clean($_POST['status'], 10);

    if ($formVars['img_status'] == true) {
      $formVars['img_status'] = $_SESSION['uid'];
    } else {
      $formVars['img_status'] = 0;
    }
   
    $q_string = 
      "img_title    = \"" . $formVars['img_title']  . "\"," . 
      "img_file     = \"" . $formVars['img_file']   . "\"," . 
      "img_status   =   " . $formVars['img_status'];

    if ($formVars['img_id'] > 0) {
      $query = "update images set " .$q_string . " where img_id = " . $formVars['img_id'];
      logaccess($_SESSION['username'], $package, "Updating images " . $formVars['img_id']);
    } else {
      $query = "insert into images set img_id = NULL," . $q_string;
      logaccess($_SESSION['username'], $package, "Adding Images");
    }

    $insert = mysql_query($query) or die($query . ": " . mysql_error());

    if ($formVars['img_status'] > 0) {
      if (file_exists($Sitepath . '/upload/' . $formVars['img_file'])) {
        rename($Sitepath . '/upload/' . $formVars['img_file'], $Sitepath . '/approved/' . $formVars['img_file']);
      }
    }
  } else {
    logaccess($_SESSION['username'], $package, "Access Denied!");
  }

?>
<html>
<meta http-equiv="REFRESH" content="0; url=image.php">
</html>

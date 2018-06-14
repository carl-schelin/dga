<?php

  include ('../login/check.php');
  include ('../function.php');

  $package = "guest.mysql.php";

  if (check_userlevel(3)) {
    $formVars['gst_id']       = clean($_POST['id'], 10);
    $formVars['gst_title']    = clean($_POST['title'], 255);
    $formVars['gst_name']     = clean($_POST['name'], 255);
    $formVars['gst_location'] = clean($_POST['location'], 10);
    $formVars['gst_order']    = clean($_POST['order'], 10);
    $formVars['gst_image']    = clean($_POST['image'], 10);
    $formVars['gst_start']    = clean($_POST['start'], 20);
    $formVars['gst_end']      = clean($_POST['end'], 20);
    $formVars['gst_text']     = clean($_POST['text'], 65535);
    $formVars['gst_status']   = clean($_POST['status'], 10);

    if ($formVars['gst_start'] == '') {
      $formVars['gst_start'] = 0;      
    }
    if ($formVars['gst_end'] == '') {
      $formVars['gst_end'] = 0;      
    }
   
    $formVars['gst_start']  = strtotime($formVars['gst_start']);
    $formVars['gst_end']    = strtotime($formVars['gst_end']);

    if ($formVars['gst_status'] == true) {
      $formVars['gst_status'] = $_SESSION['uid'];
    } else {
      $formVars['gst_status'] = 0;
    }
   
    $q_string = "select con_id from convention where con_active > 0";
    $q_convention = mysql_query($q_string) or die($q_string . ": " . mysql_error());
    $a_convention = mysql_fetch_array($q_convention);

    $q_string = 
      "gst_title    = \"" . $formVars['gst_title']    . "\"," . 
      "gst_name     = \"" . $formVars['gst_name']     . "\"," . 
      "gst_location =   " . $formVars['gst_location'] . "," . 
      "gst_order    =   " . $formVars['gst_order']    . "," . 
      "gst_image    =   " . $formVars['gst_image']    . "," . 
      "gst_start    =   " . $formVars['gst_start']    . "," . 
      "gst_end      =   " . $formVars['gst_end']      . "," . 
      "gst_text     = \"" . $formVars['gst_text']     . "\"," . 
      "gst_status   = \"" . $formVars['gst_status']   . "\"," . 
      "gst_conid    =   " . $a_convention['con_id'];

    if ($formVars['gst_id'] > 0) {
      $query = "update guests set " .$q_string . " where gst_id = " . $formVars['gst_id'];
      logaccess($_SESSION['username'], $package, "Updating guests " . $formVars['gst_id']);
    } else {
      $query = "insert into guests set gst_id = NULL," . $q_string;
      logaccess($_SESSION['username'], $package, "Adding guest of honor");
    }

    $insert = mysql_query($query) or die($query . ": " . mysql_error());
  } else {
    logaccess($_SESSION['username'], $package, "Access Denied!");
  }

?>
<html>
<meta http-equiv="REFRESH" content="0; url=guest.php">
</html>

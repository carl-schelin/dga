<?php

  include('settings.php');
  include($Sitepath . '/login/check.php');
  include($Sitepath . '/function.php');

  $package = "category.mysql.php";

// you shouldn't be able to get here without being level 3 but just in case
  if (check_userlevel(3)) {
    $formVars['cat_id']      = clean($_POST['id'], 10);
    $formVars['cat_title']   = clean($_POST['title'], 255);
    $formVars['cat_cost']    = clean($_POST['cost'], 20);
    $formVars['cat_base']    = clean($_POST['base'], 10);
    $formVars['cat_menu']    = clean($_POST['menu'], 30);
    $formVars['cat_group']   = clean($_POST['group'], 10);
    $formVars['cat_color']   = clean($_POST['color'], 15);
    $formVars['cat_email']   = clean($_POST['email'], 255);
    $formVars['cat_summary'] = clean($_POST['summary'], 512);
    $formVars['cat_text']    = clean($_POST['text'], 65535);
    $formVars['cat_status']  = clean($_POST['status'], 10);

    if ($formVars['cat_cost'] == '') {
      $formVars['cat_cost'] = 0.00;
    }

    if ($formVars['cat_status'] == true) {
      $formVars['cat_status'] = $_SESSION['uid'];
    } else {
      $formVars['cat_status'] = 0;
    }

    $q_string = "select con_id from convention where con_active > 0";
    $q_convention = mysql_query($q_string) or die($q_string . ": " . mysql_error());
    $a_convention = mysql_fetch_array($q_convention);

    $q_string = 
      "cat_conid   =   " . $a_convention['con_id']  . "," . 
      "cat_title   = \"" . $formVars['cat_title']   . "\"," . 
      "cat_cost    =   " . $formVars['cat_cost']    . "," . 
      "cat_base    =   " . $formVars['cat_base']    . "," . 
      "cat_menu    = \"" . $formVars['cat_menu']    . "\"," . 
      "cat_group   =   " . $formVars['cat_group']   . "," . 
      "cat_color   = \"" . $formVars['cat_color']   . "\"," . 
      "cat_email   = \"" . $formVars['cat_email']   . "\"," . 
      "cat_summary = \"" . $formVars['cat_summary'] . "\"," . 
      "cat_text    = \"" . $formVars['cat_text']    . "\"," . 
      "cat_status  =   " . $formVars['cat_status'];

    if ($formVars['cat_id'] > 0) {
      $query = "update categories set " .$q_string . " where cat_id = " . $formVars['cat_id'];
      logaccess($_SESSION['username'], $package, "Updating category " . $formVars['cat_id']);
    } else {
      $query = "insert into categories set cat_id = NULL," . $q_string;
      logaccess($_SESSION['username'], $package, "Adding category record");
    }

    $insert = mysql_query($query) or die($query . ": " . mysql_error());
  } else {
    logaccess($_SESSION['username'], $package, "Access Denied!");
  }

?>
<html>
<meta http-equiv="REFRESH" content="0; url=category.php">
</html>

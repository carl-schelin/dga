<?php

  include('settings.php');
  include($Sitepath . '/login/check.php');
  include($Sitepath . '/function.php');

  $package = "contact.mysql.php";

  if (check_userlevel(3)) {
    $formVars['con_id']     = clean($_POST['id'], 10);
    $formVars['con_text']   = clean($_POST['text'], 65535);
    $formVars['con_status'] = clean($_POST['status'], 10);

    if ($formVars['con_status'] == true) {
      $formVars['con_status'] = $_SESSION['uid'];
    } else {
      $formVars['con_status'] = 0;
    }
   
    $q_string = "select con_id from convention where con_active > 0";
    $q_convention = mysql_query($q_string) or die($q_string . ": " . mysql_error());
    $a_convention = mysql_fetch_array($q_convention);

    $q_string = 
      "con_text   = \"" . $formVars['con_text']   . "\"," . 
      "con_status = \"" . $formVars['con_status'] . "\"," . 
      "con_conid  =   " . $a_convention['con_id'];

    if ($formVars['con_id'] > 0) {
      $query = "update contacts set " .$q_string . " where con_id = " . $formVars['con_id'];
      logaccess($_SESSION['username'], $package, "Updating contacts " . $formVars['con_id']);
    } else {
      $query = "insert into contacts set con_id = NULL," . $q_string;
      logaccess($_SESSION['username'], $package, "Adding convention contacts");
    }

    $insert = mysql_query($query) or die($query . ": " . mysql_error());
  } else {
    logaccess($_SESSION['username'], $package, "Access Denied!");
  }

?>
<html>
<meta http-equiv="REFRESH" content="0; url=contact.php">
</html>

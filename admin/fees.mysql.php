<?php

  include('settings.php');
  include($Sitepath . '/login/check.php');
  include($Sitepath . '/function.php');

  $package = "fees.mysql.php";

  if (check_userlevel(3)) {
    $formVars['fee_id']     = clean($_POST['id'], 10);
    $formVars['fee_text']   = clean($_POST['text'], 65535);
    $formVars['fee_status'] = clean($_POST['status'], 10);

    if ($formVars['fee_status'] == true) {
      $formVars['fee_status'] = $_SESSION['uid'];
    } else {
      $formVars['fee_status'] = 0;
    }
   
    $q_string = "select con_id from convention where con_active > 0";
    $q_convention = mysql_query($q_string) or die($q_string . ": " . mysql_error());
    $a_convention = mysql_fetch_array($q_convention);

    $q_string = 
      "fee_text   = \"" . $formVars['fee_text']   . "\"," . 
      "fee_status = \"" . $formVars['fee_status'] . "\"," . 
      "fee_conid  =   " . $a_convention['con_id'];

    if ($formVars['fee_id'] > 0) {
      $query = "update fees set " .$q_string . " where fee_id = " . $formVars['fee_id'];
      logaccess($_SESSION['username'], $package, "Updating fees " . $formVars['fee_id']);
    } else {
      $query = "insert into fees set fee_id = NULL," . $q_string;
      logaccess($_SESSION['username'], $package, "Adding convention fees");
    }

    $insert = mysql_query($query) or die($query . ": " . mysql_error());
  } else {
    logaccess($_SESSION['username'], $package, "Access Denied!");
  }

?>
<html>
<meta http-equiv="REFRESH" content="0; url=fees.php">
</html>

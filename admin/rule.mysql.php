<?php

  include ('../login/check.php');
  include ('../function.php');

  $package = "rule.mysql.php";

  if (check_userlevel(3)) {
    $formVars['rul_id']     = clean($_POST['id'], 10);
    $formVars['rul_text']   = clean($_POST['text'], 65535);
    $formVars['rul_status'] = clean($_POST['status'], 10);

    if ($formVars['rul_status'] == true) {
      $formVars['rul_status'] = $_SESSION['uid'];
    } else {
      $formVars['rul_status'] = 0;
    }
   
    $q_string = "select con_id from convention where con_active > 0";
    $q_convention = mysql_query($q_string) or die($q_string . ": " . mysql_error());
    $a_convention = mysql_fetch_array($q_convention);

    $q_string = 
      "rul_text   = \"" . $formVars['rul_text']   . "\"," . 
      "rul_status = \"" . $formVars['rul_status'] . "\"," . 
      "rul_conid  =   " . $a_convention['con_id'];

    if ($formVars['rul_id'] > 0) {
      $query = "update rules set " .$q_string . " where rul_id = " . $formVars['rul_id'];
      logaccess($_SESSION['username'], $package, "Updating rules " . $formVars['rul_id']);
    } else {
      $query = "insert into rules set rul_id = NULL," . $q_string;
      logaccess($_SESSION['username'], $package, "Adding convention rules");
    }

    $insert = mysql_query($query) or die($query . ": " . mysql_error());
  } else {
    logaccess($_SESSION['username'], $package, "Access Denied!");
  }

?>
<html>
<meta http-equiv="REFRESH" content="0; url=rule.php">
</html>

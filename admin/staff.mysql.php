<?php

  include ('../login/check.php');
  include ('../function.php');

  $package = "staff.mysql.php";

  if (check_userlevel(3)) {
    $formVars['sta_id']     = clean($_POST['id'], 10);
    $formVars['sta_title']  = clean($_POST['title'], 10);
    $formVars['sta_user']   = clean($_POST['member'], 10);
    $formVars['sta_status'] = clean($_POST['status'], 10);

    if ($formVars['sta_status'] == true) {
      $formVars['sta_status'] = $_SESSION['uid'];
    } else {
      $formVars['sta_status'] = 0;
    }

    $q_string = "select con_id from convention where con_active > 0";
    $q_convention = mysql_query($q_string) or die($q_string . ": " . mysql_error());
    $a_convention = mysql_fetch_array($q_convention);

    $q_string = 
      "sta_title  = " . $formVars['sta_title']  . "," . 
      "sta_user   = " . $formVars['sta_user']   . "," . 
      "sta_status = " . $formVars['sta_status'] . "," . 
      "sta_conid  = " . $a_convention['con_id'];

    if ($formVars['sta_id'] > 0) {
      $query = "update staff set " .$q_string . " where sta_id = " . $formVars['sta_id'];
      logaccess($_SESSION['username'], $package, "Updating staff " . $formVars['sta_id']);
    } else {
      $query = "insert into staff set sta_id = NULL," . $q_string;
      logaccess($_SESSION['username'], $package, "Adding staff record");
    }

    $insert = mysql_query($query) or die($query . ": " . mysql_error());
  } else {
    logaccess($_SESSION['username'], $package, "Access Denied!");
  }

?>
<html>
<meta http-equiv="REFRESH" content="0; url=staff.php">
</html>

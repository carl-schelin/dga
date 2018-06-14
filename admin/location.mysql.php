<?php

  include ('../login/check.php');
  include ('../function.php');

  $package = "location.mysql.php";

  if (check_userlevel(3)) {
    $formVars['loc_id']     = clean($_POST['id'], 10);
    $formVars['loc_title']  = clean($_POST['title'], 100);
    $formVars['loc_subloc'] = clean($_POST['subloc'], 100);
    $formVars['loc_limit']  = clean($_POST['limit'], 10);
    $formVars['loc_status'] = clean($_POST['status'], 10);

    if ($formVars['loc_limit'] == '' ) {
      $formVars['loc_limit'] = 0;
    }

    if ($formVars['loc_status'] == true) {
      $formVars['loc_status'] = $_SESSION['uid'];
    } else {
      $formVars['loc_status'] = 0;
    }

    $q_string = "select con_id from convention where con_active > 0";
    $q_convention = mysql_query($q_string) or die($q_string . ": " . mysql_error());
    $a_convention = mysql_fetch_array($q_convention);

    $q_string = 
      "loc_title  = \"" . $formVars['loc_title']  . "\"," . 
      "loc_subloc = \"" . $formVars['loc_subloc'] . "\"," . 
      "loc_limit  =   " . $formVars['loc_limit']  . "," . 
      "loc_status =   " . $formVars['loc_status'] . "," . 
      "loc_conid  =   " . $a_convention['con_id'];

    if ($formVars['loc_id'] > 0) {
      $query = "update locations set " .$q_string . " where loc_id = " . $formVars['loc_id'];
      logaccess($_SESSION['username'], $package, "Updating location " . $formVars['loc_id']);
    } else {
      $query = "insert into locations set loc_id = NULL," . $q_string;
      logaccess($_SESSION['username'], $package, "Adding location record");
    }

    $insert = mysql_query($query) or die($query . ": " . mysql_error());
  } else {
    logaccess($_SESSION['username'], $package, "Access Denied!");
  }

?>
<html>
<meta http-equiv="REFRESH" content="0; url=location.php">
</html>

<?php

  include('settings.php');
  include($Sitepath . '/login/check.php');
  include($Sitepath . '/function.php');

  $package = "group.mysql.php";

  if (check_userlevel(3)) {
    $formVars['grp_id']     = clean($_POST['id'], 10);
    $formVars['grp_name']   = clean($_POST['title'], 255);
    $formVars['grp_status'] = clean($_POST['status'], 10);

    if ($formVars['grp_status'] == true) {
      $formVars['grp_status'] = $_SESSION['uid'];
    } else {
      $formVars['grp_status'] = 0;
    }

    $q_string = "select con_id from convention where con_active > 0";
    $q_convention = mysql_query($q_string) or die($q_string . ": " . mysql_error());
    $a_convention = mysql_fetch_array($q_convention);

    $q_string = 
      "grp_name   = \"" . $formVars['grp_name']   . "\"," . 
      "grp_status =   " . $formVars['grp_status'] . "," . 
      "grp_conid  =   " . $a_convention['con_id'];

    if ($formVars['grp_id'] > 0) {
      $query = "update groups set " .$q_string . " where grp_id = " . $formVars['grp_id'];
      logaccess($_SESSION['username'], $package, "Updating group " . $formVars['grp_id']);
    } else {
      $query = "insert into groups set grp_id = NULL," . $q_string;
      logaccess($_SESSION['username'], $package, "Adding group record");
    }

    $insert = mysql_query($query) or die($query . ": " . mysql_error());
  } else {
    logaccess($_SESSION['username'], $package, "Access Denied!");
  }

?>
<html>
<meta http-equiv="REFRESH" content="0; url=group.php">
</html>

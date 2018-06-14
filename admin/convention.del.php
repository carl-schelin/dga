<?php

  include('settings.php');
  include($Sitepath . '/login/check.php');
  include($Sitepath . '/function.php');

  $package = "convention.del.php";

// id of the record being deleted
  $formVars['id']   = clean($_GET['id'], 10);

// This deletes every single item associated with the convention from the database.
// But only delete if the site admin or developer requests it
  if (check_userlevel(2)) {

    $q_string = "delete from convention where con_id = " . $formVars['id'];
    $result = mysql_query($q_string) or die($q_string . ": " . mysql_error());

    $q_string = "delete from categories where cat_conid = " . $formVars['id'];
    $result = mysql_query($q_string) or die($q_string . ": " . mysql_error());

    $q_string = "delete from condays where day_conid = " . $formVars['id'];
    $result = mysql_query($q_string) or die($q_string . ": " . mysql_error());

    $q_string = "delete from events where evt_conid = " . $formVars['id'];
    $result = mysql_query($q_string) or die($q_string . ": " . mysql_error());

    $q_string = "delete from groups where grp_conid = " . $formVars['id'];
    $result = mysql_query($q_string) or die($q_string . ": " . mysql_error());

    $q_string = "delete from guests where gst_conid = " . $formVars['id'];
    $result = mysql_query($q_string) or die($q_string . ": " . mysql_error());

    $q_string = "delete from locations where loc_conid = " . $formVars['id'];
    $result = mysql_query($q_string) or die($q_string . ": " . mysql_error());

    $q_string = "delete from news where news_conid = " . $formVars['id'];
    $result = mysql_query($q_string) or die($q_string . ": " . mysql_error());

    $q_string = "delete from rate_events where rate_conid = " . $formVars['id'];
    $result = mysql_query($q_string) or die($q_string . ": " . mysql_error());

    $q_string = "delete from rate_judges where rate_conid = " . $formVars['id'];
    $result = mysql_query($q_string) or die($q_string . ": " . mysql_error());

    $q_string = "delete from rules where rul_conid = " . $formVars['id'];
    $result = mysql_query($q_string) or die($q_string . ": " . mysql_error());

    $q_string = "delete from signup where sup_conid = " . $formVars['id'];
    $result = mysql_query($q_string) or die($q_string . ": " . mysql_error());

    $q_string = "delete from staff where sta_conid = " . $formVars['id'];
    $result = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  } else {
    logaccess($_SESSION['username'], $package, "Access Denied!");
  }

?>
<html>
<meta http-equiv="REFRESH" content="0; url=convention.php">
</html>

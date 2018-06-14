<?php

  include('settings.php');
  include($Sitepath . '/login/check.php');
  include($Sitepath . '/function.php');

  $package = "events.del.php";

// id of the record being deleted
  $formVars['id']   = clean($_GET['id'], 10);

  if (check_userlevel(3)) {
    $q_string = "delete from events where evt_id = " . $formVars['id'];
    $result = mysql_query($q_string) or die($q_string . ": " . mysql_error());

# clear all signup records where sup_paid = 0;
# then mark paid records as 'deleted' or unavailable
# note: need to notify anyone who signed up that an event has been deleted.

    $q_string = "select sup_id from signup where sup_event = " . $formVars['id'] . " and sup_delete = 0 and sup_paid = 1";

  } else {
    logaccess($_SESSION['username'], $package, "Access Denied!");
  }

?>

<html>
<meta http-equiv="REFRESH" content="0; url=event.php">
</html>


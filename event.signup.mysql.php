<?php

  include('login/check.php');
  include('function.php');

  $package = "event.signup.php";

  logaccess($_SESSION['username'], $package, "Event Signup");

  $formVars['evt_id']    = clean($_POST['id'], 10);

# Get event details
  $q_string = "select evt_limit,evt_start,evt_end,evt_conid from events where evt_id = " . $formVars['evt_id'];
  $q_events = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  $a_events = mysql_fetch_array($q_events);

  $convention = $a_events['evt_conid'];

# note the intention of buying a ticket
  $q_string = "insert into signup set sup_id = null, sup_conid = " . $convention . ",sup_event = " . $formVars['evt_id'] . ", sup_user = " . $_SESSION['uid'];
  $q_signup = mysql_query($q_string) or die($q_string . ": " . mysql_error());

?>

<html>
<meta http-equiv="REFRESH" content="0; url=event.php">
</html>


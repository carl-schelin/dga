<?php

  include ('login/check.php');
  include ('function.php');

  $package = "event.comment.mysql.php";

  $formVars['update']         = clean($_POST['update'], 20);
  $formVars['rate_event']     = clean($_POST['event'], 10);
  $formVars['rate_user']      = clean($_SESSION['uid'], 10);
  $formVars['rate_anonymous'] = clean($_POST['anonymous'], 10);
  $formVars['rate_rating']    = clean($_POST['rating'], 10);
  $formVars['rate_text']      = clean($_POST['text'], 65535);

# so if anonymous, set status to 0, if not, set status to uid of submitter
  if ($formVars['rate_anonymous'] == true) {
    $formVars['rate_anonymous'] = 1;
    $formVars['rate_status'] = 0;
  } else {
    $formVars['rate_anonymous'] = 0;
    $formVars['rate_status'] = $_SESSION['uid'];
  }

  $q_string = "select con_id from convention where con_active > 0";
  $q_convention = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  $a_convention = mysql_fetch_array($q_convention);

  $q_string = 
    "rate_event     =   " . $formVars['rate_event']     . "," . 
    "rate_user      =   " . $formVars['rate_user']      . "," . 
    "rate_anonymous =   " . $formVars['rate_anonymous'] . "," . 
    "rate_rating    =   " . $formVars['rate_rating']    . "," . 
    "rate_text      = \"" . $formVars['rate_text']      . "\"," . 
    "rate_status    =   " . $formVars['rate_status']    . "," . 
    "rate_conid     =   " . $a_convention['con_id'];

  if ($formVars['update'] == "Submit New") {
    $query = "insert into rate_events set rate_id = NULL," . $q_string;
    logaccess($_SESSION['username'], $package, "Adding Event Rating record");
  } else {
    $query = "update rate_events set " .$q_string . " where rate_id = " . $formVars['rate_id'];
    logaccess($_SESSION['username'], $package, "Updating Event Rating " . $formVars['rate_id']);
  }

  $insert = mysql_query($query) or die($query . ": " . mysql_error());

?>
<html>
<meta http-equiv="REFRESH" content="0; url=event.php">
</html>

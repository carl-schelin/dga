<?php

  include ('login/check.php');
  include ('function.php');

  $package = "event.edit.php";

  $formVars['evt_id']       = clean($_POST['id'], 10);
  $formVars['update']       = clean($_POST['update'], 10);
  $formVars['evt_type']     = clean($_POST['type'], 10);
  $formVars['evt_title']    = clean($_POST['title'], 255);
  $formVars['evt_subtitle'] = clean($_POST['subtitle'], 255);
  $formVars['evt_host']     = clean($_POST['host'], 10);
  $formVars['evt_text']     = clean($_POST['text'], 65535);
  $formVars['startdate']    = clean($_POST['startdate'], 20);
  $formVars['starttime']    = clean($_POST['starttime'], 20);
  $formVars['enddate']      = clean($_POST['enddate'], 20);
  $formVars['endtime']      = clean($_POST['endtime'], 20);
  $formVars['evt_limit']    = clean($_POST['limit'], 10);
  $formVars['evt_complete'] = clean($_POST['complete'], 10);

  $formVars['evt_start'] = (strtotime($formVars['startdate']) + $formVars['starttime']);
  $formVars['evt_end']   = (strtotime($formVars['enddate'])   + $formVars['endtime']);

  if ($formVars['evt_complete'] == true) {
    $formVars['evt_complete'] = $_SESSION['uid'];
  } else {
    $formVars['evt_complete'] = 0;
  }

  $q_string = "select con_id from convention where con_active > 0";
  $q_convention = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  $a_convention = mysql_fetch_array($q_convention);

  $q_string = 
    "evt_type     =   " . $formVars['evt_type']     . "," . 
    "evt_title    = \"" . $formVars['evt_title']    . "\"," . 
    "evt_subtitle = \"" . $formVars['evt_subtitle'] . "\"," . 
    "evt_host     =   " . $formVars['evt_host']     . "," . 
    "evt_text     = \"" . $formVars['evt_text']     . "\"," . 
    "evt_start    =   " . $formVars['evt_start']    . "," . 
    "evt_end      =   " . $formVars['evt_end']      . "," . 
    "evt_limit    =   " . $formVars['evt_limit']    . "," . 
    "evt_complete =   " . $formVars['evt_complete'] . "," . 
    "evt_status   =   " . "0"                       . "," . 
    "evt_conid    =   " . $a_convention['con_id'];

  if ($formVars['update'] == "Submit New") {
    $query = "insert into events set evt_id = NULL," . $q_string;
    logaccess($_SESSION['username'], $package, "Adding Event record");
  } else {
    $query = "update events set " .$q_string . " where evt_id = " . $formVars['evt_id'];
    logaccess($_SESSION['username'], $package, "Updating Event " . $formVars['evt_id']);
  }

  $insert = mysql_query($query) or die($query . ": " . mysql_error());

?>
<html>
<meta http-equiv="REFRESH" content="0; url=event.php">
</html>

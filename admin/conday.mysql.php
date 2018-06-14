<?php

  include('settings.php');
  include($Sitepath . '/login/check.php');
  include($Sitepath . '/function.php');

  $package = "condays.mysql.php";

  if (check_userlevel(3)) {
    $formVars['day_id']     = clean($_POST['id'], 10);
    $formVars['update']     = clean($_POST['update'], 10);
    $formVars['day_start']  = clean($_POST['startdate'], 20);
    $formVars['day_tstart'] = clean($_POST['starttime'], 20);
    $formVars['day_end']    = clean($_POST['enddate'], 20);
    $formVars['day_tend']   = clean($_POST['endtime'], 20);
    $formVars['day_status'] = clean($_POST['status'], 10);

    if ($formVars['startdate'] == '') {
      $formVars['startdate'] = 0;
    }
    if ($formVars['enddate'] == '') {
      $formVars['enddate'] = 0;
    }

    $formVars['day_start'] = strtotime($formVars['day_start'] . " 12:00:00 AM");
    $formVars['day_end']   = strtotime($formVars['day_end']   . " 12:00:00 AM");

    if ($formVars['day_status'] == true) {
      $formVars['day_status'] = $_SESSION['uid'];
    } else {
      $formVars['day_status'] = 0;
    }

    $q_string = "select con_id from convention where con_active > 0";
    $q_convention = mysql_query($q_string) or die($q_string . ": " . mysql_error());
    $a_convention = mysql_fetch_array($q_convention);

    $q_string = 
      "day_start   =  " . $formVars['day_start']  . "," . 
      "day_end     =  " . $formVars['day_end']    . "," . 
      "day_tstart  =  " . $formVars['day_tstart'] . "," . 
      "day_tend    =  " . $formVars['day_tend']   . "," . 
      "day_status =   " . $formVars['day_status'] . "," . 
      "day_conid  =   " . $a_convention['con_id'];

    if ($formVars['update'] == "Submit New") {
      $query = "insert into condays set day_id = NULL," . $q_string;
      logaccess($_SESSION['username'], $package, "Adding Convention Day record");
    } else {
      $query = "update condays set " .$q_string . " where day_id = " . $formVars['day_id'];
      logaccess($_SESSION['username'], $package, "Updating Convention Day " . $formVars['day_id']);
    }

    $insert = mysql_query($query) or die($query . ": " . mysql_error());
  } else {
    logaccess($_SESSION['username'], $package, "Access Denied!");
  }

?>
<html>
<meta http-equiv="REFRESH" content="0; url=conday.php">
</html>

<?php

  include('settings.php');
  include($Sitepath . '/login/check.php');
  include($Sitepath . '/function.php');

  $package = "convention.mysql.php";

  if (check_userlevel(3)) {
    $formVars['con_id']      = clean($_POST['id'], 10);
    $formVars['con_title']   = clean($_POST['title'], 255);
    $formVars['con_booklet'] = clean($_POST['booklet'], 10);
    $formVars['con_start']   = clean($_POST['start'], 20);
    $formVars['con_end']     = clean($_POST['end'], 20);
    $formVars['con_pstart']  = clean($_POST['pstart'], 20);
    $formVars['con_pend']    = clean($_POST['pend'], 20);
    $formVars['con_limage']  = clean($_POST['limage'], 10);
    $formVars['con_rimage']  = clean($_POST['rimage'], 10);
    $formVars['con_active']  = clean($_POST['active'], 10);
    $formVars['con_status']  = clean($_POST['status'], 10);

    if ($formVars['con_start'] == '') {
      $formVars['con_start'] = 0;
    }
    if ($formVars['con_end'] == '') {
      $formVars['con_end'] = 0;
    }
    if ($formVars['con_pstart'] == '') {
      $formVars['con_pstart'] = 0;
    }
    if ($formVars['con_pend'] == '') {
      $formVars['con_pend'] = 0;
    }

    $formVars['con_start']  = strtotime($formVars['con_start']);
    $formVars['con_end']    = strtotime($formVars['con_end']);
    $formVars['con_pstart'] = strtotime($formVars['con_pstart']);
    $formVars['con_pend']   = strtotime($formVars['con_pend']);

    if ($formVars['con_active'] == true) {
      $formVars['con_active'] = $_SESSION['uid'];
    } else {
      $formVars['con_active'] = 0;
    }

    if ($formVars['con_status'] == true) {
      $formVars['con_status'] = $_SESSION['uid'];
    } else {
      $formVars['con_status'] = 0;
    }

    $q_string = 
      "con_title   = \"" . $formVars['con_title']   . "\"," . 
      "con_booklet =   " . $formVars['con_booklet'] . "," . 
      "con_start   =   " . $formVars['con_start']   . "," . 
      "con_end     =   " . $formVars['con_end']     . "," . 
      "con_pstart  =   " . $formVars['con_pstart']  . "," . 
      "con_pend    =   " . $formVars['con_pend']    . "," . 
      "con_limage  =   " . $formVars['con_limage']  . "," . 
      "con_rimage  =   " . $formVars['con_rimage']  . "," . 
      "con_active  =   " . $formVars['con_active']  . "," . 
      "con_status  =   " . $formVars['con_status'];

    if ($formVars['con_id'] > 0) {
      $query = "update convention set " .$q_string . " where con_id = " . $formVars['con_id'];
      logaccess($_SESSION['username'], $package, "Updating convention " . $formVars['con_id']);
    } else {
      $query = "insert into convention set con_id = NULL," . $q_string;
      logaccess($_SESSION['username'], $package, "Adding convention record");
    }

    $insert = mysql_query($query) or die($query . ": " . mysql_error());
  } else {
    logaccess($_SESSION['username'], $package, "Access Denied!");
  }

?>
<html>
<meta http-equiv="REFRESH" content="0; url=convention.php">
</html>

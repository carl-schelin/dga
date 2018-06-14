<?php

  header('Content-Type: text/javascript');

  include('settings.php');
  include($Sitepath . '/login/check.php');
  include($Sitepath . '/function.php');

  $package = "events.signup.mysql.php";

  logaccess($_SESSION['username'], $package, "Managing user signups.");

  $formVars['id']         = clean($_GET['id'], 10);
  $formVars['update']     = clean($_GET['update'], 10);
  $formVars['sup_event']  = clean($_GET['event'], 10);
  $formVars['sup_user']   = clean($_GET['user'], 10);
  $formVars['sup_status'] = clean($_GET['status'], 10);
  $formVars['sup_paid']   = clean($_GET['paid'], 10);

  if ($formVars['sup_status'] == true) {
    $formVars['sup_status'] = $_SESSION['uid'];
  } else {
    $formVars['sup_status'] = 0;
  }
  if ($formVars['sup_paid'] == true) {
    $formVars['sup_paid'] = 1;
  } else {
    $formVars['sup_paid'] = 0;
  }

  $q_string = "select con_id from convention where con_active > 0";
  $q_convention = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  $a_convention = mysql_fetch_array($q_convention);

# save the passed data.
  if (($formVars['update'] == 0 || $formVars['update'] == 1)) {

    $q_string =
        "sup_conid  =   " . $a_convention['con_id'] . "," .
        "sup_event  =   " . $formVars['sup_event']  . "," .
        "sup_user   =   " . $formVars['sup_user']   . "," .
        "sup_status =   " . $formVars['sup_status'] . "," . 
        "sup_delete =   " . "0"                     . "," .
        "sup_paid   =   " . $formVars['sup_paid'];

    if ($formVars['update'] == 0) {
      $query = "insert into signup set sup_id = NULL, " . $q_string;
    }
    if ($formVars['update'] == 1) {
      $query = "update signup set " . $q_string . " where sup_id = " . $formVars['id'];
    }

    mysql_query($query) or die($query . ": " . mysql_error());
  }


  $output  = "<table>";
  $output .= "<tr>";
  $output .= "<th width=5>Delete</th>";
  $output .= "<th>Member</th>";
  $output .= "<th>Paid</th>";
  $output .= "<th>Status</th>";
  $output .= "</tr>";

  $q_string = "select sup_id,sup_event,sup_user,sup_status,sup_paid from signup where sup_conid = " . $a_convention['con_id'] . " and sup_event = " . $formVars['id'];
  $q_signup = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  while ($a_signup = mysql_fetch_array($q_signup)) {

    if ($a_signup['sup_paid'] == 1) {
      $paid = "Yes";
    } else {
      $paid = "No";
    }

    $linkdel = "<a href=\'#\' onClick=\"javascript:delete_line(\'event.signup.del.php?id=" . $a_signup['sup_id'] . "\');\">";
    $linkstart = "<a href=\'#\' onclick=\"javascript:show_file(\'event.signup.fill.php?id=" . $a_signup['sup_id'] . "\');\">";
    $linkend = "</a>";

    $q_string = "select usr_last,usr_first from users where usr_id = " . $a_signup['sup_user'];
    $q_users = mysql_query($q_string) or die($q_string . ": " . mysql_error());
    $a_users = mysql_fetch_array($q_users);

    $output .= "<tr>";
    $output .=   "<td class=\"delete\">" . $linkdel . "X" . $linkend . "</td>";
    $output .=   "<td>" . $linkstart . $a_users['usr_last'] . ", " . $a_users['usr_first'] . $linkend . "</td>";
    $output .=   "<td>" . $paid . "</td>";

    if ($a_signup['sup_status'] > 0) {
      $q_string = "select usr_last,usr_first from users where usr_id = " . $a_signup['sup_status'];
      $q_users = mysql_query($q_string) or die($q_string . ": " . mysql_error());
      $a_users = mysql_fetch_array($q_users);
      $output .= "<td>Approved by " . $a_users['usr_first'] . " " . $a_users['usr_last'] . "</td>";
    } else {
      $output .= "<td>Pending</td>";
    }

    $output .= "</tr>";
  }

  mysql_free_result($q_signup);

  $output .= "</table>";
?>

document.getElementById('signup_mysql').innerHTML = '<?php print $output; ?>';

document.signup.update.disabled = true;


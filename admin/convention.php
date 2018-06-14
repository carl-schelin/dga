<?php
#
#  Package: convention.php
#  Version: 1.0.0
#  Author: Carl Schelin, Copyright 2013. All rights reserved.
#  Function: Manage the main convention data
#  Description: Manage the convention details

  include('settings.php');
  include($Sitepath . '/login/check.php');
  include($Sitepath . '/function.php');
  check_login('3');

  $package = "convention.php";

  logaccess($_SESSION['username'], $package, "Managing the Convention");

  $q_string = "select con_id,con_title from convention where con_active > 0";
  $q_convention = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  $a_convention = mysql_fetch_array($q_convention);

  $conactive = "<h2>" . $a_convention['con_title'] . "</h2>";

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Conventions</title>
<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

<script type="text/javascript">

function delete_line( p_script_url ) {
  var answer = confirm("By deleting this Convetion, you are also deleting\nevery associated record including events.\n\nDelete this Convention?");

  if (answer) {
    confirm_delete( p_script_url );
  }
}

function confirm_delete( p_script_url ) {
  var answer = confirm("Due to the destructive nature of this deletion,\na second question is asked just to make sure.\n\nAre you sure you want to Delete this Convention?");

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
  }
}

</script>

</head>
<body>

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<table>
<tr>
  <th width=5>Delete?</th>
  <th>Convention Title</th>
  <th>Start Date</th>
  <th>End Date</th>
  <th>Prereg Starts</th>
  <th>Prereg Ends</th>
  <th>Status</th>
  <th>Duplicate</th>
</tr>
<?php

  $q_string = "select con_id,con_title,con_start,con_end,con_pstart,con_pend,con_active,con_status from convention";
  $q_con = mysql_query($q_string) or die($q_string . ": " . mysql_error());

  if (mysql_num_rows($q_con) > 0) {
    while ($a_con = mysql_fetch_array($q_con)) {

      if ($a_con['con_active'] > 0) {
        $class = "active";
        $star = "*";
      } else {
        $class = '';
        $star = '';
      }

      print "<tr>\n";
      if (check_userlevel(2)) {
        if ($a_con['con_active'] > 0) {
          print "  <td class=\"" . $class . " delete\" title=\"You can't delete an active Convention\">--</td>\n";
        } else {
          print "  <td class=\"" . $class . " delete\"><a href=\"#\" onClick=\"delete_line('convention.del.php?id=" . $a_con['con_id'] . "');\">X</a></td>\n";
        }
      } else {
        print "  <td class=\"" . $class . " delete\">--</td>\n";
      }
      print "  <td class=\"" . $class . "\"><a href=\"convention.edit.php?id=" . $a_con['con_id'] . "\">" . $a_con['con_title'] . "</a>" . $star . "</td>\n";
      print "  <td class=\"" . $class . "\">" . date("M d, Y", $a_con['con_start']) . "</td>\n";
      print "  <td class=\"" . $class . "\">" . date("M d, Y", $a_con['con_end']) . "</td>\n";
      print "  <td class=\"" . $class . "\">" . date("M d, Y", $a_con['con_pstart']) . "</td>\n";
      print "  <td class=\"" . $class . "\">" . date("M d, Y", $a_con['con_pend']) . "</td>\n";
      print "  <td class=\"" . $class . "\">";
      if ($a_con['con_status']) {
        $q_string = "select usr_last,usr_first from users where usr_id = " . $a_con['con_status'];
        $q_users = mysql_query($q_string) or die($q_string . ": " . mysql_error());
        $a_users = mysql_fetch_array($q_users);
        print "Approved by " . $a_users['usr_first'] . " " . $a_users['usr_last'] . "</td>\n";
      } else {
        print "Pending";
      }
      print "</td>\n";
      print "<td class=\"" . $class . "\"><a href=\"convention.duplicate.php?id=" . $a_con['con_id'] . "\">Duplicate</a></td>\n";
      print "</tr>\n";
    }
  } else {
    print "<tr>\n";
    print "  <td colspan=8>No Conventions configured.</td>\n";
    print "</tr>\n";
  }

?>
</table>
<p><a href="convention.edit.php?id=0">Click here to create a new Convention.</a></p>
</div>

<div id="main">

<h1>Convention Management</h1>

<p>This is the first step in creating a new Convention. There are three steps in creating the convention.</p>

<ol>
  <li>Enter in the details. This is where you define the name, preregistration details, and the dates of the convention itself.</li>
  <li>Making it active. Once the Convention has been defined, activate it so you can fill out the details for the other conventions.</li>
  <li>Approving the Convention. Once the Convention details have been completed, Approve it so folks can start submitting events they wish to run.</li>
</ol>

<p>Note: Until the Pre-registration date passes, no one is able to sign up for an event, although the upcoming events and details will be visible.</p>

<p>Important: Clicking on the 'X' will delete the convention <strong>and every item and event associated with the Convention</strong>. Unless you're clearing 
out the Convention entirely, you probably will never need to use this.</p>

<p>Note 2: Clicking the 'Duplicate' button next to an event will create a second copy of all items associated with that Convention <strong>except for News, 
Guests, and Events</strong>. Judges will see all their past events and will be able to duplicate it to the current active convention.</p>

<p>Click on a Convention Title to edit the details</p>

</div>

<?php include($Sitepath . '/confooter.php'); ?>

</body>
</html>

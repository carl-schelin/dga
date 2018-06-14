<?php
#
#  Package: conday.php
#  Version: 1.0.0
#  Author: Carl Schelin, Copyright 2013. All rights reserved.
#  Function: Define the hours of each convention day
#  Description: Lists the different days of the convention as defined in the convention portion
#    then set the starting and ending hours.

  include('settings.php');
  include($Sitepath . '/login/check.php');
  include($Sitepath . '/function.php');
  check_login('3');

  $package = "conday.php";

  logaccess($_SESSION['username'], $package, "Managing Convention Days");

  $q_string = "select con_id,con_title from convention where con_active > 0";
  $q_convention = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  $a_convention = mysql_fetch_array($q_convention);

  $conactive = "<h2>" . $a_convention['con_title'] . "</h2>";

  if ($a_convention['con_id'] == '') {
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="REFRESH" content="5; url=index.php">
<title>No Active Convention</title>
<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>
</head>
<body>

<div id="main">

<h2>Error</h2>

</div>

<div id="main">

<p>There is no active convention. One Convention must be identified as active in order to proceed.</p>

<p>You will be redirected in 5 seconds or click <a href="index.php"> here to continue</a>.</p>

</div>

</body>
</html>
<?php
  } else {
# there is an active convention so things can be managed now.
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Convention Days</title>
<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

<script type="text/javascript">

function delete_line( p_script_url ) {
  var answer = confirm("Delete this Day?")

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

<?php print $conactive; ?>

<table>
<tr>
  <th width=5>Delete?</th>
  <th>Date & Start Time</th>
  <th>Date & End Time</th>
  <th>Status</th>
</tr>
<?php

  $q_string = "select day_id,day_start,day_end,day_tstart,day_tend,day_status from condays where day_conid = " . $a_convention['con_id'];
  $q_condays = mysql_query($q_string) or die($q_string . ": " . mysql_error());

  if (mysql_num_rows($q_condays) > 0) {
    while ($a_condays = mysql_fetch_array($q_condays)) {
      print "<tr>\n";
      print "  <td class=\"delete\"><a href=\"#\" onClick=\"delete_line('conday.del.php?id=" . $a_condays['day_id'] . "');\">X</a></td>\n";
      print "  <td><a href=\"conday.edit.php?id=" . $a_condays['day_id'] . "\">" . date("M d, Y (D) h:i:s A", ($a_condays['day_start']) + $a_condays['day_tstart']) . "</a></td>\n";
      print "  <td><a href=\"conday.edit.php?id=" . $a_condays['day_id'] . "\">" . date("M d, Y (D) h:i:s A", ($a_condays['day_end']) + $a_condays['day_tend']) . "</a></td>\n";
      if ($a_condays['day_status']) {                                                                                                   
        $q_string = "select usr_last,usr_first from users where usr_id = " . $a_condays['day_status'];
        $q_users = mysql_query($q_string) or die($q_string . ": " . mysql_error());
        $a_users = mysql_fetch_array($q_users);
        print "  <td>Approved by " . $a_users['usr_first'] . " " . $a_users['usr_last'] . "</td>\n";
      } else {                                                                                                                                                     
        print "  <td>Pending</td>\n";
      }
      print "</tr>\n";
    }
  } else {
    print "<tr>\n";
    print "  <td colspan=4>No Convention Dates have been scheduled.</td>\n";
    print "</tr>\n";
  }

?>
</table>

<p><a href="conday.edit.php?id=0">Click here to schedule a day.</a></p>

</div>

<div id="main">

<h2>Convention Management of Days</h2>

<p>The purpose of the definition of days is to make it easier for folks to manage events and exhibitors to select days that fall within the convention dates. This way 
they only need to select the day (THU, FRI, SAT, or SUN for example) vs selecting from a month calendar. Select each day and the starting and ending times. For example, if you 
have a convention that starts at 5PM on Thursday, you set the starting time for 05:00PM and no events can be scheduled prior to that date and time.</p>

<p>Click on a date to edit the details</p>

</div>

<?php include($Sitepath . '/confooter.php'); ?>

</body>
</html>
<?php
}
?>

<?php
#
#  Package: guest.php
#  Version: 1.0.0
#  Author: Carl Schelin, Copyright 2013. All rights reserved.
#  Function: Manage the Guests of Honor
#  Description: This lists the currently identified guests of honor

  include('settings.php');
  include($Sitepath . '/login/check.php');
  include($Sitepath . '/function.php');
  check_login('3');

  $package = "guest.php";

  logaccess($_SESSION['username'], $package, "Managing Guests of Honor");

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
<title>Manage Guests of Honor</title>
<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

<script type="text/javascript">

function delete_line( p_script_url ) {
  var answer = confirm("Cancel this Guest?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    clear_fields();
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
  <th>Title</th>
  <th>Name</th>
  <th>Location</th>
  <th>Start</th>
  <th>End</th>
  <th>Status</th>
</tr>
<?php

  $q_string = "select gst_id,gst_title,gst_name,gst_location,gst_start,gst_end,gst_status from guests where gst_conid = " . $a_convention['con_id'];
  $q_guests = mysql_query($q_string) or die($q_string . ": " . mysql_error());

  if (mysql_num_rows($q_guests) > 0) {
    while ($a_guests = mysql_fetch_array($q_guests)) {
      print "<tr>\n";
      print "  <td class=\"delete\"><a href=\"#\" onClick=\"delete_line('guest.del.php?id=" . $a_guests['gst_id'] . "');\">X</a></td>\n";
      print "  <td><a href=\"guest.edit.php?id=" . $a_guests['gst_id'] . "\">" . $a_guests['gst_title'] . "</a></td>\n";
      print "  <td>" . $a_guests['gst_name'] . "</td>\n";
      $q_string = "select loc_title,loc_subloc from locations where loc_id = " . $a_guests['gst_location'];
      $q_locations = mysql_query($q_string) or die($q_string . ": " . mysql_error());
      $a_locations = mysql_fetch_array($q_locations);
      print "  <td>" . $a_locations['loc_title'] . "</td>\n";
      print "  <td>" . date('M d, Y', $a_guests['gst_start']) . "</td>\n";
      print "  <td>" . date('M d, Y', $a_guests['gst_end']) . "</td>\n";
      print "  <td>";
      if ($a_guests['gst_status']) {
        $q_string = "select usr_last,usr_first from users where usr_id = " . $a_guests['gst_status'];
        $q_users = mysql_query($q_string) or die($q_string . ": " . mysql_error());
        $a_users = mysql_fetch_array($q_users);
        print "Approved by " . $a_users['usr_first'] . " " . $a_users['usr_last'];
      } else {
        print "Pending";
      }
      print "</td>\n";
      print "</tr>\n";
    }
  } else {
    print "<tr>\n";
    print "  <td colspan=8>No Guests have been scheduled for this convention.</td>\n";
    print "</tr>\n";
  }

?>
</table>

<p><a href="guest.edit.php?id=0">Click here to add a guest.</a></p>

</div>

<div id="main">

<h2>Convention Guest Management</h2>

<p>This task lets the convention manager add guests to the convention. There is a name for create unique categories for the convention. Create a title, add appropriate text, and designate the manager of this category.
This person will be the approver for all events under this category.</p>

<p>Click on a category to edit the details</p>

</div>

<?php include($Sitepath . '/confooter.php'); ?>

</body>
</html>
<?php
}
?>

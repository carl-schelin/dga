<?php
#
#  Package: signup.php
#  Version: 1.0.0
#  Author: Carl Schelin, Copyright 2013. All rights reserved.
#  Function: Manage the members
#  Description: This lists the members and the tickets they've purchased.

  include('settings.php');
  include($Sitepath . '/login/check.php');
  include($Sitepath . '/function.php');
  check_login('3');

  $package = "signup.php";

  logaccess($_SESSION['username'], $package, "Managing Member Tickets");

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
<title>Manage Member Tickets</title>

<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

<style type="text/css">

<?php

  $q_string = "select cat_id,cat_color from categories where cat_status > 0 and cat_conid = " . $a_convention['con_id'];
  $q_categories = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  while ($a_categories = mysql_fetch_array($q_categories)) {
    print ".style" . $a_categories['cat_id'] . " {\n";
    print "  background-color: " . $a_categories['cat_color'] . ";\n";
    print "}\n";
  }

?>

</style>

<script type="text/javascript">

function delete_line( p_script_url ) {
  var answer = confirm("Cancel this Ticket?")

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
  <th>Title</th>
  <th>ID</th>
  <th>Name</th>
  <th>Day</th>
  <th>Time</th>
  <th>Status</th>
</tr>
<?php

  $q_string  = "select sup_id,sup_user,sup_event,sup_status,sup_paid,sup_delete,evt_type,evt_category,evt_subcat,evt_title,";
  $q_string .= "evt_subtitle,evt_start,evt_end from signup left join events on events.evt_id = signup.sup_event where ";
  $q_string .= "sup_conid = " . $a_convention['con_id'] . " order by evt_start,evt_category";
  $q_signup = mysql_query($q_string) or die($q_string . ": " . mysql_error());

  if (mysql_num_rows($q_signup) > 0) {
    while ($a_signup = mysql_fetch_array($q_signup)) {
      print "<tr>\n";
      if ($a_signup['evt_subtitle'] != '') {
        $subtitle = " - <em>" . $a_signup['evt_subtitle'] . "</em>";
      } else {
        $subtitle = '';
      }

#      if ($a_events['evt_start'] > time()) {
        $eventclass = "class=\"style" . $a_signup['evt_type'] . "\"";
#      } else {
#        $eventclass = "class=\"inactive\"";
#      }

      print "  <td " . $eventclass . "><a href=\"event.edit.php?id=" . $a_signup['sup_event'] . "\">" . $a_signup['evt_title'] . $subtitle . "</a></td>\n";
      if ($a_signup['evt_subcat'] == '') {
        $category = $a_signup['evt_category'];
      } else {
        $category = $a_signup['evt_category'] . "." . $a_signup['evt_subcat'];
      }
      print "  <td " . $eventclass . ">" . $category . "</td>\n";
      $q_string = "select usr_last,usr_first from users where usr_id = " . $a_signup['sup_user'];
      $q_users = mysql_query($q_string) or die($q_string . ": " . mysql_error());
      $a_users = mysql_fetch_array($q_users);
      print "  <td " . $eventclass . ">" . $a_users['usr_first'] . " " . $a_users['usr_last'] . "</td>\n";
      print "  <td " . $eventclass . ">" . date('D', $a_signup['evt_start']) . "</td>\n";
      print "  <td " . $eventclass . ">" . date('h:i:s A', $a_signup['evt_start']) . "-" . date('h:i:s A', $a_signup['evt_end']) . "</td>\n";
      print "  <td " . $eventclass . ">";
      if ($a_signup['sup_status']) {
        $q_string = "select usr_last,usr_first from users where usr_id = " . $a_signup['sup_status'];
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
    print "  <td colspan=8>No one has signed up for any events.</td>\n";
    print "</tr>\n";
  }

?>
</table>

</div>

<div id="main">

<h2>Convention Event Signup Management</h2>

<p>This screen provides an overall view of the events and who has purchased tickets. Clicking on the event will take you to the event edit screen which also contains the 
section on managing signups.</p>

<p>Click on a title to edit the details of the event and signups</p>

</div>

<?php include($Sitepath . '/confooter.php'); ?>

</body>
</html>
<?php
}
?>

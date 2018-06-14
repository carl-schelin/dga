<?php
session_start();
#
#  Package: changes.php
#  Version: 1.0.0
#  Author: Carl Schelin, Copyright 2013. All rights reserved.
#  Function: Show events that have changed since the user last logged in
#  Description: This page gets the last login date from the user and 
#    checks the last change date of all events and displays it for them.
  include('settings.php');

  $package = "changes.php";

# if the user isn't logged in, we'll want to open the database and retrieve the information
# but not let the user act on it. Just see the events and view the details.
# perhaps 'signup' logs the user in as a link.
  if (isset($_SESSION['username'])) {
    include($Sitepath . '/login/check.php');
    include($Sitepath . '/function.php');
    check_login(4);

    logaccess($_SESSION['username'], $package, "View schedule changes");

  } else {
    include($Sitepath . '/minifunc.php');

# once a database connection is made, all the other data pulls work as expected
# make sure you don't write any changes until someone logs in.
    $db = mysql_connect($DBserver,$DBuser,$DBpassword);
    if (!$db) {
      die('Couldn\'t connect: ' . mysql_error());
    } else {
      $DBlogout = mysql_select_db($DBname,$db);
      if (!$DBlogout) {
        die('Not connected : ' . mysql_error());
      }
    }

  }

  $q_string = "select con_id,con_title from convention where con_active > 0";
  $q_convention = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  $a_convention = mysql_fetch_array($q_convention);

  $conactive = "<h2>" . $a_convention['con_title'] . "</h2>";

  if ($a_convention['con_id'] == '') {
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="REFRESH" content="5; url=index.php">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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
<title>Changes in the Schedule</title>

<script type="text/javascript" language="javascript">

</script>

<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

</head>
<body>

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<?php
  $urlview = '';
  $formVars['view'] = '';
  $formVars['sort'] = '';

  print "<h1>Event Cancellations</h1>\n";

  print "<table>\n";
  print "<tr>\n";
  print "  <th><a href=\"" . $package . "?sort=evt_title" . $urlview . "\">Title</a></th>\n";
  print "  <th><a href=\"" . $package . "?sort=usr_last,usr_first" . $urlview . "\">Host</a></th>\n";
  print "  <th><a href=\"" . $package . "?sort=evt_category,evt_subcat" . $urlview . "\">Event ID</a></th>\n";
  print "  <th><a href=\"" . $package . "?sort=evt_start" . $urlview . "\">Day</a></th>\n";
  print "  <th><a href=\"" . $package . "?sort=evt_start" . $urlview . "\">Start</a></th>\n";
  print "</tr>\n";

  $q_string  = "select evt_id,evt_type,evt_category,evt_limit,evt_subcat,evt_title,evt_subtitle,evt_text,evt_start,";
  $q_string .= "evt_end,evt_host,evt_status,usr_last,usr_first from events left join users on users.usr_id = events.evt_host ";
  $q_string .= "where " . $formVars['view'] . " evt_status = -1 and evt_conid = " . $a_convention['con_id'] . $formVars['sort'];

  $q_events = mysql_query($q_string) or die($q_string . ": " . mysql_error());

  if (mysql_num_rows($q_events) > 0) {
    while ($a_events = mysql_fetch_array($q_events)) {
      $q_string = "select cat_id,cat_color from categories where cat_id = " . $a_events['evt_type'];                                   
      $q_categories = mysql_query($q_string) or die($q_string . ": " . mysql_error());
      $a_categories = mysql_fetch_array($q_categories);                               
                                                       
      if ($a_events['evt_subtitle'] == '') {                 
        $event = $a_events['evt_title'];
      } else {
        $event = $a_events['evt_title'] . " - <em>" . $a_events['evt_subtitle'] . "</em>";
      }

      print "<tr>\n";
      print "  <td id=\"title_" . $a_events['evt_id'] . "\" class=\"style" . $a_categories['cat_id'] . "\" title=\"" . $a_events['evt_text'] . "\"><a href=\"event.view.php?id=" . $a_events['evt_id'] . "\">" . $event . "</a></td>\n";
      print "  <td id=\"host_" . $a_events['evt_id'] . "\" class=\"style" . $a_categories['cat_id'] . "\">" . $a_events['usr_first'] . " " . $a_events['usr_last'] . "</td>\n";
      if ($a_events['evt_category'] == '') {                                                                                                                                   
        print "  <td id=\"category_" . $a_events['evt_id'] . "\" class=\"style" . $a_categories['cat_id'] . "\"></td>\n";
      } else {                                                                                                           
        print "  <td id=\"category_" . $a_events['evt_id'] . "\" class=\"style" . $a_categories['cat_id'] . "\">" . $a_events['evt_category'] . "." . $a_events['evt_subcat'] . "</td>\n";
      }                                                                                                                                                                                   
      print "  <td id=\"day_" . $a_events['evt_id'] . "\" class=\"style" . $a_categories['cat_id'] . "\">" . date('D', $a_events['evt_start']) . "</td>\n";
      print "  <td id=\"start_" . $a_events['evt_id'] . "\" class=\"style" . $a_categories['cat_id'] . "\">" . date('h:i:s A', $a_events['evt_start']) . "-" . date('h:i:s A', $a_events['evt_end']) . "</td>\n";
      print "</tr>\n";
    }
  } else {
    print "<tr>\n";
    print "<td colspan=5>No events have been canceled</td>\n";
    print "</tr>\n";
  }
  print "</table>\n";

  print "<h1>Schedule Changes</h1>\n";

  print "<table>\n";
  print "<tr>\n";
  print "  <th><a href=\"" . $package . "?sort=evt_title" . $urlview . "\">Title</a></th>\n";
  print "  <th><a href=\"" . $package . "?sort=usr_last,usr_first" . $urlview . "\">Host</a></th>\n";
  print "  <th><a href=\"" . $package . "?sort=evt_category,evt_subcat" . $urlview . "\">Event ID</a></th>\n";
  print "  <th><a href=\"" . $package . "?sort=evt_start" . $urlview . "\">Day</a></th>\n";
  print "  <th><a href=\"" . $package . "?sort=evt_start" . $urlview . "\">Start</a></th>\n";
  print "</tr>\n";
  print "<tr>\n";
  print "<td colspan=5>No events have been changed</td>\n";
  print "</tr>\n";
  print "</table>\n";

  print "<h1>Schedule Additions</h1>\n";

  print "<table>\n";
  print "<tr>\n";
  print "  <th><a href=\"" . $package . "?sort=evt_title" . $urlview . "\">Title</a></th>\n";
  print "  <th><a href=\"" . $package . "?sort=usr_last,usr_first" . $urlview . "\">Host</a></th>\n";
  print "  <th><a href=\"" . $package . "?sort=evt_category,evt_subcat" . $urlview . "\">Event ID</a></th>\n";
  print "  <th><a href=\"" . $package . "?sort=evt_start" . $urlview . "\">Day</a></th>\n";
  print "  <th><a href=\"" . $package . "?sort=evt_start" . $urlview . "\">Start</a></th>\n";
  print "</tr>\n";
  print "<tr>\n";
  print "<td colspan=5>No events have been added since you last logged in.</td>\n";
  print "</tr>\n";
  print "</table>\n";

?>
</div>

<?php include($Sitepath . '/confooter.php'); ?>

</body>
</html>
<?php
  }
?>

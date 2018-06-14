<?php
#
#  Package: event.php
#  Version: 1.0.0
#  Author: Carl Schelin, Copyright 2013. All rights reserved.
#  Function: Manage the events used by the convention
#  Description: This lists and lets you edit the various convention events.

  include('settings.php');
  include($Sitepath . '/login/check.php');
  include($Sitepath . '/function.php');
  check_login('3');

  $package = "event.php";

  logaccess($_SESSION['username'], $package, "Managing Events");

  if (isset($_GET['sort'])) {
    $formVars['sort'] = " order by " . clean($_GET['sort'], 50);
  } else {
    $formVars['sort'] = " order by evt_category desc,evt_subcat desc";
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
<title>Manage Convention Events</title>

<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

<style type="text/css">

<?php

  $q_string = "select cat_id,cat_color from categories";
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
  var answer = confirm("Delete this Event?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    window.location.href = p_script_url;
  }
}

</script>

</head>
<body>

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<?php print $conactive; ?>

<?php

  if (check_userlevel(2)) {
    $leftjoin = '';
    $checkuser = '';
  } else {
    $leftjoin = "left join staff on categories.cat_group = staff.sta_title ";
    $checkuser = "sta_user = " . $_SESSION['uid'] . " and ";
  }

  $q_string  = "select cat_id,cat_title,cat_color from categories " . $leftjoin . "where " . $checkuser . " cat_status > 0 and cat_conid = " . $a_convention['con_id'] . " order by cat_base";
  $q_categories = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  while ($a_categories = mysql_fetch_array($q_categories)) {

    print "<table>\n";
    print "<tr>\n";
    print "  <th colspan=8>" . $a_categories['cat_title'] . "</th>\n";
    print "</tr>\n";
    print "<tr>\n";
    print "  <th width=5>Delete?</th>\n";
    print "  <th><a href=\"" . $package . "?sort=evt_title\">Title</a></th>\n";
    print "  <th><a href=\"" . $package . "?sort=usr_last,usr_first\">Host</a></th>\n";
    print "  <th><a href=\"" . $package . "?sort=evt_category,evt_subcat\">Event ID</a></th>\n";
    print "  <th><a href=\"" . $package . "?sort=evt_start\">Day</a></th>\n";
    print "  <th><a href=\"" . $package . "?sort=evt_start,evt_end\">Start</a></th>\n";
    print "  <th><a href=\"" . $package . "?sort=evt_status\">Status</a></th>\n";
    print "</tr>\n";

    $q_string  = "select evt_id,evt_category,evt_subcat,evt_title,evt_host,evt_start,evt_end,evt_complete,";
    $q_string .= "evt_status,usr_last,usr_first from events left join users on users.usr_id = events.evt_host ";
    $q_string .= "where evt_type = " . $a_categories['cat_id'] . " and evt_conid = " . $a_convention['con_id'] . $formVars['sort'];
    $q_events = mysql_query($q_string) or die($q_string . ": " . mysql_error());

    if (mysql_num_rows($q_events) > 0) {
      while ($a_events = mysql_fetch_array($q_events)) {

#        if ($a_events['evt_start'] > time()) {
          $eventclass = "class=\"style" . $a_categories['cat_id'];
#        } else {
#          $eventclass = "class=\"inactive";
#        }

        print "<tr>\n";
        print "  <td " . $eventclass . " delete\"><a href=\"#\" onClick=\"delete_line('event.del.php?id=" . $a_events['evt_id'] . "');\">X</a></td>\n";
        print "  <td " . $eventclass . "\"><a href=\"event.edit.php?id=" . $a_events['evt_id'] . "\">" . $a_events['evt_title'] . "</a></td>\n";
        print "  <td " . $eventclass . "\"><a href=\"/judge.php?id=" . $a_events['evt_host'] . "\">" . $a_events['usr_first'] . " " . $a_events['usr_last'] . "</a></td>\n";
        print "  <td " . $eventclass . "\">" . $a_events['evt_category'] . "." . $a_events['evt_subcat'] . "</td>\n";
        print "  <td " . $eventclass . "\">" . date('D', $a_events['evt_start']) . "</td>\n";
        print "  <td " . $eventclass . "\">" . date('h:i:s A', $a_events['evt_start']) . "-" . date('h:i:s A', $a_events['evt_end']) . "</td>\n";
        if ($a_events['evt_status']) {
          $q_string = "select usr_last,usr_first from users where usr_id = " . $a_events['evt_status'];
          $q_users = mysql_query($q_string) or die($q_string . ": " . mysql_error());
          $a_users = mysql_fetch_array($q_users);
          print "  <td " . $eventclass . "\">Approved by " . $a_users['usr_first'] . " " . $a_users['usr_last'] . "</td>\n";
        } else {
          if ($a_events['evt_complete'] > 0) {
            print "  <td " . $eventclass . "\" title=\"The Judge has completed the description.\">Ready</td>\n";
          } else {
            print "  <td " . $eventclass . "\" title=\"Waiting on the Judge to complete the event.\">Pending</td>\n";
          }
        }
        print "</tr>\n";
      }
    } else {
      print "<tr>\n";
      print "  <td colspan=7>No Events scheduled for this category.</td>\n";
      print "</tr>\n";
    }
    print "</table>\n";

  }

?>

<p><a href="event.edit.php?id=0">Click here to create a new event.</a></p>

</div>

<div id="main">

<h2>Convention Category Management</h2>

<p>This task lets the convention management create unique categories for the convention. Create a title, add appropriate text, and designate the manager of this category.
This person will be the approver for all events under this category.</p>

<p>Click on a category to edit the details</p>

</div>

<?php include($Sitepath . '/confooter.php'); ?>

</body>
</html>
<?php
}
?>

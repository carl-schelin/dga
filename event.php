<?php
session_start();
#
#  Package: event.php
#  Version: 1.0.0
#  Author: Carl Schelin, Copyright 2013. All rights reserved.
#  Function: Manage the events used by the convention
#  Description: This lists and lets you edit the various convention events.
  include('settings.php');

  $package = "event.php";

# if the user isn't logged in, we'll want to open the database and retrieve the information
# but not let the user act on it. Just see the events and view the details.
# perhaps 'signup' logs the user in as a link.
  if (isset($_SESSION['username'])) {
    include($Sitepath . '/login/check.php');
    include($Sitepath . '/function.php');
    check_login(4);

    logaccess($_SESSION['username'], $package, "Managing Events");

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

  if (isset($_GET['sort'])) {
    $formVars['sort'] = " order by " . clean($_GET['sort'], 50);
    $urlsort = "&sort=" . clean($_GET['sort'], 50);
  } else {
    $formVars['sort'] = " order by evt_start,evt_category,evt_subcat,evt_title";
    $urlsort = '';
  }

  if (isset($_GET['view'])) {
    $formVars['view'] = " evt_type = " . clean($_GET['view'], 10) . " and ";
    $urlview = "&view=" . clean($_GET['view'], 10);
  } else {
    $formVars['view'] = '';
    $urlview = '';
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
</head>
<body>
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
  var answer = confirm("Delete this Event?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
  }
}

function attach_file( p_script_url ) {
  script = document.createElement('script');
  script.src = p_script_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

</script>

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<?php print $conactive; ?>

<?php

# fill an array with start and end events to be used to darken events
# get all the events the user has signed up for
  $count = 0;
  if (isset($_SESSION['username'])) {
    $q_string = "select sup_event from signup where sup_user = " . $_SESSION['uid'] . " and sup_conid = " . $a_convention['con_id'] . " and sup_delete = 0";
    $q_signup = mysql_query($q_string) or die($q_string . ": " . mysql_error());
    while ($a_signup = mysql_fetch_array($q_signup)) {
      $q_string = "select evt_start,evt_end from events where evt_id = " . $a_signup['sup_event'] . " and evt_status > 0"; 
      $q_events = mysql_query($q_string) or die($q_string . ": " . mysql_error());
      $a_events = mysql_fetch_array($q_events);

      $evtid[$count]     = $a_signup['sup_event'];
      $evtsval[$count]   = $a_events['evt_start'];
      $evteval[$count++] = $a_events['evt_end'];
    }

# get all the events the user is running
    $q_string = "select evt_id,evt_start,evt_end from events where evt_conid = " . $a_convention['con_id'] . " and evt_host = " . $_SESSION['uid'] . " and evt_status > 0"; 
    $q_events = mysql_query($q_string) or die($q_string . ": " . mysql_error());
    while ($a_events = mysql_fetch_array($q_events)) {
      $evtid[$count]     = $a_events['evt_id'];
      $evtsval[$count]   = $a_events['evt_start'];
      $evteval[$count++] = $a_events['evt_end'];
    }
  }

# display events
  print "<div class=\"menu\">\n";
  print "<ul>\n";
  print "  <li>Select:</li>\n";
  print "  <li><a href=\"" . $Siteroot . "/event.php\">Show All</a></li>\n";
  $q_string = "select cat_id,cat_menu from categories where cat_status > 0 and cat_conid = " . $a_convention['con_id'] . " order by cat_menu";
  $q_categories = mysql_query($q_string) or die($q_string . ": " . mysql_error());

  while ($a_categories = mysql_fetch_array($q_categories)) {
    print "  <li><a href=\"" . $Siteroot . "/event.php?view=" . $a_categories['cat_id'] . $urlsort . "\">" . $a_categories['cat_menu'] . "</a></li>\n";
  }
  print "</ul>\n";
  print "</div>\n";

  print "<form>\n";
  print "<table>\n";
  print "<tr>\n";
  print "  <th><a href=\"" . $package . "?sort=evt_title" . $urlview . "\">Title</a></th>\n";
  print "  <th><a href=\"" . $package . "?sort=usr_last,usr_first" . $urlview . "\">Host</a></th>\n";
  print "  <th><a href=\"" . $package . "?sort=evt_category,evt_subcat" . $urlview . "\">Event ID</a></th>\n";
  print "  <th><a href=\"" . $package . "?sort=evt_start" . $urlview . "\">Day</a></th>\n";
  print "  <th><a href=\"" . $package . "?sort=evt_start" . $urlview . "\">Start</a></th>\n";
  print "  <th>Sign-Up</th>\n";
  print "</tr>\n";

  $q_string  = "select evt_id,evt_type,evt_category,evt_limit,evt_subcat,evt_title,evt_subtitle,evt_text,evt_start,";
  $q_string .= "evt_end,evt_host,evt_status,cat_id from events left join categories on categories.cat_id = events.evt_type ";
  $q_string .= "where " . $formVars['view'] . " evt_status > 0 and cat_status > 0 and evt_conid = " . $a_convention['con_id'] . $formVars['sort'];

  $q_events = mysql_query($q_string) or die($q_string . ": " . mysql_error());

  if (mysql_num_rows($q_events) > 0) {
    while ($a_events = mysql_fetch_array($q_events)) {

      if ($a_events['evt_subtitle'] == '') {
        $event = $a_events['evt_title'];
      } else {
        $event = $a_events['evt_title'] . " - <em>" . $a_events['evt_subtitle'] . "</em>";
      }

#      if ($a_events['evt_start'] > time()) {
        $eventclass = "class=\"style" . $a_events['cat_id'] . "\"";
#      } else {
#        $eventclass = "class=\"inactive\"";
#      }

# block of code to blank out events where the member has signed up for events.
      for ($i = 0; $i < $count; $i++) {
#if an event start falls between the start and end of my event
        if ($a_events['evt_start'] >= $evtsval[$i] && $a_events['evt_start'] < $evteval[$i] && $a_events['evt_id'] != $evtid[$i]) {
          $eventclass = "class=\"inactive\"";
        }

# and if an event end falls betwen the start and end of my event
        if ($a_events['evt_end'] > $evtsval[$i] && $a_events['evt_end'] <= $evteval[$i] && $a_events['evt_id'] != $evtid[$i]) {
          $eventclass = "class=\"inactive\"";
        }

# finally if my event starts after the event and ends before the event
        if ($evtsval[$i] > $a_events['evt_start'] && $evteval[$i] < $a_events['evt_end'] && $a_events['evt_id'] != $evtid[$i]) {
          $eventclass = "class=\"inactive\"";
        }

      }

      print "<tr>\n";
      print "  <td id=\"title_" . $a_events['evt_id'] . "\" " . $eventclass . " title=\"" . $a_events['evt_text'] . "\"><a href=\"event.view.php?id=" . $a_events['evt_id'] . "\">" . $event . "</a></td>\n";

      $q_string = "select usr_first,usr_last from users where usr_id = " . $a_events['evt_host'];
      $q_users = mysql_query($q_string) or die($q_string . ": " . mysql_error());
      $a_users = mysql_fetch_array($q_users);

      print "  <td id=\"host_" . $a_events['evt_id'] . "\" " . $eventclass . ">" . $a_users['usr_first'] . " " . $a_users['usr_last'] . "</td>\n";
      if ($a_events['evt_category'] == '') {
        print "  <td id=\"category_" . $a_events['evt_id'] . "\" " . $eventclass . "></td>\n";
      } else {
        print "  <td id=\"category_" . $a_events['evt_id'] . "\" " . $eventclass . ">" . $a_events['evt_category'] . "." . $a_events['evt_subcat'] . "</td>\n";
      }
      print "  <td id=\"day_" . $a_events['evt_id'] . "\" " . $eventclass . ">" . date('D', $a_events['evt_start']) . "</td>\n";
      print "  <td id=\"start_" . $a_events['evt_id'] . "\" " . $eventclass . ">" . date('h:i:s A', $a_events['evt_start']) . "-" . date('h:i:s A', $a_events['evt_end']) . "</td>\n";

# only count the ones who have paid for a ticket and haven't turned it in.
      $q_string = "select count(sup_id) from signup where sup_event = " . $a_events['evt_id'] . " and sup_paid = 1 and sup_delete = 0";
      $q_paid = mysql_query($q_string) or die($q_string . ": " . mysql_error());
      $a_paid = mysql_fetch_array($q_paid);

      $q_string = "select count(sup_id) from signup where sup_event = " . $a_events['evt_id'] . " and sup_paid = 0 and sup_delete = 0";
      $q_intent = mysql_query($q_string) or die($q_string . ": " . mysql_error());
      $a_intent = mysql_fetch_array($q_intent);

# only provide link if user logged in
      if (isset($_SESSION['username'])) {
        $linkstart = "<a href=\"#\" onclick=\"attach_file('signup.php?id=" . $a_events['evt_id'] . "');\">";
        $linkend = '</a>';
      } else {
        $linkstart = '';
        $linkend = '';
      }

      print "  <td id=\"signup_" . $a_events['evt_id'] . "\" " . $eventclass . ">";
      if ($a_paid['count(sup_id)'] < $a_events['evt_limit']) {
        print $linkstart . ($a_events['evt_limit'] - $a_paid['count(sup_id)']) . " of " . $a_events['evt_limit'] . " Left! (" . $a_intent['count(sup_id)'] . ")" . $linkend;
      } else {
        if ($a_events['evt_limit'] == 0) {
          print $linkstart . $a_paid['count(sup_id)'] . " of Unlimited! (" . $a_intent['count(sup_id)'] . ")" . $linkend;
        } else {
          print "Full!";
        }
      }
      print "</td>\n";
      print "</tr>\n";
    }
  } else {
    print "<tr>\n";
    print "  <td colspan=5>There currently are no approved Events scheduled for this convention.</td>\n";
    print "</tr>\n";
  }

?>
</table>
</form>

</div>

<div id="main">

<h2>Convention Event Management</h2>

<p>This lists all the active events available to be selected. It lists the Game Title, the Judge's Name, the Event ID, the start day and time and the end time, and a 
description of its availabilty.</p>

<p>Click on an Event to view the details</p>

<p>The Signup column shows the number of total number of people who have paid for a ticket out of the total number of people who can attend. In parenthesis is the number 
of people who have signed up but haven't paid yet. Click on the Signup column to toggle the intention to purchase a ticket. Until you go through the Check out process 
in your "My Orders" page, the ticket is not confirmed.</p>

</div>

<?php include($Sitepath . '/confooter.php'); ?>

</body>
</html>
<?php
}
?>

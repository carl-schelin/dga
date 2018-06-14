<?php 
session_start();
#
#  Package: event.php
#  Version: 1.0.0
#  Author: Carl Schelin, Copyright 2013. All rights reserved.
#  Function: Manage the events used by the convention
#  Description: This lists and lets you edit the various convention events.
  include('settings.php');

  $package = "event.view.php";

# if the user isn't logged in, we'll want to open the database and retrieve the information
# but not let the user act on it. Just see the events and view the details.
# perhaps 'signup' logs the user in as a link.
  if (isset($_SESSION['username'])) {
    include($Sitepath . '/login/check.php');
    include($Sitepath . '/function.php');
    check_login(4);

    logaccess($_SESSION['username'], $package, "View events");

    $disabled = '';
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
    $disabled = 'disabled';

  }

  if (isset($_GET['id'])) {
    $formVars['id'] = clean($_GET['id'], 10);
  } else {
    $formVars['id'] = 0;
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
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>View a Convention Event</title>

<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

<script language="javascript" type="text/javascript">

function textCounter(field,cntfield,maxlimit) {
  if (field.value.length > maxlimit)
    field.value = field.value.substring(0, maxlimit);
  else
    cntfield.value = maxlimit - field.value.length;
}
 
</script>

</head>
<body>

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<form name="eventmgr" action="event.signup.mysql.php" method="post">
<?php

# retrieve the details about the event to be edited.
  if ($formVars['id'] != 0) {
    $q_string  = "select evt_conid,evt_type,evt_category,evt_subcat,evt_title,evt_subtitle,evt_host,evt_text,evt_start,";
    $q_string .= "evt_end,evt_location,evt_limit,evt_complete,evt_status from events where evt_id = " . $formVars['id'];
    $q_events = mysql_query($q_string) or die($q_string . ": " . mysql_error());
    $a_events = mysql_fetch_array($q_events);

    $starttime = date("G:1:s A", $a_events['evt_start']);
    $endtime = date("G:1:s A", $a_events['evt_end']);

    $q_string = "select count(sup_id) from signup where sup_event = " . $formVars['id'] . " and sup_paid = 1 and sup_delete = 0";
    $q_signup = mysql_query($q_string) or die($q_string . ": " . mysql_error());
    $a_signup = mysql_fetch_array($q_signup);

    $paid = $a_signup['count(sup_id)'];

    if ($paid >= $a_events['evt_limit']) {
      $disabled = 'disabled';
    }
    if ($a_convention['con_id'] != $a_events['evt_conid']) {
      $disabled = 'disabled';
    }

    $q_string = "select count(sup_id) from signup where sup_event = " . $formVars['id'] . " and sup_paid = 0 and sup_delete = 0";
    $q_signup = mysql_query($q_string) or die($q_string . ": " . mysql_error());
    $a_signup = mysql_fetch_array($q_signup);

    $intend = $a_signup['count(sup_id)'];
  }

?>
<table>
<tr>
  <td class='button'><input type="hidden" name="id" value="<?php print $formVars['id']; ?>"><input type="button" <?php print $disabled; ?> name="signup" value="Sign-Up"></td>
</tr>
</table>
</form>

<?php
  $q_string = "select cat_id,cat_title from categories where cat_id = " . $a_events['evt_type'];
  $q_categories = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  $a_categories = mysql_fetch_array($q_categories);

  print "<p class=\"events\">Category: " . $a_categories['cat_title'] . "</p>\n";

  print "<p class=\"evtcat\"><strong>" . $a_events['evt_category'] . " " . $a_events['evt_title'] . "</strong></p>\n";

  print "<p class=\"events\"><em>";
  if ($a_events['evt_subtitle'] != '') {
    print $a_events['evt_subtitle'] . ", "; 
  }
  print "Hosted by ";
  $q_string = "select usr_id,usr_last,usr_first from users where usr_id = " . $a_events['evt_host'];
  $q_users = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  $a_users = mysql_fetch_array($q_users);
  print $a_users['usr_first'] . " " . $a_users['usr_last'] . "</em></p>\n";

  $a_events['evt_text'] = str_replace("<p>", "<p class=\"events\">", $a_events['evt_text']);
  print $a_events['evt_text'];

  print "<p class=\"events\">Limit: " . $a_events['evt_limit'] . ". " . $paid . " members have purchased a ticket and " . $intend . " members have indicated an intention to purchase.</p>\n";

  $q_string = "select loc_title,loc_subloc from locations where loc_id = " . $a_events['evt_location'];
  $q_locations = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  $a_locations = mysql_fetch_array($q_locations);
  print "<p class=\"events\">Location: " . $a_locations['loc_title'] . " " . $a_locations['loc_subloc'] . "</p>\n";

  print "<p class=\"events\"><strong>" . $a_events['evt_category'] . "." . $a_events['evt_subcat'] . " ";
  print date('D h:i:sA', $a_events['evt_start']) . "-";
  print date('h:i:sA', $a_events['evt_end']) . "</p>\n";
?>

</div>

<div id="main">

<h2>Event Comments</h2>

<?php

  $q_string = "select rate_user,rate_event,rate_rating,rate_text,rate_timestamp,rate_anonymous from rate_events where rate_event = " . $formVars['id'] . " and rate_status > 0";
  $q_rate_events = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  while ($a_rate_events = mysql_fetch_array($q_rate_events)) {
    $q_string = "select usr_first,usr_last from users where usr_id = " . $a_rate_events['rate_user'];
    $q_users = mysql_query($q_string) or die($q_string . ": " . mysql_error());
    $a_users = mysql_fetch_array($q_users);

    if ($a_rate_events['rate_anonymous']) {
      $user = "Anonymous";
    } else {
      $user = $a_users['usr_first'] . " " . $a_users['usr_last'];
    }

    print "<p>Posted by: " . $user . "</p>\n";

    print "<p>Date posted: " . $a_rate_events['rate_timestamp'] . "</p>\n";
    print "<p>Rating: " . $a_rate_events['rate_rating'] . "</p>\n";
    print "<p>Comment: " . $a_rate_events['rate_text'] . "</p>\n";
    print "<p><input type=\"submit\" name=\"report\" value=\"Report Abuse\"></p>\n";
    print "<hr>\n";
  }

?>

<h2>Rate This Event</h2>

<form name="commentmgr" action="event.comment.mysql.php" method="post">

<input type="hidden" name="event" value="<?php print $formVars['id']; ?>">

<p>Rate this event: 
<input checked type="radio" name="rating" value="0"> No Rating
<input type="radio" name="rating" value="1"> Bad
<input type="radio" name="rating" value="2"> 2
<input type="radio" name="rating" value="3"> Poor
<input type="radio" name="rating" value="4"> 4
<input type="radio" name="rating" value="5"> Okay
<input type="radio" name="rating" value="6"> 6
<input type="radio" name="rating" value="7"> Good
<input type="radio" name="rating" value="8"> 8
<input type="radio" name="rating" value="9"> Excellent!
<input type="radio" name="rating" value="10"> The Best Ever!
</p>

<p>Leave a comment:
<br><textarea name="text" cols=90 rows=10 onKeyDown="textCounter(document.commentmgr.text,document.commentmgr.remLen,65535);" onKeyUp="textCounter(document.commentmgr.text,document.commentmgr.remLen,65535);"></textarea><br><input readonly type="text" name="remLen" size="4" maxlength="4" value="65535"> characters left <input type="checkbox" name="anonymous"> Post comment anonymously?<br>Anonymous comments will need to be approved before it will be available for viewing.<br><strong>Note:</strong> Comment is still associated with your account but only you and the Coordinators will be able to see the source of the comment.</p>


<p><input type="submit" name="update" value="Submit New"></p>

</form>

</div>

<?php include($Sitepath . '/confooter.php'); ?>

</body>
</html>
<?php
  }
?>

<?php
#
#  Package: view.php
#  Version: 1.0.0
#  Author: Carl Schelin, Copyright 2013. All rights reserved.
#  Function: View of all the existing information
#  Description: This is mainly used to view the various text based information for cleanliness
#    and to make sure formatting is working correctly.

  include('settings.php');
  include($Sitepath . '/login/check.php');
  include($Sitepath . '/function.php');
  check_login('3');

  $package = "view.php";

  logaccess($_SESSION['username'], $package, "Viewing the convention information");

  $q_string = "select con_id,con_title from convention where con_active > 0";
  $q_convention = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  $a_convention = mysql_fetch_array($q_convention);

  $conactive = "<h2>" . $a_convention['con_title'] . "</h2>";

  if ($a_convention['con_id'] == '') {
?>
<!DOCTYPE HTML>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="REFRESH" content="5; url=index.php">
<head>
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
<title>Overall View of Convention Details</title>
</head>
<body>
<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

<script type="text/javascript">

</script>

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<?php

  print "<h1>Welcome to " . $a_convention['con_title'] . "</h1>\n";

  print "<p>The Denver Gamers Association would like to welcome you to GenghisCon XXXIV at the Red Lion Hotel, located in Aurora 
at Parker Road and I-225. We are glad to have the volunteers and gamers back with us that make each convention so successful, 
and are happy to ahve new faces join with us in making the Con even better.</p>\n";

  print "<h2>Convention Coordinators</h2>\n";

  print "<p>A special thanks to the following volunteers for all their hard work in putting together this years Genghis Con XXXIV.</p>\n";

  $q_string = "select sta_id,sta_title,sta_user,sta_status from staff where sta_conid = " . $a_convention['con_id'] . " and sta_status > 0";
  $q_staff = mysql_query($q_string) or die($q_string . ": " . mysql_error());

  if (mysql_num_rows($q_staff) > 0) {
    while ($a_staff = mysql_fetch_array($q_staff)) {

      $q_string = "select grp_name from groups where grp_id = " . $a_staff['sta_title'];                                                
      $q_groups = mysql_query($q_string) or die($q_string . ": " . mysql_error());      
      $a_groups = mysql_fetch_array($q_groups);                                   
      print "<p>" . $a_groups['grp_name'];

      $q_string = "select usr_last,usr_first from users where usr_id = " . $a_staff['sta_user'];                      
      $q_users = mysql_query($q_string) or die($q_string . ": " . mysql_error());
      $a_users = mysql_fetch_array($q_users);                                   
      print $a_users['usr_first'] . " " . $a_users['usr_last'] . "</p>\n";
    }
  }

  print "<div class=\"guests\">\n";

  $title = '';
  $q_string  = "select gst_title,gst_name,gst_image,gst_location,gst_start,gst_end,gst_text from guests ";
  $q_string .= "where gst_status > 0 and gst_conid = " . $a_convention['con_id'] . " order by gst_title,gst_order,gst_name";
  $q_guests = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  while ($a_guests = mysql_fetch_array($q_guests)) {
    if ($a_guests['gst_title'] != $title) {
      print "<h1 class=\"guests\">" . $a_guests['gst_title'] . "</h1>\n";
      $title = $a_guests['gst_title'];
    }

    $q_string = "select img_file from images where img_id = " . $a_guests['gst_image'];
    $q_images = mysql_query($q_string) or die($q_string . ": " . mysql_error());
    $a_images = mysql_fetch_array($q_images);  

    if ($a_images['img_file'] != '') {
      print "<img class=\"guests\" src=\"/approved/" . $a_images['img_file'] . "\">\n";
    }
    print "<h2 class=\"guests\">" . $a_guests['gst_name'] . "</h2>\n";

    $a_guests['gst_text'] = str_replace("<p>", "<p class=\"guests\">", $a_guests['gst_text']);
    print $a_guests['gst_text'] . "\n";
  }

  print "</div>\n";

  print "<h2>Convention Rules</h3>\n";

  $q_string = "select rul_id,rul_text from rules where rul_conid = " . $a_convention['con_id'];
  $q_rules = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  $a_rules = mysql_fetch_array($q_rules);

  $a_rules['rul_text'] = str_replace("<p>", "<p class=\"rules\">", $a_rules['rul_text']);
  $a_rules['rul_text'] = str_replace("<li>", "<li class=\"rules\">", $a_rules['rul_text']);
  print $a_rules['rul_text'];


  $dayofweek = '';
  $starttable = "<table>\n";
  $endtable = '';
  $q_string  = "select evt_category,evt_subcat,evt_title,evt_subtitle,evt_start,evt_end,evt_status ";
  $q_string .= "from events where evt_conid = " . $a_convention['con_id'] . " order by evt_start,evt_category";
  $q_events = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  while ($a_events = mysql_fetch_array($q_events)) {

    $currentday = date("l", $a_events['evt_start']);
    if ($dayofweek != $currentday) {
      print $endtable;
      print "<h1>" . $currentday . "</h1>\n";
      print $starttable;
      $endtable = "</table>\n";
      $dayofweek = $currentday;
    }

    if ($a_events['evt_subtitle'] == '') {
      $event = $a_events['evt_title'];
    } else {
      $event = $a_events['evt_title'] . " - " . $a_events['evt_subtitle'];
    }
    print "<tr>\n";
    print "  <td>" . $event . "</td>\n";
    print "  <td>" . $a_events['evt_category'] . "." . $a_events['evt_subcat'] . "</td>\n";
    print "  <td>" . date("h:i:s A", $a_events['evt_start']) . "-" . date("h:i:s A", $a_events['evt_end']) . "</td>\n";
    print "</tr>\n";


  }
  print "</table>\n";

  $q_string = "select cat_id,cat_title,cat_group,cat_color,cat_text,cat_status from categories where cat_status > 0 and cat_conid = " . $a_convention['con_id'];
  $q_categories = mysql_query($q_string) or die($q_string . ": " . mysql_error());

  if (mysql_num_rows($q_categories) > 0) {

    print "<div class=\"events\">";

    while ($a_categories = mysql_fetch_array($q_categories)) {

      print "<h1>" . $a_categories['cat_title'] . "</h1>\n";

      print $a_categories['cat_text'];

      print "<h1>" . $a_categories['cat_title'] . " Events</h1>\n";

      $q_string  = "select evt_id,evt_category,evt_subcat,evt_title,evt_subtitle,evt_host,evt_text,evt_start,";
      $q_string .= "evt_end,evt_limit from events where evt_type = " . $a_categories['cat_id'];
      $q_string .= " order by evt_category,evt_subcat,evt_start";
      $q_events = mysql_query($q_string) or die($q_string . ": " . mysql_error());
      while ($a_events = mysql_fetch_array($q_events)) {

        print "<p class=\"events\"><strong><a href=\"event.edit.php?id=" . $a_events['evt_id'] . "\">" . $a_events['evt_category'] . " " . $a_events['evt_title'] . "</a></strong></p>\n";

        if ($a_events['evt_subtitle'] == '') {
          $subtitle = "";
        } else {
          $subtitle = $a_events['evt_subtitle'] . ", ";
        }

        if ($a_events['evt_host'] == 0) {
          $hosted = "";
        } else {
          $q_string = "select usr_last,usr_first from users where usr_id = " . $a_events['evt_host'];
          $q_users = mysql_query($q_string) or die($q_string . ": " . mysql_error());
          $a_users = mysql_fetch_array($q_users);

          $hosted = "Hosted by " . $a_users['usr_first'] . " " . $a_users['usr_last'];
        }

        print "<p class=\"events\"><em>" . $subtitle . $hosted . "</em></p>\n";

        $a_events['evt_text'] = str_replace("<p>", "<p class=\"events\">", $a_events['evt_text']);
        print $a_events['evt_text'];

        print "<p class=\"events\">Limit: " . $a_events['evt_limit'] . "</p>\n";

        print "<p class=\"events\"><strong>" . $a_events['evt_category'] . "." . $a_events['evt_subcat'] . " " . date("D h:i:sA", $a_events['evt_start']) . " - " . date("h:i:sA", $a_events['evt_end']) . "</strong></p>\n";
      }
    }
    print "</div>\n";
  }

?>

</div>

<?php include($Sitepath . '/confooter.php'); ?>

</body>
</html>
<?php
}
?>

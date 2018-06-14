<?php
session_start();
#
#  Package: guests.php
#  Version: 1.0.0
#  Author: Carl Schelin, Copyright 2013. All rights reserved.
#  Function: Provide access to the photos of past conventions
#  Description: 
  include('settings.php');

  $package = "guests.php";

# if the user isn't logged in, we'll want to open the database and retrieve the information
# but not let the user act on it. Just see the events and view the details.
# perhaps 'signup' logs the user in as a link.
  if (isset($_SESSION['username'])) {
    include($Sitepath . '/login/check.php');
    include($Sitepath . '/function.php');
    check_login(4);

    logaccess($_SESSION['username'], $package, "View guests");

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
<title>Guest Listing</title>
</head>
<body>
<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div class="guests" id="main">

<?php
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
      print "<img class=\"guests\" src=\"" . $Siteroot . "/approved/" . $a_images['img_file'] . "\">\n";
    }
    print "<h2 class=\"guests\">" . $a_guests['gst_name'] . "</h2>\n";

    $a_guests['gst_text'] = str_replace("<p>", "<p class=\"guests\">", $a_guests['gst_text']);
    print $a_guests['gst_text'] . "\n";
  }
?>

</div>

<?php include($Sitepath . '/confooter.php'); ?>

</body>
</html>
<?php
  }
?>

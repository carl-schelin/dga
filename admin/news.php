<?php
#
#  Package: news.php
#  Version: 1.0.0
#  Author: Carl Schelin, Copyright 2013. All rights reserved.
#  Function: Manage the news stories to be displayed on the main page
#  Description: This lets you manage the news stories to be put on 
#    the front page of the site. You can set activation and deactivation 
#    dates and times in order to expire news stories

  include('settings.php');
  include($Sitepath . '/login/check.php');
  include($Sitepath . '/function.php');
  check_login('3');

  $package = "news.php";

  logaccess($_SESSION['username'], $package, "Managing News Stories");

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
<title>Manage Convention News</title>
<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

<script type="text/javascript">

function delete_line( p_script_url ) {
  var answer = confirm("Delete this Story?")

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
  <th>Start</th>
  <th>End</th>
  <th>Status</th>
</tr>
<?php

  $q_string = "select news_id,news_title,news_start,news_end,news_status from news where news_conid = " . $a_convention['con_id'];
  $q_news = mysql_query($q_string) or die($q_string . ": " . mysql_error());

  if (mysql_num_rows($q_news) > 0) {
    while ($a_news = mysql_fetch_array($q_news)) {
      print "<tr>\n";
      print "  <td class=\"delete\"><a href=\"#\" onClick=\"delete_line('news.del.php?id=" . $a_news['news_id'] . "');\">X</a></td>\n";
      print "  <td><a href=\"news.edit.php?id=" . $a_news['news_id'] . "\">" . $a_news['news_title'] . "</a></td>\n";
      print "  <td>" . date("M d, Y", $a_news['news_start']) . "</td>\n";
      print "  <td>" . date("M d, Y", $a_news['news_end']) . "</td>\n";
      if ($a_news['news_status']) {
        $q_string = "select usr_last,usr_first from users where usr_id = " . $a_news['news_status'];
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
    print "  <td colspan=6>No News Articles created.</td>\n";
    print "</tr>\n";
  }

?>
</table>

<p><a href="news.edit.php?id=0">Click here to create News Articles.</a></p>

</div>

<div id="main">

<h2>Convention News Story Management</h2>

<p>This task lets the convention management manage the news stories. This includes setting start and expiration dates for stories that have a limited lifetime.</p>

<p>Click on a news story to edit the details</p>

</div>

<?php include($Sitepath . '/confooter.php'); ?>

</body>
</html>
<?php
  }
?>

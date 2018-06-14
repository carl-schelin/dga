<?php
#
#  Package: mailinglist.php
#  Version: 1.0.0
#  Author: Carl Schelin, Copyright 2013. All rights reserved.
#  Function: Provide mailing list access to users
#  Description: 

  include('settings.php');
  include($Sitepath . '/login/check.php');
  include($Sitepath . '/function.php');
  check_login('4');

  $package = "mailinglist.php";

  logaccess($_SESSION['username'], $package, "Mailing List");

  $q_string = "select con_id,con_title,con_booklet,con_limage,con_rimage from convention where con_active > 0";
  $q_convention = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  $a_convention = mysql_fetch_array($q_convention);

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Announcements Mailing List</title>
</head>
<body>
<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<form>
<table>
<tr>
  <td>
<h2>Mailing List Subscription</h2>

<p>To receive our newsletter, subscribe to our mailing list!</p>

<p>Subscription to announcements@denvergamers.org (Denver Gamers Announcement Email)</p>

<p>Subscribe to this list to receive announcements from the Denver Gamers Association regarding upcoming events and activities!</p>

<p>Email: <input type="text" name="email" size=50></p>

<p>Name: <input type="text" name="name" size=50></p>

<p><input type="submit" name="mailing" value="Subscribe"><input type="submit" name="mailing" value="Unsubscribe"></p>

</td>
</tr>
</table>
</form>

</div>

<?php include($Sitepath . '/confooter.php'); ?>

</body>
</html>

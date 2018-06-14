<?php
#
#  Package: exhibitor.php
#  Version: 1.0.0
#  Author: Carl Schelin, Copyright 2013. All rights reserved.
#  Function: Provide access to Exhibitor's for the Convention
#  Description: 

  include('settings.php');
  include($Sitepath . '/login/check.php');
  include($Sitepath . '/function.php');
  check_login('4');

  $package = "exhibitor.php";

  logaccess($_SESSION['username'], $package, "Exhibitor Assignments");

  $q_string = "select con_id,con_title,con_booklet,con_limage,con_rimage from convention where con_active > 0";
  $q_convention = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  $a_convention = mysql_fetch_array($q_convention);

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Exhibitor Assignments</title>
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
<h2>Exhibitor Page</h2>

<p>Holding Spot</p>

</td>
</tr>
</table>
</form>

</div>

<?php include($Sitepath . '/confooter.php'); ?>

</body>
</html>

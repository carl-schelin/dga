<?php 

  include('settings.php');
  include($Sitepath . '/login/check.php');
  include($Sitepath . '/function.php');
  check_login(3);

  $package = "location.edit.php";

  logaccess($_SESSION['username'], $package, "Add or Edit an event location");

  if (isset($_GET['id'])) {
    $formVars['id'] = clean($_GET['id'], 10);
  } else {
    $formVars['id'] = 0;
  }

  $q_string = "select con_id from convention where con_active > 0";
  $q_convention = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  $a_convention = mysql_fetch_array($q_convention);

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Event Locations</title>

<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

</head>
<body>

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<form name="locationmgr" action="location.mysql.php" method="post">
<table>
<?php

# retrieve the details about the group to be edited.
  if ($formVars['id'] != 0) {
    $q_string = "select loc_title,loc_subloc,loc_limit,loc_status from locations where loc_id = " . $formVars['id'];
    $q_locations = mysql_query($q_string) or die($q_string . ": " . mysql_error());
    $a_locations = mysql_fetch_array($q_locations);

    $submit = "<input type=\"submit\" value=\"Update\">";
  } else {
    $submit = "<input type=\"submit\" value=\"Submit New\">";
  }

?>
<tr>
  <td class="button"><input type="hidden" name="id" value="<?php print $formVars['id']; ?>"><?php print $submit; ?>
</td>
</tr>
</table>

<table>
<tr>
  <td>Room Location: <input type="text" name="title" value="<?php print $a_locations['loc_title']; ?>" size=80></td>
</tr>
<tr>
  <td>Table: <input type="text" name="subloc" value="<?php print $a_locations['loc_subloc']; ?>" size=80></td>
</tr>
<tr>
  <td>Capacity: <input type"text" name="limit" value="<?php print $a_locations['loc_limit']; ?>" size=20></td>
</tr>
<tr>
  <td><input type="checkbox" <?php if ($a_locations['loc_status'] > 0) { print "checked"; } ?> name="status"> Approve?</td>
</tr>
</table>
</form>

</div>

<?php include($Sitepath . '/confooter.php'); ?>

</body>
</html>

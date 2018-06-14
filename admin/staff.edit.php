<?php 

  include('settings.php');
  include($Sitepath . '/login/check.php');
  include($Sitepath . '/function.php');
  check_login(3);

  $package = "staff.edit.php";

  logaccess($_SESSION['username'], $package, "Add or Edit a convention title");

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
<title>Manage Convention Staff</title>

<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

</head>
<body>

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<form name="staffmgr" action="staff.mysql.php" method="post">
<table>
<?php

# retrieve the details about the group to be edited.
  if ($formVars['id'] != 0) {
    $q_string = "select sta_title,sta_user,sta_status from staff where sta_id = " . $formVars['id'];
    $q_staff = mysql_query($q_string) or die($q_string . ": " . mysql_error());
    $a_staff = mysql_fetch_array($q_staff);

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
  <td>Title: <select name="title">
<option value="0">None</option>
<?php
  $q_string = "select grp_id,grp_name from groups where grp_conid = " . $a_convention['con_id'] . " order by grp_name";
  $q_groups = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  while ($a_groups = mysql_fetch_array($q_groups)) {
    if ($a_staff['sta_title'] == $a_groups['grp_id']) {
      print "<option selected value=\"" . $a_groups['grp_id'] . "\">" . $a_groups['grp_name'] . "</option>\n";
    } else {
      print "<option value=\"" . $a_groups['grp_id'] . "\">" . $a_groups['grp_name'] . "</option>\n";
    }
  }
?>
</select></td>
</tr>
<tr>
  <td>Member: <select name="member">
<option value="0">None</option>
<?php
  $q_string = "select usr_id,usr_last,usr_first from users where usr_level < 4  and usr_disabled = 0 order by usr_last,usr_first";
  $q_users = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  while ($a_users = mysql_fetch_array($q_users)) {
    if ($a_staff['sta_user'] == $a_users['usr_id']) {
      print "<option selected value=\"" . $a_users['usr_id'] . "\">" . $a_users['usr_last'] . ", " . $a_users['usr_first'] . "</option>\n";
    } else {
      print "<option value=\"" . $a_users['usr_id'] . "\">" . $a_users['usr_last'] . ", " . $a_users['usr_first'] . "</option>\n";
    }
  }
?>
</select></td>
</tr>
<tr>
  <td><input type="checkbox" <?php if ($a_staff['sta_status'] > 0) { print "checked"; } ?> name="status"> Approve?</td>
</tr>
</table>
</form>

</div>

<?php include($Sitepath . '/confooter.php'); ?>

</body>
</html>

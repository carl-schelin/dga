<?php 

  include('settings.php');
  include($Sitepath . '/login/check.php');
  include($Sitepath . '/function.php');
  check_login(3);

  $package = "group.edit.php";

  logaccess($_SESSION['username'], $package, "Add or Edit a convention title");

  if (isset($_GET['id'])) {
    $formVars['id'] = clean($_GET['id'], 10);
  } else {
    $formVars['id'] = 0;
  }

  $q_string = "select con_id,con_title from convention where con_active > 0";
  $q_convention = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  $a_convention = mysql_fetch_array($q_convention);

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Convention Titles</title>

<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

</head>
<body>

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<form name="groupmgr" action="group.mysql.php" method="post">
<table>
<?php

# retrieve the details about the group to be edited.
  if ($formVars['id'] != 0) {
    $q_string = "select grp_name,grp_status from groups where grp_id = " . $formVars['id'];
    $q_groups = mysql_query($q_string) or die($q_string . ": " . mysql_error());
    $a_groups = mysql_fetch_array($q_groups);

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
  <td colspan=3>Group Name: <input type="text" name="title" value="<?php print $a_groups['grp_name']; ?>" size=30></td>
</tr>
<tr>         
  <td><input type="checkbox" <?php if ($a_groups['grp_status'] > 0) { print "checked"; } ?> name="status"> Approve?</td>
</tr>
</table>
</form>

</div>

<?php include($Sitepath . '/confooter.php'); ?>

</body>
</html>

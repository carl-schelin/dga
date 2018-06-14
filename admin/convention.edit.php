<?php 

  include('settings.php');
  include($Sitepath . '/login/check.php');
  include($Sitepath . '/function.php');
  check_login(3);

  $package = "convention.edit.php";

  logaccess($_SESSION['username'], $package, "Add or Edit a convention listing");

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
<title>Manage Convention</title>

<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

<style type="text/css" title="currentStyle" media="screen">
  @import "../datepickr/datepickr.css";
</style>

</head>
<body>

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<form name="conventionmgr" action="convention.mysql.php" method="post">
<table>
<?php

  if ($formVars['id'] != 0) {
# find out if any other convention is currently active
    $q_string = "select sum(con_active) from convention where con_id != " . $formVars['id'];
    $q_con = mysql_query($q_string) or die($q_string . ": " . mysql_error());
    $a_con = mysql_fetch_array($q_con);

    if ($a_con['sum(con_active)'] > 0) {
      $active = "disabled ";
    } else {
      $active = "";
    }

# retrieve the details about the convention to be edited.
    $q_string  = "select con_title,con_booklet,con_start,con_end,con_pstart,con_pend,con_limage,";
    $q_string .= "con_rimage,con_active,con_status from convention where con_id = " . $formVars['id'];
    $q_con = mysql_query($q_string) or die($q_string . ": " . mysql_error());
    $a_con = mysql_fetch_array($q_con);

    $submit = "<input type=\"submit\" value=\"Update\"><input type=\"submit\" value=\"Submit New\">";
  } else {
    $submit = "<input type=\"submit\" value=\"Submit New\">";
  }

?>
<tr>
  <td class="button"><input type="hidden" name="id" value="<?php print $formVars['id']; ?>"><?php print $submit; ?></td>
</tr>
</table>

<table>
<tr>
  <td colspan=3>Convention Name: <input type="text" name="title" value="<?php print $a_con['con_title']; ?>" size=30></td>
</tr>
<tr>
  <td>Booklet Filename: <select name="booklet">
<option value="0">None</option>
<?php
  $q_string = "select img_id,img_title from images order by img_title";
  $q_images = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  while ($a_images = mysql_fetch_array($q_images)) {
    if ($a_con['con_booklet'] == $a_images['img_id']) {
      print "<option selected value=\"" . $a_images['img_id'] . "\">" . $a_images['img_title'] . "</option>\n";
    } else {
      print "<option value=\"" . $a_images['img_id'] . "\">" . $a_images['img_title'] . "</option>\n";
    }
  }
?>
</select></td>
</tr>
<tr>
  <td>Start Date: <input type="text" id="start" name="start" value="<?php print date('M d, Y', $a_con['con_start']); ?>" size=20></td>
</tr>
<tr>
  <td>End Date: <input type="text" id="end" name="end" value="<?php print date('M d, Y', $a_con['con_end']); ?>" size=20></td>
</tr>
<tr>
  <td>Preregistration Starts: <input type="text" id="pstart" name="pstart" value="<?php print date('M d, Y', $a_con['con_pstart']); ?>" size=20></td>
</tr>
<tr>
  <td>Preregistration Ends: <input type="text" id="pend" name="pend" value="<?php print date('M d, Y', $a_con['con_pend']); ?>" size=20></td>
</tr>
<tr>
  <td>Left Image Filename: <select name="limage">
<option value="0">None</option>
<?php
  $q_string = "select img_id,img_title from images order by img_title";
  $q_images = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  while ($a_images = mysql_fetch_array($q_images)) {
    if ($a_con['con_limage'] == $a_images['img_id']) {
      print "<option selected value=\"" . $a_images['img_id'] . "\">" . $a_images['img_title'] . "</option>\n";
    } else {
      print "<option value=\"" . $a_images['img_id'] . "\">" . $a_images['img_title'] . "</option>\n";
    }
  }
?>
</select></td>
</tr>
<tr>
  <td>Right Image Filename: <select name="rimage">
<option value="0">None</option>
<?php
  $q_string = "select img_id,img_title from images order by img_title";
  $q_images = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  while ($a_images = mysql_fetch_array($q_images)) {
    if ($a_con['con_rimage'] == $a_images['img_id']) {
      print "<option selected value=\"" . $a_images['img_id'] . "\">" . $a_images['img_title'] . "</option>\n";
    } else {
      print "<option value=\"" . $a_images['img_id'] . "\">" . $a_images['img_title'] . "</option>\n";
    }
  }
?>
</select></td>
</tr>
<tr>
  <td><input type="checkbox" <?php print $active; if ($a_con['con_active'] > 0) { print "checked"; } ?> name="active"> Make this Convention Active? Only one Convention can be active at a time. Then all additional editing is associated with the active convention.</td>
</tr>
<tr>
  <td><input type="checkbox" <?php if ($a_con['con_status'] > 0) { print "checked"; } ?> name="status"> Approve? Checking this box opens this Convention up for activity. From purchasing tickets to scheduling events.</td>
</tr>
</table>
</form>

</div>

<script type="text/javascript" src="../datepickr/datepickr.js"></script>

<script type="text/javascript">

  new datepickr('start', {
    'dateFormat': 'M d, Y'
  });

  new datepickr('end', {
    'dateFormat': 'M d, Y'
  });

  new datepickr('pstart', {
    'dateFormat': 'M d, Y'
  });

  new datepickr('pend', {
    'dateFormat': 'M d, Y'
  });

</script>

<?php include($Sitepath . '/confooter.php'); ?>

</body>
</html>

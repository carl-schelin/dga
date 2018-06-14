<?php 

  include('settings.php');
  include($Sitepath . '/login/check.php');
  include($Sitepath . '/function.php');
  check_login(3);

  $package = "conday.edit.php";

  logaccess($_SESSION['username'], $package, "Manage convention days");

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
<title>Manage Convention Events</title>

<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

</head>
<body>

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<form name="condaymgr" action="conday.mysql.php" method="post">
<table>
<?php

# retrieve the details about the event to be edited.
  if ($formVars['id'] != 0) {
    $q_string = "select day_start,day_end,day_tstart,day_tend,day_status from condays where day_id = " . $formVars['id'];
    $q_condays = mysql_query($q_string) or die($q_string . ": " . mysql_error());
    $a_condays = mysql_fetch_array($q_condays);

    $submit = "<input type=\"submit\" name=\"update\" value=\"Update\"><input type=\"submit\" name=\"update\" value=\"Submit New\">";
  } else {
    $submit = "<input type=\"submit\" name=\"update\" value=\"Submit New\">";
  }

?>
<tr>
  <td class="button"><input type="hidden" name="id" value="<?php print $formVars['id']; ?>"><?php print $submit; ?>
</td>
</tr>
</table>

<table>
<tr>
  <td>Start Date: <input type="text" id="startdate" name="startdate" value="<?php print date('M d, Y', $a_condays['day_start']); ?>" size=20 onblur="dupeDate(this.form);"> Start Time: <select name="starttime">
<?php
for ($i = 0; $i < 86401; $i += 1800) {
  if ($a_condays['day_tstart'] == $i) {
    print "<option selected value=\"" . $i . "\">" . date('g:i:s A', $i) . "</option>\n";
  } else {
    print "<option value=\"" . $i . "\">" . date('g:i:s A', $i) . "</option>\n";
  }
}
?>
</select>
</tr>
<tr>
  <td>End Date: <input type="text" id="enddate" name="enddate" value="<?php print date('M d, Y', $a_condays['day_end']); ?>" size=20> End Time: <select name="endtime">
<?php
for ($i = 0; $i < 86401; $i += 1800) {
  if ($a_condays['day_end'] == $i) {
    print "<option selected value=\"" . $i . "\">" . date('g:i:s A', $i) . "</option>\n";
  } else {
    print "<option value=\"" . $i . "\">" . date('g:i:s A', $i) . "</option>\n";
  }
}
?>
</select></td>
</tr>
<tr>
  <td><input type="checkbox" <?php if ($a_condays['day_status'] > 0) { print "checked"; } ?> name="status"> Approve?</td>
</tr>
</table>
</form>

</div>

<script type="text/javascript" src="../datepickr/datepickr.js"></script>

<script type="text/javascript">

  new datepickr('startdate', {
    'dateFormat': 'M d, Y'
  });

  new datepickr('enddate', {
    'dateFormat': 'M d, Y'
  });

function textCounter(field,cntfield,maxlimit) {
  if (field.value.length > maxlimit)
    field.value = field.value.substring(0, maxlimit);
  else
    cntfield.value = maxlimit - field.value.length;
}
 
function dupeDate(f) {
  f.enddate.value = f.startdate.value;
}

</script>

<?php include($Sitepath . '/confooter.php'); ?>

</body>
</html>

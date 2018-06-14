<?php 

  include('settings.php');
  include($Sitepath . '/login/check.php');
  include($Sitepath . '/function.php');
  check_login(3);

  $package = "guest.edit.php";

  logaccess($_SESSION['username'], $package, "Add or Edit a convention guest");

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
<title>Manage Convention Guests</title>

<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

<script language="javascript" type="text/javascript">

function textCounter(field,cntfield,maxlimit) {
  if (field.value.length > maxlimit)
    field.value = field.value.substring(0, maxlimit);
  else
    cntfield.value = maxlimit - field.value.length;
}
 
</script>

</head>
<body>

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<form name="guestmgr" action="guest.mysql.php" method="post">
<table>
<?php

# retrieve the details about the guest to be edited.
  if ($formVars['id'] != 0) {
    $q_string  = "select gst_title,gst_name,gst_order,gst_image,gst_location,gst_start,gst_end,";
    $q_string .= "gst_text,gst_status from guests where gst_id = " . $formVars['id'];
    $q_guests = mysql_query($q_string) or die($q_string . ": " . mysql_error());
    $a_guests = mysql_fetch_array($q_guests);

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
  <td colspan=3>Type of Guest: <input type="text" name="title" value="<?php print $a_guests['gst_title']; ?>" size=60></td>
</tr>
<tr>
  <td colspan=3>Name: <input type="text" name="name" value="<?php print $a_guests['gst_name']; ?>" size=60></td>
</tr>
<tr>
  <td colspan=3>Index: <input type="text" name="order" value="<?php print $a_guests['gst_order']; ?>" size=10> - Set the display order of guests.</td>
</tr>
<tr>
  <td>Location: <select name="location">
<option value="0">None</option>
<?php
  $q_string = "select loc_id,loc_title,loc_subloc from locations where loc_conid = " . $a_convention['con_id'] . " and loc_status > 0 order by loc_title,loc_subloc";
  $q_locations = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  while ($a_locations = mysql_fetch_array($q_locations)) {
    if ($a_guests['gst_location'] == $a_locations['loc_id']) {
      print "<option selected value=\"" . $a_locations['loc_id'] . "\">" . $a_locations['loc_title'] . " (" . $a_locations['loc_subloc'] . ")</option>\n";
    } else {
      print "<option value=\"" . $a_locations['loc_id'] . "\">" . $a_locations['loc_title'] . " (" . $a_locations['loc_subloc'] . ")</option>\n";
    }
  }
?>
</select></td>
</tr>
<tr>
  <td>Start Date: <input type="text" id="start" name="start" value="<?php print date('M d, Y', $a_guests['gst_start']); ?>" size=20></td>
</tr>
<tr>
  <td>End Date: <input type="text" id="end" name="end" value="<?php print date('M d, Y', $a_guests['gst_end']); ?>" size=20></td>
</tr>
<tr>
    <td>Introduction: <textarea name="text" cols=90 rows=10 onKeyDown="textCounter(document.guestmgr.text,document.guestmgr.remLen,65535);" onKeyUp="textCounter(document.guestmgr.text,document.guestmgr.remLen,65535);"><?php print $a_guests['gst_text']; ?></textarea><br><input readonly type="text" name="remLen" size="3" maxlength="3" value="65535"> characters left</td>
</tr>
<tr>
  <td>Picture Filename: <select name="image">
<option value="0">None</option>
<?php
  $q_string = "select img_id,img_title from images order by img_title";
  $q_images = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  while ($a_images = mysql_fetch_array($q_images)) {
    if ($a_guests['gst_image'] == $a_images['img_id']) {
      print "<option selected value=\"" . $a_images['img_id'] . "\">" . $a_images['img_title'] . "</option>\n";
    } else {
      print "<option value=\"" . $a_images['img_id'] . "\">" . $a_images['img_title'] . "</option>\n";
    }
  }
?>
</select></td>
<tr>
  <td><input type="checkbox" <?php if ($a_guests['gst_status'] > 0) { print "checked"; } ?> name="status"> Approve?</td>
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

</script>

<?php include($Sitepath . '/confooter.php'); ?>

</body>
</html>

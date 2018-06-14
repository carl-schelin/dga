<?php 

  include ('settings.php');
  include ($Sitepath . '/login/check.php');
  include ($Sitepath . '/function.php');
  check_login(4);

  $package = "event.edit.php";

  logaccess($_SESSION['username'], $package, "Manage events");

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
<title>Manage Your Convention Events</title>

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

<form name="eventmgr" action="event.mysql.php" method="post">
<table>
<?php

# retrieve the details about the event to be edited.
  if ($formVars['id'] != 0) {
    $q_string  = "select evt_type,evt_category,evt_subcat,evt_title,evt_subtitle,evt_host,evt_text,evt_note,evt_start,";
    $q_string .= "evt_end,evt_location,evt_limit,evt_complete,evt_status from events where evt_id = " . $formVars['id'];
    $q_events = mysql_query($q_string) or die($q_string . ": " . mysql_error());
    $a_events = mysql_fetch_array($q_events);

    $submit = "<input type=\"submit\" name=\"update\" value=\"Update\"><input type=\"submit\" name=\"update\" value=\"Submit New\">";
    $dayselected = " selected";
  } else {
    $a_events['evt_host'] = $_SESSION['uid'];
    $submit = "<input type=\"submit\" name=\"update\" value=\"Submit New\">";
    $dayselected = "";
  }

?>
<tr>
  <td class="button"><input type="hidden" name="id" value="<?php print $formVars['id']; ?>"><?php print $submit; ?>
</td>
</tr>
</table>

<table>
<tr>
  <td>Category: <select name="type">
<option value="0">None</option>
<?php
  $q_string = "select cat_id,cat_title from categories where cat_conid = " . $a_convention['con_id'] . " and cat_status > 0 order by cat_title";
  $q_categories = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  while ($a_categories = mysql_fetch_array($q_categories)) {
    if ($a_events['evt_type'] == $a_categories['cat_id']) {
      print "<option selected value=\"" . $a_categories['cat_id'] . "\">" . $a_categories['cat_title'] . "</option>\n";
    } else {
      print "<option value=\"" . $a_categories['cat_id'] . "\">" . $a_categories['cat_title'] . "</option>\n";
    }
  }
?>
</select></td>
</tr>
<tr>
  <td>Game or Game System: <input type="text" name="title" value="<?php print $a_events['evt_title']; ?>" size=60></td>
</tr>
<tr>
  <td>Game Module or Short Description: <input type="text" name="subtitle" value="<?php print $a_events['evt_subtitle']; ?>" size=60></td>
</tr>
<tr>
  <td>Host: <select name="host">
<option value="0">None</option>
<?php
  $q_string = "select usr_id,usr_last,usr_first from users where usr_disabled = 0 order by usr_last,usr_first";
  $q_users = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  while ($a_users = mysql_fetch_array($q_users)) {
    if ($a_events['evt_host'] == $a_users['usr_id']) {
      print "<option selected value=\"" . $a_users['usr_id'] . "\">" . $a_users['usr_last'] . ", " . $a_users['usr_first'] . "</option>\n";
    } else {
      print "<option value=\"" . $a_users['usr_id'] . "\">" . $a_users['usr_last'] . ", " . $a_users['usr_first'] . "</option>\n";
    }
  }
?>
</select> - Only the Host can identify this event as completed and ready for approval.</td>
</tr>
<tr>
  <td>Number of Players: <input type="text" name="limit" value="<?php print $a_events['evt_limit']; ?>" size=20></td>
</tr>
<tr>
  <td>Requested Start Day: 
<?php
  $selected = " checked";
  $q_string = "select day_id,day_start from condays where day_status > 0 and day_conid = " . $a_convention['con_id'];
  $q_condays = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  while ($a_condays = mysql_fetch_array($q_condays)) {
    if ($dayselected == '') {
      print "<input type=\"radio\"" . $selected . " name=\"startdate\" value=\"" . $a_condays['day_id'] . "\">" . date("D", $a_condays['day_start']) . " ";
      $selected = '';
    } else {
      if (date("D", $a_events['evt_start']) == date("D", $a_condays['day_start'])) {
        print "<input type=\"radio\" checked name=\"startdate\" value=\"" . $a_condays['day_start'] . "\">" . date("D", $a_condays['day_start']) . " ";
      } else {
        print "<input type=\"radio\" name=\"startdate\" value=\"" . $a_condays['day_start'] . "\">" . date("D", $a_condays['day_start']) . " ";
      }
    }
  }
?>
 - Requested Start Time: <select name="starttime">
<?php
for ($i = 0; $i < 86401; $i += 1800) {
  if (date('g:i:s A', $a_events['evt_start']) == date('g:i:s A', $i)) {
    print "<option selected value=\"" . $i . "\">" . date('g:i:s A', $i) . "</option>\n";
  } else {
    print "<option value=\"" . $i . "\">" . date('g:i:s A', $i) . "</option>\n";
  }
}
?>
</select></td>
</tr>
<tr>
  <td>Requested End Day: 
<?php
  $selected = " checked";
  $q_string = "select day_id,day_start from condays where day_status > 0 and day_conid = " . $a_convention['con_id'];
  $q_condays = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  while ($a_condays = mysql_fetch_array($q_condays)) {
    if ($dayselected == '') {
      print "<input type=\"radio\"" . $selected . " name=\"enddate\" value=\"" . $a_condays['day_id'] . "\">" . date("D", $a_condays['day_start']) . " ";
      $selected = '';
    } else {
      if (date("D", $a_events['evt_end']) == date("D", $a_condays['day_start'])) {
        print "<input type=\"radio\" checked name=\"enddate\" value=\"" . $a_condays['day_start'] . "\">" . date("D", $a_condays['day_start']) . " ";
      } else {
        print "<input type=\"radio\" name=\"enddate\" value=\"" . $a_condays['day_start'] . "\">" . date("D", $a_condays['day_start']) . " ";
      }
    }
  }
?>
 - Requested End Time: <select name="endtime">
<?php
for ($i = 0; $i < 86401; $i += 1800) {
  if (date('g:i:s A', $a_events['evt_end']) == date('g:i:s A', $i)) {
    print "<option selected value=\"" . $i . "\">" . date('g:i:s A', $i) . "</option>\n";
  } else {
    print "<option value=\"" . $i . "\">" . date('g:i:s A', $i) . "</option>\n";
  }
}
?>
</select></td>
</tr>
<tr>
    <td>Event Details: <textarea name="text" cols=90 rows=10 onKeyDown="textCounter(document.eventmgr.text,document.eventmgr.remLen,65535);" onKeyUp="textCounter(document.eventmgr.text,document.eventmgr.remLen,65535);"><?php print $a_events['evt_text']; ?></textarea><br><input readonly type="text" name="remLen" size="4" maxlength="4" value="65535"> characters left</td>
</tr>
<tr>
  <td>Note to Coordinator: <input type="text" name="note" value="<?php print $a_events['evt_note']; ?>" size=60></td>
</tr>
<tr>
  <td><input type="checkbox" <?php if ($a_events['evt_complete'] > 0) { print "checked"; } ?> name="complete"> Is this event ready to be approved by the Coordinator?</td>
</tr>
</table>
</form>

</div>

<div id="main">

<h2>Manage The Events You Judge</h2>

<p>Enter your event details here. Select the appropriate type of event so the proper coordinator can assign tables or rooms and approve your event. When you're done editing, 
check off the box at the bottom so the coordinator can approve.</p>

<p><strong>Note:</strong> If you're editing an already approved event, <strong>it will be marked as unapproved so the coordinator can review it</strong>.</p>

<p><strong>Note 2:</strong> Saving an event from a prior convention will save it as an event in the current active convention. So if you view an event you ran at the last 
convention and click the 'Submit New' button, the new event will be associated with the current convention. You <strong>will</strong> need to update the information 
as the dates and times will be incorrect.</p>

</div>

<?php include($Sitepath . '/confooter.php'); ?>

</body>
</html>

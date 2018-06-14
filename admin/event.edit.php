<?php 

  include('settings.php');
  include($Sitepath . '/login/check.php');
  include($Sitepath . '/function.php');
  check_login(3);

  $package = "event.edit.php";

  logaccess($_SESSION['username'], $package, "Manage events");

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

<script language="javascript" type="text/javascript">

function textCounter(field,cntfield,maxlimit) {
  if (field.value.length > maxlimit)
    field.value = field.value.substring(0, maxlimit);
  else
    cntfield.value = maxlimit - field.value.length;
}

function delete_line( p_script_url ) {
  var answer = confirm("Delete this ticket?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    clear_fields();
  }
}

function attach_file( p_script_url ) {
  // create new script element, set its relative URL, and load it
  script = document.createElement('script');
  script.src = p_script_url + "&id=" + document.signup.id.value;
  document.getElementsByTagName('head')[0].appendChild(script);
}
 
function show_file( p_script_url ) {
  // create new script element, set its relative URL, and load it
  script = document.createElement('script');
  script.src = p_script_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}
 
function clear_fields() {
  show_file('event.signup.mysql.php?update=-1&id=<?php print $formVars['id']; ?>');
}

</script>

</head>
<body onLoad="clear_fields();">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<form name="eventmgr" action="event.mysql.php" method="post">
<table>
<?php

# retrieve the details about the event to be edited.
  if ($formVars['id'] != 0) {
    $q_string  = "select evt_type,evt_category,evt_subcat,evt_title,evt_cost,evt_subtitle,evt_host,evt_text,evt_start,";
    $q_string .= "evt_end,evt_location,evt_limit,evt_complete,evt_status from events where evt_id = " . $formVars['id'];
    $q_events = mysql_query($q_string) or die($q_string . ": " . mysql_error());
    $a_events = mysql_fetch_array($q_events);

    $submit = "<input type=\"submit\" name=\"update\" value=\"Update\"><input type=\"submit\" name=\"update\" value=\"Submit New\">";
    $dayselected = " selected";
  } else {
    $submit = "<input type=\"submit\" name=\"update\" value=\"Submit New\">";
    $dayselected = '';
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
  <td>Event Cost: <input type="text" name="cost" value="<?php print $a_events['evt_cost']; ?>" size=10> - Only supply a cost if this is different than the overall category cost.</td>
</tr>
<tr>
  <td>Game Identifier: <input type="text" name="category" value="<?php print $a_events['evt_category']; ?>" size=10> <a href="">Increment</a></td>
</tr>
<tr>
  <td>Game Number: <input type="text" name="subcat" value="<?php print $a_events['evt_subcat']; ?>" size=5> <a href="">Increment</a></td>
</tr>
<tr>
  <td>Location: <select name="location">
<option value="0">None</option>
<?php
  $q_string = "select loc_id,loc_title,loc_subloc from locations where loc_conid = " . $a_convention['con_id'] . " and loc_status > 0 order by loc_title,loc_subloc";
  $q_locations = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  while ($a_locations = mysql_fetch_array($q_locations)) {
    if ($a_events['evt_location'] == $a_locations['loc_id']) {
      print "<option selected value=\"" . $a_locations['loc_id'] . "\">" . $a_locations['loc_title'] . "-" . $a_locations['loc_subloc'] . "</option>\n";
    } else {
      print "<option value=\"" . $a_locations['loc_id'] . "\">" . $a_locations['loc_title'] . "-" . $a_locations['loc_subloc'] . "</option>\n";
    }
  }
?>
</select></td>
</tr>
<tr>
  <td>Title: <input type="text" name="title" value="<?php print $a_events['evt_title']; ?>" size=60></td>
</tr>
<tr>
  <td>One line description: <input type="text" name="subtitle" value="<?php print $a_events['evt_subtitle']; ?>" size=60></td>
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
</select></td>
</tr>
<tr>
  <td>Number of Players: <input type="text" name="limit" value="<?php print $a_events['evt_limit']; ?>" size=20></td>
</tr>
<tr>
  <td>Start Day: 
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
 - Start Time: <select name="starttime">
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
  <td>End Day: 
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
 - End Time: <select name="endtime">
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
  <td><input type="checkbox" <?php if ($a_events['evt_complete'] > 0) { print "checked"; } ?> name="complete"> Completed? Ready to be approved?</td>
</tr>
<tr>
  <td><input type="checkbox" <?php if ($a_events['evt_status'] > 0) { print "checked"; } ?> name="status"> Approve?</td>
</tr>
</table>
</form>

</div>

<div id="main">

<form name="signup">
<table>
<tr>
  <th colspan=3>Event Sign-up Page</th>
</tr>
<tr>
  <td colspan=3 class="button">
<input type="button" disabled="true" name="update" value="Update" onClick="javascript:attach_file('event.signup.mysql.php?update=1&event=<?php print $formVars['id']; ?>&user=' + user.value + '&paid=' + paid.checked + '&status=' + status.checked);">
<input type="hidden" name="id" value="0">
<input type="button" name="signup" value="Submit New" onClick="javascript:attach_file('event.signup.mysql.php?update=0&event=<?php print $formVars['id']; ?>&user=' + user.value + '&paid=' + paid.checked + '&status=' + status.checked);">
</tr>
<tr>
  <td>Member <select name="user">
<option value="0">None</option>
<?php
  $q_string = "select usr_id,usr_last,usr_first from users order by usr_last,usr_first";
  $q_users = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  while ($a_users = mysql_fetch_array($q_users)) {
    print "<option value=\"" . $a_users['usr_id'] . "\">" . $a_users['usr_last'] . ", " . $a_users['usr_first'] . "</option>\n";
  }
?>
</select></td>
  <td>Paid? <input type="checkbox" name="paid"></td>
  <td>Approve? <input type="checkbox" name="status"></td>
</tr>
</table>
</form>

<span id="signup_mysql"></span>

</div>

<?php include($Sitepath . '/confooter.php'); ?>

</body>
</html>

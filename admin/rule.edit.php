<?php 

  include('settings.php');
  include($Sitepath . '/login/check.php');
  include($Sitepath . '/function.php');
  check_login(3);

  $package = "rule.edit.php";

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
<title>Manage Convention Rules</title>

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

<form name="rulemgr" action="rule.mysql.php" method="post">
<table>
<?php

# retrieve the details about the guest to be edited.
  if ($formVars['id'] != 0) {
    $q_string = "select rul_text,rul_status from rules where rul_id = " . $formVars['id'];
    $q_rules = mysql_query($q_string) or die($q_string . ": " . mysql_error());
    $a_rules = mysql_fetch_array($q_rules);

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
    <td>Rules: <textarea name="text" cols=90 rows=30 onKeyDown="textCounter(document.rulemgr.text,document.rulemgr.remLen,65535);" onKeyUp="textCounter(document.rulemgr.text,document.rulemgr.remLen,65535);"><?php print $a_rules['rul_text']; ?></textarea><br><input readonly type="text" name="remLen" size="3" maxlength="3" value="65535"> characters left</td>
</tr>
<tr>
  <td><input type="checkbox" <?php if ($a_rules['rul_status'] > 0) { print "checked"; } ?> name="status"> Approve?</td>
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

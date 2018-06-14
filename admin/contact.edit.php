<?php 

  include('settings.php');
  include($Sitepath . '/login/check.php');
  include($Sitepath . '/function.php');
  check_login(3);

  $package = "contact.edit.php";

  logaccess($_SESSION['username'], $package, "Add or Edit a convention contact");

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

<form name="contactemgr" action="contact.mysql.php" method="post">
<table>
<?php

# retrieve the details about the guest to be edited.
  if ($formVars['id'] != 0) {
    $q_string = "select con_text,con_status from contacts where con_id = " . $formVars['id'];
    $q_contacts = mysql_query($q_string) or die($q_string . ": " . mysql_error());
    $a_contacts = mysql_fetch_array($q_contacts);

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
    <td>Contacts: <textarea name="text" cols=90 rows=30 onKeyDown="textCounter(document.rulemgr.text,document.rulemgr.remLen,65535);" onKeyUp="textCounter(document.rulemgr.text,document.rulemgr.remLen,65535);"><?php print $a_contacts['con_text']; ?></textarea><br><input readonly type="text" name="remLen" size="3" maxlength="3" value="65535"> characters left</td>
</tr>
<tr>
  <td><input type="checkbox" <?php if ($a_contacts['con_status'] > 0) { print "checked"; } ?> name="status"> Approve?</td>
</tr>
</table>
</form>

</div>

<?php include($Sitepath . '/confooter.php'); ?>

</body>
</html>

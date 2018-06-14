<?php 

  include('settings.php');
  include($Sitepath . '/login/check.php');
  include($Sitepath . '/function.php');
  check_login(3);

  $package = "guest.edit.php";

  logaccess($_SESSION['username'], $package, "Add or Edit a News Item");

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
<title>Manage Convention News</title>

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

<form name="newsmgr" action="news.mysql.php" method="post">
<table>
<?php

# retrieve the details about the guest to be edited.
  if ($formVars['id'] != 0) {
    $q_string = "select news_title,news_start,news_end,news_text,news_status from news where news_id = " . $formVars['id'];
    $q_news = mysql_query($q_string) or die($q_string . ": " . mysql_error());
    $a_news = mysql_fetch_array($q_news);

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
  <td>News Header: <input type="text" name="title" value="<?php print $a_news['news_title']; ?>" size=60></td>
</tr>
<tr>
  <td>Article Active: <input type="text" id="start" name="start" value="<?php print date('M d, Y', $a_news['news_start']); ?>" size=20></td>
</tr>
<tr>
  <td>Article Expires: <input type="text" id="end" name="end" value="<?php print date('M d, Y', $a_news['news_end']); ?>" size=20></td>
</tr>
<tr>
    <td>Article Text: <textarea name="text" cols=90 rows=10 onKeyDown="textCounter(document.newsmgr.text,document.newsmgr.remLen,65535);" onKeyUp="textCounter(document.newsmgr.text,document.newsmgr.remLen,65535);"><?php print $a_news['news_text']; ?></textarea><br><input readonly type="text" name="remLen" size="3" maxlength="3" value="65535"> characters left</td>
</tr>
<tr>
  <td><input type="checkbox" <?php if ($a_news['news_status'] > 0) { print "checked"; } ?> name="status"> Approve?</td>
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

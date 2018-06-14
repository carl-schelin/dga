<?php 

  include('settings.php');
  include($Sitepath . '/login/check.php');
  include($Sitepath . '/function.php');
  check_login(3);

  $package = "category.edit.php";

  logaccess($_SESSION['username'], $package, "Add or Edit a convention category");

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
<title>Manage Convention Categories</title>

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

<form name="categorymgr" action="category.mysql.php" method="post">
<table>
<?php

# retrieve the details about the categories to be edited.
  if ($formVars['id'] != 0) {
    $q_string = "select cat_title,cat_menu,cat_base,cat_cost,cat_group,cat_color,cat_email,cat_summary,cat_text,cat_status from categories where cat_id = " . $formVars['id'];
    $q_categories = mysql_query($q_string) or die($q_string . ": " . mysql_error());
    $a_categories = mysql_fetch_array($q_categories);

    $submit = "<input type=\"submit\" value=\"Update\">";
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
  <td>Category Name: <input type="text" name="title" value="<?php print $a_categories['cat_title']; ?>" size=30></td>
</tr>
<tr>
  <td>Short Menu Name: <input type="text" name="menu" value="<?php print $a_categories['cat_menu']; ?>" size=20></td>
</tr>
<tr>
  <td>Base Event ID: <input type="text" name="base" value="<?php print $a_categories['cat_base']; ?>" size=20></td>
</tr>
<tr>
  <td>Cost: <input type="text" name="cost" value="<?php print $a_categories['cat_cost']; ?>" size=20> - The cost of all tickets under this category. Unique costs can be assigned individually.</td>
</tr>
<tr>
  <td>Coordinator Title: <select name="group">
  <option value="0">None</option>
<?php
  $q_string = "select grp_id,grp_name from groups where grp_conid = " . $a_convention['con_id'] . " order by grp_name";
  $q_groups = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  while ($a_groups = mysql_fetch_array($q_groups)) {
    if ($a_categories['cat_group'] == $a_groups['grp_id']) {
      print "<option selected value=\"" . $a_groups['grp_id'] . "\">" . $a_groups['grp_name'] . "</option>\n";
    } else {
      print "<option value=\"" . $a_groups['grp_id'] . "\">" . $a_groups['grp_name'] . "</option>\n";
    }
  }
?>
</select></td>
</tr>
<tr>
  <td>Identifying Color: <input type="text" name="color" value="<?php print $a_categories['cat_color']; ?>" size=30></td>
</tr>
<tr>
  <td>Description: <textarea name="text" cols=90 rows=10 onKeyDown="textCounter(document.categorymgr.text,document.categorymgr.remLen,65535);" onKeyUp="textCounter(document.categorymgr.text,document.categorymgr.remLen,65535);"><?php print $a_categories['cat_text']; ?></textarea><br><input readonly type="text" name="remLen" size="3" maxlength="3" value="65535"> characters left</td>
</tr>
<tr>
  <td>Contact E-Mail: <input type="text" name="email" value="<?php print $a_categories['cat_email']; ?>" size=30></td>
</tr>
<tr>
  <td>Contact Text: <textarea name="summary" cols=90 rows=2 onKeyDown="textCounter(document.categorymgr.summary,document.categorymgr.remLen2,512);" onKeyUp="textCounter(document.categorymgr.summary,document.categorymgr.remLen2,512);"><?php print $a_categories['cat_summary']; ?></textarea><br><input readonly type="text" name="remLen2" size="3" maxlength="3" value="65535"> characters left</td>
</tr>
<tr>
  <td><input type="checkbox" <?php if ($a_categories['cat_status'] > 0) { print "checked"; } ?> name="status"> Approve? Checking this box lets Coordinators and Judges manage events for this category.</td>
</tr>
</table>
</form>

</div>

<span id="preview_mysql"></span>

<?php include($Sitepath . '/confooter.php'); ?>

</body>
</html>

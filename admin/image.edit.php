<?php 

  include('settings.php');
  include($Sitepath . '/login/check.php');
  include($Sitepath . '/function.php');
  check_login(3);

  $package = "image.edit.php";

  logaccess($_SESSION['username'], $package, "Add or Edit an image");

  if (isset($_GET['id'])) {
    $formVars['id'] = clean($_GET['id'], 10);
  } else {
    $formVars['id'] = 0;
  }

  if (isset($_GET['filename'])) {
    $formVars['filename'] = clean($_GET['filename'], 255);
  } else {
    $formVars['filename'] = '';
  }

  $q_string = "select con_id from convention where con_active > 0";
  $q_convention = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  $a_convention = mysql_fetch_array($q_convention);

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Site Images</title>

<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

</head>
<body>

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<form name="imagemgr" action="image.mysql.php" method="POST">
<table>
<?php

# retrieve the details about the guest to be edited.
  if ($formVars['id'] != 0) {
    $q_string  = "select img_title,img_file,img_status from images where img_id = " . $formVars['id'];
    $q_images = mysql_query($q_string) or die($q_string . ": " . mysql_error());
    $a_images = mysql_fetch_array($q_images);

    $submit = "<input type=\"submit\" value=\"Update\">";
  } else {
    $a_images['img_file'] = $formVars['filename'];
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
  <td>Title: <input type="text" name="title" value="<?php print $a_images['img_title']; ?>" size=60></td>
</tr>
<tr>
  <td>Filename: <input type="text" name="file" value="<?php print $a_images['img_file']; ?>" size=60></td>
</tr>
<tr>
  <td><input type="checkbox" <?php if ($a_images['img_status'] > 0) { print "checked"; } ?> name="status"> Approve?</td>
</tr>
</table>
</form>

<form imgmgr enctype="multipart/form-data" action="image.upload.php" method="POST">
<table>
<tr>
  <th>File Manager</th>
</tr>
<tr>
  <td>
Choose a file to upload (10M Max, .jpg/.gif/.png/.pdf accepted):
<input type="hidden" name="MAX_FILE_SIZE" value="10000000">
<input type="file" name="userfile">
<input type="submit" value="Upload File">
</td>
</tr>
</table>
</form>

</div>

<?php include($Sitepath . '/confooter.php'); ?>

</body>
</html>

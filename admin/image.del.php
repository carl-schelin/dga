<?php

  include ('../login/check.php');
  include ('../function.php');

  $package = "image.del.php";

// id of the record being deleted
  $formVars['id']   = clean($_GET['id'], 10);

  if (check_userlevel(3)) {
    $q_string = "select img_file,img_status from images where img_id = " . $formVars['id'];
    $q_images = mysql_query($q_string) or die($q_string . ": " . mysql_error());
    $a_images = mysql_fetch_array($q_images);

    if ($a_images['img_status'] > 0) {
      if (file_exists('/var/www/approved/' . $a_images['img_file'])) {
        unlink('/var/www/approved/' . $a_images['img_file']);
      }
    } else {
      if (file_exists('/var/www/upload/' . $a_images['img_file'])) {
        unlink('/var/www/upload/' . $a_images['img_file']);
      }
    }

    $q_string = "delete from images where img_id = " . $formVars['id'];
    $result = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  } else {
    logaccess($_SESSION['username'], $package, "Access Denied!");
  }

?>

<html>
<meta http-equiv="REFRESH" content="0; url=image.php">
</html>


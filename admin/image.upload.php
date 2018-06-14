<?php

  include('settings.php');
  include($Sitepath . '/login/check.php');
  include($Sitepath . '/function.php');

  $package = "image.upload.php";

  if (check_userlevel(3)) {
    $formVars['id'] = clean($_SESSION['uid'], 10);

    $errorString = "";

    if ($_FILES['userfile']['error'] == 0) {
      $uploaded_type = $_FILES['userfile']['type'];
      $uploaded_size = $_FILES['userfile']['size'];
    }

// This is our size condition, just in case the previous block missed it.
    if ($uploaded_size > 10000000) {
      $errorString .= "\n<br>Error: Filesize uploaded (" . $uploaded_size . ") was too large. You are limited to 10 Megabytes total size.";
    }

    if ($uploaded_type == "image/jpeg") {
      $ext = ".jpg";
    }

    if ($uploaded_type == "image/pjpeg") {
      $ext = ".jpg";
    }

    if ($uploaded_type == "image/gif") {
      $ext = ".gif";
    }

    if ($uploaded_type == "image/png") {
      $ext = ".png";
    }

    if ($uploaded_type == "application/pdf") {
      $ext = ".pdf";
    }

    if ($ext == "") {
      $errorString .= "\n<br>Error: Unsupported file type " . $uploaded_type;
    }

// This is our limit file type condition
    if ($uploaded_type == "text/php") {
      $errorString .= "\n<br>Error: You're funny. Don't try uploading php files again, thanks.";
    }

// Now the script has finished the validation, check if there were any errors
    if (!empty($errorString)) {
// There are errors. Show them and exit.
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Upload Images</title>
</head>
<body bgcolor="white">
<h3>Upload Image Errors</h3>
<?=$errorString?>
<br><a href="image.edit.php">Return to the edit form</a>
<br><a href="index.php">Return to the index</a>
</body>
</html>
<?php
      exit;
    }

// If we made it here, then the data is reasonably valid, move the file

    $target = $Sitepath . "/upload/";
    $target = $target . $_FILES['userfile']['name'];

// If everything is ok we try to upload it
    if( move_uploaded_file($_FILES['userfile']['tmp_name'], $target) ) { 
      header("Location: image.edit.php?filename=" . $_FILES['userfile']['name']);
    } else {
      echo "Sorry, there was a problem uploading your file.";
    }
  }

?>

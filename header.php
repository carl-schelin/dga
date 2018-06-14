<?php
  session_start();
  include('settings.php');
?>
<!DOCTYPE HTML>
<html>
<head>

<title><?php print $Sitename; ?></title>
<META HTTP-EQUIV="Pragma" CONTENT="no-cache"> <!-- Important for security -->
<META HTTP-EQUIV="Expires" CONTENT="-1">

<meta name="robots" content="index,follow">
<link rel="stylesheet" href="/login/stylesheet.css" /> <!-- Main Stylesheet -->

</head>
<body>

<div id="header">
    
<div id="title"><a href="<?php print $Siteroot; ?>/"><h1>Login</h1></a></div>
    
<? 
// If the user is logged in, display the logout link.
  if (isset($_SESSION['username'])) {
    echo "<div id='logout'><a href='" . $Siteroot . "/login/logout.php'>Logout (" . $_SESSION['username'] . ")</a></div>";
  } else {
    echo "<div id='login'><a href='" . $Siteroot . "'>Login</a></div>";
  }
?>

</div>

<div id="main">

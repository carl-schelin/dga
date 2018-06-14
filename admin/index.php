<?php

  include('settings.php');
  include($Sitepath . '/login/check.php');
  include($Sitepath . '/function.php');
  check_login(3);

  $package = "index.php";

  logaccess($_SESSION['username'], $package, "Show the site management menu.");

  $q_string  = "select con_title,con_booklet,con_start,con_end,con_pstart,con_pend,con_limage,";
  $q_string .= "con_rimage from convention where con_active > 0";
  $q_convention = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  $a_convention = mysql_fetch_array($q_convention);

  $q_string = "select img_file from images where img_id = " . $a_convention['con_booklet'];
  $q_booklet = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  $a_booklet = mysql_fetch_array($q_booklet);

  $q_string = "select img_file from images where img_id = " . $a_convention['con_limage'];
  $q_limage = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  $a_limage = mysql_fetch_array($q_limage);

  $q_string = "select img_file from images where img_id = " . $a_convention['con_rimage'];
  $q_rimage = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  $a_rimage = mysql_fetch_array($q_rimage);

  if ($a_convention['con_title'] == '') {
    $conactive = '';
    $a_convention['con_title'] = "No active convention defined.";
  } else {
    $conactive = "<h2>" . $a_convention['con_title'] . "</h2>";
    $a_convention['con_title'] = "Preregistration starts " . date('M d, Y', $a_convention['con_pstart']) . " and ends on " . date('M d, Y', $a_convention['con_pend']) . " and the convention runs from " . date('M d, Y', $a_convention['con_start']) . " through " . date('M d, Y', $a_convention['con_end']) . ".";
  }

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Site Management Tools</title>

<script type="text/javascript" language="javascript">

</script>

<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

</head>
<body>

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">
  <div id="contentwrapper">
    <div id="contentcolumn">
      <div class="innertube">
        <a href="<?php print $Siteroot; ?>/approved/<?php print $a_booklet['img_file']; ?>"><img src="<?php print $Siteroot; ?>/approved/<?php print $a_limage['img_file']; ?>" style="float: left; margin-right: 40px;"></a>
        <?php print $conactive; ?>
        <ul>
          <li><a href="convention.php">Convention Configuration</a> - <?php print $a_convention['con_title']; ?></li>
          <li><a href="category.php">Convention Categories</a> - Define the Common Events</li>
          <li><a href="group.php">Convention Titles</a></li>
          <li><a href="staff.php">Staff Management</a> - Associate Members with Titles</li>
          <li><a href="location.php">Location Management</a> - Set the Member Capacity</li>
          <li><a href="rule.php">Rules Management</a></li>
          <li><a href="fees.php">Convention Fees Management</a></li>
          <li><a href="contact.php">Convention Contact Information Management</a></li>
          <li><a href="conday.php">Define the Convention Days</a></li>
        </ul>

        <h2>Content Management</h2>
        <ul>
          <li><a href="event.php">Event Management</a></li>
          <li><a href="signup.php">Member Signup Management</a></li>
          <li><a href="news.php">News Management</a></li>
          <li><a href="guest.php">Guest Management</a></li>
          <li><a href="image.php">Image Management</a></li>
        </ul>
        <p><a href="view.php">Click here for a preview of all data</a></p>
      </div> 
    </div>  
  </div>

  <div id="rightcolumn">
    <div class="innertube">
      <img src="<?php print $Siteroot; ?>/approved/<?php print $a_rimage['img_file']; ?>">
    </div>
  </div>
</div>

<?php include($Sitepath . '/confooter.php'); ?>

</body>
</html>

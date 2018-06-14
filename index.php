<?php
  session_start();
# we're trying to show a page without having to use the login system. 
# If logged in, then show the standard login screens
# and show the logged in view (editable) of the information.
# really the login stuff should be for event management and 
# site management. The other stuff should be readable regardless
  include('settings.php');

  $package = "index.php";

  if (isset($_SESSION['username'])) {
    include($Sitepath . '/login/check.php');
    include($Sitepath . '/function.php');
    check_login(4);

    logaccess($_SESSION['username'], $package, "Load index now that user is logged in");
  } else {
    include ($Sitepath . '/minifunc.php');

# once a database connection is made, all the other data pulls work as expected
# make sure you don't write any changes until someone logs in.
    $db = mysql_connect($DBserver,$DBuser,$DBpassword);
    if (!$db) {
      die('Couldn\'t connect: ' . mysql_error());
    } else {
      $DBlogout = mysql_select_db($DBname,$db);
      if (!$DBlogout) {
        die('Not connected : ' . mysql_error());
      }
    }
  }

  $q_string = "select con_id,con_title,con_booklet,con_limage,con_rimage from convention where con_active > 0";
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

  $conactive = "<h2>" . $a_convention['con_title'] . "</h2>";

  if ($a_convention['con_id'] == '') {
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="REFRESH" content="5; url=admin/convention.php">
<title>No Active Convention</title>
<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

</head>
<body>

<div id="main">

<h2>Error</h2>

</div>

<div id="main">

<p>There is no active convention. One Convention must be identified as active in order to proceed.</p>

<p>You will be redirected in 5 seconds or click <a href="admin/convention.php"> here to continue</a>.</p>

</div>

</body>
</html>
<?php
  } else {
# there is an active convention so things can be managed now.
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php print $Sitename . " Presents " . $a_convention['con_title']; ?></title>

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
<?php
  $today = time();

  $q_string = "select news_title,news_text from news where news_start < " . $today . " and news_end > " . $today . " and news_status > 0 and news_conid = " . $a_convention['con_id'] . " order by news_start desc";
  $q_news = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  while ($a_news = mysql_fetch_array($q_news)) {
    print "<h2>" . $a_news['news_title'] . "</h2>\n";
    print $a_news['news_text'];
    print "<hr>\n";
  }
?>
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
<?php
  }
?>

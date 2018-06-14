<?php
#
#  Package: category.php
#  Version: 1.0.0
#  Author: Carl Schelin, Copyright 2013. All rights reserved.
#  Function: Manage the categories used by the convention
#  Description: This lists and lets you edit the various convention categories
#    in use for the convention staff.

  include('settings.php');
  include($Sitepath . '/login/check.php');
  include($Sitepath . '/function.php');
  check_login('3');

  $package = "category.php";

  logaccess($_SESSION['username'], $package, "Managing Categories");

  $q_string = "select con_id,con_title from convention where con_active > 0";
  $q_convention = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  $a_convention = mysql_fetch_array($q_convention);

  $conactive = "<h2>" . $a_convention['con_title'] . "</h2>";

  if ($a_convention['con_id'] == '') {
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="REFRESH" content="5; url=index.php">
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

<p>You will be redirected in 5 seconds or click <a href="index.php"> here to continue</a>.</p>

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
<title>Manage Convention Categories</title>
<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

<style type="text/css">

<?php

  $q_string = "select cat_id,cat_color from categories";
  $q_categories = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  while ($a_categories = mysql_fetch_array($q_categories)) {
    print ".style" . $a_categories['cat_id'] . " {\n";
    print "  background-color: " . $a_categories['cat_color'] . ";\n";
    print "}\n";
  }

?>

</style>

<script type="text/javascript">

function delete_line( p_script_url ) {
  var answer = confirm("Delete this Category?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    clear_fields();
  }
}

</script>

</head>
<body>

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<?php print $conactive; ?>

<table>
<tr>
  <th width=5>Delete?</th>
  <th>Title</th>
  <th>Coordinator</th>
  <th>Color</th>
  <th>Status</th>
</tr>
<?php

  $q_string = "select cat_id,cat_title,cat_group,cat_color,cat_status from categories where cat_conid = " . $a_convention['con_id'] . " order by cat_base";
  $q_categories = mysql_query($q_string) or die($q_string . ": " . mysql_error());

  if (mysql_num_rows($q_categories) > 0) {
    while ($a_categories = mysql_fetch_array($q_categories)) {
      print "<tr>\n";
      print "  <td class=\"delete style" . $a_categories['cat_id'] . "\"><a href=\"#\" onClick=\"delete_line('category.del.php?id=" . $a_categories['cat_id'] . "');\">X</a></td>\n";
      print "  <td class=\"style" . $a_categories['cat_id'] . "\"><a href=\"category.edit.php?id=" . $a_categories['cat_id'] . "\">" . $a_categories['cat_title'] . "</a></td>\n";
      $q_string = "select grp_name from groups where grp_id = " . $a_categories['cat_group'];
      $q_groups = mysql_query($q_string) or die($q_string . ": " . mysql_error());
      $a_groups = mysql_fetch_array($q_groups);
      print "  <td class=\"style" . $a_categories['cat_id'] . "\">" . $a_groups['grp_name'] . "</td>\n";
      print "  <td class=\"style" . $a_categories['cat_id'] . "\">" . $a_categories['cat_color'] . "</td>\n";
      print "  <td class=\"style" . $a_categories['cat_id'] . "\">";
      if ($a_categories['cat_status']) {
        $q_string = "select usr_last,usr_first from users where usr_id = " . $a_categories['cat_status'];
        $q_users = mysql_query($q_string) or die($q_string . ": " . mysql_error());
        $a_users = mysql_fetch_array($q_users);                                    
        print "Approved by " . $a_users['usr_first'] . " " . $a_users['usr_last'];
      } else {
        print "Pending";
      }
      print "</td>\n";
      print "</tr>\n";
    }
  } else {
    print "<tr>\n";
    print "  <td colspan=5>No Categories configured for this convention.</td>\n";
    print "</tr>\n";
  }

?>
</table>

<p><a href="category.edit.php?id=0">Click here to create a Category.</a></p>

</div>

<div id="main">

<h2>Convention Category Management</h2>

<p>This task lets the convention site owner create unique categories for the convention. Create a title, a default ticket cost, assign a unique color, add appropriate text to 
describe the category, and designate the coordinator of this category who will be the approver for all events associated with this category.</p>

<p>Click on a category to edit the details</p>

</div>

<?php include($Sitepath . '/confooter.php'); ?>

</body>
</html>
<?php
}
?>

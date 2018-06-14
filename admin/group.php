<?php
#
#  Package: group.php
#  Version: 1.0.0
#  Author: Carl Schelin, Copyright 2013. All rights reserved.
#  Function: Manage the Convention Titles
#  Description: This lists the groups

  include('settings.php');
  include($Sitepath . '/login/check.php');
  include($Sitepath . '/function.php');
  check_login('3');

  $package = "group.php";

  logaccess($_SESSION['username'], $package, "Managing Convention Titles");

  $q_string = "select con_id,con_title from convention where con_active > 0";
  $q_convention = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  $a_convention = mysql_fetch_array($q_convention);

  $conactive = "<h2>" . $a_convention['con_title'] . "</h2>";

  if ($a_convention['con_id'] == '') {
?>
<!DOCTYPE HTML>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="REFRESH" content="5; url=index.php">
<head>
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
<title>Manage Convention Titles</title>
<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

<script type="text/javascript">

function delete_line( p_script_url ) {
  var answer = confirm("Delete this Group?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
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
  <th>Status</th>
</tr>
<?php

  $q_string = "select grp_id,grp_name,grp_status from groups where grp_conid = " . $a_convention['con_id'];
  $q_groups = mysql_query($q_string) or die($q_string . ": " . mysql_error());

  if (mysql_num_rows($q_groups) > 0) {
    while ($a_groups = mysql_fetch_array($q_groups)) {
      print "<tr>\n";
      print "  <td class=\"delete\"><a href=\"#\" onClick=\"delete_line('group.del.php?id=" . $a_groups['grp_id'] . "');\">X</a></td>\n";
      print "  <td><a href=\"group.edit.php?id=" . $a_groups['grp_id'] . "\">" . $a_groups['grp_name'] . "</a></td>\n";
      if ($a_groups['grp_status']) {
        $q_string = "select usr_last,usr_first from users where usr_id = " . $a_groups['grp_status'];
        $q_users = mysql_query($q_string) or die($q_string . ": " . mysql_error());                   
        $a_users = mysql_fetch_array($q_users);                                                     
        print "  <td>Approved by " . $a_users['usr_first'] . " " . $a_users['usr_last'] . "</td>\n";
      } else {                                                                                      
        print "  <td>Pending</td>\n";
      }                              
      print "</tr>\n";
    }
  } else {
    print "<tr>\n";
    print "  <td colspan=4><a href=\"group.edit.php?id=0\">No Convention Titles have been created for this convention. Click here to add your first title.</a></td>\n";
    print "</tr>\n";
  }

?>
</table>

<p><a href="group.edit.php?id=0">Click here to add a title.</a></p>

</div>

<div id="main">

<h2>Convention Title Management</h2>

<p>This is basically creating group names or "Titles". You'll assign staff to the various groups. Later you'll assign a group to manage a category. Members of this group 
will be able to manage events created for that category.</p>

<p>Click on a Ttile to edit the details or click the link to create a new Title.</p>

</div>

<?php include($Sitepath . '/confooter.php'); ?>

</body>
</html>
<?php
}
?>

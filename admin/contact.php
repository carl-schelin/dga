<?php
#
#  Package: contact.php
#  Version: 1.0.0
#  Author: Carl Schelin, Copyright 2013. All rights reserved.
#  Function: Manage the Convention Contacts page
#  Description: This lists all the contact information

  include('settings.php');
  include($Sitepath . '/login/check.php');
  include($Sitepath . '/function.php');
  check_login('3');

  $package = "contact.php";

  logaccess($_SESSION['username'], $package, "Managing Convention Contacts");

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
<title>Manage Contacts</title>
<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

<script type="text/javascript">

function delete_line( p_script_url ) {
  var answer = confirm("Delete this Contact?")

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
  <th>Convention</th>
  <th>Status</th>
</tr>
<?php

  $q_string = "select con_id,con_text,con_status from contacts where con_conid = " . $a_convention['con_id'];
  $q_contacts = mysql_query($q_string) or die($q_string . ": " . mysql_error());

  if (mysql_num_rows($q_contacts) > 0) {
    while ($a_contacts = mysql_fetch_array($q_contacts)) {
      print "<tr>\n";
      print "  <td class=\"delete\"><a href=\"#\" onClick=\"delete_line('contact.del.php?id=" . $a_contacts['con_id'] . "');\">X</a></td>\n";
      print "  <td><a href=\"contact.edit.php?id=" . $a_contacts['con_id'] . "\">" . $a_convention['con_title'] . "</a></td>\n";
      print "  <td>";
      if ($a_contacts['con_status']) {
        $q_string = "select usr_last,usr_first from users where usr_id = " . $a_contacts['con_status'];
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
    print "  <td colspan=8>No Contacts page defined for this convention.</td>\n";
    print "</tr>\n";
  }

?>
</table>

<p><a href="contact.edit.php?id=0">Click here to add Contacts.</a></p>

</div>

<div id="main">

<h2>Convention Contact Information Management</h2>

<p>This information is at the top of the contacts page followed by the summaries and email addresses associated with each category. So you only need to put in the main convention 
contact info and perhaps an introduction to the category summaries.</p>

<p>Click on a contact title to edit the details</p>

</div>

<?php include($Sitepath . '/confooter.php'); ?>

</body>
</html>
<?php
}
?>

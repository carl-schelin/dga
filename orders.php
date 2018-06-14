<?php
#
#  Package: orders.php
#  Version: 1.0.0
#  Author: Carl Schelin, Copyright 2013. All rights reserved.
#  Function: Manage the events that members have ordered
#  Description: 

  include('settings.php');
  include($Sitepath . '/login/check.php');
  include($Sitepath . '/function.php');
  check_login('4');

  $package = "orders.php";

  logaccess($_SESSION['username'], $package, "Managing My Orders");

# this lets a coordinator view any user's event listing
  if (isset($_GET['id'])) {
    if (check_userlevel(3)) {
      $formVars['id'] = clean($_GET['id'], 10);
    } else {
      $formVars['id'] = $_SESSION['uid'];
    }
  } else {
    $formVars['id'] = $_SESSION['uid'];
  }

  if (isset($_GET['sort'])) {
    $formVars['sort'] = " order by " . clean($_GET['sort'], 50);
  } else {
    $formVars['sort'] = " order by evt_start,evt_category,evt_subcat,evt_title";
  }

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
<title>Manage Convention Events</title>
</head>
<body>
<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

<style type="text/css">

<?php
     
  $q_string = "select cat_id,cat_color from categories where cat_status > 0 and cat_conid = " . $a_convention['con_id'];
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
  var answer = confirm("Delete this Event?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
  }   
}

function attach_file( p_script_url ) {
  script = document.createElement('script');
  script.src = p_script_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

</script>

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<?php print $conactive; ?>

<?php
  print "<table>\n";
  print "<tr>\n";
  print "  <th width=5>Delete</th>\n";
  print "  <th><a href=\"" . $package . "?sort=evt_title\">Title</a></th>\n";
  print "  <th><a href=\"" . $package . "?sort=evt_category,evt_subcat\">Event ID</a></th>\n";
  print "  <th><a href=\"" . $package . "?sort=usr_last,usr_first\">Host</a></th>\n";
  print "  <th><a href=\"" . $package . "?sort=evt_start\">Day</a></th>\n";
  print "  <th><a href=\"" . $package . "?sort=evt_start\">Start</a></th>\n";
  print "</tr>\n";

  $q_string  = "select sup_id,evt_id,evt_type,evt_category,evt_subcat,evt_title,evt_text,evt_start,evt_end,";
  $q_string .= "evt_status,evt_host from signup left join events on signup.sup_event = events.evt_id where sup_conid = " . $a_convention['con_id'] . $formVars['sort'];
  $q_signup = mysql_query($q_string) or die($q_string . ": " . mysql_error());

  if (mysql_num_rows($q_signup) > 0) {
    while ($a_signup = mysql_fetch_array($q_signup)) {

#      if ($a_signup['evt_start'] > time()) {
        $eventclass = "style" . $a_signup['evt_type'];
#      } else {
#        $eventclass = "inactive";
#      }

      print "<tr>\n";
      print "  <td class=\"" . $eventclass . " delete\"><a href=\"#\" onClick=\"delete_line('orders.del.php?id=" . $a_signup['sup_id'] . "');\">X</a></td>\n";
      print "  <td class=\"" . $eventclass . "\" title=\"" . $a_signup['evt_text'] . "\">";
      print "<a href=\"event.view.php?id=" . $a_signup['evt_id'] . "\">" . $a_signup['evt_title'] . "</td>\n";
      $q_string = "select usr_last,usr_first from users where usr_id = " . $a_signup['evt_host'];
      $q_users = mysql_query($q_string) or die($q_string . ": " . mysql_error());
      $a_users = mysql_fetch_array($q_users);
      print "  <td class=\"" . $eventclass . "\"><a href=\"judge.php?id=" . $a_signup['evt_host'] . "\">" . $a_users['usr_first'] . " " . $a_users['usr_last'] . "</a></td>\n";
      if ($a_signup['evt_category'] == '') {
        print "  <td class=\"" . $eventclass . "\"></td>\n";
      } else {
        print "  <td class=\"" . $eventclass . "\">" . $a_signup['evt_category'] . "." . $a_signup['evt_subcat'] . "</td>\n";
      }
      print "  <td class=\"" . $eventclass . "\">" . date('D', $a_signup['evt_start']) . "</td>\n";
      print "  <td class=\"" . $eventclass . "\">" . date('h:i:s A', $a_signup['evt_start']) . "-" . date('h:i:s A', $a_signup['evt_end']) . "</td>\n";
      print "</tr>\n";
    }
  } else {
    print "<tr>\n";
    print "  <td colspan=6>You haven't signed up for any events at this time.</td>\n";
    print "</tr>\n";
  }

?>
</table>

</div>

<div id="main">

<h2>Ordered Events</h2>

<p>This page contains a list of all the events you've have signed up for. Until you have paid for the events, the number of available seats will not change. The event will 
be identified as "I'm in!" once you've paid.</p>

</div>

<?php include($Sitepath . '/confooter.php'); ?>

</body>
</html>
<?php
}
?>

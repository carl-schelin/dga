<?php
#
#  Package: judge.php
#  Version: 1.0.0
#  Author: Carl Schelin, Copyright 2013. All rights reserved.
#  Function: Manage the events used by the convention
#  Description: This lists and lets you edit the various convention events.

  include('settings.php');
  include($Sitepath . '/login/check.php');
  include($Sitepath . '/function.php');
  check_login('4');

  $package = "judge.php";

  logaccess($_SESSION['username'], $package, "Managing My Events");

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

<script type="text/javascript">

function delete_line( p_script_url ) {
  var answer = confirm("Delete this Event?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    clear_fields();
  }
}

</script>

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<?php print $conactive; ?>

<?php
  print "<table>\n";
  print "<tr>\n";
  print "  <th><a href=\"" . $package . "?sort=evt_title\">Title</a></th>\n";
  print "  <th><a href=\"" . $package . "?sort=evt_category,evt_subcat\">Event ID</a></th>\n";
  print "  <th><a href=\"" . $package . "?sort=evt_start\">Day</a></th>\n";
  print "  <th><a href=\"" . $package . "?sort=evt_start,evt_end\">Start</a></th>\n";
  print "  <th><a href=\"" . $package . "?sort=evt_status\">Status</a></th>\n";
  print "</tr>\n";

  $q_string = "select evt_id,evt_type,evt_host,evt_category,evt_subcat,evt_title,evt_text,evt_start,evt_end,evt_status from events where evt_host = " . $formVars['id'] . " and evt_conid = " . $a_convention['con_id'] . $formVars['sort'];
  $q_events = mysql_query($q_string) or die($q_string . ": " . mysql_error());

  if (mysql_num_rows($q_events) > 0) {
    while ($a_events = mysql_fetch_array($q_events)) {
      $q_string = "select cat_color from categories where cat_id = " . $a_events['evt_type'];
      $q_categories = mysql_query($q_string) or die($q_string . ": " . mysql_error());       
      $a_categories = mysql_fetch_array($q_categories);
      print "<tr>\n";
      print "  <td style=\"background-color:" . $a_categories['cat_color'] . ";\" title=\"" . $a_events['evt_text'] . "\">";
      if ($a_events['evt_host'] == $_SESSION['uid'] || check_userlevel(3)) {
        print "<a href=\"event.edit.php?id=" . $a_events['evt_id'] . "\"><img src=\"imgs/pencil.gif\"></a>";
      }
      print "<a href=\"event.view.php?id=" . $a_events['evt_id'] . "\">" . $a_events['evt_title'] . "</a></td>\n";
      if ($a_events['evt_category'] == '') {
        print "  <td style=\"background-color:" . $a_categories['cat_color'] . ";\"></td>\n";
      } else {
        print "  <td style=\"background-color:" . $a_categories['cat_color'] . ";\">" . $a_events['evt_category'] . "." . $a_events['evt_subcat'] . "</td>\n";
      }
      print "  <td style=\"background-color:" . $a_categories['cat_color'] . ";\">" . date('D', $a_events['evt_start']) . "</td>\n";
      print "  <td style=\"background-color:" . $a_categories['cat_color'] . ";\">" . date('h:i:s A', $a_events['evt_start']) . "-" . date('h:i:s A', $a_events['evt_end']) . "</td>\n";
      print "  <td style=\"background-color:" . $a_categories['cat_color'] . ";\">";
      if ($a_events['evt_status']) {
        print "Approved";
      } else {
        print "Pending";
      }
      print "</td>\n";
      print "</tr>\n";
    }
  } else {
    print "<tr>\n";
    print "  <td colspan=6>You aren't scheduled to run any events at this time.</td>\n";
    print "</tr>\n";
  }

?>
</table>

<p><a href="event.edit.php?id=0">Click here to create a new event.</a></p>

</div>

<div id="main">
<?php
  $q_string = "select con_id,con_title,con_start,con_end from convention where con_id != " . $a_convention['con_id'];
  $q_convention = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  while ($a_convention = mysql_fetch_array($q_convention)) {

    print "<h2>" . $a_convention['con_title'] . "</h2>\n";
    print "<h3>" . date("M d, Y", $a_convention['con_start']) . " to " . date("M d, Y", $a_convention['con_end']) . "</h3>\n";

    print "<table>\n";
    print "<tr>\n";
    print "  <th><a href=\"" . $package . "?sort=evt_title\">Title</a></th>\n";
    print "  <th><a href=\"" . $package . "?sort=evt_category,evt_subcat\">Event ID</a></th>\n";
    print "  <th><a href=\"" . $package . "?sort=evt_start\">Day</a></th>\n";
    print "  <th><a href=\"" . $package . "?sort=evt_start,evt_end\">Start</a></th>\n";
    print "  <th><a href=\"" . $package . "?sort=evt_status\">Status</a></th>\n";
    print "</tr>\n";

    $q_string = "select evt_id,evt_type,evt_category,evt_subcat,evt_title,evt_text,evt_start,evt_end,evt_status from events where evt_host = " . $formVars['id'] . " and evt_conid = " . $a_convention['con_id'] . $formVars['sort'];
    $q_events = mysql_query($q_string) or die($q_string . ": " . mysql_error());

    if (mysql_num_rows($q_events) > 0) {
      while ($a_events = mysql_fetch_array($q_events)) {
        $q_string = "select cat_color from categories where cat_id = " . $a_events['evt_type'];
        $q_categories = mysql_query($q_string) or die($q_string . ": " . mysql_error());       
        $a_categories = mysql_fetch_array($q_categories);
        print "<tr>\n";
        print "  <td style=\"background-color:" . $a_categories['cat_color'] . ";\" title=\"" . $a_events['evt_text'] . "\">";
        if ($a_events['evt_host'] == $_SESSION['uid'] || check_userlevel(3)) {
          print "<a href=\"event.edit.php?id=" . $a_events['evt_id'] . "\"><img src=\"imgs/pencil.gif\"></a>";
        }
        print "<a href=\"event.view.php?id=" . $a_events['evt_id'] . "\">" . $a_events['evt_title'] . "</a></td>\n";
        if ($a_events['evt_category'] == '') {
          print "  <td style=\"background-color:" . $a_categories['cat_color'] . ";\"></td>\n";
        } else {
          print "  <td style=\"background-color:" . $a_categories['cat_color'] . ";\">" . $a_events['evt_category'] . "." . $a_events['evt_subcat'] . "</td>\n";
        }
        print "  <td style=\"background-color:" . $a_categories['cat_color'] . ";\">" . date('D', $a_events['evt_start']) . "</td>\n";
        print "  <td style=\"background-color:" . $a_categories['cat_color'] . ";\">" . date('h:i:s A', $a_events['evt_start']) . "-" . date('h:i:s A', $a_events['evt_end']) . "</td>\n";
        print "  <td style=\"background-color:" . $a_categories['cat_color'] . ";\">";
        if ($a_events['evt_status']) {
          print "Approved";
        } else {
          print "Pending";
        }
        print "</td>\n";
        print "</tr>\n";
      }
    } else {
      print "<tr>\n";
      print "  <td colspan=6>You aren't scheduled to run any events at this time.</td>\n";
      print "</tr>\n";
    }

    print "</table>\n";
  }

?>
</div>

<div id="main">

<h2>Judge Events</h2>

<p>This page contains a list of all the events you've volunteered to run. You are able to edit the events up until it is approved by your Coordinator. If you need to 
make a change after that, you'll need to get in touch with him or her to unapprove it.</p>

<p><strong>Note:</strong> When volunteering, make sure you schedule time for set up and tear down and time for breaks. Folks who buy tickets expect games to be available 
and on time as they have other events they have paid to attend. Events that run over causing cancellation might reflect poorly on you when judges are rated.</p>

</div>

<?php include($Sitepath . '/confooter.php'); ?>

</body>
</html>
<?php
}
?>

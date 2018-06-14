<?php
# Script: groups.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: Retrieve data and update the database with the new info. Prepare and display the table

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "groups.mysql.php";
    $formVars['update'] = clean($_GET['update'], 10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(1)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']               = clean($_GET['id'],                10);
        $formVars['grp_name']         = clean($_GET['grp_name'],         100);
        $formVars['grp_manager']      = clean($_GET['grp_manager'],       10);
        $formVars['grp_email']        = clean($_GET['grp_email'],        255);
        $formVars['grp_disabled']     = clean($_GET['grp_disabled'],     255);
        $formVars['grp_changedby']    = clean($_SESSION['uid'],           10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }

        if (strlen($formVars['grp_name']) > 0) {
          logaccess($_SESSION['uid'], $package, "Building the query.");

# get old group manager.
          $q_string  = "select grp_manager ";
          $q_string .= "from groups ";
          $q_string .= "where grp_id = " . $formVars['id'] . " ";
          $q_groups = mysql_query($q_string) or die($q_string . ": " . mysql_error());
          if (mysql_num_rows($q_groups) > 0) {
            $a_groups = mysql_fetch_array($q_groups);
# got it, now update everyone in the same group with the same old manager assuming the group already exists.
            $q_string  = "update ";
            $q_string .= "users ";
            $q_string .= "set usr_manager = " . $formVars['grp_manager'] . " ";
            $q_string .= "where usr_group = " . $formVars['id'] . " and usr_manager = " . $a_groups['grp_manager'] . " ";
            $result = mysql_query($q_string) or die($q_string . ": " . mysql_error());
          }

# all done. now update groups with the new information.
          $q_string =
            "grp_name          = \"" . $formVars['grp_name']          . "\"," . 
            "grp_manager       =   " . $formVars['grp_manager']       . "," . 
            "grp_email         = \"" . $formVars['grp_email']         . "\"," . 
            "grp_disabled      =   " . $formVars['grp_disabled']      . "," . 
            "grp_changedby     =   " . $formVars['grp_changedby'];

          if ($formVars['update'] == 0) {
            $query = "insert into groups set grp_id = NULL," . $q_string;
            $message = "Group added.";
          }
          if ($formVars['update'] == 1) {
            $query = "update groups set " . $q_string . " where grp_id = " . $formVars['id'];
            $message = "Group updated.";
          }

          logaccess($_SESSION['uid'], $package, "Saving Changes to: " . $formVars['grp_name']);

          mysql_query($query) or die($query . ": " . mysql_error());

          print "alert('" . $message . "');\n";
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }

      $group  = "<table class=\"ui-styled-table\">\n";
      $group .= "<tr>\n";
      $group .= "  <th class=\"ui-state-default\">Group Listing</th>\n";
      $group .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('group-listing-help');\">Help</a></th>\n";
      $group .= "</tr>\n";
      $group .= "</table>\n";

      $group .= "<div id=\"group-listing-help\" style=\"display: none\">\n";

      $magic  = "<table class=\"ui-styled-table\">\n";
      $magic .= "<tr>\n";
      $magic .= "  <th class=\"ui-state-default\">Group Listing</th>\n";
      $magic .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('magic-listing-help');\">Help</a></th>\n";
      $magic .= "</tr>\n";
      $magic .= "</table>\n";

      $magic .= "<div id=\"magic-listing-help\" style=\"display: none\">\n";

      $changelog  = "<table class=\"ui-styled-table\">\n";
      $changelog .= "<tr>\n";
      $changelog .= "  <th class=\"ui-state-default\">Group Listing</th>\n";
      $changelog .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('changelog-listing-help');\">Help</a></th>\n";
      $changelog .= "</tr>\n";
      $changelog .= "</table>\n";

      $changelog .= "<div id=\"changelog-listing-help\" style=\"display: none\">\n";


      $header  = "<div class=\"main-help ui-widget-content\">\n";

      $header .= "<ul>\n";
      $header .= "  <li><strong>Group Listing</strong>\n";
      $header .= "  <ul>\n";
      $header .= "    <li><strong>Delete (x)</strong> - Click here to delete this group from the Inventory. It's better to disable the user.</li>\n";
      $header .= "    <li><strong>Editing</strong> - Click on a group to toggle the form and edit the group.</li>\n";
      $header .= "    <li><strong>Highlight</strong> - If a group is <span class=\"ui-state-error\">highlighted</span>, then the group has been disabled and will not be visible in any selection menus.</li>\n";
      $header .= "  </ul></li>\n";
      $header .= "</ul>\n";

      $header .= "</div>\n";

      $header .= "</div>\n";


      $title  = "<table class=\"ui-styled-table\">";
      $title .= "<tr>";
      if (check_userlevel(1)) {
        $title .= "  <th class=\"ui-state-default\">Del</th>";
      }
      $title .= "  <th class=\"ui-state-default\">Id</th>";
      $title .= "  <th class=\"ui-state-default\">Group</th>";
      $title .= "  <th class=\"ui-state-default\">Group EMail</th>";
      $title .= "  <th class=\"ui-state-default\">Group Manager</th>";
      $title .= "</tr>";

      $group     .= $header . $title;


      $title  = "<table class=\"ui-styled-table\">";
      $title .= "<tr>";
      if (check_userlevel(1)) {
        $title .= "  <th class=\"ui-state-default\">Del</th>";
      }
      $title .= "  <th class=\"ui-state-default\">Id</th>";
      $title .= "  <th class=\"ui-state-default\">Group</th>";
      $title .= "</tr>";

      $magic     .= $header . $title;


      $title  = "<table class=\"ui-styled-table\">";
      $title .= "<tr>";
      if (check_userlevel(1)) {
        $title .= "  <th class=\"ui-state-default\">Del</th>";
      }
      $title .= "  <th class=\"ui-state-default\">Id</th>";
      $title .= "  <th class=\"ui-state-default\">Group</th>";
      $title .= "</tr>";

      $changelog .= $header . $title;


      $q_string  = "select grp_id,grp_name,grp_email,usr_last,usr_first,grp_disabled ";
      $q_string .= "from groups ";
      $q_string .= "left join users on users.usr_id = groups.grp_manager ";
      $q_string .= "order by grp_name";
      $q_groups = mysql_query($q_string) or die (mysql_error());
      if (mysql_num_rows($q_groups) > 0) {
        while ($a_groups = mysql_fetch_array($q_groups)) {

          $linkstart = "<a href=\"#\" onclick=\"show_file('groups.fill.php?id="  . $a_groups['grp_id'] . "');jQuery('#dialogGroup').dialog('open');\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onclick=\"delete_line('groups.del.php?id=" . $a_groups['grp_id'] . "');\">";
          $linkend = "</a>";

          $class = "ui-widget-content";
          if ($a_groups['grp_disabled']) {
            $class = "ui-state-error";
          }

          $group .= "<tr>";
          if (check_userlevel(1)) {
            $group .= "  <td class=\"" . $class . " delete\">" . $linkdel   . "</td>";
          }
          $group .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_groups['grp_id']           . $linkend . "</td>";
          $group .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_groups['grp_name']         . $linkend . "</td>";
          $group .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_groups['grp_email']        . $linkend . "</td>";
          $group .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_groups['usr_first'] . " " . $a_groups['usr_last'] . $linkend . "</td>";
          $group .= "</tr>";

          $magic .= "<tr>";
          if (check_userlevel(1)) {
            $magic .= "  <td class=\"" . $class . " delete\">" . $linkdel   . "</td>";
          }
          $magic .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_groups['grp_id']           . $linkend . "</td>";
          $magic .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_groups['grp_name']         . $linkend . "</td>";
          $magic .= "</tr>";

          $changelog .= "<tr>";
          if (check_userlevel(1)) {
            $changelog .= "  <td class=\"" . $class . " delete\">" . $linkdel   . "</td>";
          }
          $changelog .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_groups['grp_id']           . $linkend . "</td>";
          $changelog .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_groups['grp_name']         . $linkend . "</td>";
          $changelog .= "</tr>";


        }
      } else {
        $class = "ui-widget-content";

        $group .= "<tr>";
        $group .= "  <td class=\"" . $class . "\" colspan=\"6\">No records found.</td>";
        $group .= "</tr>";

        $magic .= "<tr>";
        $magic .= "  <td class=\"" . $class . "\" colspan=\"6\">No records found.</td>";
        $magic .= "</tr>";

        $changelog .= "<tr>";
        $changelog .= "  <td class=\"" . $class . "\" colspan=\"6\">No records found.</td>";
        $changelog .= "</tr>";
      }

      mysql_free_result($q_groups);

      $group .= "</table>";
      $magic .= "</table>";
      $changelog .= "</table>";

      print "document.getElementById('group_mysql').innerHTML = '"     . mysql_real_escape_string($group)     . "';\n\n";

      print "document.groups.grp_name.value = '';\n";
      print "document.groups.grp_email.value = '';\n";
      print "document.groups.grp_manager[0].selected = true;\n";
      print "document.groups.grp_disabled[0].selected = true;\n";
    } else {
      logaccess($_SESSION['uid'], $package, "Unauthorized access.");
    }
  }
?>

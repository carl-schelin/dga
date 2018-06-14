<?php

  include('settings.php');
  include($Sitepath . '/login/check.php');
  include($Sitepath . '/function.php');

  $package = "convention.duplicate.php";

// id of the record being duplicated
  $formVars['id']   = clean($_GET['id'], 10);

// This duplicates the identified convention except for the news, events, and guests.

  if (check_userlevel(3)) {
    $q_string = 
      "con_title  = \"" . "New Convention" . "\"," . 
      "con_active =   " . "0"              . "," . 
      "con_status =   " . "0";

    $query = "insert into convention set con_id = null," . $q_string;
    $result = mysql_query($query) or die($query . ": " . mysql_error());
    $newconvention = mysql_insert_id();

# get the new convention record first then copy the existing information

    if ($newconvention != 0) {
# do the categories...
      $q_string = "select cat_base,cat_title,cat_menu,cat_cost,cat_group,cat_color,cat_text from categories where cat_conid = " . $formVars['id'];
      $q_categories = mysql_query($q_string) or die($q_string . ": " . mysql_error());
      while ($a_categories = mysql_fetch_array($q_categories)) {
        $q_string = 
          "cat_conid =   " . $newconvention             . "," . 
          "cat_base  =   " . $a_categories['cat_base']  . "," . 
          "cat_title = \"" . $a_categories['cat_title'] . "\"," . 
          "cat_menu  = \"" . $a_categories['cat_menu']  . "\"," . 
          "cat_cost  =   " . $a_categories['cat_cost']  . "," . 
          "cat_group =   " . $a_categories['cat_group'] . "," . 
          "cat_color = \"" . $a_categories['cat_color'] . "\"," . 
          "cat_text  = \"" . $a_categories['cat_text']  . "\"";

        $query = "insert into categories set cat_id = null," . $q_string;
        $result = mysql_query($query) or die($query . ": " . mysql_error());

      }

# do the condays...
      $q_string = "select day_start,day_end,day_tstart,day_tend from condays where day_conid = " . $formVars['id'];
      $q_condays = mysql_query($q_string) or die($q_string . ": " . mysql_error());
      while ($a_condays = mysql_fetch_array($q_condays)) {
        $q_string = 
          "day_conid  =   " . $newconvention           . "," . 
          "day_start  =   " . $a_condays['day_start']  . "," . 
          "day_end    =   " . $a_condays['day_end']    . "," . 
          "day_tstart =   " . $a_condays['day_tstart'] . "," . 
          "day_tend   =   " . $a_condays['day_tend'];

        $query = "insert into condays set day_id = null," . $q_string;
        $result = mysql_query($query) or die($query . ": " . mysql_error());

      }

# do the groups...
      $q_string = "select grp_name from groups where grp_conid = " . $formVars['id'];
      $q_groups = mysql_query($q_string) or die($q_string . ": " . mysql_error());
      while ($a_groups = mysql_fetch_array($q_groups)) {
        $q_string = 
          "grp_conid =   " . $newconvention        . "," . 
          "grp_name  = \"" . $a_groups['grp_name'] . "\"";

        $query = "insert into groups set grp_id = null," . $q_string;
        $result = mysql_query($query) or die($query . ": " . mysql_error());

      }

# do the locations...
      $q_string = "select loc_title,loc_subloc,loc_limit from locations where loc_conid = " . $formVars['id'];
      $q_locations = mysql_query($q_string) or die($q_string . ": " . mysql_error());
      while ($a_locations = mysql_fetch_array($q_locations)) {
        $q_string = 
          "loc_conid  =   " . $newconvention             . "," . 
          "loc_title  = \"" . $a_locations['loc_title']  . "\"," .
          "loc_subloc = \"" . $a_locations['loc_subloc'] . "\"," .
          "loc_limit  =   " . $a_locations['loc_limit'];

        $query = "insert into locations set loc_id = null," . $q_string;
        $result = mysql_query($query) or die($query . ": " . mysql_error());

      }

# do the rules...
      $q_string = "select rul_text from rules where rul_conid = " . $formVars['id'];
      $q_rules = mysql_query($q_string) or die($q_string . ": " . mysql_error());
      while ($a_rules = mysql_fetch_array($q_rules)) {
        $q_string = 
          "rul_conid =   " . $newconvention       . "," . 
          "rul_text  = \"" . $a_rules['rul_text'] . "\"";

        $query = "insert into rules set rul_id = null," . $q_string;
        $result = mysql_query($query) or die($query . ": " . mysql_error());

      }

# do the staff...
      $q_string = "select sta_title,sta_user from staff where sta_conid = " . $formVars['id'];
      $q_staff = mysql_query($q_string) or die($q_string . ": " . mysql_error());
      while ($a_staff = mysql_fetch_array($q_staff)) {
        $q_string = 
          "sta_conid =   " . $newconvention        . "," . 
          "sta_title =   " . $a_staff['sta_title'] . "," . 
          "sta_user  =   " . $a_staff['sta_user'];

        $query = "insert into staff set sta_id = null," . $q_string;
        $result = mysql_query($query) or die($query . ": " . mysql_error());

      }
    }
  } else {
    logaccess($_SESSION['username'], $package, "Access Denied!"); 
  }

?>

<html>
<meta http-equiv="REFRESH" content="0; url=convention.php">
</html>

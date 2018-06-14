<?php

  header('Content-Type: text/javascript');

  include('login/check.php');
  include('function.php');

  $package = "signup.php";

  logaccess($_SESSION['username'], $package, "Event Signup");

  $formVars['evt_id']    = clean($_GET['id'], 10);

# Get event details
  $q_string = "select evt_limit,evt_start,evt_end,evt_conid from events where evt_id = " . $formVars['evt_id'];
  $q_events = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  $a_events = mysql_fetch_array($q_events);

  $convention = $a_events['evt_conid'];

# note the intention of buying a ticket
  $q_string = "insert into signup set sup_id = null, sup_conid = " . $convention . ",sup_event = " . $formVars['evt_id'] . ", sup_user = " . $_SESSION['uid'];
  $q_signup = mysql_query($q_string) or die($q_string . ": " . mysql_error());

# count the ones who have paid for a ticket
  $q_string = "select count(sup_id) from signup where sup_event = " . $formVars['evt_id'] . " and sup_paid = 1 and sup_delete = 0";
  $q_paid = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  $a_paid = mysql_fetch_array($q_paid);

# count the ones who are planning on paying for a ticket
  $q_string = "select count(sup_id) from signup where sup_event = " . $formVars['evt_id'] . " and sup_paid = 0 and sup_delete = 0";
  $q_intent = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  $a_intent = mysql_fetch_array($q_intent);

  $linkstart = "<a href=\"#\" onclick=\"attach_file(\'signup.php?id=" . $formVars['evt_id'] . "\');\">";
  $linkend = '</a>';

  if ($a_paid['count(sup_id)'] < $a_events['evt_limit']) {
    $output = $linkstart . ($a_events['evt_limit'] - $a_signup['count(sup_id)']) . " of " . $a_events['evt_limit'] . " Left! (" . $a_intent['count(sup_id)'] . ")" . $linkend;
  } else {
    if ($a_events['evt_limit'] == 0) {
      $output = $linkstart . $a_signup['count(sup_id)'] . " of Unlimited! (" . $a_intend['count(sup_id)'] . $linkend;
    } else {
      $output = "Full!";
    }
  }

# get all the event details
  $q_string = "select evt_start,evt_end from events where evt_id = " . $formVars['evt_id'];
  $q_events = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  $a_events = mysql_fetch_array($q_events);

# now build the query
  $q_string  = "select evt_id from events where ";
  $q_string .= "(evt_start >= " . $a_events['evt_start'] . " and evt_start <  " . $a_events['evt_end'] . ") or ";
  $q_string .= "(evt_end   >  " . $a_events['evt_start'] . " and evt_end   <= " . $a_events['evt_end'] . ") or ";
  $q_string .= "(evt_start <  " . $a_events['evt_start'] . " and evt_end   >  " . $a_events['evt_end'] . ") and ";
  $q_string .= "evt_id != " . $formVars['evt_id'] . " and evt_conid = " . $convention . " and evt_status > 0";

  $q_events = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  while ($a_events = mysql_fetch_array($q_events)) {
    print "if (navigator.appName == \"Microsoft Internet Explorer\") {\n";
    print "  document.getElementById('title_" . $a_events['evt_id'] . "').className = \"inactive\";\n";
    print "  document.getElementById('host_" . $a_events['evt_id'] . "').className = \"inactive\";\n";
    print "  document.getElementById('category_" . $a_events['evt_id'] . "').className = \"inactive\";\n";
    print "  document.getElementById('day_" . $a_events['evt_id'] . "').className = \"inactive\";\n";
    print "  document.getElementById('start_" . $a_events['evt_id'] . "').className = \"inactive\";\n";
    print "  document.getElementById('signup_" . $a_events['evt_id'] . "').className = \"inactive\";\n";
    print "} else {\n";
    print "  document.getElementById('title_" . $a_events['evt_id'] . "').setAttribute(\"class\",\"inactive\");\n";
    print "  document.getElementById('host_" . $a_events['evt_id'] . "').setAttribute(\"class\",\"inactive\");\n";
    print "  document.getElementById('category_" . $a_events['evt_id'] . "').setAttribute(\"class\",\"inactive\");\n";
    print "  document.getElementById('day_" . $a_events['evt_id'] . "').setAttribute(\"class\",\"inactive\");\n";
    print "  document.getElementById('start_" . $a_events['evt_id'] . "').setAttribute(\"class\",\"inactive\");\n";
    print "  document.getElementById('signup_" . $a_events['evt_id'] . "').setAttribute(\"class\",\"inactive\");\n";
    print "}\n";
  }

?>

document.getElementById('signup_<?php print $formVars['evt_id']; ?>').innerHTML = '<?php print $output; ?>';


<?php

  header('Content-Type: text/javascript');

  include('settings.php');
  include($Sitepath . '/login/check.php');
  include($Sitepath . '/function.php');

  $formVars['id'] = clean($_GET['id'], 10);

// Retrieve the data
  $q_string  = "select sup_id,sup_user,sup_paid,sup_status from signup where sup_id = " . $formVars['id'];
  $q_signup = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  $a_signup = mysql_fetch_array($q_signup);

  $count = 1;
  $user = 0;
  $q_string = "select usr_id from users order by usr_last,usr_first";
  $q_users = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  while ($a_users = mysql_fetch_array($q_users)) {
    if ($a_users['usr_id'] == $a_signup['sup_user']) {
      $user = $count;
    }
    $count++;
  }

  mysql_free_result($q_signup);

?>

document.signup.user['<?php print $user; ?>'].selected = 'true';
document.signup.paid.checked = <?php if ($a_signup['sup_paid']) { print 'true'; } else { print 'false'; } ?>;
document.signup.status.checked = <?php if ($a_signup['sup_status']) { print 'true'; } else { print 'false'; } ?>;

document.signup.id.value = <?php print $formVars['id']; ?>;

document.signup.update.disabled = false;


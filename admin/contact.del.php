<?php

  include('settings.php');
  include($Sitepath . '/login/check.php');
  include($Sitepath . '/function.php');

  $package = "contact.del.php";

// id of the record being deleted
  $formVars['id']   = clean($_GET['id'], 10);

  if (check_userlevel(3)) {
    $q_string = "delete from contacts where con_id = " . $formVars['id'];
    $result = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  } else {
    logaccess($_SESSION['username'], $package, "Access Denied!");
  }

?>

<html>
<meta http-equiv="REFRESH" content="0; url=contact.php">
</html>


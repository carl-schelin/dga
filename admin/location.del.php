<?php

  include ('../login/check.php');
  include ('../function.php');

  $package = "group.del.php";

// id of the record being deleted
  $formVars['id']   = clean($_GET['id'], 10);

  if (check_userlevel(3)) {
    $q_string = "delete from locations where loc_id = " . $formVars['id'];
    $result = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  } else {
    logaccess($_SESSION['username'], $package, "Access Denied!");
  }

?>

<html>
<meta http-equiv="REFRESH" content="0; url=group.php">
</html>


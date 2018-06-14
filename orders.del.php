<?php

  header('Content-Type: text/javascript');

  include ('login/check.php');
  include ('function.php');

  $package = "orders.del.php";

// id of the record being deleted
  $formVars['id']   = clean($_GET['id'], 10);

  if (check_userlevel(4)) {
    $q_string = "delete from signup where sup_id = " . $formVars['id'];
    $result = mysql_query($q_string) or die($q_string . ": " . mysql_error());
  } else {
    logaccess($_SESSION['username'], $package, "Access Denied!");
  }

?>

window.location.href = "orders.php";


  <li><a href="<?php print $Siteroot; ?>/account.php">Account 
<?php
if (isset($_SESSION['username'])) {
  print " (" . $_SESSION['username'] . ")";
}
?></a>
    <ul>
<?php
  if (isset($_SESSION['username'])) {
?>
      <li><a href="<?php print $Siteroot; ?>/login/user_admin.php">Account Details</a></li>
      <li><a href="<?php print $Siteroot; ?>/orders.php">My Orders</a></li>               
      <li><a href="<?php print $Siteroot; ?>/judge.php">My Events</a></li>               
      <li><a href="<?php print $Siteroot; ?>/login/logout.php">Logout (<?php print $_SESSION['username']; ?>)</a></li>
<?php
    if (check_userlevel(3)) {
?>
      <li><a href="">-------------------------</a></li>
      <li><a href="<?php print $Siteroot; ?>/login/admin">User Management</a></li>
      <li><a href="<?php print $Siteroot; ?>/admin/index.php">Site Management</a></li>
<?php
    }
  } else {
?>
      <li><a href="<?php print $Siteroot; ?>/login/login.php">Login</a></li>
<?php
  }
?>
    </ul>
  </li>
</ul>

</div>

</div>
